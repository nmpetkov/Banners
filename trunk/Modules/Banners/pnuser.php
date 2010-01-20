<?php
/**
 * Zikula Application Framework
 *
 * @copyright (c) 2002, Zikula Development Team
 * @link http://www.zikula.org
 * @version $Id: pnuser.php 9 2008-11-05 21:42:16Z Guite $
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package Zikula_Value_Addons
 * @subpackage Banners
 */

/**
 * the main user function
 *
 * @author Devin Hayes
 * @return string HTML output string
 */
function banners_user_main()
{
    // Security check
    if (!SecurityUtil::checkPermission('Banners::', '::', ACCESS_READ)) {
        return LogUtil::registerPermissionError();
    }

    // load the admin language file to avoid duplication
    pnModLangLoad('Banners', 'admin');

    // Create output object
    $pnRender = pnRender::getInstance('Banners', false);

    return $pnRender->fetch('banners_user_main.htm');
}

/**
 * the main user function
 *
 * @author Devin Hayes
 * @return string HTML output string
 */
function Banners_user_client($args)
{
    $login = FormUtil::getPassedValue('login', isset($args['login']) ? $args['login'] : null, 'REQUEST');
    $pass = FormUtil::getPassedValue('pass', isset($args['pass']) ? $args['pass'] : null, 'REQUEST');

    // Security check
    if (!SecurityUtil::checkPermission('Banners::', '::', ACCESS_READ)) {
        return LogUtil::registerPermissionError();
    }

    // load the admin language file to avoid duplication
    pnModLangLoad('Banners', 'admin');

    // check our input
    if ($login == '' OR $pass == '') {
        LogUtil::registerError (_BANNERS_LOGININCORR);
        return pnRedirect(pnModURL('Banners', 'user', 'main'));
    }

    // check the authorisation key
    // note - we're not confirming the auth key since the banner client setup doesn't have any sessions
    // so the auth key checking breaks a reload
    /*if (!SecurityUtil::confirmAuthKey()) {
        LogUtil::registerError (_BADAUTHKEY);
        return pnRedirect(pnModURL('Banners', 'user', 'main'));
    }*/

    // validate the user login
    $client = pnModAPIFunc('Banners', 'user', 'validateclient', array('login' => $login, 'pass' => $pass));
    if (!$client) {
        LogUtil::registerError (_BANNERS_LOGININCORR);
        return pnRedirect(pnModURL('Banners', 'user', 'main'));
    } else {
        $banners = pnModAPIFunc('Banners', 'user', 'getall', array('cid' => $client['cid']));
    }

    // calculate some additional values
    foreach($banners as $key => $banner) {
        if ($banners[$key]['impmade'] == 0) {
            $banners[$key]['percent'] = 0;
        } else {
            $banners[$key]['percent'] = substr(100 * $banners[$key]['clicks'] / $banners[$key]['impmade'], 0, 5);
        }
        if ($banners[$key]['imptotal'] == 0) {
            $banners[$key]['impleft'] =_BANNERS_UNLIMITED;
            $banners[$key]['imptotal'] = _BANNERS_UNLIMITED;
        } else {
            $banners[$key]['impleft'] = $banners[$key]['imptotal']-$banners[$key]['impmade'];
        }
    }

    // Create output object
    $pnRender = pnRender::getInstance('Banners', false);
    $pnRender->assign('banners', $banners);
    $pnRender->assign(array('login' => $login, 'pass' => $pass));
    $pnRender->assign($client);
    return $pnRender->fetch('banners_user_config.htm');
}

/**
 * e-mail usage stats for a banner to the designated contact e-mail
 *
 * @author Devin Hayes
 * @return string HTML output string
 */
function Banners_user_emailstats($args)
{
    $login = FormUtil::getPassedValue('login', isset($args['login']) ? $args['login'] : null, 'GET');
    $pass = FormUtil::getPassedValue('pass', isset($args['pass']) ? $args['pass'] : null, 'GET');
    $cid = FormUtil::getPassedValue('cid', isset($args['cid']) ? $args['cid'] : null, 'GET');
    $bid = FormUtil::getPassedValue('bid', isset($args['bid']) ? $args['bid'] : null, 'GET');

    // Security check
    if (!SecurityUtil::checkPermission('Banners::', '::', ACCESS_READ)) {
        return LogUtil::registerPermissionError();
    }

    // load the admin language file to avoid duplication
    pnModLangLoad('Banners', 'admin');

    $client = pnModAPIFunc('Banners', 'user', 'validateclient', array('login' => $login, 'pass' => $pass));
    if (!$client) {
        LogUtil::registerError (_BANNERS_LOGININCORR);
    } else {
        if (!pnModAPIFunc('Banners', 'user', 'emailstats',
                           array('bid' => $bid, 'email' => $client['email'], 'cid' => $client['cid']))) {
            LogUtil::registerError (_BANNERS_CONTACTADMIN);
        } else {
            LogUtil::registerStatus (_BANNERS_STATSSENT);
        }
    }

    return pnRedirect(pnModURL('Banners', 'user', 'client', array('login' => $login, 'pass' => $pass)));
}

/**
 * update the banners target url
 *
 * @author Devin Hayes
 * @return string HTML output string
 */
function Banners_user_changeurl($args)
{
    $login = FormUtil::getPassedValue('login', isset($args['login']) ? $args['login'] : null, 'POST');
    $pass = FormUtil::getPassedValue('pass', isset($args['pass']) ? $args['pass'] : null, 'POST');
    $cid = FormUtil::getPassedValue('cid', isset($args['cid']) ? $args['cid'] : null, 'POST');
    $bid = FormUtil::getPassedValue('bid', isset($args['bid']) ? $args['bid'] : null, 'POST');
    $url = FormUtil::getPassedValue('url', isset($args['url']) ? $args['url'] : null, 'POST');

    if (!SecurityUtil::checkPermission('Banners::', '::', ACCESS_READ)) {
        return LogUtil::registerPermissionError();
    }

    // load the admin language file to avoid duplication
    pnModLangLoad('Banners', 'admin');

    if (!isset($bid) || !is_numeric($bid)){
        return DataUtil::formatForDisplayHTML(_MODARGSERROR);
    }

    // check client credentials
    $client = pnModAPIFunc('Banners', 'user', 'validateclient', array('login' => $login, 'pass' => $pass));
    if (!$client) {
        LogUtil::registerError (_BANNERS_LOGININCORR);
    } else {
        if (!pnModAPIFunc('Banners', 'user', 'changeurl',
                          array('bid' => $bid, 'url' => $url))) {
            LogUtil::registerError (_BANNERS_CONTACTADMIN);
        } else {
            LogUtil::registerStatus (_BANNERS_URLCHANGED);
        }
    }

    return pnRedirect(pnModURL('Banners', 'user', 'client', array('login' => $login, 'pass' => $pass)));
}

/**
 * register a click on a banner and redirec to the target url
 *
 * @author Devin Hayes
 * @return string HTML output string
 */
function Banners_user_click($args)
{
    // check that were coming from a local referer
    if (!pnLocalReferer(true)) {
        return DataUtil::formatForDisplay(_MODULENOAUTH);
    }

    $bid = FormUtil::getPassedValue('bid', isset($args['bid']) ? $args['bid'] : null, 'GET');

    if (!isset($bid) && !is_numeric($bid)){
        return LogUtil::registerError (_MODARGSERROR);
    }

    $banner = pnModAPIFunc('Banners', 'user', 'get', array('bid' => $bid));
    if (!$banner) {
        return DataUtil::formatForDisplayHTML(_NOSUCHITEM);
    }

    // register the click and redirect
    if (pnModAPIFunc('Banners', 'user', 'click', array('bid' => $bid))){
        if (strpos($banner['clickurl'], 'index.php') === 0) {
            // do nothing, local system URL
        } elseif (substr($banner['clickurl'], 0, 7) != 'http://'){
            $banner['clickurl'] = 'http://'.$banner['clickurl'];
        }
        return pnRedirect($banner['clickurl'], array('HTTP/1.1 301 Moved Permanently'));
    }
    return false;
}

/**
 * display a random banner
 *
 * code migrated from pnBannerDisplay which now calls this api
 * @see pnBanners.php
 * @param $args['type'] banner type
 * @return string containing banner or &nbsp;
 */
function Banners_user_display($args)
{

    // test on config settings
    if (pnModGetVar('Banners', 'banners') != 1) {
        return '&nbsp;';
    }

    // check our input
    if (!isset($args['type']) || !is_numeric($args['type'])) {
        $args['type'] = 1;
    }

    // get the banner count
    $numrows = pnModAPIFunc('Banners', 'user', 'countitems', array('type' => $args['type']));

    // Get a random banner if exist any.
    // More efficient random stuff, thanks to Cristian Arroyo from http://www.planetalinux.com.ar
    if ($numrows >= 1) {
        $numrows = $numrows-1;
        mt_srand((double)microtime() * 1000000);
        $bannum = mt_rand(0, $numrows);
    } else {
        return '&nbsp;';
    }

    $banners = pnModAPIFunc('Banners', 'user', 'getall', array('type' => $args['type']));
    if (isset($banners[$bannum])) {
        $banner = $banners[$bannum];
    } else {
        return '&nbsp;';
    }

    // check the current host and admin exceptions
	// log the impression if required
    $myIP = pnModGetVar('banners', 'myIP');
    $myhost = pnServerGetVar('REMOTE_ADDR');
    if (!empty($myIP) && substr($myhost, 0, strlen($myIP)) != $myIP) {
        pnModAPIFunc('Banners', 'user', 'impmade', array('bid' => $banner['bid']));
    }

    // Check if this impression is the last one and print the banner
    if ($banner['imptotal'] > 0 && $banner['imptotal'] == $banner['impmade']) {
        pnModAPIFunc('Banners', 'user', 'finish', array('bid' => $banner['bid']));
    }

    // check for a flash banner
    if (strtolower(substr($banner['imageurl'], -4)) == '.swf') {
	    // flash banner code based on Banners Plus code; tidied for (x)html
		// Original comment (Powered by E.U LUGUNAR (http://www.lugunar.com))
        $bannerstring = '<object codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=5,0,0,0" classid="clsid:D27CDB6E-AE6D-11CF-96B8-444553540000">';
        $bannerstring .= '<param name="movie" value="'.DataUtil::formatForDisplay($banner['imageurl']).'" />';
        $bannerstring .= '<param name="quality" value="high" />';
        $bannerstring .= '<embed name="animacion" src='.DataUtil::formatForDisplay($banner['imageurl']).' quality="high" pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" />';
        $bannerstring .= '</object>';
	} else {
	if ($banner['clickurl']) {
        $bannerstring = '<a href="'. DataUtil::formatForDisplay(pnModURL('Banners', 'user', 'click', array('bid' => $banner['bid']))).'" title="' . DataUtil::formatForDisplay($banner['clickurl']);
        if (pnModGetVar('Banners', 'openinnewwindow')) {
            $bannerstring .= ' " target="_blank';
        }
        $bannerstring .= '">';
        $bannerstring .= '<img src="'.DataUtil::formatForDisplay($banner['imageurl']) . '" alt="'.DataUtil::formatForDisplay($banner['clickurl']) .'" />';
        $bannerstring .= '</a>'; }else{
		$bannerstring .= '<img src="'.DataUtil::formatForDisplay($banner['imageurl']) .'" />';}
}
    return $bannerstring;
}
 /**
 * display a random banner
 *
 * code migrated from pnBannerDisplay which now calls this api
 * @see pnBanners.php
 * @param $args['type'] banner type
 * @return string containing banner or &nbsp;
 */
function Banners_user_rotate($args)
{

    // test on config settings
    if (pnModGetVar('Banners', 'banners') != 1) {
        return '&nbsp;';
    }

    // check our input
    if (!isset($args['type']) || !is_numeric($args['type'])) {
        $args['type'] = 1;
    }

    // get the banner count
    $numrows = pnModAPIFunc('Banners', 'user', 'countitems', array('type' => $args['type']));

    $banners = pnModAPIFunc('Banners', 'user', 'getall', array('type' => $args['type']));
	$count = $numrows;
	$banid = 0;
	while ($count >= 1) {$count--;
	$allbanners[$count] = array ('banid' => $banid,'imageurl' => $banners[$count]['imageurl'], 'clickurl' => $banners[$count]['clickurl']);
	$banid++;
	}

    // check the current host and admin exceptions
	// log the impression if required
    $myIP = pnModGetVar('banners', 'myIP');
    $myhost = pnServerGetVar('REMOTE_ADDR');
    if ((!empty($myIP) && substr($myhost, 0, strlen($myIP)) != $myIP) && (isset($banners['bid']))){
        pnModAPIFunc('Banners', 'user', 'impmade', array('bid' => $banners['bid']));
    }

    // Check if this impression is the last one and print the banner
    if ((isset($banners['impmade'])) && ($banners['imptotal'] > 0 && $banners['imptotal'] == $banners['impmade'])) {
        pnModAPIFunc('Banners', 'user', 'finish', array('bid' => $banners['bid']));
    }

return $allbanners;

}