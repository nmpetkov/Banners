<?php

/**
 * @package      Banners
 * @author       Halbrook Technologies
 * @author       Michael Halbrook
 * @author       Devin Hayes
 * @author       Craig Heydenburg
 * @link         http://www.halbrooktech.com
 * @copyright    Copyright (C) 2010
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */
class Banners_Controller_Admin extends Zikula_AbstractController {

    /**
     * the main administration function
     *
     * @return       output       The main module admin page.
     */
    public function main() {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Banners::', '::', ACCESS_EDIT), LogUtil::getErrorMsgPermission());
		$this->redirect(ModUtil::url('Banners', 'admin', 'overview'));
    }

    /**
     * display form to create a new banner
     *
     * @return string HTML output string
     */
    public function newentry() {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Banners::', '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());

        // get list of current clients and assign to template
        $clients = ModUtil::apiFunc('Banners', 'user', 'getallclients');
        $clientitems = array();
        if (is_array($clients)) {
            foreach ($clients as $client) {
                $clientitems[$client['cid']] = $client['name'];
            }
        }
        $this->view->assign('clients', $clientitems);
        $this->view->assign('catregistry', CategoryRegistryUtil::getRegisteredModuleCategories('Banners', 'banners'));

        // return the output
        return $this->view->fetch('admin/new.tpl');
    }

    /**
     * display form to create a new client
     *
     * @return string HTML output string
     */
    public function newclient() {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Banners::', '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());

        // return the output
        return $this->view->fetch('admin/clientnew.tpl');
    }

    /**
     * view items
     *
     * @param int $startnum the start item id for the pager
     * @return string HTML output string
     */
    public function overview() {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Banners::', '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());

        // get list of banners
        $banners = ModUtil::apiFunc('Banners', 'user', 'getall', array('active' => true));
        foreach ($banners as $key => $banner) {
            $banners[$key] = Banners_Util::computestats($banner);
        }
        $this->view->assign('activebanneritems', $banners);

        // get list of finished banners
        $finishedbanners = ModUtil::apiFunc('Banners', 'user', 'getallfinished', array('active' => true));
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
     * @return mixed int banner id if successful
     */
    public function create() {
        
        $banner = FormUtil::getPassedValue('banner', null, 'POST');

        $this->checkCsrfToken();
        
        // get storage directory
        $banners_dir = $this->getVar('storagedir');
        $uploaded_image = Banners_ImagesUtil::uploadImage('imagefile', $banners_dir, '');
        
        if ($uploaded_image) {
            $banner['imageurl'] = System::getBaseUrl() . $banners_dir . '/' . $uploaded_image;
        }
        
        /*
        // construct the url provided
        // $imageuploaded
        */

        // Create the banner
        $bid = ModUtil::apiFunc('Banners', 'admin', 'create', $banner);

        // The return value of the function is checked
        if ($bid != false) {
            // Success
            LogUtil::registerStatus($this->__('Banner Created'));
        }

        $this->redirect(ModUtil::url('Banners', 'admin', 'overview'));
    }

    /**
     * modify a banner
     *
     * @return string HTML output string
     */
    public function modify() {

        $bid   = FormUtil::getPassedValue('bid', null, 'GET');
        $limit = FormUtil::getPassedValue('limit', 0, 'GET');

        if (!is_numeric($bid)) {
            return LogUtil::registerArgsError();
        }

        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Banners::', "$bid::", ACCESS_EDIT), LogUtil::getErrorMsgPermission());

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
        $banner['limit'] = $limit;

        // assign the banner item
        if (empty($banner['enddate']) || $banner['enddate'] == '0000-00-00') {
            $banner['enddate'] = new DateTime(null);
        } else {
            $banner['enddate'] = new DateTime($banner['enddate']);
        }
       $this->view->assign('banner', $banner);

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
     * @return bool
     */
    public function update() {
    
        $banner = FormUtil::getPassedValue('banner', null, 'POST');
        $bid = $banner['bid'];

        $this->checkCsrfToken();
        
        // get storage directory
        $banners_dir = $this->getVar('storagedir');
        
        // get the old banner image
        $current_banner = ModUtil::apiFunc('Banners', 'user', 'get', array('bid' => $bid));
        $current_imageurl = $current_banner['imageurl'];
        $current_image = (empty($current_imageurl))?'':substr($current_imageurl, strrpos($current_imageurl, '/')+1);
        
        $uploaded_image = Banners_ImagesUtil::uploadImage('imagefile', $banners_dir, $current_image);
        
        if ($uploaded_image) {
            $banner['imageurl'] = System::getBaseUrl() . $banners_dir . '/' . $uploaded_image;
        }

        if (ModUtil::apiFunc('Banners', 'admin', 'update', $banner)) {
            LogUtil::registerStatus($this->__('Banner Updated'));
        }

        $this->redirect(ModUtil::url('Banners', 'admin', 'overview'));
    }

    /**
     * delete a banner
     *
     * @return mixed HTML output string if no confirmation, true if succesful, false otherwise
     */
    public function delete() {
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

        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Banners::', "$bid::", ACCESS_DELETE), LogUtil::getErrorMsgPermission());

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

        $this->checkCsrfToken();
        
        // delete the image from the filesystem
        $current_imageurl = $banner['imageurl'];
        $current_image = (empty($current_imageurl))?'':substr($current_imageurl, strrpos($current_imageurl, '/')+1);
        
        Banners_ImagesUtil::deleteImage($current_image);

        // Delete the banner
        // The return value of the function is checked
        if (ModUtil::apiFunc('Banners', 'admin', 'delete', array('bid' => $bid))) {
            // Success
            LogUtil::registerStatus($this->__('Banner Deleted'));
        }

        // This function generated no output, and so now it is complete we redirect
        // the user to an appropriate page for them to carry on their work
        $this->redirect(ModUtil::url('Banners', 'admin', 'overview'));
    }

    /**
     * create a client
     *
     * @return mixed int banner id if successful
     */
    public function createclient() {
        $client = FormUtil::getPassedValue('client', null, 'POST');

        $this->checkCsrfToken();

        if (ModUtil::apiFunc('Banners', 'admin', 'createclient', $client)) {
            LogUtil::registerStatus($this->__('Banner Client Created'));
        }

        $this->redirect(ModUtil::url('Banners', 'admin', 'overview'));
    }

    /**
     * modify a banner client
     *
     * @return string HTML output string
     */
    public function modifyclient() {
        $cid = FormUtil::getPassedValue('cid', null, 'GET');

        if (!is_numeric($cid)) {
            return LogUtil::registerArgsError();
        }

        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Banners::', "$cid::", ACCESS_EDIT), LogUtil::getErrorMsgPermission());

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
     * @return bool
     */
    public function updateclient() {
        $client = FormUtil::getPassedValue('client', null, 'POST');

        $this->checkCsrfToken();

        if (ModUtil::apiFunc('Banners', 'admin', 'updateclient', $client)) {
            LogUtil::registerStatus($this->__('Client Updated'));
        }

        $this->redirect(ModUtil::url('Banners', 'admin', 'overview'));
    }

    /**
     * delete a banner
     *
     * @return mixed HTML output string if no confirmation, true if succesful, false otherwise
     */
    public function deleteclient() {
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

        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Banners::', "$cid::", ACCESS_DELETE), LogUtil::getErrorMsgPermission());

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

        $this->checkCsrfToken();

        // Delete the banner
        // The return value of the function is checked
        if (ModUtil::apiFunc('Banners', 'admin', 'deleteclient', array('cid' => $cid))) {
            // Success
            LogUtil::registerStatus($this->__('Client Deleted'));
        }

        // This function generated no output, and so now it is complete we redirect
        // the user to an appropriate page for them to carry on their work
        $this->redirect(ModUtil::url('Banners', 'admin', 'overview'));
    }

    /**
     * delete a finished banner
     *
     * @return mixed HTML output string if no confirmation, true if succesful, false otherwise
     */
    public function deletefinished() {
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

        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Banners::', "$bid::", ACCESS_DELETE), LogUtil::getErrorMsgPermission());

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

        $this->checkCsrfToken();

        // Delete the banner
        // The return value of the function is checked
        if (ModUtil::apiFunc('Banners', 'admin', 'deletefinished', array('bid' => $bid))) {
            // Success
            LogUtil::registerStatus($this->__('Banner Deleted'));
        }

        $this->redirect(ModUtil::url('Banners', 'admin', 'overview'));
    }

    /**
     * This is a standard function to modify the configuration parameters of the
     * module
     *
     * @return string HTML output string
     */
    public function modifyconfig() {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Banners::', '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());

        $this->view->assign('IPlist', implode(',', $this->getVar('myIP')));
        if (!empty($_SERVER['HTTP_X_FORWARD_FOR'])) {
            $currentip = $_SERVER['HTTP_X_FORWARD_FOR'];
        } else {
            $currentip = $_SERVER['REMOTE_ADDR'];
        }
        $this->view->assign('currentip', $currentip);

        // Return the output that has been generated by this function
        return $this->view->fetch('admin/modifyconfig.tpl');
    }

    /**
     * This is a standard function to update the configuration parameters of the
     * module given the information passed back by the modification form
     *
     * @return redirect
     */
    public function updateconfig() {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Banners::', '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());

        $modvars = array(
            'banners'         => FormUtil::getPassedValue('banners', 0, 'POST'),
            'enablecats'      => FormUtil::getPassedValue('enablecats', 0, 'POST'),
            'openinnewwindow' => FormUtil::getPassedValue('openinnewwindow', 0, 'POST'),
            'myIP'            => array_map('trim', explode(',', FormUtil::getPassedValue('myIP', null, 'POST'))),
            'storagedir' => trim(FormUtil::getPassedValue('storagedir', '', 'POST')),
        );

        $this->checkCsrfToken();

        // Update module variables.
        if ($this->setVars($modvars)) {
            LogUtil::registerStatus($this->__('Configuration updated'));
        } else {
            LogUtil::registerError($this->__('Configuration could not be updated'));
        }
        
        $this->redirect(ModUtil::url('Banners', 'admin', 'overview'));
    }

}