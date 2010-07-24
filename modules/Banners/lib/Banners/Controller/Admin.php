<?php

/**
 * @package      Banners
 * @version      $Id:
 * @author       Halbrook Technologies
 * @author       Michael Halbrook
 * @author       Devin Hayes
 * @author       Craig Heydenburg
 * @link         http://www.halbrooktech.com
 * @copyright    Copyright (C) 2010
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */
class Banners_Controller_Admin extends Zikula_Controller {

    /**
     * the main administration function
     *
     * @return       output       The main module admin page.
     */
    public function main() {
        // Security check
        if (!SecurityUtil::checkPermission('Banners::', '::', ACCESS_EDIT)) {
            return LogUtil::registerPermissionError();
        }

        // Return the output that has been generated by this function
        return $this->overview();
    }

    /**
     * display form to create a new banner
     *
     * @return string HTML output string
     */
    public function newentry($args) {
        // Security check
        if (!SecurityUtil::checkPermission('Banners::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }

        // Check if Banners variable is active, if not then print a message
        $this->view->assign('bannersenabled', ModUtil::getVar('Banners', 'banners'));

        // get list of current clients and assign to template
        $clients = ModUtil::apiFunc('Banners', 'user', 'getallclients');
        $clientitems = array();
        if (is_array($clients)) {
            foreach ($clients as $client) {
                $clientitems[$client['cid']] = $client['name'];
            }
        }
        $this->view->assign('clients', $clientitems);

        $this->view->assign('enablecats', ModUtil::getVar('Banners', 'enablecats'));
        $this->view->assign('catregistry', CategoryRegistryUtil::getRegisteredModuleCategories('Banners', 'banners'));

        // return the output
        return $this->view->fetch('admin/new.tpl');
    }

    /**
     * display form to create a new client
     *
     * @return string HTML output string
     */
    public function newclient($args) {
        // Security check
        if (!SecurityUtil::checkPermission('Banners::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }

        // Check if Banners variable is active, if not then print a message
        $this->view->assign('bannersenabled', ModUtil::getVar('Banners', 'banners'));

        // return the output
        return $this->view->fetch('admin/clientnew.tpl');
    }

    /**
     * view items
     *
     * @param int $startnum the start item id for the pager
     * @return string HTML output string
     */
    public function overview($args) {
        // Security check
        if (!SecurityUtil::checkPermission('Banners::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }

        // Check if Banners variable is active, if not then print a message
        $this->view->assign('bannersenabled', ModUtil::getVar('Banners', 'banners'));

        // get list of banners
        $banners = ModUtil::apiFunc('Banners', 'user', 'getall', array('clientinfo' => true));
        foreach ($banners as $key => $banner) {
            $banners[$key] = Banners_Util::computestats($banner);
        }
        $this->view->assign('activebanneritems', $banners);

        // get list of finished banners
        $finishedbanners = ModUtil::apiFunc('Banners', 'user', 'getallfinished', array('clientinfo' => true));
        foreach ($finishedbanners as $key => $banner) {
            $finishedbanners[$key] = Banners_Util::computestats($banner);
        }
        $this->view->assign('finishedbanners', $finishedbanners);

        // get all clients
        $activeclients = ModUtil::apiFunc('Banners', 'user', 'getallclients');
        $this->view->assign('activeclients', $activeclients);

        return $this->view->fetch('admin/overview.tpl');
    }

    /**
     * create a banner
     *
     * @param int $cid client id
     * @param int $idtype banner type id
     * @param int $imptotal total impressions purchased
     * @param string $imageurl source url of the banner image
     * @param string $clickurl destination url for the banner
     * @return mixed int banner id if successful
     */
    public function create($args) {
        $banner = FormUtil::getPassedValue('banner', null, 'POST');

        // Confirm authorisation code.
        if (!SecurityUtil::confirmAuthKey()) {
            return LogUtil::registerAuthidError(ModUtil::url('Banners', 'admin', 'overview'));
        }

        // Create the banner
        $bid = ModUtil::apiFunc('Banners', 'admin', 'create', $banner);

        // The return value of the function is checked
        if ($bid != false) {
            // Success
            LogUtil::registerStatus($this->__('Banner Created'));
        }

        return System::redirect(ModUtil::url('Banners', 'admin', 'overview'));
    }

    /**
     * modify a banner
     *
     * @param int $args['bid'] the banner id
     * @return string HTML output string
     */
    public function modify($args) {

        $bid   = FormUtil::getPassedValue('bid', null, 'GET');
        $limit = FormUtil::getPassedValue('limit', 0, 'GET');

        if (!is_numeric($bid)) {
            return LogUtil::registerArgsError();
        }

        // security check
        if (!SecurityUtil::checkPermission('Banners::Banner', "$bid::", ACCESS_EDIT)) {
            return LogUtil::registerPermissionError();
        }

        // get the banner
        $banner = ModUtil::apiFunc('Banners', 'user', 'get', array('bid' => $bid));
        // prepare selected category array
        $selectedcatsarray = array();
        foreach ($banner['__CATEGORIES__'] as $prop => $info) {
            $selectedcatsarray[$prop] = $info['id'];
        }

        if ($banner == false) {
            return LogUtil::registerError($this->__('No such item found.'));
        }
        if ($limit) {
            $banner['imptotal'] = $banner['impmade'];
        }

        // assign the banner item
        $this->view->assign('banner', $banner);

        $this->view->assign('enablecats', ModUtil::getVar('Banners', 'enablecats'));
        $this->view->assign('catregistry', CategoryRegistryUtil::getRegisteredModuleCategories('Banners', 'banners'));
        $this->view->assign('selectedcatsarray', $selectedcatsarray);

        // build a list of clients suitable for html_options
        $allclients = ModUtil::apiFunc('Banners', 'user', 'getallclients');
        $clients = array();
        foreach ($allclients as $client) {
            $clients[$client['cid']] = $client['name'];
        }
        $this->view->assign('clients', $clients);

        return $this->view->fetch('admin/banneredit.tpl');
    }

    /**
     * update a banner
     *
     * @param int $cid client id
     * @param int $idtype banner type id
     * @param int $imptotal total impressions purchased
     * @param int $impadded additional impressions added
     * @param string $imageurl source url of the banner image
     * @param string $clickurl destination url for the banner
     * @return bool
     */
    public function update($args) {
        $banner = FormUtil::getPassedValue('banner', null, 'POST');

        // Confirm authorisation code.
        if (!SecurityUtil::confirmAuthKey()) {
            return LogUtil::registerAuthidError(ModUtil::url('Banners', 'admin', 'overview'));
        }
        if (ModUtil::apiFunc('Banners', 'admin', 'update', $banner)) {
            LogUtil::registerStatus($this->__('Banner Updated'));
        }

        return System::redirect(ModUtil::url('Banners', 'admin', 'main'));
    }

    /**
     * delete a banner
     *
     * @param int $bid banner id
     * @param int $objectid generic object id maps to bid if present
     * @param bool $confirmation confirmation of the deletion
     * @return mixed HTML output string if no confirmation, true if succesful, false otherwise
     */
    public function delete($args) {
        $bid          = (int) FormUtil::getPassedValue('bid', null, 'REQUEST');
        $objectid     = (int) FormUtil::getPassedValue('objectid', null, 'REQUEST');
        $confirmation = FormUtil::getPassedValue('confirmation', null, 'POST');
        if ($objectid) {
            $bid = $objectid;
        }

        // Get the existing banner
        $banner = ModUtil::apiFunc('Banners', 'user', 'get', array(
                    'bid' => $bid,
                    'clientinfo' => true));
        if ($banner == false) {
            return LogUtil::registerError($this->__('No such item found.'));
        }
        $banner = Banners_Util::computestats($banner);

        // Security check
        if (!SecurityUtil::checkPermission('Banners::', "$bid::", ACCESS_DELETE)) {
            return LogUtil::registerPermissionError();
        }

        // Check for confirmation.
        if (empty($confirmation)) {
            // No confirmation yet
            // Add the message id
            $this->view->assign('bid', $bid);

            // assign the full item
            $this->view->assign('banner', $banner);

            // Return the output that has been generated by this function
            return $this->view->fetch('admin/bannerdelete.tpl');
        }

        // Confirm authorisation code.
        if (!SecurityUtil::confirmAuthKey()) {
            return LogUtil::registerAuthidError(ModUtil::url('Banners', 'admin', 'overview'));
        }

        // Delete the banner
        // The return value of the function is checked
        if (ModUtil::apiFunc('Banners', 'admin', 'delete', array('bid' => $bid))) {
            // Success
            LogUtil::registerStatus($this->__('Banner Deleted'));
        }

        // This function generated no output, and so now it is complete we redirect
        // the user to an appropriate page for them to carry on their work
        return System::redirect(ModUtil::url('Banners', 'admin', 'overview'));
    }

    /**
     * create a client
     *
     * @param int $name client name
     * @param int $contact client contact name
     * @param int $email client e-mail address
     * @param string $login client login name
     * @param string $passwd client login password
     * @param string $extrainfo additional client information
     * @return mixed int banner id if successful
     */
    public function createclient($args) {
        $client = FormUtil::getPassedValue('client', null, 'POST');

        // Confirm authorisation code.
        if (!SecurityUtil::confirmAuthKey()) {
            return LogUtil::registerAuthidError(ModUtil::url('Banners', 'admin', 'overview'));
        }

        if (ModUtil::apiFunc('Banners', 'admin', 'createclient', $client)) {
            LogUtil::registerStatus($this->__('Banner Client Created'));
        }

        return System::redirect(ModUtil::url('Banners', 'admin', 'main'));
    }

    /**
     * modify a banner client
     *
     * @param int $cid the client id
     * @return string HTML output string
     */
    public function modifyclient($args) {
        $cid = FormUtil::getPassedValue('cid', null, 'GET');

        if (!is_numeric($cid)) {
            return LogUtil::registerArgsError();
        }

        // security check
        if (!SecurityUtil::checkPermission('Banners::Client', "$cid::", ACCESS_EDIT)) {
            return LogUtil::registerPermissionError();
        }

        // get the banner
        $client = ModUtil::apiFunc('Banners', 'user', 'getclient', array('cid' => $cid));

        if ($client == false) {
            return LogUtil::registerError($this->__('No such item found.'));
        }

        // assign the banner item
        $this->view->assign('client', $client);

        return $this->view->fetch('admin/clientedit.tpl');
    }

    /**
     * update a banner client
     *
     * @param int $cid client id
     * @param int $name client name
     * @param int $contact client contact name
     * @param int $email client e-mail address
     * @param string $login client login name
     * @param string $passwd client login password
     * @param string $extrainfo additional client information
     * @return bool
     */
    public function updateclient($args) {
        $client = FormUtil::getPassedValue('client', null, 'POST');

        // Confirm authorisation code.
        if (!SecurityUtil::confirmAuthKey()) {
            return LogUtil::registerAuthidError(ModUtil::url('Banners', 'admin', 'overview'));
        }

        if (ModUtil::apiFunc('Banners', 'admin', 'updateclient', $client)) {
            LogUtil::registerStatus($this->__('Client Updated'));
        }

        return System::redirect(ModUtil::url('Banners', 'admin', 'main'));
    }

    /**
     * delete a banner
     *
     * @param int $cid client id
     * @param int $objectid generic object id maps to bid if present
     * @param bool $confirmation confirmation of the deletion
     * @return mixed HTML output string if no confirmation, true if succesful, false otherwise
     */
    public function deleteclient($args) {
        $cid          = FormUtil::getPassedValue('cid', null, 'REQUEST');
        $objectid     = FormUtil::getPassedValue('objectid', null, 'REQUEST');
        $confirmation = FormUtil::getPassedValue('confirmation', null, 'POST');
        if (!empty($objectid)) {
            $cid = $objectid;
        }

        // Get the existing banner
        $client = ModUtil::apiFunc('Banners', 'user', 'getclient', array('cid' => $cid));

        if ($client == false) {
            return LogUtil::registerError($this->__('No such item found.'));
        }

        // Security check
        if (!SecurityUtil::checkPermission('Banners::Client', "$cid::", ACCESS_DELETE)) {
            return LogUtil::registerPermissionError();
        }

        // Check for confirmation.
        if (empty($confirmation)) {
            // No confirmation yet
            // Add the message id
            $this->view->assign('client', $client);

            // assign the full item
            $this->view->assign('banners', ModUtil::apiFunc('Banners', 'user', 'getall', array('cid' => $cid)));

            // Return the output that has been generated by this function
            return $this->view->fetch('admin/clientdelete.tpl');
        }

        // Confirm authorisation code.
        if (!SecurityUtil::confirmAuthKey()) {
            return LogUtil::registerAuthidError(ModUtil::url('Banners', 'admin', 'overview'));
        }

        // Delete the banner
        // The return value of the function is checked
        if (ModUtil::apiFunc('Banners', 'admin', 'deleteclient', array('cid' => $cid))) {
            // Success
            LogUtil::registerStatus($this->__('Client Deleted'));
        }

        // This function generated no output, and so now it is complete we redirect
        // the user to an appropriate page for them to carry on their work
        return System::redirect(ModUtil::url('Banners', 'admin', 'overview'));
    }

    /**
     * delete a finished banner
     *
     * @param int $bid banner id
     * @param int $objectid generic object id maps to bid if present
     * @param bool $confirmation confirmation of the deletion
     * @return mixed HTML output string if no confirmation, true if succesful, false otherwise
     */
    public function deletefinished($args) {
        $bid          = FormUtil::getPassedValue('bid', null, 'REQUEST');
        $objectid     = FormUtil::getPassedValue('objectid', null, 'REQUEST');
        $confirmation = FormUtil::getPassedValue('confirmation', null, 'POST');
        if (!empty($objectid)) {
            $bid = $objectid;
        }

        // Get the existing banner
        $banner = ModUtil::apiFunc('Banners', 'user', 'get', array(
                    'bid' => $bid,
                    'clientinfo' => true));
        $banner = Banners_Util::computestats($banner);

        if ($banner == false) {
            return LogUtil::registerError($this->__('No such item found.'));
        }

        // Security check
        if (!SecurityUtil::checkPermission('Banners::', "$bid::", ACCESS_DELETE)) {
            return LogUtil::registerPermissionError();
        }

        // Check for confirmation.
        if (empty($confirmation)) {
            // No confirmation yet
            // Add the message id
            $this->view->assign('bid', $bid);

            // assign the full item
            $this->view->assign('banner', $banner);

            // Return the output that has been generated by this function
            return $this->view->fetch('admin/bannerdelete.tpl');
        }

        // Confirm authorisation code.
        if (!SecurityUtil::confirmAuthKey()) {
            return LogUtil::registerAuthidError(ModUtil::url('Banners', 'admin', 'overview'));
        }

        // Delete the banner
        // The return value of the function is checked
        if (ModUtil::apiFunc('Banners', 'admin', 'deletefinished', array('bid' => $bid))) {
            // Success
            LogUtil::registerStatus($this->__('Banner Deleted'));
        }

        return System::redirect(ModUtil::url('Banners', 'admin', 'overview'));
    }

    /**
     * This is a standard function to modify the configuration parameters of the
     * module
     *
     * @return string HTML output string
     */
    public function modifyconfig() {
        // Security check
        if (!SecurityUtil::checkPermission('Banners::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }

        // Number of items to display per page
        $this->view->assign(ModUtil::getVar('Banners'));

        // Return the output that has been generated by this function
        return $this->view->fetch('admin/modifyconfig.tpl');
    }

    /**
     * This is a standard function to update the configuration parameters of the
     * module given the information passed back by the modification form
     *
     * @param int $itemsperpage the number messages per page in the admin panel
     * @return bool true if successful, false otherwise
     */
    public function updateconfig() {
        if (!SecurityUtil::checkPermission('Banners::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }

        $banners         = FormUtil::getPassedValue('banners', null, 'POST');
        $myIP            = FormUtil::getPassedValue('myIP', null, 'POST');
        $openinnewwindow = FormUtil::getPassedValue('openinnewwindow', null, 'POST');
        $enablecats      = FormUtil::getPassedValue('enablecats', true, 'POST');

        // Confirm authorisation code.
        if (!SecurityUtil::confirmAuthKey()) {
            return LogUtil::registerAuthidError(ModUtil::url('Banners', 'admin', 'overview'));
        }

        // Update module variables.
        ModUtil::setVar('Banners', 'banners', $banners);
        ModUtil::setVar('Banners', 'myIP', $myIP);
        ModUtil::setVar('Banners', 'openinnewwindow', $openinnewwindow);
        ModUtil::setVar('Banners', 'enablecats', $enablecats);

        // Let any other modules know that the modules configuration has been updated
        $this->callHooks('module', 'updateconfig', 'Banners', array('module' => 'Banners'));

        // the module configuration has been updated successfuly
        LogUtil::registerStatus($this->__('Configuration Updated'));

        return System::redirect(ModUtil::url('Banners', 'admin', 'overview'));
    }

}