<?php
/**
 * Zikula Application Framework
 *
 * @copyright (c) 2002, Zikula Development Team
 * @link http://www.zikula.org
 * @version $Id: pnadmin.php 9 2008-11-05 21:42:16Z Guite $
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package Zikula_Value_Addons
 * @subpackage Banners
 */

/**
 * the main administration function
 *
 * @author       Devin Hayes
 * @return       output       The main module admin page.
 */
function Banners_admin_main()
{
    // Security check
    if (!SecurityUtil::checkPermission('Banners::', '::', ACCESS_EDIT)) {
        return LogUtil::registerPermissionError();
    }

    // Create output object - this object will store all of our output so that
    // we can return it easily when required
    $pnRender = pnRender::getInstance('Banners');

    // Return the output that has been generated by this function
    return $pnRender->fetch('banners_admin_main.htm');
}

/**
 * display form to create a new banner/client
 *
 * @author Devin Hayes
 * @return string HTML output string
 */
function Banners_admin_new($args)
{
    // Security check
    if (!SecurityUtil::checkPermission('Banners::', '::', ACCESS_ADMIN)) {
        return LogUtil::registerPermissionError();
    }

    // Create output object
    $pnRender = pnRender::getInstance('Banners', false);

    // Check if Banners variable is active, if not then print a message
    $pnRender->assign('bannersenabled', pnModGetVar('Banners', 'banners'));

    // get list of current clients and assign to template
    $clients = pnModAPIFunc('Banners', 'user', 'getallclients');
    $clientitems = array();
    if (is_array($clients)) {
        foreach($clients as $client) {
            $clientitems[$client['cid']] = $client['name'];
        }
    }
    $pnRender->assign('clients', $clientitems);

    // return the output
    return $pnRender->fetch('banners_admin_new.htm');
}

/**
 * view items
 *
 * @author Devin Hayes
 * @param int $startnum the start item id for the pager
 * @return string HTML output string
 */
function Banners_admin_view($args)
{
    // Security check
    if (!SecurityUtil::checkPermission('Banners::', '::', ACCESS_ADMIN)) {
        return LogUtil::registerPermissionError();
    }

    // Create output object
    $pnRender = pnRender::getInstance('Banners', false);

    // Check if Banners variable is active, if not then print a message
    $pnRender->assign('bannersenabled', pnModGetVar('Banners', 'banners'));

    // get list of banners
    $activebanneritems = pnModAPIFunc('Banners', 'user', 'getall', array('clientinfo' => true));
    $pnRender->assign('activebanneritems', $activebanneritems);

    // get list of finished banners
    $finishedbanners = pnModAPIFunc('Banners', 'user', 'getallfinished');
    $pnRender->assign('finishedbanners', $finishedbanners);

    // get all clients
    $activeclients = pnModAPIFunc('Banners', 'user', 'getallclients');
    $pnRender->assign('activeclients', $activeclients);

    return $pnRender->fetch('banners_admin_view.htm');
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
function Banners_admin_create($args)
{
    $banner = FormUtil::getPassedValue('banner', isset($args['banner']) ? $args['banner'] : null, 'POST');

    // Confirm authorisation code.
    if (!SecurityUtil::confirmAuthKey()) {
        return LogUtil::registerAuthidError (pnModURL('Banners', 'admin', 'view'));
    }

    // Notable by its absence there is no security check here.
    // Create the banner
    $bid = pnModAPIFunc('Banners', 'admin', 'create',
	                    array('cid'  => $banner['cid'],
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
    return pnRedirect(pnModURL('Banners', 'admin', 'view'));
}

/**
 * modify a banner
 *
 * @author Devin Hayes
 * @param int $args['bid'] the banner id
 * @return string HTML output string
 */
function Banners_admin_modify($args)
{
    $bid = FormUtil::getPassedValue('bid', isset($args['bid']) ? $args['bid'] : null, 'GET');

    if (!is_numeric($bid)){
        return LogUtil::registerError (_MODARGSERROR);
    }

    // security check
    if (!SecurityUtil::checkPermission('Banners::Banner', "$bid::", ACCESS_EDIT)) {
        return LogUtil::registerPermissionError();
    }

    // get the banner
    $banner = pnModAPIFunc('Banners', 'user', 'get', array('bid' => $bid));

    if ($banner == false) {
        return DataUtil::formatForDisplayHTML(_NOSUCHITEM);
    }

    // create a new output object
    $pnRender = pnRender::getInstance('Banners', false);

    // assign the banner item
    $pnRender->assign($banner);

    // build a list of clients suitable for html_options
    $allclients = pnModAPIFunc('Banners', 'user', 'getallclients');
    $clients = array();
    foreach ($allclients as $client) {
        $clients[$client['cid']] = $client['name'];
    }
    $pnRender->assign('clients', $clients);

    return $pnRender->fetch('banners_admin_banneredit.htm');
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
function Banners_admin_update($args)
{
    $banner = FormUtil::getPassedValue('banner', isset($args['banner']) ? $args['banner'] : null, 'POST');

    // Confirm authorisation code.
    if (!SecurityUtil::confirmAuthKey()) {
        return LogUtil::registerAuthidError (pnModURL('Banners', 'admin', 'view'));
    }

    if (pnModAPIFunc('Banners', 'admin', 'update',
                     array('bid' => $banner['bid'],
                     'cid' => $banner['cid'],
                     'idtype' => $banner['idtype'],
                     'imptotal' => $banner['imptotal'],
                     'impadded' => $banner['impadded'],
                     'imageurl' => $banner['imageurl'],
                     'clickurl' => $banner['clickurl']))){
        LogUtil::registerStatus (_BANNERS_UPDATED);
    }

    return pnRedirect(pnModURL('Banners', 'admin', 'main'));
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
function Banners_admin_delete($args)
{
    $bid          = (int)FormUtil::getPassedValue('bid', isset($args['bid']) ? $args['bid'] : null, 'REQUEST');
    $objectid     = (int)FormUtil::getPassedValue('objectid', isset($args['objectid']) ? $args['objectid'] : null, 'REQUEST');
    $confirmation = FormUtil::getPassedValue('confirmation', null, 'POST');
    if ($objectid) {
        $bid = $objectid;
    }

    // Get the existing admin message
    $banner = pnModAPIFunc('Banners', 'user', 'get', array('bid' => $bid, 'clientinfo' => true));
    if ($banner == false) {
        return DataUtil::formatForDisplayHTML(_NOSUCHITEM);
    }

    // Security check
    if (!SecurityUtil::checkPermission('Banners::', "$bid::", ACCESS_DELETE)) {
        return LogUtil::registerPermissionError();
    }

    // Check for confirmation.
    if (empty($confirmation)) {
        // No confirmation yet
        // Create output object
        $pnRender = pnRender::getInstance('Banners', false);

        // Add the message id
        $pnRender->assign('bid', $bid);

        // assign the full item
        $pnRender->assign($banner);

        // Return the output that has been generated by this function
        return $pnRender->fetch('banners_admin_bannerdelete.htm');
    }

    // Confirm authorisation code.
    if (!SecurityUtil::confirmAuthKey()) {
        return LogUtil::registerAuthidError (pnModURL('Banners', 'admin', 'view'));
    }

    // Delete the banner
    // The return value of the function is checked
    if (pnModAPIFunc('Banners', 'admin', 'delete', array('bid' => $bid))) {
        // Success
        LogUtil::registerStatus (_BANNERS_DELETED);
    }

    // This function generated no output, and so now it is complete we redirect
    // the user to an appropriate page for them to carry on their work
    return pnRedirect(pnModURL('Banners', 'admin', 'view'));
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
function Banners_admin_createclient($args)
{
    $client = FormUtil::getPassedValue('client', isset($args['client']) ? $args['client'] : null, 'POST');

    // Confirm authorisation code.
    if (!SecurityUtil::confirmAuthKey()) {
        return LogUtil::registerAuthidError (pnModURL('Banners', 'admin', 'view'));
    }

    if (pnModAPIFunc('Banners', 'admin', 'createclient',
                    array('cname' => $client['cname'],
                          'contact' => $client['contact'],
                          'email' => $client['email'],
                          'login' => $client['login'],
                          'passwd' => $client['passwd'],
                          'extrainfo' => $client['extrainfo']))){
        LogUtil::registerStatus (_BANNERS_CLIENTCREATED);
    }

    return pnRedirect(pnModURL('Banners', 'admin', 'main'));
}

/**
 * modify a banner client
 *
 * @author Devin Hayes
 * @param int $cid the client id
 * @return string HTML output string
 */
function Banners_admin_modifyclient($args)
{
    $cid = FormUtil::getPassedValue('cid', isset($args['cid']) ? $args['cid'] : null, 'GET');

    if (!is_numeric($cid)){
        return LogUtil::registerError (_MODARGSERROR);
    }

    // security check
    if (!SecurityUtil::checkPermission('Banners::Client', "$cid::", ACCESS_EDIT)) {
        return LogUtil::registerPermissionError();
    }

    // get the banner
    $client = pnModAPIFunc('Banners', 'user', 'getclient', array('cid' => $cid));

    if ($client == false) {
        return DataUtil::formatForDisplayHTML(_NOSUCHITEM);
    }

    // create a new output object
    $pnRender = pnRender::getInstance('Banners', false);

    // assign the banner item
    $pnRender->assign($client);

    return $pnRender->fetch('banners_admin_clientedit.htm');
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
function Banners_admin_updateclient($args)
{
    $client = FormUtil::getPassedValue('client', isset($args['client']) ? $args['client'] : null, 'POST');

    // Confirm authorisation code.
    if (!SecurityUtil::confirmAuthKey()) {
        return LogUtil::registerAuthidError (pnModURL('Banners', 'admin', 'view'));
    }

    if (pnModAPIFunc('Banners', 'admin', 'updateclient',
                     array('cid' => $client['cid'],
                           'cname' => $client['cname'],
                           'contact' => $client['contact'],
                           'email' => $client['email'],
                           'extrainfo' => $client['extrainfo'],
                           'login' => $client['login'],
                           'passwd' => $client['passwd']))){
        LogUtil::registerStatus (_BANNERS_CLIENTUPDATED);
    }

    return pnRedirect(pnModURL('Banners', 'admin', 'main'));
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
function Banners_admin_deleteclient($args)
{
    $cid = FormUtil::getPassedValue('cid', isset($args['cid']) ? $args['cid'] : null, 'REQUEST');
    $objectid = FormUtil::getPassedValue('objectid', isset($args['objectid']) ? $args['objectid'] : null, 'REQUEST');
    $confirmation = FormUtil::getPassedValue('confirmation', null, 'POST');
    if (!empty($objectid)) {
        $cid = $objectid;
    }

    // Get the existing admin message
    $client = pnModAPIFunc('Banners', 'user', 'getclient', array('cid' => $cid));

    if ($client == false) {
        return DataUtil::formatForDisplayHTML(_NOSUCHITEM);
    }

    // Security check
    if (!SecurityUtil::checkPermission('Banners::Client', "$cid::", ACCESS_DELETE)) {
        return LogUtil::registerPermissionError();
    }

    // Check for confirmation.
    if (empty($confirmation)) {
        // No confirmation yet
        // Create output object
        $pnRender = pnRender::getInstance('Banners', false);

        // Add the message id
        $pnRender->assign('cid', $cid);

        // assign the full item
        $pnRender->assign('banners', pnModAPIFunc('Banners', 'user', 'getall', array('cid' => $cid)));

        // Return the output that has been generated by this function
        return $pnRender->fetch('banners_admin_clientdelete.htm');
    }

    // Confirm authorisation code.
    if (!SecurityUtil::confirmAuthKey()) {
        return LogUtil::registerAuthidError (pnModURL('Banners', 'admin', 'view'));
    }

    // Delete the banner
    // The return value of the function is checked
    if (pnModAPIFunc('Banners', 'admin', 'deleteclient', array('cid' => $cid))) {
        // Success
        LogUtil::registerStatus (_BANNERS_CLIENTDELETED);
    }

    // This function generated no output, and so now it is complete we redirect
    // the user to an appropriate page for them to carry on their work
    return pnRedirect(pnModURL('Banners', 'admin', 'view'));
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
function Banners_admin_deletefinished($args)
{
    $bid = FormUtil::getPassedValue('bid', isset($args['bid']) ? $args['bid'] : null, 'REQUEST');
    $objectid = FormUtil::getPassedValue('objectid', isset($args['objectid']) ? $args['objectid'] : null, 'REQUEST');
    $confirmation = FormUtil::getPassedValue('confirmation', null, 'POST');
    if (!empty($objectid)) {
        $bid = $objectid;
    }

    // Get the existing admin message
    $banner = pnModAPIFunc('Banners', 'user', 'get', array('bid' => $bid));

    if ($banner == false) {
        return DataUtil::formatForDisplayHTML(_NOSUCHITEM);
    }

    // Security check
    if (!SecurityUtil::checkPermission('Banners::', "$bid::", ACCESS_DELETE)) {
        return LogUtil::registerPermissionError();
    }

    // Check for confirmation.
    if (empty($confirmation)) {
        // No confirmation yet
        // Create output object
        $pnRender = pnRender::getInstance('Banners', false);

        // Add the message id
        $pnRender->assign('bid', $bid);

        // assign the full item
        $pnRender->assign(pnModAPIFunc('Banners', 'user', 'getfinished', array('bid' => $bid)));

        // Return the output that has been generated by this function
        return $pnRender->fetch('banners_admin_bannerdelete.htm');
    }

    // Confirm authorisation code.
    if (!SecurityUtil::confirmAuthKey()) {
        return LogUtil::registerAuthidError (pnModURL('Banners', 'admin', 'view'));
    }

    // Delete the banner
    // The return value of the function is checked
    if (pnModAPIFunc('Banners', 'admin', 'deletefinished', array('bid' => $bid))) {
        // Success
        LogUtil::registerStatus (_BANNERS_DELETED);
    }

    // This function generated no output, and so now it is complete we redirect
    // the user to an appropriate page for them to carry on their work
    return pnRedirect(pnModURL('Banners', 'admin', 'view'));
}

/**************************** configuration functions ****************************/

/**
 * This is a standard function to modify the configuration parameters of the
 * module
 *
 * @author Devin Hayes
 * @return string HTML output string
 */
function Banners_admin_modifyconfig()
{
    // Security check
    if (!SecurityUtil::checkPermission('Banners::', '::', ACCESS_ADMIN)) {
        return LogUtil::registerPermissionError();
    }

    // Create output object
    $pnRender = pnRender::getInstance('Banners', false);

    // Number of items to display per page
    $pnRender->assign(pnModGetVar('Banners'));

    // Return the output that has been generated by this function
    return $pnRender->fetch('banners_admin_modifyconfig.htm');
}

/**
 * This is a standard function to update the configuration parameters of the
 * module given the information passed back by the modification form
 *
 * @author Devin Hayes
 * @param int $itemsperpage the number messages per page in the admin panel
 * @return bool true if successful, false otherwise
 */
function Banners_admin_updateconfig()
{
    if (!SecurityUtil::checkPermission('Banners::', '::', ACCESS_ADMIN)) {
        return LogUtil::registerPermissionError();
    }

    $banners         = FormUtil::getPassedValue('banners', null, 'POST');
    $myIP            = FormUtil::getPassedValue('myIP', null, 'POST');
    $openinnewwindow = FormUtil::getPassedValue('openinnewwindow', null, 'POST');

    // Confirm authorisation code.
    if (!SecurityUtil::confirmAuthKey()) {
        return LogUtil::registerAuthidError (pnModURL('Banners', 'admin', 'view'));
    }

    // Update module variables.
    pnModSetVar('Banners', 'banners', $banners);
    pnModSetVar('Banners', 'myIP', $myIP);
    pnModSetVar('Banners', 'openinnewwindow', $openinnewwindow);

    // Let any other modules know that the modules configuration has been updated
    pnModCallHooks('module','updateconfig','Banners', array('module' => 'Banners'));

    // the module configuration has been updated successfuly
    LogUtil::registerStatus (_CONFIGUPDATED);

    // This function generated no output, and so now it is complete we redirect
    // the user to an appropriate page for them to carry on their work
    return pnRedirect(pnModURL('Banners', 'admin', 'view'));
}
