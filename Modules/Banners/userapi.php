<?php
/**
 * @package      Banners
 * @version      $Id:
 * @author       Halbrook Technologies
 * @link         http://www.halbrooktech.com
 * @copyright    Copyright (C) 2010
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */
class Banners_user extends AbstractApi {
    /**
     * get all banners
     *
     * @author Mark West
     * @param    int     $args['startnum']   (optional) first item to return
     * @param    int     $args['numitems']   (optional) number if items to return
     * @param    int     $args['type']          (optional) banner type
     * @param    int     $args['cid']            (optional) client id
     * @param    bool  $args['clientinfo']  (optional) include client info
     *
     * @return   array   array of items, or false on failure
     */
    function Banners_userapi_getall($args) {
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

        $items = array();

        // Security check
        if (!SecurityUtil::checkPermission('Banners::', '::', ACCESS_READ)) {
            return $items;
        }

        // define the permission filter to apply
        $permFilter = array(array('realm'          => 0,
                        'component_left' => 'Banners',
                        'instance_left'  => 'bid',
                        'instance_right' => '',
                        'level'          => ACCESS_READ));

        $wheres = array();
        // allow filtering by banner type
        if (isset($args['type'])) {
            $wheres[] = 'pn_type=\''.DataUtil::formatForStore($args['type']).'\'';
        }
        // allow filtering by client id
        if (isset($args['cid'])) {
            $wheres[] = 'pn_cid='.DataUtil::formatForStore((int)$args['cid']);
        }
        $where = implode (' AND ', $wheres);

        // get the objects from the db
        if ($args['clientinfo']) {
            $joininfo[] = array ('join_table'          =>  'bannersclient',
                    'join_field'          =>  'name',
                    'object_field_name'   =>  'cname',
                    'compare_field_table' =>  'cid',
                    'compare_field_join'  =>  'cid');

            $items = DBUtil::selectExpandedObjectArray('banners', $joininfo, $where, 'bid', $args['startnum']-1, $args['numitems'], '', $permFilter);
        } else {
            $items = DBUtil::selectObjectArray('banners', $where, 'bid', $args['startnum']-1, $args['numitems'], '', $permFilter);
        }

        if ($items === false) {
            return LogUtil::registerError (_GETFAILED);
        }

        // Return the items
        return $items;
    }

    /**
     * Get a banner
     *
     * @author Devin Hayes
     * @param $args['bid'] id of the banner
     * @param $args['cid'] id of the client (optional)
     * @param bool  $args['clientinfo']  (optional) include client info
     * @return mixed array if bid is valid, false otherwise
     */
    function Banners_userapi_get($args) {
        // Argument check
        if (!isset($args['bid']) || !is_numeric($args['bid'])) {
            return LogUtil::registerError (_MODARGSERROR);
        }
        if (!isset($args['clientinfo']) || !is_bool($args['clientinfo'])) {
            $args['clientinfo'] = false;
        }

        // define the permission filter to apply
        $permFilter = array(array('realm'          => 0,
                        'component_left' => 'Banners',
                        'instance_left'  => 'bid',
                        'instance_right' => '',
                        'level'          => ACCESS_READ));

        // get the banner
        if ($args['clientinfo']) {
            $join[]     = array ('join_table'          =>  'bannersclient',
                    'join_field'          =>  'name',
                    'object_field_name'   =>  'cname',
                    'compare_field_table' =>  'cid',
                    'compare_field_join'  =>  'cid');

            $banner = DBUtil::selectExpandedObjectByID('banners', $join, $args['bid'], 'bid', '', $permFilter);
        } else {
            $banner = DBUtil::selectObjectByID('banners', $args['bid'], 'bid', '', $permFilter);
        }

        // check the optional client id field
        if (isset($args['cid']) && is_numeric($args['cid']) && $banner['cid'] != $args['cid']) {
            return LogUtil::registerError (_MODARGSERROR);
        }

        return $banner;
    }

    /**
     * utility function to count the number of items held by this module
     *
     * @author Devin Hayes
     * @param    int     $args['type']       (optional) banner type
     * @todo     add support for the banner type parameter
     * @return   integer   number of items held by this module
     */
    function Banners_userapi_countitems($args) {
        // allow filtering by banner type
        (isset($args['type'])) ? $w = "pn_type='".DataUtil::formatForStore($args['type'])."'" : $w = '';

        return DBUtil::selectObjectCount('banners', $w);
    }

    /**
     * get all banner clients
     *
     * @author Mark West
     * @param    int     $args['startnum']   (optional) first item to return
     * @param    int     $args['numitems']   (optional) number if items to return
     * @return   array   array of items, or false on failure
     */
    function Banners_userapi_getallclients($args) {
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
        $permFilter = array(array('realm'          => 0,
                        'component_left' => 'Banners',
                        'instance_left'  => 'cid',
                        'instance_right' => '',
                        'level'          => ACCESS_READ));

        // get the objects from the db
        $items = DBUtil::selectObjectArray('bannersclient', '', 'cid', $args['startnum']-1, $args['numitems'], '', $permFilter);

        // get the active banner counts for each client
        foreach ($items as $key => $item) {
            $items[$key]['bannercount'] = DBUtil::selectObjectCountByID('banners', $item['cid'], 'cid');
        }
        if ($items === false) {
            return LogUtil::registerError (_GETFAILED);
        }

        // Return the items
        return $items;
    }

    /**
     * Get a banner client
     *
     * @author Mark West
     * @param $args['cid'] id of the banner client
     * @return mixed array if bid is valid, false otherwise
     */
    function Banners_userapi_getclient($args) {
        // Argument check
        if (!isset($args['cid']) || !is_numeric($args['cid'])) {
            return LogUtil::registerError (_MODARGSERROR);
        }

        // define the permission filter to apply
        $permFilter = array(array('realm'          => 0,
                        'component_left' => 'Banners',
                        'instance_left'  => 'cid',
                        'instance_right' => '',
                        'level'          => ACCESS_READ));

        return DBUtil::selectObjectByID('bannersclient', $args['cid'], 'cid', '', $permFilter);
    }

    /**
     * utility function to count the number of items held by this module
     *
     * @author Devin Hayes
     * @return   integer   number of items held by this module
     */
    function Banners_userapi_countclientitems($args) {
        return DBUtil::selectObjectCount('bannersclient', '');
    }

    /**
     * get all banners
     *
     * @author Mark West
     * @param    int     $args['startnum']   (optional) first item to return
     * @param    int     $args['numitems']   (optional) number if items to return
     * @return   array   array of items, or false on failure
     */
    function Banners_userapi_getallfinished($args) {
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
        $permFilter = array(array('realm'          => 0,
                        'component_left' => 'Banners',
                        'instance_left'  => 'bid',
                        'instance_right' => '',
                        'level'          => ACCESS_READ));

        // get the objects from the db
        $items = DBUtil::selectObjectArray('bannersfinish', '', 'bid', $args['startnum']-1, $args['numitems'], '', $permFilter);

        if ($items === false) {
            return LogUtil::registerError (_GETFAILED);
        }

        // Return the items
        return $items;
    }

    /**
     * Get a banner
     *
     * @author Devin Hayes
     * @param $args['bid'] id of the banner
     * @return mixed array if bid is valid, false otherwise
     */
    function Banners_userapi_getfinished($args) {
        // Argument check
        if (!isset($args['bid']) || !is_numeric($args['bid'])) {
            return LogUtil::registerError (_MODARGSERROR);
        }

        // define the permission filter to apply
        $permFilter = array(array('realm'          => 0,
                        'component_left' => 'Banners',
                        'instance_left'  => 'bid',
                        'instance_right' => '',
                        'level'          => ACCESS_READ));

        return DBUtil::selectObjectByID('bannersfinish', $args['bid'], 'bid', '', $permFilter);
    }

    /**
     * utility function to count the number of items held by this module
     *
     * @author Devin Hayes
     * @return   integer   number of items held by this module
     */
    function Banners_userapi_countfinisheditems($args) {
        return DBUtil::selectObjectCount('bannersfinish', '');
    }

    /**
     * register a click on a banner
     *
     * @author Devin Hayes
     * @param $args['bid'] id of the banner
     * @return bool true if successful, false otherwise
     *
     */
    function Banners_userapi_click($args) {
        // Argument check
        if (!isset($args['bid']) || !is_numeric($args['bid'])) {
            return LogUtil::registerError (_MODARGSERROR);
        }

        return DBUtil::incrementObjectFieldByID('banners', 'clicks', $args['bid'], 'bid');
    }

    /*
 * register an impression
 *
 * @author Devin Hayes
 * @param $args['bid'] id of the banner
 * @return bool true if successful, false otherwise
    */
    function Banners_userapi_impmade($args) {
        // Argument check
        if (!isset($args['bid']) || !is_numeric($args['bid'])) {
            return LogUtil::registerError (_MODARGSERROR);
        }

        return DBUtil::incrementObjectFieldByID('banners', 'impmade', $args['bid'], 'bid');
    }

    /**
     * send banner stats to the client
     *
     * @author Devin Hayes
     * @param $args['bid'] id of the banner
     * @param $args['cid'] id of the client
     * @return bool true if successful, false otherwise
     */
    function Banners_userapi_emailstats($args) {
        // Argument check
        if (!isset($args['bid']) || !is_numeric($args['bid']) ||
                !isset($args['cid']) || !is_numeric($args['cid'])) {
            return LogUtil::registerError (_MODARGSERROR);
        }

        $banner = pnModAPIFunc('Banners', 'user', 'get', array('bid' => $args['bid'], 'cid' => $args['cid']));
        $client = pnModAPIFunc('Banners', 'user', 'getclient', array('cid' => $args['cid']));
        if (!$banner) {
            return LogUtil::registerError (_MODARGSERROR);
        }

        // calculate some additional values
        if ($banner['impmade'] == 0) {
            $banner['percent'] = 0;
        } else {
            $banner['percent'] = substr(100 * $banner['clicks'] / $banner['impmade'], 0, 5);
        }

        if ($banner['imptotal'] == 0) {
            $banner['left'] =_BANNERS_UNLIMITED;
            $banner['imptotal'] = _BANNERS_UNLIMITED;
        } else {
            $banner['left'] = $banner['imptotal']-$banner['impmade'];
        }

        $pnRender = pnRender::getInstance('Banners', false);
        $pnRender->assign('banner', $banner);
        $pnRender->assign('client', $client);
        $pnRender->assign('date', date("F jS Y, h:iA."));
        $subject = $pnRender->fetch('banners_userapi_emailstats_subject.htm');
        $message = $pnRender->fetch('banners_userapi_emailstats_body.htm');
        $mailsent = pnModAPIFunc('Mailer', 'user', 'sendmessage',
                array('toaddress' => $client['email'], 'toname' => $client['contact'],
                'subject' => $subject, 'body' => $message));
        if ($mailsent) {
            return true;
        }
    }

    /**
     * update the url of a banner
     *
     * @author Devin Hayes
     * @param $args['bid'] banner id
     * @param $args['url'] new banner url
     * @return true if successful, false otherwise
     */
    function Banners_userapi_changeurl($args) {
        // Argument check
        if (!isset($args['bid']) || !is_numeric($args['bid'])
                || !isset($args['url'])) {
            return LogUtil::registerError (_MODARGSERROR);
        }

        // create object
        $obj = array();
        $obj['bid']      = $args['bid'];
        $obj['clickurl'] = $args['url'];

        // update object
        $res = DBUtil::updateObject ($obj, 'banners', 'bid');

        return (boolean)$res;
    }

    /**
     * Move a banner to the finished banners table
     *
     * @author Devin Hayes
     * @param $args['bid'] banner id
     * @return true if successful, false otherwise
     */
    function Banners_userapi_finish($args) {
        // Argument check
        if (!isset($args['bid']) || !is_numeric($args['bid'])) {
            return LogUtil::registerError (_MODARGSERROR);
        }

        // get the banner
        $banner = pnModAPIFunc('Banners', 'user', 'get', array('bid' => $args['bid']));

        // create object
        $obj = array();
        $obj['cid']         = $banner['cid'];
        $obj['impressions'] = $banner['impmade'];
        $obj['clicks']      = $banner['clicks'];
        $obj['datestart']   = $banner['date'];

        // insert object
        $res = DBUtil::insertObject ($obj, 'bannersfinish', 'bid');
        if ($res === false) {
            return false;
        }

        // delete the banner
        pnModAPIFunc('Banners', 'user', 'delete', array('bid' => $args['bid']));

        return true;
    }

    /**
     * validate client login
     *
     * @author Devin Hayes
     * @param $args['login']    client login
     * @param $args['password'] client password
     * @return mixed client array if successful, false otherwise
     */
    function banners_userapi_validateclient($args) {
        // Argument check
        if (!isset($args['login']) || !isset($args['pass'])) {
            return LogUtil::registerError (_MODARGSERROR);
        }

        $pntable = pnDBGetTables();
        $column = $pntable['bannersclient_column'];

        $where  = "$column[login] = '".DataUtil::formatForStore($args['login'])."' AND
                $column[passwd] = '".DataUtil::formatForStore($args['pass'])."'";
        $client = DBUtil::selectObject ('bannersclient', $where);
        if (!$client) {
            return false;
        }

        return $client;

    }
}
