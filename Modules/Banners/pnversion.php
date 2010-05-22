<?php
/**
 * @package      Banners
 * @version      $Id:
 * @author       Halbrook Technologies
 * @link         http://www.halbrooktech.com
 * @copyright    Copyright (C) 2010
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */
$dom = ZLanguage::getModuleDomain('Banners');

$modversion['name']           = 'Banners';
$modversion['displayname']    = __('Banners', $dom);
$modversion['description']    = __('Banners Management', $dom);
$modversion['version'] = '3.0';
$modversion['credits'] = 'pndocs/credits.txt';
$modversion['help'] = 'pndocs/help.txt';
$modversion['changelog'] = 'pndocs/changelog.txt';
$modversion['license'] = 'pndocs/license.txt';
$modversion['author'] = 'Michael Halbrook';
$modversion['contact'] = 'http://www.halbrooktech.com';
$modversion['securityschema'] = array('Banners::' => 'Banner ID::',
                                      'Banners::Client' => 'Client ID::');
