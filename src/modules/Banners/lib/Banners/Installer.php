<?php

/**
 * @package      Banners
 * @author       Halbrook Technologies
 * @author       Devin Hayes
 * @author       Craig Heydenburg
 * @link         http://www.halbrooktech.com
 * @copyright    Copyright (C) 2010
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */
class Banners_Installer extends Zikula_AbstractInstaller
{

    /**
     * initialise the Banners module
     * This function is only ever called once during the lifetime of a particular
     * module instance
     *
     * @todo identify suitable indexes
     * @return bool true if successful, false otherwise
     */
    public function install() {
        // create the table
        if (!DBUtil::createTable('banners')) {
            return LogUtil::registerError($this->__f('Error! Could not create the table [%s].', 'banners'));
        }
        if (!DBUtil::createTable('bannersclient')) {
            return LogUtil::registerError($this->__f('Error! Could not create the table [%s].', 'bannersclient'));
        }

        // set default mod values
        if (!empty($_SERVER['HTTP_X_FORWARD_FOR'])) {
            $currentip = $_SERVER['HTTP_X_FORWARD_FOR'];
        } else {
            $currentip = $_SERVER['REMOTE_ADDR'];
        }
        ModUtil::setVar('Banners', 'myIP', array($currentip));
        ModUtil::setVar('Banners', 'banners', true); // active
        ModUtil::setVar('Banners', 'openinnewwindow', false);
        ModUtil::setVar('Banners', 'enablecats', true);

        $result = Banners_Util::createCategories();
        if ($result) {
            LogUtil::registerStatus($this->__('IAB Banner Types entered into Categories module.'));
        }

        // register plugins with View so plugins can be used systemwide
        EventUtil::registerPersistentModuleHandler('Banners', 'view.init', array('Banners_Util', 'registerPluginDir'));
        EventUtil::registerPersistentModuleHandler('Banners', 'module.content.gettypes', array('Banners_Util', 'getTypes'));

        // Initialisation successful
        LogUtil::registerStatus($this->__('Banners module installed'));
        return true;
    }

    /**
     * upgrade the Banners module from an old version
     * This function can be called multiple times
     * @return bool true if successful, false otherwise
     */
    public function upgrade($oldversion) {

        // Upgrade dependent on old version number
        switch ($oldversion) {
            case '1.0': // version 1.0 was shipped with PN .7x
            case '1.0.0':
                // migrate the old config vars into module vars
                if (Config::getVar('banners') != '') {
                    $myIP = Config::getVar('myIP');
                    $banners = Config::getVar('banners');
                    ModUtil::setVar('Banners', 'myIP', array($myIP));
                    ModUtil::setVar('Banners', 'banners', $banners);
                    ModUtil::setVar('Banners', 'openinnewwindow', false);
                    Config::delVar('myIP');
                    Config::delVar('banners');
                }
            case '2.0.0': // there was no version 2 afaik
            case '2.1':   // known version
            case '2.1.0':
            case '2.2':
                ModUtil::setVar('Banners', 'enablecats', true);
                if (!DBUtil::changeTable('banners')) {
                    LogUtil::registerError($this->__('Error! Could not upgrade the tables.'));
                }
                $oldtypes = $this->_setupOldTypes();
                $cats = Banners_Util::createCategories($oldtypes); // install module types into Categories mod
                if ($cats) {
                    LogUtil::registerStatus($this->__('IAB Banner Types entered into Categories module.'));
                    if ($this->_updateBanners($cats)) {
                        LogUtil::registerStatus($this->__('Banners updated to new Banner Types'));
                    } else {
                        LogUtil::registerError($this->__('Error! Could not update Banners to new Banner Types'));
                    }
                }

                if (!DBUtil::changeTable('bannersclient')){
                    LogUtil::registerError($this->__('Error! Could not upgrade the tables.'));
                }
                $currentuser = UserUtil::getVar('uid');
                $tables = DBUtil::getTables();
                $tableName = $tables['bannersclient'];
                $column = $tables["bannersclient_column"]['uid'];
                $sql = "UPDATE $tableName SET $column='$currentuser'"; // associates existing clients with current user
                if (DBUtil::executeSQL($sql)) {
                    $url = ModUtil::url('Banners', 'admin');
                    $linktext = $this->__('Banners admin interface');
                    LogUtil::registerStatus($this->__f('You must manually reassociate ALL your clients with a Zikula Username in the <a href="%1$s">%2$s</a>.', array(
                                        $url, $linktext)));
                } else {
                    LogUtil::registerError($this->__('Error! Unable to reassociate clients with Current User.'));
                }

                if ($this->_moveFinished($cats)) {
                    LogUtil::registerStatus($this->__('Old finished banners moved to inactive.'));
                } else {
                    LogUtil::registerError($this->__('Error! Could not move old finished banners to inactive.'));
                }
                DBUtil::dropTable('bannersfinish');

                ModUtil::setVar('Banners', 'banners', true);  // assume upgrade = enabled
                $myIP = ModUtil::getVar('Banners', 'myIP');
                if (!is_array($myIP)) {
                    ModUtil::setVar('Banners', 'myIP', array($myIP));
                }

                // register plugins with View so plugins can be used systemwide
                EventUtil::registerPersistentModuleHandler('Banners', 'view.init', array('Banners_Util', 'registerPluginDir'));
                EventUtil::registerPersistentModuleHandler('Banners', 'module.content.gettypes', array('Banners_Util', 'getTypes'));
                // upgrade the Content plugin names
                if (ModUtil::available('Content')) {
                    Content_Installer::updateContentType('Banners');
                }

            case '3.0.0':
            // future development
        }

        // Update successful
        return true;
    }

    /**
     * delete the Banners module
     * This function is only ever called once during the lifetime of a particular
     * module instance
     * @return bool true if successful, false otherwise
     */
    public function uninstall() {
        // drop the tables for the module
        DBUtil::dropTable('banners');
        DBUtil::dropTable('bannersclient');

        // delete all module vars
        ModUtil::delVar('Banners');

        // Delete entries from category registry
        ModUtil::dbInfoLoad('Categories');
        DBUtil::deleteWhere('categories_registry', "crg_modname='Banners'");
        DBUtil::deleteWhere('categories_mapobj', "cmo_modname='Banners'");

        // unregister plugins dir
        EventUtil::unregisterPersistentModuleHandler('Banners', 'view.init', array('Banners_Util', 'registerPluginDir'));

        // Delete successful
        return true;
    }

    /**
     * Setup the old banner types (<v2.1) as Category arrays to be added
     *
     * @return array
     */
    private function _setupOldTypes() {
        $types = DBUtil::selectFieldArray('banners', 'imageurl', '', 'pn_type', TRUE, 'type');
        $catdef = array();
        foreach ($types as $type => $imageurl) {
            if (is_file($imageurl)) {
                $imageinfo = getimagesize($imageurl);
            } else {
                $imageinfo = FALSE;
            }
            if (!$imageinfo) {
                $imagewidth = 250; // default if can't find value
                $imageheight = 250; // default if can't find value
            } else {
                $imagewidth = $imageinfo[0];
                $imageheight = $imageinfo[1];
            }
            $catdef[] = array(
                'rootpath' => '/__SYSTEM__/General/IAB_Ad_Units',
                'name' => 'imported_' . $type,
                'value' => null,
                'displayname' => $this->__("imported_") . $type,
                'description' => $this->__("imported_") . $type,
                'attributes' => array(
                    'width' => $imagewidth,
                    'height' => $imageheight,
                    'time' => 15
                )
            );
        }
        return $catdef;
    }

    /**
     * update old banners (<v2.1) to new categorization (v3+)
     * and activate old banners
     *
     * @param array $cats (catname => catid)
     * @return bool success
     */
    private function _updateBanners($cats) {
        $cols = array(
            'bid',
            'type');
        $banners = DBUtil::selectObjectArray('banners', '', '', -1, -1, 'bid', null, null, $cols);
        $result = true;
        $count = 1;
        $updatedbanner = array();
        foreach ($banners as $bid => $banner) {
            $catkey = $this->__("imported_") . $banner['type'];
            $updatedbanner[$bid]['bid'] = $bid;
            $updatedbanner[$bid]['active'] = 1;
            $updatedbanner[$bid]['title'] = $this->__('unnamed_') . $count;
            $updatedbanner[$bid]['__CATEGORIES__'] = array('Main' => $cats[$catkey]);
            $updatedbanner[$bid]['__META__'] = array('module' => 'Banners');
            $count++;
        }

        return DBUtil::updateObjectArray($updatedbanner, 'banners', 'bid');
    }

    /**
     * move finished banners back to banners table as inactive
     * 
     * @param array $cats (catname => catid)
     * @return bool success
     */
    private function _moveFinished($cats) {
        $pfx = System::getVar('prefix');
        $sql = "SELECT * from " . $pfx . "_bannersfinish";
        if ($result = DBUtil::executeSQL($sql)) {
            $banners = DBUtil::marshallFieldArray($result);
            $result = true;
            $count = 1;
            foreach ($banners as $banner) {
                $newbanner = array(
                    '__CATEGORIES__' => array('Main' => $cats['Undefined']),
                    '__META__' => array('module' => 'Banners'),
                    'active' => 0,
                    'title' => $this->__f('Inactive Banner %s', $count),
                    'imageurl' => $this->__('undefined'),
                    'clickurl' => $this->__('undefined'),
                    'cid' => $banner['cid'],
                    'impmade' => $banner['impressions'],
                    'imptotal' => $banner['impressions'],
                    'clicks' => $banner['clicks'],
                    'bid' => null);
                $result = $result && ModUtil::apiFunc('Banners', 'admin', 'create', $newbanner);
                $count++;
            }
        }
        return $result;
    }

    public static function LegacyContentTypeMap()
    {
        $oldToNew = array(
            'banner' => 'Banner'
        );
        return $oldToNew;
    }

}