<?php
/**
 * @package      Banners
 * @version      $Id:
 * @author       Devin Hayes
 * @author       Halbrook Technologies
 * @author       Craig Heydenburg
 * @author       Mark West
 * @link         http://www.halbrooktech.com
 * @copyright    Copyright (C) 2010
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */
class Banners_Api_Admin extends Zikula_Api {
    /**
     * get available admin panel links
     *
     * @return array array of admin links
     */
    public function getlinks() {
        $links = array();

        if (SecurityUtil::checkPermission('Banners::', '::', ACCESS_READ)) {
            $links[] = array(
                'url' => ModUtil::url('Banners', 'admin', 'overview'),
                'text' => $this->__('Banner List'),
                'class' => 'z-icon-es-list');
        }
        if (SecurityUtil::checkPermission('Banners::', '::', ACCESS_ADD)) {
            $links[] = array(
                'url' => ModUtil::url('Banners', 'admin', 'newclient'),
                'text' => $this->__('New Client'),
                'class' => 'z-icon-es-new');
        }
        if (SecurityUtil::checkPermission('Banners::', '::', ACCESS_ADD)) {
            $clients = ModUtil::apiFunc('Banners', 'user', 'getallclients');
            if (empty($clients)) {
                $links[] = array(
                    'url' => ModUtil::url('Banners', 'admin', 'newentry'),
                    'text' => $this->__('New Banner'),
                    'class' => 'z-icon-es-new',
                    'title' => $this->__('Create Client First'),
                    'disabled' => 'disabled');
            } else {
                $links[] = array(
                    'url' => ModUtil::url('Banners', 'admin', 'newentry'),
                    'text' => $this->__('New Banner'),
                    'class' => 'z-icon-es-new');
            }

        }
        if (SecurityUtil::checkPermission('Banners::', '::', ACCESS_ADMIN)) {
            $links[] = array(
                'url' => ModUtil::url('Banners', 'admin', 'modifyconfig'),
                'text' => $this->__('Module Configuration'),
                'class' => 'z-icon-es-config');
        }
        if (SecurityUtil::checkPermission('Banners::', '::', ACCESS_ADMIN)) {
            $links[] = array(
                'url' => 'http://code.zikula.org/banners/wiki/FeatureDocs300',
                'text' => $this->__('Documentation'),
                'class' => 'z-icon-es-info');
        }

        return $links;
    }
    
    /**
     * create a banner
     *
     * @param int $cid client id
     * @param int $idtype banner type id
     * @param int $imptotal total impressions purchased
     * @param string $imageurl source url of the banner image
     * @param string $clickurl destination url for the banner
     * @return string HTML output string
     */
    public function create($args) {
        // Argument check
        if (!isset($args['cid']) ||
                !isset($args['title']) ||
                !isset($args['imptotal']) ||
                !isset($args['imageurl']) ||
                !isset($args['clickurl'])) {
            return LogUtil::registerArgsError();
        }

        // Security check
        if (!SecurityUtil::checkPermission('Banners::', '::', ACCESS_ADD)) {
            return LogUtil::registerPermissionError();
        }

        if (!DBUtil::insertObject($args, 'banners', 'bid')) {
            return LogUtil::registerError($this->__('Error! Creation attempt failed.'));
        }

        // Return the id of the newly created item to the calling process
        return $banner['bid'];
    }

    /**
     * update a banner
     *
     * @param int $bid banner id
     * @param int $cid client id
     * @param int $idtype banner type id
     * @param int $impadded additional impressions purchased
     * @param int $imptotal total impressions purchased
     * @param string $imageurl source url of the banner image
     * @param string $clickurl destination url for the banner
     * @return string HTML output string
     */
    public function update($args) {
        // Argument check
        
        if (!isset($args['bid']) ||
                !isset($args['cid']) ||
                !isset($args['title']) ||
                !isset($args['imptotal']) ||
                !isset($args['impadded']) ||
                !isset($args['imageurl']) ||
                !isset($args['clickurl'])) {
            return LogUtil::registerArgsError();
        }

        // Get the existing banner
        $banner = ModUtil::apiFunc('Banners', 'user', 'get', array('bid' => $args['bid']));

        if ($banner == false) {
            return LogUtil::registerError($this->__('No such item found.'));
        }

        // Security check
        if (!SecurityUtil::checkPermission('Banners::', "$bid::", ACCESS_EDIT)) {
            return LogUtil::registerPermissionError();
        }

        $args['imptotal'] += $args['impadded'];

        if (!DBUtil::updateObject($args, 'banners', '', 'bid')) {
            return LogUtil::registerError($this->__('Error! Update attempt failed.'));
        }

        return true;
    }

    /**
     * delete a banner
     *
     * @param int $bid banner id
     * @return bool true on success, false on failure
     */
    public function delete($args) {
        // Argument check
        if (!isset($args['bid'])) {
            return LogUtil::registerArgsError();
        }

        // Get the existing banner
        // this is likely unneeded
        $banner = ModUtil::apiFunc('Banners', 'user', 'get', array('bid' => $args['bid']));

        if ($banner == false) {
            return LogUtil::registerError($this->__('No such item found.'));
        }

        // Security check
        if (!SecurityUtil::checkPermission('Banners::', "$args[bid]::", ACCESS_DELETE)) {
            return LogUtil::registerPermissionError();
        }

        if (!DBUtil::deleteObjectByID('banners', $args['bid'], 'bid')) {
            return LogUtil::registerError($this->__('Error! Deletion attempt failed.'));
        }

        // Let the calling process know that we have finished successfully
        return true;
    }

    /**
     * delete all banners for a client
     *
     * @param int $cid client id
     * @return bool true on success, false on failure
     */
    public function deleteall($args) {
        // Argument check
        if (!isset($args['cid'])) {
            return LogUtil::registerArgsError();
        }

        // Security check
        if (!SecurityUtil::checkPermission('Banners::Client', "$args[cid]::", ACCESS_DELETE)) {
            return LogUtil::registerPermissionError();
        }

        if (!DBUtil::deleteObjectByID('banners', $args['cid'], 'cid')) {
            return LogUtil::registerError($this->__('Error! Deletion attempt failed.'));
        }

        // Let the calling process know that we have finished successfully
        return true;
    }

    /**
     * create a client
     *
     * @param int $name client name
     * @param int $contact client contact name
     * @param int $email client contact e-mail
     * @param string $login client login name
     * @param string $password client login password
     * @param string $extrainfo additional client info
     * @return mixed int client id if successful, false otherwise
     */
    public function createclient($args) {
        // Argument check
        if (!isset($args['name']) ||
            !isset($args['contact']) ||
            !isset($args['uid']) ||
            !isset($args['extrainfo'])) {
            return LogUtil::registerArgsError();
        }

        // Security check
        if (!SecurityUtil::checkPermission('Banners::Client', '::', ACCESS_ADD)) {
            return LogUtil::registerPermissionError();
        }

        // create the item array
        $client = array(
                'name'      => $args['name'],
                'contact'   => $args['contact'],
                'uid'       => $args['uid'],
                'extrainfo' => $args['extrainfo']);

        if (!DBUtil::insertObject($client, 'bannersclient', 'cid')) {
            return LogUtil::registerError($this->__('Error! Creation attempt failed.'));
        }

        // Return the id of the newly created item to the calling process
        return $client['cid'];
    }

    /**
     * update a banner
     *
     * @param int $cid client id
     * @param int $name client name
     * @param int $contact client contact name
     * @param int $email client contact e-mail
     * @param string $login client login name
     * @param string $password client login password
     * @param string $extrainfo additional client info
     * @return bool true if successful, false otherwise
     */
    public function updateclient($args) {
        // Argument check
        if (!isset($args['cid']) ||
            !isset($args['name']) ||
            !isset($args['contact']) ||
            !isset($args['uid']) ||
            !isset($args['extrainfo'])) {
            return LogUtil::registerArgsError();
        }

        // Get the existing banner
        $client = ModUtil::apiFunc('Banners', 'user', 'getclient', array('cid' => $args['cid']));

        if ($client == false) {
            return LogUtil::registerError($this->__('No such item found.'));
        }

        // Security check
        if (!SecurityUtil::checkPermission('Banners::Client', "$args[cid]::", ACCESS_EDIT)) {
            return LogUtil::registerPermissionError();
        }

        // create the new item array
        $client = array(
                'cid'       => $args['cid'],
                'name'      => $args['name'],
                'contact'   => $args['contact'],
                'uid'       => $args['uid'],
                'extrainfo' => $args['extrainfo']);

        if (!DBUtil::updateObject($client, 'bannersclient', '', 'cid')) {
            return LogUtil::registerError($this->__('Error! Update attempt failed.'));
        }

        return true;
    }

    /**
     * delete a client
     *
     * @param int $cid client id
     * @return bool true on success, false on failure
     */
    public function deleteclient($args) {
        // Argument check
        if (!isset($args['cid'])) {
            return LogUtil::registerArgsError();
        }

        // Get the existing banner
        $client = ModUtil::apiFunc('Banners', 'user', 'getclient', array('cid' => $args['cid']));

        if ($client == false) {
            return LogUtil::registerError($this->__('No such item found.'));
        }

        // Security check
        if (!SecurityUtil::checkPermission('Banners::Client', "$args[cid]::", ACCESS_DELETE)) {
            return LogUtil::registerPermissionError();
        }

        // delete any banners for this client first
        if (!ModUtil::apiFunc('Banners', 'admin', 'deleteall', array('cid' => $args['cid']))) {
            return LogUtil::registerError($this->__('Error! Deletion attempt failed.'));
        }

        // now delete the client
        if (!DBUtil::deleteObjectByID('bannersclient', $args['cid'], 'cid')) {
            return LogUtil::registerError($this->__('Error! Deletion attempt failed.'));
        }

        // Let the calling process know that we have finished successfully
        return true;
    }

    /**
     * delete an inactive banner
     *
     * @param int $bid banner id
     * @return bool true on success, false on failure
     */
    public function deletefinished($args) {
        $args['active'] = 0;
        return $this->delete($args);
    }
}