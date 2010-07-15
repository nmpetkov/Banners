<?php
/**
 * @package      Banners
 * @version      $Id:
 * @author       Halbrook Technologies
 * @link         http://www.halbrooktech.com
 * @copyright    Copyright (C) 2010
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */
class Banners_Controller_User extends Zikula_Controller {
    /**
     * the main user function
     *
     * @author Devin Hayes
     * @return string HTML output string
     */
    public function main() {
        // Security check
        if (!SecurityUtil::checkPermission('Banners::', '::', ACCESS_READ)) {
            return LogUtil::registerPermissionError();
        }

        return $this->view->fetch('user/main.tpl');
    }

    /**
     * the main user function
     *
     * @author Devin Hayes
     * @return string HTML output string
     */
    public function client($args) {
        $login = FormUtil::getPassedValue('login', isset($args['login']) ? $args['login'] : null, 'REQUEST');
        $pass = FormUtil::getPassedValue('pass', isset($args['pass']) ? $args['pass'] : null, 'REQUEST');

        // Security check
        if (!SecurityUtil::checkPermission('Banners::', '::', ACCESS_READ)) {
            return LogUtil::registerPermissionError();
        }



        // check our input
        if ($login == '' OR $pass == '') {
            LogUtil::registerError($this->__('Login Incorrect'));
            return System::redirect(ModUtil::url('Banners', 'user', 'main'));
        }

        // validate the user login
        $client = ModUtil::apiFunc('Banners', 'user', 'validateclient', array('login' => $login, 'pass' => $pass));
        if (!$client) {
            LogUtil::registerError($this->__('Login Incorrect'));
            return System::redirect(ModUtil::url('Banners', 'user', 'main'));
        } else {
            $banners = ModUtil::apiFunc('Banners', 'user', 'getall', array('cid' => $client['cid']));
        }

        // calculate some additional values
        foreach($banners as $key => $banner) {
            if ($banners[$key]['impmade'] == 0) {
                $banners[$key]['percent'] = 0;
            } else {
                $banners[$key]['percent'] = substr(100 * $banners[$key]['clicks'] / $banners[$key]['impmade'], 0, 5);
            }
            if ($banners[$key]['imptotal'] == 0) {
                $banners[$key]['impleft'] ='Unlimited';
                $banners[$key]['imptotal'] = 'Unlimited';
            } else {
                $banners[$key]['impleft'] = $banners[$key]['imptotal']-$banners[$key]['impmade'];
            }
        }

        $this->view->assign('banners', $banners);
        $this->view->assign(array('login' => $login, 'pass' => $pass));
        $this->view->assign($client);
        return $this->view->fetch('user/config.tpl');
    }

    /**
     * e-mail usage stats for a banner to the designated contact e-mail
     *
     * @author Devin Hayes
     * @return string HTML output string
     */
    public function emailstats($args) {
        $login = FormUtil::getPassedValue('login', isset($args['login']) ? $args['login'] : null, 'GET');
        $pass = FormUtil::getPassedValue('pass', isset($args['pass']) ? $args['pass'] : null, 'GET');
        $cid = FormUtil::getPassedValue('cid', isset($args['cid']) ? $args['cid'] : null, 'GET');
        $bid = FormUtil::getPassedValue('bid', isset($args['bid']) ? $args['bid'] : null, 'GET');

        // Security check
        if (!SecurityUtil::checkPermission('Banners::', '::', ACCESS_READ)) {
            return LogUtil::registerPermissionError();
        }



        $client = ModUtil::apiFunc('Banners', 'user', 'validateclient', array('login' => $login, 'pass' => $pass));
        if (!$client) {
            LogUtil::registerError($this->__('Login Incorrect'));
        } else {
            if (!ModUtil::apiFunc('Banners', 'user', 'emailstats',
            array('bid' => $bid, 'email' => $client['email'], 'cid' => $client['cid']))) {
                LogUtil::registerError($this->__('Please contact the administrator.'));
            } else {
                LogUtil::registerStatus($this->__('Statistics e-mailed'));
            }
        }

        return System::redirect(ModUtil::url('Banners', 'user', 'client', array('login' => $login, 'pass' => $pass)));
    }

    /**
     * update the banners target url
     *
     * @author Devin Hayes
     * @return string HTML output string
     */
    public function changeurl($args) {
        $login = FormUtil::getPassedValue('login', isset($args['login']) ? $args['login'] : null, 'POST');
        $pass = FormUtil::getPassedValue('pass', isset($args['pass']) ? $args['pass'] : null, 'POST');
        $cid = FormUtil::getPassedValue('cid', isset($args['cid']) ? $args['cid'] : null, 'POST');
        $bid = FormUtil::getPassedValue('bid', isset($args['bid']) ? $args['bid'] : null, 'POST');
        $url = FormUtil::getPassedValue('url', isset($args['url']) ? $args['url'] : null, 'POST');

        if (!SecurityUtil::checkPermission('Banners::', '::', ACCESS_READ)) {
            return LogUtil::registerPermissionError();
        }


        if (!isset($bid) || !is_numeric($bid)) {
            return LogUtil::registerError($this->__('Error! Could not do what you wanted. Please check your input.'));
        }

        // check client credentials
        $client = ModUtil::apiFunc('Banners', 'user', 'validateclient', array('login' => $login, 'pass' => $pass));
        if (!$client) {
            LogUtil::registerError($this->__('Login Incorrect'));
        } else {
            if (!ModUtil::apiFunc('Banners', 'user', 'changeurl',
            array('bid' => $bid, 'url' => $url))) {
                LogUtil::registerError($this->__('Please contact the administrator.'));
            } else {
                LogUtil::registerStatus($this->__('URL Changed'));
            }
        }

        return System::redirect(ModUtil::url('Banners', 'user', 'client', array('login' => $login, 'pass' => $pass)));
    }

    /**
     * register a click on a banner and redirec to the target url
     *
     * @author Devin Hayes
     * @return string HTML output string
     */
    public function click($args) {
        // check that were coming from a local referer
        if (!System::localReferer(true)) {
            return DataUtil::formatForDisplay('Sorry! No authorization to access this module.');
        }

        $bid = FormUtil::getPassedValue('bid', isset($args['bid']) ? $args['bid'] : null, 'GET');

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
                $banner['clickurl'] = 'http://'.$banner['clickurl'];
            }
            return System::redirect($banner['clickurl'], array('HTTP/1.1 301 Moved Permanently'));
        }
        return false;
    }

    /**
     * display a random banner
     *
     * @param $args['type'] banner type
     * @return string containing banner or &nbsp;
     */
    public function display($args) {

        // test on config settings
        if (ModUtil::getVar('Banners', 'banners') != 1) {
            return '&nbsp;';
        }

        // check our input
        if (!isset($args['btype']) || !is_numeric($args['btype'])) {
            $args['btype'] = 1;
        }

        // get the banner count
        
        $numrows = ModUtil::apiFunc('Banners', 'user', 'countitems', array('type' => $args['type']));
        
        // Get a random banner if exist any.
        // More efficient random stuff, thanks to Cristian Arroyo from http://www.planetalinux.com.ar
        if ($numrows >= 1) {
            $numrows = $numrows-1;
            mt_srand((double)microtime() * 1000000);
            $bannum = mt_rand(0, $numrows);
        } else {
            return '&nbsp;';
        }

        $banners = ModUtil::apiFunc('Banners', 'user', 'getall', array('btype' => $args['btype']));
        if (isset($banners[$bannum])) {
            $banner = $banners[$bannum];
        } else {
            return '&nbsp;';
        }

        // check the current host and admin exceptions
        // log the impression if required
        $myIP = ModUtil::getVar('banners', 'myIP');
        $myhost = System::serverGetVar('REMOTE_ADDR');
        if (!empty($myIP) && substr($myhost, 0, strlen($myIP)) != $myIP) {
            ModUtil::apiFunc('Banners', 'user', 'impmade', array('bid' => $banner['bid']));
        }

        // Check if this impression is the last one and print the banner
        if ($banner['imptotal'] > 0 && $banner['imptotal'] == $banner['impmade']) {
            ModUtil::apiFunc('Banners', 'user', 'finish', array('bid' => $banner['bid']));
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
                $bannerstring = '<a href="'. DataUtil::formatForDisplay(ModUtil::url('Banners', 'user', 'click', array('bid' => $banner['bid']))).'" title="' . DataUtil::formatForDisplay($banner['clickurl']);
                if (ModUtil::getVar('Banners', 'openinnewwindow')) {
                    $bannerstring .= ' " target="_blank';
                }
                $bannerstring .= '">';
                $bannerstring .= '<img src="'.DataUtil::formatForDisplay($banner['imageurl']) . '" alt="'.DataUtil::formatForDisplay($banner['clickurl']) .'" />';
                $bannerstring .= '</a>';
            }else {
                $bannerstring .= '<img src="'.DataUtil::formatForDisplay($banner['imageurl']) .'" />';
            }
        }
        return $bannerstring;
    }
    /**
     * display a random banner
     *
     * @param $args['type'] banner type
     * @return string containing banner or &nbsp;
     */
    public function rotate($args) {

        // test on config settings
        if (ModUtil::getVar('Banners', 'banners') != 1) {
            return '&nbsp;';
        }

        // check our input
        if (!isset($args['type']) || !is_numeric($args['type'])) {
            $args['type'] = 1;
        }

        // get the banner count
        $numrows = ModUtil::apiFunc('Banners', 'user', 'countitems', array('type' => $args['type']));

        $banners = ModUtil::apiFunc('Banners', 'user', 'getall', array('type' => $args['type']));
        $count = $numrows;
        $banid = 0;
        while ($count >= 1) {
            $count--;
            $allbanners[$count] = array ('banid' => $banid,'imageurl' => $banners[$count]['imageurl'], 'clickurl' => $banners[$count]['clickurl']);
            $banid++;
        }

        // check the current host and admin exceptions
        // log the impression if required
        $myIP = ModUtil::getVar('banners', 'myIP');
        $myhost = System::serverGetVar('REMOTE_ADDR');
        if ((!empty($myIP) && substr($myhost, 0, strlen($myIP)) != $myIP) && (isset($banners['bid']))) {
            ModUtil::apiFunc('Banners', 'user', 'impmade', array('bid' => $banners['bid']));
        }

        // Check if this impression is the last one and print the banner
        if ((isset($banners['impmade'])) && ($banners['imptotal'] > 0 && $banners['imptotal'] == $banners['impmade'])) {
            ModUtil::apiFunc('Banners', 'user', 'finish', array('bid' => $banners['bid']));
        }

        return $allbanners;

    }
}