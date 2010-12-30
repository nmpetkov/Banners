<?php

/**
 * @package      Banners
 * @author       Halbrook Technologies
 * @author       Devin Hayes
 * @author       Craig Heydenburg
 * @link         http://www.halbrooktech.com
 * @copyright    Copyright (C) 2010
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */
class Banners_Controller_User extends Zikula_Controller {

    /**
     * the main user function
     *
     * @return string HTML output string
     */
    public function main() {
        // Security check
        if (!SecurityUtil::checkPermission('Banners::', '::', ACCESS_READ)) {
            return LogUtil::registerPermissionError();
        }

        return $this->client();
    }

    /**
     * the main user function
     *
     * @return string HTML output string
     */
    public function client() {
        // Security check
        if (!SecurityUtil::checkPermission('Banners::', '::', ACCESS_READ)) {
            return LogUtil::registerPermissionError();
        }

        // validate the user
        $client = ModUtil::apiFunc('Banners', 'user', 'validateclient');
        if (!$client) {
            return $this->view->fetch('user/nonclient.tpl');
        } else {
            $banners = ModUtil::apiFunc('Banners', 'user', 'getall', array(
                'cid' => $client['cid'],
                'active' => -1));
        }

        // calculate some additional values
        foreach ($banners as $key => $banner) {
            $banners[$key] = Banners_Util::computestats($banner);
            $banners[$key]['led'] = $banner['active'] ? 'greenled.gif' : 'redled.gif';
        }

        $this->view->assign('banners', $banners);
        $this->view->assign('client', $client);
        return $this->view->fetch('user/client.tpl');
    }

    /**
     * e-mail usage stats for a banner to the designated contact e-mail
     *
     * @return string HTML output string
     */
    public function emailstats() {
        $cid = FormUtil::getPassedValue('cid', null, 'GET');
        $bid = FormUtil::getPassedValue('bid', null, 'GET');

        // Security check
        if (!SecurityUtil::checkPermission('Banners::', '::', ACCESS_READ)) {
            return LogUtil::registerPermissionError();
        }

        $banner = ModUtil::apiFunc('Banners', 'user', 'get', (array(
                    'bid' => $bid,
                    'cid' => $cid)));
        $client = ModUtil::apiFunc('Banners', 'user', 'getclient', (array(
                    'cid' => $cid)));
        if (!$banner) {
            return LogUtil::registerError($this->__f('Error! Could not find banner (%s)', $bid));
        }

        $banner = Banners_Util::computestats($banner);

        $this->view->assign('banner', $banner);
        $this->view->assign('client', $client);
        $this->view->assign('date', date("F jS Y, h:iA."));
        $message = $this->view->fetch('email/stats_body.tpl');
        $mailsent = ModUtil::apiFunc('Mailer', 'user', 'sendmessage', array(
                    'toaddress' => $client['email'],
                    'toname' => $client['contact'],
                    'subject' => $this->__f('Advertising stats for %s', System::getVar('sitename')),
                    'body' => $message));
        if ($mailsent) {
            LogUtil::registerStatus($this->__('Statistics e-mailed'));
        } else {
            LogUtil::registerError($this->__('Error! Could not email statistics.'));
        }

        return System::redirect(ModUtil::url('Banners', 'user', 'client'));
    }

    public function editurl() {
        $bid = FormUtil::getPassedValue('bid', null, 'GET');

        // validate the user
        $client = ModUtil::apiFunc('Banners', 'user', 'validateclient');
        if (!$client) {
            return $this->view->fetch('user/nonclient.tpl');
        } else {
            $banner = ModUtil::apiFunc('Banners', 'user', 'get', array('bid' => $bid));
        }

        $this->view->assign('banner', $banner);
        $this->view->assign('client', $client);
        return $this->view->fetch('user/editurl.tpl');
    }

    /**
     * update the banners target url
     *
     * @return string HTML output string
     */
    public function changeurl() {
        $cid = FormUtil::getPassedValue('cid', null, 'POST');
        $bid = FormUtil::getPassedValue('bid', null, 'POST');
        $url = FormUtil::getPassedValue('url', null, 'POST');

        if (!SecurityUtil::checkPermission('Banners::', '::', ACCESS_READ)) {
            return LogUtil::registerPermissionError();
        }

        if (!isset($bid) || !is_numeric($bid)) {
            return LogUtil::registerError($this->__('Error! Could not do what you wanted. Please check your input.'));
        }

        // check client credentials
        $client = ModUtil::apiFunc('Banners', 'user', 'validateclient');
        if (!$client) {
            LogUtil::registerError($this->__('Not a Valid Banners Client'));
        } else {
            if (!ModUtil::apiFunc('Banners', 'user', 'changeurl', array(
                        'bid' => $bid,
                        'url' => $url))) {
                LogUtil::registerError($this->__('Please contact the administrator.'));
            } else {
                LogUtil::registerStatus($this->__('URL Changed'));
            }
        }

        return System::redirect(ModUtil::url('Banners', 'user', 'client'));
    }

    /**
     * register a click on a banner and redirec to the target url
     *
     * @return string HTML output string
     */
    public function click() {
        // check that were coming from a local referer
        if (!System::localReferer(true)) {
            return DataUtil::formatForDisplay('Sorry! No authorization to access this module.');
        }

        $bid = FormUtil::getPassedValue('bid', null, 'GET');

        if (!isset($bid) && !is_numeric($bid)) {
            return LogUtil::registerArgsError();
        }

        $banner = ModUtil::apiFunc('Banners', 'user', 'get', array('bid' => $bid));
        if (!$banner) {
            return LogUtil::registerError($this->__('No such item found.'));
        }

        // register the click and redirect
        if (ModUtil::apiFunc('Banners', 'user', 'click', array('bid' => $bid))) {
            if (strpos($banner['clickurl'], 'index.php') === 0) {
                // do nothing, local system URL
            } elseif (substr($banner['clickurl'], 0, 7) != 'http://') {
                $banner['clickurl'] = 'http://' . $banner['clickurl'];
            }
            return System::redirect($banner['clickurl'], array('HTTP/1.1 301 Moved Permanently'));
        }
        return false;
    }

    /**
     * display a random banner
     *
     * @param array $args['blocktype'] banner type
     * @param string $args['hovertext']
     * @return string containing banner or &nbsp;
     */
    public function display($args) {

        // test on config settings
        if ($this->getVar('banners') != 1) {
            return '&nbsp;';
        }
        $hovertext = !empty($args['hovertext']) ? $args['hovertext'] : '';
        $catFilter = $args['blocktype'];
        $catFilter['__META__']['module'] = 'Banners';
        // get the banner count
        $numrows = ModUtil::apiFunc('Banners', 'user', 'countitems', array('catFilter' => $catFilter));


        // Get a random banner if exist any.
        // More efficient random stuff, thanks to Cristian Arroyo from http://www.planetalinux.com.ar
        // Craig Heydenburg -  14 Aug 2010
        // Note: As of PHP 4.2.0, there is no need to seed the random number generator with srand() or mt_srand() as this is now done automatically.
        // http://us2.php.net/manual/en/function.mt-srand.php
        // TODO: change this ???
        if ($numrows >= 1) {
            $numrows = $numrows - 1;
            mt_srand((double) microtime() * 1000000);
            $bannum = mt_rand(0, $numrows);
        } else {
            return '&nbsp;';
        }

        // TODO would be nice if we could randomly fetch only one banner instead of all of them.
        $banners = ModUtil::apiFunc('Banners', 'user', 'getall', array('catFilter' => $catFilter));
        if (isset($banners[$bannum])) {
            $banner = $banners[$bannum];
        } else {
            return array('displaystring' => '&nbsp;');
        }

        // check the current host and admin exceptions
        // log the impression if required
        $myIParray = $this->getVar('myIP');
        $myhost = System::serverGetVar('REMOTE_ADDR');
        if (!in_array($myhost, $myIParray)) {
            ModUtil::apiFunc('Banners', 'user', 'impmade', array('bid' => $banner['bid']));
            $banner['impmade']++; // increment in local instance
        }

        // Check if this impression is the last one and print the banner
        if (($banner['imptotal'] > 0) && ($banner['impmade'] >= $banner['imptotal'])) {
            ModUtil::apiFunc('Banners', 'user', 'finish', array('bid' => $banner['bid']));
        }

        // check for a flash banner
        if (strtolower(substr($banner['imageurl'], -4)) == '.swf') {
            // flash banner code based on Banners Plus code; tidied for (x)html
            // Original comment (Powered by E.U LUGUNAR (http://www.lugunar.com))
            $bannerstring  = '<object codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=5,0,0,0" classid="clsid:D27CDB6E-AE6D-11CF-96B8-444553540000">';
            $bannerstring .= '<param name="movie" value="' . DataUtil::formatForDisplay($banner['imageurl']) . '" />';
            $bannerstring .= '<param name="quality" value="high" />';
            $bannerstring .= '<embed name="animacion" src=' . DataUtil::formatForDisplay($banner['imageurl']) . ' quality="high" pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" />';
            $bannerstring .= '</object>';
        } else {
            if ($banner['clickurl']) {
                $title = '';
                $target = '';
                $url = DataUtil::formatForDisplay(ModUtil::url('Banners', 'user', 'click', array('bid' => $banner['bid'])));
                if (empty($hovertext)) {
                    $title = ' title="' . DataUtil::formatForDisplay($banner['clickurl']) . '"';
                }
                if ($this->getVar('openinnewwindow')) {
                    $target = ' target="_blank"';
                }
                $bannerstring  = "<a href='$url'" . $title . $target . '>';
                $bannerstring .= '<img src="' . DataUtil::formatForDisplay($banner['imageurl']) . '" alt="' . DataUtil::formatForDisplay($banner['clickurl']) . '" />';
                $bannerstring .= '</a>';
            } else {
                $bannerstring .= '<img src="' . DataUtil::formatForDisplay($banner['imageurl']) . '" />';
            }
        }
        $banner['displaystring'] = $bannerstring;
        return $banner;
    }

    /**
     * display a random banner
     *
     * @param $args['blocktype'] banner type
     * @return string containing banner or &nbsp;
     */
    public function rotate($args) {

        // test on config settings
        if ($this->getVar('banners') != 1) {
            return '&nbsp;';
        }

        $catFilter = $args['blocktype'];
        $catFilter['__META__']['module'] = 'Banners';

        // get the banners
        $banners = ModUtil::apiFunc('Banners', 'user', 'getall', array('catFilter' => $catFilter));

        $banid = 0;
        foreach ($banners as $key => $banner) {
            $banners[$key]['banid'] = $banid;
            $banid++;
        }

        // check the current host and admin exceptions
        // log the impression if required
        $myIParray = $this->getVar('myIP');
        $myhost = System::serverGetVar('REMOTE_ADDR');

        foreach ($banners as $banner) {
            //if ((!empty($myIP) && substr($myhost, 0, strlen($myIP)) != $myIP)) {
            if (!in_array($myhost, $myIParray)) {
                ModUtil::apiFunc('Banners', 'user', 'impmade', array('bid' => $banner['bid']));
            }

            // Check if this impression is the last one and print the banner
            if (($banner['imptotal'] > 0) && ($banner['impmade'] >= $banner['imptotal'])) {
                ModUtil::apiFunc('Banners', 'user', 'finish', array('bid' => $banner['bid']));
            }
        }

        return $banners;
    }

}