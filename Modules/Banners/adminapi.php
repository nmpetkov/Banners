<?php
/**
 * @package      Banners
 * @version      $Id:
 * @author       Halbrook Technologies
 * @link         http://www.halbrooktech.com
 * @copyright    Copyright (C) 2010
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */
class Banners_adminapi extends AbstractApi {
    /**
     * create a banner
     *
     * @author Devin Hayes
     * @param int $cid client id
     * @param int $idtype banner type id
     * @param int $imptotal total impressions purchased
     * @param string $imageurl source url of the banner image
     * @param string $clickurl destination url for the banner
     * @return string HTML output string
     */
    function Banners_adminapi_create($args) {
        // Argument check
        if (!isset($args['cid']) ||
                !isset($args['idtype']) ||
                !isset($args['imptotal']) ||
                !isset($args['imageurl']) ||
                !isset($args['clickurl'])) {
            return LogUtil::registerError (_MODARGSERROR);
        }

        // Security check
        if (!SecurityUtil::checkPermission('Banners::', '::', ACCESS_ADD)) {
            return LogUtil::registerPermissionError();
        }

        // create the item array
        $banner = array('cid' => $args['cid'],
                'type' => $args['idtype'],
                'imptotal' => $args['imptotal'],
                'imageurl' => $args['imageurl'],
                'clickurl' => $args['clickurl']);

        if (!DBUtil::insertObject($banner, 'banners', 'bid')) {
            return LogUtil::registerError (_CREATEFAILED);
        }

        // Return the id of the newly created item to the calling process
        return $banner['bid'];
    }

    /**
     * update a banner
     *
     * @author Devin Hayes
     * @param int $bid banner id
     * @param int $cid client id
     * @param int $idtype banner type id
     * @param int $impadded additional impressions purchased
     * @param int $imptotal total impressions purchased
     * @param string $imageurl source url of the banner image
     * @param string $clickurl destination url for the banner
     * @return string HTML output string
     */
    function Banners_adminapi_update($args) {
        // Argument check
        if (!isset($args['bid']) ||
                !isset($args['cid']) ||
                !isset($args['idtype']) ||
                !isset($args['imptotal']) ||
                !isset($args['impadded']) ||
                !isset($args['imageurl']) ||
                !isset($args['clickurl'])) {
            return LogUtil::registerError (_MODARGSERROR);
        }

        // Get the existing admin message
        $banner = pnModAPIFunc('Banners', 'user', 'get', array('bid' => $args['bid']));

        if ($banner == false) {
            return LogUtil::registerError (_NOSUCHITEM);
        }

        // Security check
        if (!SecurityUtil::checkPermission('Banners::', "$bid::", ACCESS_EDIT)) {
            return LogUtil::registerPermissionError();
        }

        // create the item array
        $banner = array('bid' => $args['bid'],
                'cid' => $args['cid'],
                'type' => $args['idtype'],
                'imptotal' => $args['imptotal'],
                'imageurl' => $args['imageurl'],
                'clickurl' => $args['clickurl']);
        $banner['imptotal'] += $args['impadded'];

        if (!DBUtil::updateObject($banner, 'banners', '', 'bid')) {
            return LogUtil::registerError (_UPDATEFAILED);
        }

        return true;
    }

    /**
     * delete a banner
     *
     * @author Devin Hayes
     * @param int $bid banner id
     * @return bool true on success, false on failure
     */
    function Banners_adminapi_delete($args) {
        // Argument check
        if (!isset($args['bid'])) {
            return LogUtil::registerError (_MODARGSERROR);
        }

        // Get the existing admin message
        $banner = pnModAPIFunc('Banners', 'user', 'get', array('bid' => $args['bid']));

        if ($banner == false) {
            return LogUtil::registerError (_NOSUCHITEM);
        }

        // Security check
        if (!SecurityUtil::checkPermission('Banners::', "$args[bid]::", ACCESS_DELETE)) {
            return LogUtil::registerPermissionError();
        }

        if (!DBUtil::deleteObjectByID('banners', $args['bid'], 'bid')) {
            return LogUtil::registerError (_DELETEFAILED);
        }

        // Let the calling process know that we have finished successfully
        return true;
    }

    /**
     * delete all banners for a client
     *
     * @author Devin Hayes
     * @param int $cid client id
     * @return bool true on success, false on failure
     */
    function Banners_adminapi_deleteall($args) {
        // Argument check
        if (!isset($args['cid'])) {
            return LogUtil::registerError (_MODARGSERROR);
        }

        // Security check
        if (!SecurityUtil::checkPermission('Banners::Client', "$args[cid]::", ACCESS_DELETE)) {
            return LogUtil::registerPermissionError();
        }

        if (!DBUtil::deleteObjectByID('banners', $args['cid'], 'cid')) {
            return LogUtil::registerError (_DELETEFAILED);
        }

        // Let the calling process know that we have finished successfully
        return true;
    }

    /********************* client functions *************************/

    /**
     * create a client
     *
     * @author Devin Hayes
     * @param int $cname client name
     * @param int $contact client contact name
     * @param int $email client contact e-mail
     * @param string $login client login name
     * @param string $password client login password
     * @param string $extrainfo additional client info
     * @return mixed int client id if successful, false otherwise
     */
    function Banners_adminapi_createclient($args) {
        // Argument check
        if (!isset($args['cname']) ||
                !isset($args['contact']) ||
                !isset($args['email']) ||
                !isset($args['login']) ||
                !isset($args['passwd']) ||
                !isset($args['extrainfo'])) {
            return LogUtil::registerError (_MODARGSERROR);
        }

        // Security check
        if (!SecurityUtil::checkPermission('Banners::Client', '::', ACCESS_ADD)) {
            return LogUtil::registerPermissionError();
        }

        // create the item array
        $client = array('name' => $args['cname'],
                'contact' => $args['contact'],
                'email' => $args['email'],
                'login' => $args['login'],
                'passwd' => $args['passwd'],
                'extrainfo' => $args['extrainfo']);

        if (!DBUtil::insertObject($client, 'bannersclient', 'cid')) {
            return LogUtil::registerError (_CREATEFAILED);
        }

        // Return the id of the newly created item to the calling process
        return $client['cid'];
    }

    /**
     * update a banner
     *
     * @author Devin Hayes
     * @param int $cid client id
     * @param int $cname client name
     * @param int $contact client contact name
     * @param int $email client contact e-mail
     * @param string $login client login name
     * @param string $password client login password
     * @param string $extrainfo additional client info
     * @return bool true if successful, false otherwise
     */
    function Banners_adminapi_updateclient($args) {
        // Argument check
        if (!isset($args['cid']) ||
                !isset($args['cname']) ||
                !isset($args['contact']) ||
                !isset($args['email']) ||
                !isset($args['login']) ||
                !isset($args['passwd']) ||
                !isset($args['extrainfo'])) {
            return LogUtil::registerError (_MODARGSERROR);
        }

        // Get the existing admin message
        $client = pnModAPIFunc('Banners', 'user', 'getclient', array('cid' => $args['cid']));

        if ($client == false) {
            return LogUtil::registerError (_NOSUCHITEM);
        }

        // Security check
        if (!SecurityUtil::checkPermission('Banners::Client', "$args[cid]::", ACCESS_EDIT)) {
            return LogUtil::registerPermissionError();
        }

        // create the new item array
        $client = array('cid' => $args['cid'],
                'name' => $args['cname'],
                'contact' => $args['contact'],
                'email' => $args['email'],
                'login' => $args['login'],
                'passwd' => $args['passwd'],
                'extrainfo' => $args['extrainfo']);

        if (!DBUtil::updateObject($client, 'bannersclient', '', 'cid')) {
            return LogUtil::registerError (_UPDATEFAILED);
        }

        return true;
    }

    /**
     * delete a client
     *
     * @author Devin Hayes
     * @param int $cid client id
     * @return bool true on success, false on failure
     */
    function Banners_adminapi_deleteclient($args) {
        // Argument check
        if (!isset($args['cid'])) {
            return LogUtil::registerError (_MODARGSERROR);
        }

        // Get the existing admin message
        $client = pnModAPIFunc('Banners', 'user', 'getclient', array('cid' => $args['cid']));

        if ($client == false) {
            return LogUtil::registerError (_NOSUCHITEM);
        }

        // Security check
        if (!SecurityUtil::checkPermission('Banners::Client', "$args[cid]::", ACCESS_DELETE)) {
            return LogUtil::registerPermissionError();
        }

        // delete any banners for this client first
        if (!pnModAPIFunc('Banners', 'admin', 'deleteall', array('cid' => $args['cid']))) {
            return LogUtil::registerError (_DELETEFAILED);
        }

        // now delete the client
        if (!DBUtil::deleteObjectByID('bannersclient', $args['cid'], 'cid')) {
            return LogUtil::registerError (_DELETEFAILED);
        }

        // Let the calling process know that we have finished successfully
        return true;
    }

    /******************* finished banners functions ************************/

    /**
     * delete a finished banner
     *
     * @author Devin Hayes
     * @param int $bid banner id
     * @return bool true on success, false on failure
     */
    function Banners_adminapi_deletefinished($args) {
        // Argument check
        if (!isset($args['bid'])) {
            return LogUtil::registerError (_MODARGSERROR);
        }

        // Get the existing admin message
        $banner = pnModAPIFunc('Banners', 'user', 'getfinished', array('bid' => $args['bid']));

        if ($banner == false) {
            return LogUtil::registerError (_NOSUCHITEM);
        }

        // Security check
        if (!SecurityUtil::checkPermission('Banners::', "$args[bid]::", ACCESS_DELETE)) {
            return LogUtil::registerPermissionError();
        }

        if (!DBUtil::deleteObjectByID('bannersfinished', $args['bid'], 'bid')) {
            return LogUtil::registerError (_DELETEFAILED);
        }

        // Let the calling process know that we have finished successfully
        return true;
    }

    /**
     * get available admin panel links
     *
     * @author Mark West
     * @return array array of admin links
     */
    function Banners_adminapi_getlinks() {
        $links = array();

        pnModLangLoad('Banners', 'admin');

        if (SecurityUtil::checkPermission('Banners::', '::', ACCESS_READ)) {
            $links[] = array('url' => pnModURL('Banners', 'admin', 'view'), 'text' => _BANNERS_VIEW);
        }
        if (SecurityUtil::checkPermission('Banners::', '::', ACCESS_ADD)) {
            $links[] = array('url' => pnModURL('Banners', 'admin', 'new'), 'text' => _BANNERS_ADD);
        }
        if (SecurityUtil::checkPermission('Banners::', '::', ACCESS_ADMIN)) {
            $links[] = array('url' => pnModURL('Banners', 'admin', 'modifyconfig'), 'text' => _MODIFYCONFIG);
        }

        return $links;
    }
}