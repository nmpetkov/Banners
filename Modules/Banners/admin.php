<?php
/**
 * @package      Banners
 * @version      $Id:
 * @author       Halbrook Technologies
 * @link         http://www.halbrooktech.com
 * @copyright    Copyright (C) 2010
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */
class Banners_admin extends AbstractController {
    /**
     * the main administration function
     *
     * @author       Michael Halbrook
     * @return       output       The main module admin page.
     */
    public function main() {
        // Security check
        if (!SecurityUtil::checkPermission('Banners::', '::', ACCESS_EDIT)) {
            return LogUtil::registerPermissionError();
        }

        // Create output object - this object will store all of our output so that
        // we can return it easily when required
        $render = Renderer::getInstance('Banners');


        // Return the output that has been generated by this function
        return $render->fetch('banners_admin_main.htm');
    }

    /**
     * display form to create a new banner/client
     *
     * @author Devin Hayes
     * @return string HTML output string
     */
    public function newentry($args) {
        // Security check
        if (!SecurityUtil::checkPermission('Banners::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }

        // Create output object
        $render = Renderer::getInstance('Banners', false);

        // Check if Banners variable is active, if not then print a message
        $render->assign('bannersenabled', ModUtil::getVar('Banners', 'banners'));

        // get list of current clients and assign to template
        $clients = ModUtil::apiFunc('Banners', 'user', 'getallclients');
        $clientitems = array();
        if (is_array($clients)) {
            foreach($clients as $client) {
                $clientitems[$client['cid']] = $client['name'];
            }
        }
        $render->assign('clients', $clientitems);

        // return the output
        return $render->fetch('banners_admin_new.htm');
    }

    /**
     * view items
     *
     * @author Devin Hayes
     * @param int $startnum the start item id for the pager
     * @return string HTML output string
     */
    public function view($args) {
        // Security check
        if (!SecurityUtil::checkPermission('Banners::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }

        // Create output object
        $render = Renderer::getInstance('Banners', false);

        // Check if Banners variable is active, if not then print a message
        $render->assign('bannersenabled', ModUtil::getVar('Banners', 'banners'));

        // get list of banners
        $activebanneritems = ModUtil::apiFunc('Banners', 'user', 'getall', array('clientinfo' => true));
        $render->assign('activebanneritems', $activebanneritems);

        // get list of finished banners
        $finishedbanners = ModUtil::apiFunc('Banners', 'user', 'getallfinished');
        $render->assign('finishedbanners', $finishedbanners);

        // get all clients
        $activeclients = ModUtil::apiFunc('Banners', 'user', 'getallclients');
        $render->assign('activeclients', $activeclients);

        return $render->fetch('banners_admin_view.htm');
    }

    /**
     * create a banner
     *
     * @author Devin Hayes
     * @param int $cid client id
     * @param int $idtype banner type id
     * @param int $imptotal total impressions purchased
     * @param string $imageurl source url of the banner image
     * @param string $clickurl destination url for the banner
     * @return mixed int banner id if successful
     */
    public function create($args) {
        $banner = FormUtil::getPassedValue('banner', isset($args['banner']) ? $args['banner'] : null, 'POST');

        // Confirm authorisation code.
        if (!SecurityUtil::confirmAuthKey()) {
            return LogUtil::registerAuthidError (ModUtil::url('Banners', 'admin', 'view'));
        }

        // Notable by its absence there is no security check here.
        // Create the banner
        $bid = ModUtil::apiFunc('Banners', 'admin', 'create',
                array('cid'  => $banner['cid'],
                'name'    => $banner['name'],
                'idtype'   => $banner['idtype'],
                'imptotal' => $banner['imptotal'],
                'imageurl' => $banner['imageurl'],
                'clickurl' => $banner['clickurl']));

        // The return value of the function is checked
        if ($bid != false) {
            // Success
            LogUtil::registerStatus (_BANNERS_CREATED);
        }

        // This function generated no output, and so now it is complete we redirect
        // the user to an appropriate page for them to carry on their work
        return System::redirect(ModUtil::url('Banners', 'admin', 'view'));
    }

    /**
     * modify a banner
     *
     * @author Devin Hayes
     * @param int $args['bid'] the banner id
     * @return string HTML output string
     */
    public function modify($args) {
        $bid = FormUtil::getPassedValue('bid', isset($args['bid']) ? $args['bid'] : null, 'GET');

        if (!is_numeric($bid)) {
            return LogUtil::registerError ('Error! Could not do what you wanted. Please check your input.');
        }

        // security check
        if (!SecurityUtil::checkPermission('Banners::Banner', "$bid::", ACCESS_EDIT)) {
            return LogUtil::registerPermissionError();
        }

        // get the banner
        $banner = ModUtil::apiFunc('Banners', 'user', 'get', array('bid' => $bid));

        if ($banner == false) {
            return DataUtil::formatForDisplayHTML('No such item found.');
        }

        // create a new output object
        $render = Renderer::getInstance('Banners', false);

        // assign the banner item
        $render->assign($banner);

        // build a list of clients suitable for html_options
        $allclients = ModUtil::apiFunc('Banners', 'user', 'getallclients');
        $clients = array();
        foreach ($allclients as $client) {
            $clients[$client['cid']] = $client['name'];
        }
        $render->assign('clients', $clients);

        return $render->fetch('banners_admin_banneredit.htm');
    }

    /**
     * update a banner
     *
     * @author Devin Hayes
     * @param int $cid client id
     * @param int $idtype banner type id
     * @param int $imptotal total impressions purchased
     * @param int $impadded additional impressions added
     * @param string $imageurl source url of the banner image
     * @param string $clickurl destination url for the banner
     * @return bool
     */
    public function update($args) {
        $banner = FormUtil::getPassedValue('banner', isset($args['banner']) ? $args['banner'] : null, 'POST');

        // Confirm authorisation code.
        if (!SecurityUtil::confirmAuthKey()) {
            return LogUtil::registerAuthidError (ModUtil::url('Banners', 'admin', 'view'));
        }

        if (ModUtil::apiFunc('Banners', 'admin', 'update',
        array('bid' => $banner['bid'],
        'cid' => $banner['cid'],
        'idtype' => $banner['idtype'],
        'imptotal' => $banner['imptotal'],
        'impadded' => $banner['impadded'],
        'imageurl' => $banner['imageurl'],
        'clickurl' => $banner['clickurl']))) {
            LogUtil::registerStatus (_BANNERS_UPDATED);
        }

        return System::redirect(ModUtil::url('Banners', 'admin', 'main'));
    }

    /**
     * delete a banner
     *
     * @author Devin Hayes
     * @param int $bid banner id
     * @param int $objectid generic object id maps to bid if present
     * @param bool $confirmation confirmation of the deletion
     * @return mixed HTML output string if no confirmation, true if succesful, false otherwise
     */
    public function delete($args) {
        $bid          = (int)FormUtil::getPassedValue('bid', isset($args['bid']) ? $args['bid'] : null, 'REQUEST');
        $objectid     = (int)FormUtil::getPassedValue('objectid', isset($args['objectid']) ? $args['objectid'] : null, 'REQUEST');
        $confirmation = FormUtil::getPassedValue('confirmation', null, 'POST');
        if ($objectid) {
            $bid = $objectid;
        }

        // Get the existing admin message
        $banner = ModUtil::apiFunc('Banners', 'user', 'get', array('bid' => $bid, 'clientinfo' => true));
        if ($banner == false) {
            return DataUtil::formatForDisplayHTML('No such item found.');
        }

        // Security check
        if (!SecurityUtil::checkPermission('Banners::', "$bid::", ACCESS_DELETE)) {
            return LogUtil::registerPermissionError();
        }

        // Check for confirmation.
        if (empty($confirmation)) {
            // No confirmation yet
            // Create output object
            $render = Renderer::getInstance('Banners', false);

            // Add the message id
            $render->assign('bid', $bid);

            // assign the full item
            $render->assign($banner);

            // Return the output that has been generated by this function
            return $render->fetch('banners_admin_bannerdelete.htm');
        }

        // Confirm authorisation code.
        if (!SecurityUtil::confirmAuthKey()) {
            return LogUtil::registerAuthidError (ModUtil::url('Banners', 'admin', 'view'));
        }

        // Delete the banner
        // The return value of the function is checked
        if (ModUtil::apiFunc('Banners', 'admin', 'delete', array('bid' => $bid))) {
            // Success
            LogUtil::registerStatus (_BANNERS_DELETED);
        }

        // This function generated no output, and so now it is complete we redirect
        // the user to an appropriate page for them to carry on their work
        return System::redirect(ModUtil::url('Banners', 'admin', 'view'));
    }

    /**************************** client functions ****************************/

    /**
     * create a client
     *
     * @author Devin Hayes
     * @param int $cname client name
     * @param int $contact client contact name
     * @param int $email client e-mail address
     * @param string $login client login name
     * @param string $passwd client login password
     * @param string $extrainfo additional client information
     * @return mixed int banner id if successful
     */
    public function createclient($args) {
        $client = FormUtil::getPassedValue('client', isset($args['client']) ? $args['client'] : null, 'POST');

        // Confirm authorisation code.
        if (!SecurityUtil::confirmAuthKey()) {
            return LogUtil::registerAuthidError (ModUtil::url('Banners', 'admin', 'view'));
        }

        if (ModUtil::apiFunc('Banners', 'admin', 'createclient',
        array('cname' => $client['cname'],
        'contact' => $client['contact'],
        'email' => $client['email'],
        'login' => $client['login'],
        'passwd' => $client['passwd'],
        'extrainfo' => $client['extrainfo']))) {
            LogUtil::registerStatus ('Banner Client Created');
        }

        return System::redirect(ModUtil::url('Banners', 'admin', 'main'));
    }

    /**
     * modify a banner client
     *
     * @author Devin Hayes
     * @param int $cid the client id
     * @return string HTML output string
     */
    public function modifyclient($args) {
        $cid = FormUtil::getPassedValue('cid', isset($args['cid']) ? $args['cid'] : null, 'GET');

        if (!is_numeric($cid)) {
            return LogUtil::registerError ('Error! Could not do what you wanted. Please check your input.');
        }

        // security check
        if (!SecurityUtil::checkPermission('Banners::Client', "$cid::", ACCESS_EDIT)) {
            return LogUtil::registerPermissionError();
        }

        // get the banner
        $client = ModUtil::apiFunc('Banners', 'user', 'getclient', array('cid' => $cid));

        if ($client == false) {
            return DataUtil::formatForDisplayHTML('No such item found.');
        }

        // create a new output object
        $render = Renderer::getInstance('Banners', false);

        // assign the banner item
        $render->assign($client);

        return $render->fetch('banners_admin_clientedit.htm');
    }

    /**
     * update a banner client
     *
     * @author Devin Hayes
     * @param int $cid client id
     * @param int $cname client name
     * @param int $contact client contact name
     * @param int $email client e-mail address
     * @param string $login client login name
     * @param string $passwd client login password
     * @param string $extrainfo additional client information
     * @return bool
     */
    public function updateclient($args) {
        $client = FormUtil::getPassedValue('client', isset($args['client']) ? $args['client'] : null, 'POST');

        // Confirm authorisation code.
        if (!SecurityUtil::confirmAuthKey()) {
            return LogUtil::registerAuthidError (ModUtil::url('Banners', 'admin', 'view'));
        }

        if (ModUtil::apiFunc('Banners', 'admin', 'updateclient',
        array('cid' => $client['cid'],
        'cname' => $client['cname'],
        'contact' => $client['contact'],
        'email' => $client['email'],
        'extrainfo' => $client['extrainfo'],
        'login' => $client['login'],
        'passwd' => $client['passwd']))) {
            LogUtil::registerStatus (_BANNERS_CLIENTUPDATED);
        }

        return System::redirect(ModUtil::url('Banners', 'admin', 'main'));
    }

    /**
     * delete a banner
     *
     * @author Devin Hayes
     * @param int $cid client id
     * @param int $objectid generic object id maps to bid if present
     * @param bool $confirmation confirmation of the deletion
     * @return mixed HTML output string if no confirmation, true if succesful, false otherwise
     */
    public function deleteclient($args) {
        $cid = FormUtil::getPassedValue('cid', isset($args['cid']) ? $args['cid'] : null, 'REQUEST');
        $objectid = FormUtil::getPassedValue('objectid', isset($args['objectid']) ? $args['objectid'] : null, 'REQUEST');
        $confirmation = FormUtil::getPassedValue('confirmation', null, 'POST');
        if (!empty($objectid)) {
            $cid = $objectid;
        }

        // Get the existing admin message
        $client = ModUtil::apiFunc('Banners', 'user', 'getclient', array('cid' => $cid));

        if ($client == false) {
            return DataUtil::formatForDisplayHTML('No such item found.');
        }

        // Security check
        if (!SecurityUtil::checkPermission('Banners::Client', "$cid::", ACCESS_DELETE)) {
            return LogUtil::registerPermissionError();
        }

        // Check for confirmation.
        if (empty($confirmation)) {
            // No confirmation yet
            // Create output object
            $render = Renderer::getInstance('Banners', false);

            // Add the message id
            $render->assign('cid', $cid);

            // assign the full item
            $render->assign('banners', ModUtil::apiFunc('Banners', 'user', 'getall', array('cid' => $cid)));

            // Return the output that has been generated by this function
            return $render->fetch('banners_admin_clientdelete.htm');
        }

        // Confirm authorisation code.
        if (!SecurityUtil::confirmAuthKey()) {
            return LogUtil::registerAuthidError (ModUtil::url('Banners', 'admin', 'view'));
        }

        // Delete the banner
        // The return value of the function is checked
        if (ModUtil::apiFunc('Banners', 'admin', 'deleteclient', array('cid' => $cid))) {
            // Success
            LogUtil::registerStatus (_BANNERS_CLIENTDELETED);
        }

        // This function generated no output, and so now it is complete we redirect
        // the user to an appropriate page for them to carry on their work
        return System::redirect(ModUtil::url('Banners', 'admin', 'view'));
    }

    /**************************** finished banner functions ****************************/

    /**
     * delete a finished banner
     *
     * @author Devin Hayes
     * @param int $bid banner id
     * @param int $objectid generic object id maps to bid if present
     * @param bool $confirmation confirmation of the deletion
     * @return mixed HTML output string if no confirmation, true if succesful, false otherwise
     */
    public function deletefinished($args) {
        $bid = FormUtil::getPassedValue('bid', isset($args['bid']) ? $args['bid'] : null, 'REQUEST');
        $objectid = FormUtil::getPassedValue('objectid', isset($args['objectid']) ? $args['objectid'] : null, 'REQUEST');
        $confirmation = FormUtil::getPassedValue('confirmation', null, 'POST');
        if (!empty($objectid)) {
            $bid = $objectid;
        }

        // Get the existing admin message
        $banner = ModUtil::apiFunc('Banners', 'user', 'get', array('bid' => $bid));

        if ($banner == false) {
            return DataUtil::formatForDisplayHTML('No such item found.');
        }

        // Security check
        if (!SecurityUtil::checkPermission('Banners::', "$bid::", ACCESS_DELETE)) {
            return LogUtil::registerPermissionError();
        }

        // Check for confirmation.
        if (empty($confirmation)) {
            // No confirmation yet
            // Create output object
            $render = Renderer::getInstance('Banners', false);

            // Add the message id
            $render->assign('bid', $bid);

            // assign the full item
            $render->assign(ModUtil::apiFunc('Banners', 'user', 'getfinished', array('bid' => $bid)));

            // Return the output that has been generated by this function
            return $render->fetch('banners_admin_bannerdelete.htm');
        }

        // Confirm authorisation code.
        if (!SecurityUtil::confirmAuthKey()) {
            return LogUtil::registerAuthidError (ModUtil::url('Banners', 'admin', 'view'));
        }

        // Delete the banner
        // The return value of the function is checked
        if (ModUtil::apiFunc('Banners', 'admin', 'deletefinished', array('bid' => $bid))) {
            // Success
            LogUtil::registerStatus (_BANNERS_DELETED);
        }

        // This function generated no output, and so now it is complete we redirect
        // the user to an appropriate page for them to carry on their work
        return System::redirect(ModUtil::url('Banners', 'admin', 'view'));
    }

    /**************************** configuration functions ****************************/

    /**
     * This is a standard function to modify the configuration parameters of the
     * module
     *
     * @author Devin Hayes
     * @return string HTML output string
     */
    public function modifyconfig() {
        // Security check
        if (!SecurityUtil::checkPermission('Banners::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }

        // Create output object
        $render = Renderer::getInstance('Banners', false);

        // Number of items to display per page
        $render->assign(ModUtil::getVar('Banners'));

        // Return the output that has been generated by this function
        return $render->fetch('banners_admin_modifyconfig.htm');
    }

    /**
     * This is a standard function to update the configuration parameters of the
     * module given the information passed back by the modification form
     *
     * @author Devin Hayes
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

        // Confirm authorisation code.
        if (!SecurityUtil::confirmAuthKey()) {
            return LogUtil::registerAuthidError (ModUtil::url('Banners', 'admin', 'view'));
        }

        // Update module variables.
        ModUtil::setVar('Banners', 'banners', $banners);
        ModUtil::setVar('Banners', 'myIP', $myIP);
        ModUtil::setVar('Banners', 'openinnewwindow', $openinnewwindow);

        // Let any other modules know that the modules configuration has been updated
        ModUtil::callHooks('module','updateconfig','Banners', array('module' => 'Banners'));

        // the module configuration has been updated successfuly
        LogUtil::registerStatus (_CONFIGUPDATED);

        // This function generated no output, and so now it is complete we redirect
        // the user to an appropriate page for them to carry on their work
        return System::redirect(ModUtil::url('Banners', 'admin', 'view'));
    }
}