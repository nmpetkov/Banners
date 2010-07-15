<?php
/**
 * @package      Banners
 * @version      $Id:
 * @author       Halbrook Technologies
 * @link         http://www.halbrooktech.com
 * @copyright    Copyright (C) 2010
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */

class Banners_Installer extends Zikula_Installer
{
    /**
     * initialise the Banners module
     * This function is only ever called once during the lifetime of a particular
     * module instance
     *
     * @todo identify suitable indexes
     * @author Devin Hayes
     * @return bool true if successful, false otherwise
     */
    public function install() {
        // create the table
        if (!DBUtil::createTable('banners')) {
            return false;
        }
        if (!DBUtil::createTable('bannersclient')) {
            return false;
        }
        if (!DBUtil::createTable('bannersfinish')) {
            return false;
        }

        $banners = ModUtil::getVar('banners');
        ModUtil::setVar('Banners', 'myIP', '127.0.0.1');
        ModUtil::setVar('Banners', 'banners', $banners);
        ModUtil::setVar('Banners', 'openinnewwinow', false);


        // Initialisation successful
        LogUtil::registerStatus($this->__('Banners module installed'));
        return true;
    }

    /**
     * upgrade the Banners module from an old version
     * This function can be called multiple times
     * @author Devin Hayes
     * @return bool true if successful, false otherwise
     */
    public function upgrade($oldversion) {
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
                if (Config::getVar('banners') != '') {
                    $myIP = Config::getVar('myIP');
                    $banners = Config::getVar('banners');
                    ModUtil::setVar('Banners', 'myIP', $myIP);
                    ModUtil::setVar('Banners', 'banners', $banners);
                    ModUtil::setVar('Banners', 'openinnewwinow', false);
                    Config::delVar('myIP');
                    Config::delVar('banners');
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
    public function uninstall() {
        // drop the three tables for the module
        $tables = array('banners', 'bannersclient', 'bannersfinish');
        foreach ($tables as $table) {
            if (!DBUtil::dropTable($table)) {
                return false;
            }
        }

        // delete all module vars
        ModUtil::delVar('Banners');

        // Delete successful
        return true;
    }
}