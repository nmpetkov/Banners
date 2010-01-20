<?php
/**
 * Zikula Application Framework
 *
 * @copyright (c) 2002, Zikula Development Team
 * @link http://www.zikula.org
 * @version $Id: pnversion.php 9 2008-11-05 21:42:16Z Guite $
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package Zikula_Value_Addons
 * @subpackage Banners
 */

$modversion['name']           = 'Banners';
$modversion['displayname']    = _BANNERS_DISPLAYNAME;
$modversion['description']    = _BANNERS_DESCRIPTION;
$modversion['version'] = '2.2';
$modversion['credits'] = 'pndocs/credits.txt';
$modversion['help'] = 'pndocs/help.txt';
$modversion['changelog'] = 'pndocs/changelog.txt';
$modversion['license'] = 'pndocs/license.txt';
$modversion['official'] = 1;
$modversion['author'] = 'Michael Halbrook';
$modversion['contact'] = 'http://www.halbrooktech.com';
$modversion['securityschema'] = array('Banners::' => 'Banner ID::',
                                      'Banners::Client' => 'Client ID::');
