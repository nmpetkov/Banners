<?php
/**
 * @package      Banners
 * @version      $Id:
 * @author       Halbrook Technologies
 * @author       Devin Hayes
 * @author       Craig Heydenburg
 * @link         http://www.halbrooktech.com
 * @copyright    Copyright (C) 2010
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */

class Banners_Installer extends Zikula_Installer
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
            return false;
        }
        if (!DBUtil::createTable('bannersclient')) {
            return false;
        }
        if (!DBUtil::createTable('bannersfinish')) {
            return false;
        }

        $banners = ModUtil::getVar('banners');
        ModUtil::setVar('Banners', 'myIP', '127.0.0.1');
        ModUtil::setVar('Banners', 'banners', $banners);
        ModUtil::setVar('Banners', 'openinnewwinow', false);

        $util = new Banners_Util;
        $result = $util->createCategories();
        if ($result) {
            LogUtil::registerStatus($this->__('IAB Banner Types entered into Categories module.'));
        }

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
        // create the three tables
        $tables = array('banners', 'bannersclient', 'bannersfinish');
        foreach ($tables as $table) {
            if (!DBUtil::changeTable($table)) {
                return false;
            }
        }

        // Upgrade dependent on old version number
        switch($oldversion) {
            // version 1.0 was shipped with PN .7x
            case 1.0:
            // migrate the old config vars into module vars
                if (Config::getVar('banners') != '') {
                    $myIP = Config::getVar('myIP');
                    $banners = Config::getVar('banners');
                    ModUtil::setVar('Banners', 'myIP', $myIP);
                    ModUtil::setVar('Banners', 'banners', $banners);
                    ModUtil::setVar('Banners', 'openinnewwinow', false);
                    Config::delVar('myIP');
                    Config::delVar('banners');
                }
                $util = new Banners_Util;
                $result = $util->createCategories();

                // TODO should do something with existing banner types, I guess.
                if ($result) {
                    LogUtil::registerStatus($this->__('IAB Banner Types entered into Categories module.'));
                }
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
        // drop the three tables for the module
        $tables = array('banners', 'bannersclient', 'bannersfinish');
        foreach ($tables as $table) {
            if (!DBUtil::dropTable($table)) {
                return false;
            }
        }

        // delete all module vars
        ModUtil::delVar('Banners');

        // Delete entries from category registry
        ModUtil::dbInfoLoad('Categories');
        DBUtil::deleteWhere('categories_registry', "crg_modname='Banners'");
        DBUtil::deleteWhere('categories_mapobj', "cmo_modname='Banners'");

        // Delete successful
        return true;
    }
}