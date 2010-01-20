<?php
/**
 * Zikula Application Framework
 *
 * @copyright (c) 2002, Zikula Development Team
 * @link http://www.zikula.org
 * @version $Id: pninit.php 9 2008-11-05 21:42:16Z Guite $
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package Zikula_Value_Addons
 * @subpackage Banners
 */

/**
 * initialise the Banners module
 * This function is only ever called once during the lifetime of a particular
 * module instance
 *
 * @todo identify suitable indexes
 * @author Devin Hayes
 * @return bool true if successful, false otherwise
 */
function Banners_init()
{
    // create the three tables
    $tables = array('banners', 'bannersclient', 'bannersfinish');
    foreach ($tables as $table) {
        if (!DBUtil::createTable($table)) {
            return false;
        }
    }

    // migrate the old config vars into module vars
    if (pnConfigGetVar('banners') != ''){
        $myIP = pnConfigGetVar('myIP');
        $banners = pnConfigGetVar('banners');
        pnModSetVar('Banners', 'myIP', $myIP);
        pnModSetVar('Banners', 'banners', $banners);
		pnConfigDelVar('myIP');
		pnConfigDelVar('banners');
    } else {
        pnModSetVar('Banners', 'myIP', '127.0.0.1');
        pnModSetVar('Banners', 'banners', false);
    }
    pnModSetVar('Banners', 'openinnewwindow', false);

    // Initialisation successful
    return true;
}

/**
 * upgrade the Banners module from an old version
 * This function can be called multiple times
 * @author Devin Hayes
 * @return bool true if successful, false otherwise
 */
function Banners_upgrade($oldversion)
{
    // create the three tables
    $tables = array('banners', 'bannersclient', 'bannersfinish');
    foreach ($tables as $table) {
        if (!DBUtil::changeTable($table)) {
            return false;
        }
    }

    // Upgrade dependent on old version number
    switch($oldversion) {
        // version 1.0 was shipped with PN .7x
        case 1.0:
            // migrate the old config vars into module vars
            if (pnConfigGetVar('banners') != ''){
                $myIP = pnConfigGetVar('myIP');
                $banners = pnConfigGetVar('banners');
                pnModSetVar('Banners', 'myIP', $myIP);
                pnModSetVar('Banners', 'banners', $banners);
                pnModSetVar('Banners', 'openinnewwinow', false);
                pnConfigDelVar('myIP');
                pnConfigDelVar('banners');
            }
            break;
    }

    // Update successful
    return true;
}

/**
 * delete the Banners module
 * This function is only ever called once during the lifetime of a particular
 * module instance
 * @author Devin Hayes
 * @return bool true if successful, false otherwise
 */
function Banners_delete()
{
    // drop the three tables for the module
    $tables = array('banners', 'bannersclient', 'bannersfinish');
    foreach ($tables as $table) {
        if (!DBUtil::dropTable($table)) {
            return false;
        }
    }

    // delete all module vars
    pnModDelVar('Banners');

    // Delete successful
    return true;
}
