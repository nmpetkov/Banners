<?php

/**
 * @package      Banners
 * @author       Halbrook Technologies
 * @author       Michael D. Halbrook
 * @author       Devin Hayes
 * @author       Craig Heydenburg
 * @author       Mark West
 * @link         http://www.halbrooktech.com
 * @copyright    Copyright (C) 2010
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */
class Banners_Api_User extends Zikula_AbstractApi {

    /**
     * get all banners
     *
     * @param    int     $args['startnum']    (optional) first item to return
     * @param    int     $args['numitems']    (optional) number if items to return
     * @param    int     $args['catFilter']   (optional) banner blocktype
     * @param    int     $args['cid']         (optional) client id
     * @param    bool    $args['clientinfo']  (optional) include client info
     * @param    bool    $args['active']      (optional) default true return active banners (-1 to return all)
     *
     * @return   array   array of items, or false on failure
     */
    public function getall($args) {
        // Optional arguments.
        if (!isset($args['startnum']) || !is_numeric($args['startnum'])) {
            $args['startnum'] = 1;
        }
        if (!isset($args['numitems']) || !is_numeric($args['numitems'])) {
            $args['numitems'] = -1;
        }
        if (!isset($args['clientinfo']) || !is_bool($args['clientinfo'])) {
            $args['clientinfo'] = false;
        }
        if (!isset($args['catFilter']) || !is_array($args['catFilter'])) {
            $args['catFilter'] = array();
        }
        if (!isset($args['active'])) {
            $args['active'] = 1;
        }

        $items = array();

        // Security check
        if (!SecurityUtil::checkPermission('Banners::', '::', ACCESS_READ)) {
            return $items;
        }
        
        // before anything else, disable expired banners
        ModUtil::apiFunc('Banners', 'admin', 'disableExpired');

        // define the permission filter to apply
        $permFilter = array(array(
                'realm'          => 0,
                'component_left' => 'Banners',
                'instance_left'  => 'bid',
                'instance_right' => '',
                'level'          => ACCESS_READ));

        $wheres = array();
        $dbtable = DBUtil::getTables();
        $columns = $dbtable['banners_column'];
        // allow filtering by client id
        if (isset($args['cid'])) {
            $wheres[] = $columns['cid'] . '=' . DataUtil::formatForStore((int) $args['cid']);
        }
        if ($args['active'] == 1 || $args['active'] == 0) {
            $wheres[] = $columns['active'] . '=' . $args['active'];
        }

        $where = implode(' AND ', $wheres);

        // get the objects from the db
        if ($args['clientinfo']) {
            $joininfo[] = array(
                'join_table'          => 'bannersclient',
                'join_field'          => 'name',
                'object_field_name'   => 'name',
                'compare_field_table' => 'cid',
                'compare_field_join'  => 'cid');
            // cannot do category-based permissions here because cannot have join with Filter :-(
            $items = DBUtil::selectExpandedObjectArray('banners', $joininfo, $where, 'bid', $args['startnum'] - 1, $args['numitems'], '', $permFilter, $args['catFilter']);
        } else {
            $items = DBUtil::selectObjectArray('banners', $where, 'bid', $args['startnum'] - 1, $args['numitems'], '', $permFilter, $args['catFilter']);
        }

        // ease display of blocktype
        $lang = ZLanguage::getLanguageCode();
        foreach ($items as $key => $item) {
            $items[$key]['typename'] = $item['__CATEGORIES__']['Main']['display_name'][$lang];
        }

        if ($items === false) {
            return LogUtil::registerError($this->__('Error! Could not load items.'));
        }

        // Return the items
        return $items;
    }

    /**
     * Get an active banner
     *
     * @param $args['bid'] id of the banner
     * @param $args['cid'] id of the client (optional)
     * @param bool  $args['clientinfo']  (optional) include client info
     * @return mixed array if bid is valid, false otherwise
     */
    public function get($args) {
        // Argument check
        if (!isset($args['bid']) || !is_numeric($args['bid'])) {
            return LogUtil::registerArgsError();
        }
        if (!isset($args['clientinfo']) || !is_bool($args['clientinfo'])) {
            $args['clientinfo'] = false;
        }
        
        // before anything else, disable expired banners
        ModUtil::apiFunc('Banners', 'admin', 'disableExpired');

        // define the permission filter to apply
        $permFilter = array(array(
                'realm'          => 0,
                'component_left' => 'Banners',
                'instance_left'  => 'bid',
                'instance_right' => '',
                'level'          => ACCESS_READ));

        // get the banner
        if ($args['clientinfo']) {
            $join[] = array(
                'join_table'          => 'bannersclient',
                'join_field'          => 'name',
                'object_field_name'   => 'name',
                'compare_field_table' => 'cid',
                'compare_field_join'  => 'cid');

            $banner = DBUtil::selectExpandedObjectByID('banners', $join, $args['bid'], 'bid', '', $permFilter);
        } else {
            $banner = DBUtil::selectObjectByID('banners', $args['bid'], 'bid', '', $permFilter);
        }
        // ease display of blocktype
        $lang = ZLanguage::getLanguageCode();
        $banner['typename'] = $banner['__CATEGORIES__']['Main']['display_name'][$lang];

        // check the optional client id field
        if (isset($args['cid']) && is_numeric($args['cid']) && $banner['cid'] != $args['cid']) {
            return LogUtil::registerArgsError();
        }

        return $banner;
    }

    /**
     * count the number of active banners
     *
     * @return   integer   number of items held by this module
     */
    public function countitems($args) {
        if (!isset($args['catFilter']) || !is_array($args['catFilter'])) {
            $args['catFilter'] = array();
        }
        if (!isset($args['active'])) {
            $args['active'] = 1;
        }

        // before anything else, disable expired banners
        ModUtil::apiFunc('Banners', 'admin', 'disableExpired');
        
        $dbtable = DBUtil::getTables();
        $columns = $dbtable['banners_column'];
        $where = $columns['active'] . '=' . $args['active'];
        return DBUtil::selectObjectCount('banners', $where, '1', false, $args['catFilter']);
    }

    /**
     * get all banner clients
     *
     * @param    int     $args['startnum']   (optional) first item to return
     * @param    int     $args['numitems']   (optional) number if items to return
     * @return   array   array of items, or false on failure
     */
    public function getallclients($args) {
        // Optional arguments.
        if (!isset($args['startnum']) || !is_numeric($args['startnum'])) {
            $args['startnum'] = 1;
        }
        if (!isset($args['numitems']) || !is_numeric($args['numitems'])) {
            $args['numitems'] = -1;
        }

        $items = array();

        // Security check
        if (!SecurityUtil::checkPermission('Banners::', '::', ACCESS_READ)) {
            return $items;
        }

        // define the permission filter to apply
        $permFilter = array(array(
                'realm'          => 0,
                'component_left' => 'Banners',
                'instance_left'  => 'cid',
                'instance_right' => '',
                'level'          => ACCESS_READ));

        // get the objects from the db
        $items = DBUtil::selectObjectArray('bannersclient', '', 'cid', $args['startnum'] - 1, $args['numitems'], '', $permFilter);
        // get the active banner counts for each client
        foreach ($items as $key => $item) {
            $items[$key]['bannercount'] = DBUtil::selectObjectCountByID('banners', $item['cid'], 'cid');
            $uservars = UserUtil::getVars($item['uid']);
            $items[$key]['zuname'] = $uservars['uname'];
            $items[$key]['email'] = $uservars['email'];
        }
        if ($items === false) {
            return LogUtil::registerError($this->__('Error! Could not load items.'));
        }

        // Return the items
        return $items;
    }

    /**
     * Get a banner client
     *
     * @param $args['cid'] id of the banner client
     * @return mixed array if bid is valid, false otherwise
     */
    public function getclient($args) {
        // Argument check
        if (!isset($args['cid']) || !is_numeric($args['cid'])) {
            return LogUtil::registerArgsError();
        }

        // define the permission filter to apply
        $permFilter = array(array(
                'realm'          => 0,
                'component_left' => 'Banners',
                'instance_left'  => 'cid',
                'instance_right' => '',
                'level'          => ACCESS_READ));

        $client = DBUtil::selectObjectByID('bannersclient', $args['cid'], 'cid', '', $permFilter);
        $uservars = UserUtil::getVars($client['uid']);
        $client['zuname'] = $uservars['uname'];
        $client['email'] = $uservars['email'];
        return $client;
    }

    /**
     * utility function to count the number of items held by this module
     *
     * @return   integer   number of items held by this module
     */
    public function countclientitems($args) {
        return DBUtil::selectObjectCount('bannersclient', '');
    }

    /**
     * get all inactive banners
     *
     * @param    int     $args['startnum']   (optional) first item to return
     * @param    int     $args['numitems']   (optional) number if items to return
     * @return   array   array of items, or false on failure
     */
    public function getallfinished($args) {
        $args['active'] = 0;
        return $this->getall($args);
    }

    /**
     * Get an inactive banner
     *
     * @param $args['bid'] id of the banner
     * @return mixed array if bid is valid, false otherwise
     */
    public function getfinished($args) {
        $args['active'] = 0;
        return $this->get($args);
    }

    /**
     * count the number of inactive banners
     *
     * @return   integer   number of items held by this module
     */
    public function countfinisheditems($args) {
        $args['active'] = 0;
        return $this->countitems($args);
    }

    /**
     * register a click on a banner
     *
     * @author Devin Hayes
     * @param $args['bid'] id of the banner
     * @return bool true if successful, false otherwise
     *
     */
    public function click($args) {
        // Argument check
        if (!isset($args['bid']) || !is_numeric($args['bid'])) {
            return LogUtil::registerArgsError();
        }

        // check the current host and admin exceptions
        // do not register click if exception
        $myIParray = $this->getvar('myIP');
        $myhost = System::serverGetVar('REMOTE_ADDR');
        if (in_array($myhost, $myIParray)) {
            return true;
        }

        return DBUtil::incrementObjectFieldByID('banners', 'clicks', $args['bid'], 'bid');
    }

    /**
     * register an impression
     *
     * @param $args['bid'] id of the banner
     * @return bool true if successful, false otherwise
     */
    public function impmade($args) {
        // Argument check
        if (!isset($args['bid']) || !is_numeric($args['bid'])) {
            return LogUtil::registerArgsError();
        }

        return DBUtil::incrementObjectFieldByID('banners', 'impmade', $args['bid'], 'bid');
    }

    /**
     * update the url of a banner
     *
     * @param $args['bid'] banner id
     * @param $args['url'] new banner url
     * @return true if successful, false otherwise
     */
    public function changeurl($args) {
        // Argument check
        if (!isset($args['bid']) || !is_numeric($args['bid'])
                || !isset($args['url'])) {
            return LogUtil::registerArgsError();
        }

        // create object
        $obj = array();
        $obj['bid']      = $args['bid'];
        $obj['clickurl'] = $args['url'];

        // update object
        $res = DBUtil::updateObject($obj, 'banners', '', 'bid');

        return (boolean) $res;
    }

    /**
     * Mark the banner as inactive
     *
     * @param $args['bid'] banner id
     * @return true if successful, false otherwise
     */
    public function finish($args) {
        // Argument check
        if (!isset($args['bid']) || !is_numeric($args['bid'])) {
            return LogUtil::registerArgsError();
        }
        $obj = array();
        $obj['bid'] = $args['bid'];
        $obj['active'] = 0;

        // update object
        $res = DBUtil::updateObject($obj, 'banners', '', 'bid');

        return true;
    }

    /**
     * validate client login
     *
     * @return mixed client array if successful, false otherwise
     */
    public function validateclient() {
        $permFilter = array(array(
                'realm'          => 0,
                'component_left' => 'Banners',
                'instance_left'  => 'cid',
                'instance_right' => '',
                'level'          => ACCESS_READ));
        return DBUtil::selectObjectByID('bannersclient', UserUtil::getVar('uid'), 'uid', '', $permFilter);
    }

}