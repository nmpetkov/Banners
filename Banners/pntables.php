<?php
/**
 * Zikula Application Framework
 *
 * @copyright (c) 2002, Zikula Development Team
 * @link http://www.zikula.org
 * @version $Id: pntables.php 9 2008-11-05 21:42:16Z Guite $
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package Zikula_Value_Addons
 * @subpackage Banners
 */

/**
 * This function is called internally by the core whenever the module is
 * loaded.  It adds in the information
 */
function Banners_pntables()
{
    // Initialise table array
    $pntable = array();

    // Main banners column
    $pntable['banners'] = DBUtil::getLimitedTablename('banners');
    $pntable['banners_column'] = array('bid'       => 'pn_bid',
                                       'cid'       => 'pn_cid',
                                       'type'      => 'pn_type',
                                       'imptotal'  => 'pn_imptotal',
                                       'impmade'   => 'pn_impmade',
                                       'clicks'    => 'pn_clicks',
                                       'imageurl'  => 'pn_imageurl',
                                       'clickurl'  => 'pn_clickurl',
                                       'date'      => 'pn_date');
    $pntable['banners_column_def'] = array('bid'      => 'I AUTOINCREMENT PRIMARY',
                                           'cid'      => "I NOTNULL DEFAULT '0'",
                                           'type'     => "C(2) NOTNULL DEFAULT '0'",
                                           'imptotal' => "I NOTNULL DEFAULT '0'",
                                           'impmade'  => "I NOTNULL DEFAULT '0'",
                                           'clicks'   => "I NOTNULL DEFAULT '0'",
                                           'imageurl' => "C(255) NOTNULL DEFAULT ''",
                                           'clickurl' => "C(255) NOTNULL DEFAULT ''",
                                           'date'     => 'T DEFAULT NULL');
    // add standard data fields
    ObjectUtil::addStandardFieldsToTableDefinition ($pntable['banners_column'], 'pn_');
    ObjectUtil::addStandardFieldsToTableDataDefinition($pntable['banners_column_def']);


    // Advertising clients
    $pntable['bannersclient'] = DBUtil::getLimitedTablename('bannersclient');
    $pntable['bannersclient_column'] = array('cid'       => 'pn_cid',
                                             'name'      => 'pn_name',
                                             'contact'   => 'pn_contact',
                                             'email'     => 'pn_email',
                                             'login'     => 'pn_login',
                                             'passwd'    => 'pn_passwd',
                                             'extrainfo' => 'pn_extrainfo');
    $pntable['bannersclient_column_def'] = array('cid'       => 'I AUTOINCREMENT PRIMARY',
                                                 'name'      => 'C(60) NOTNULL',
                                                 'contact'   => 'C(60) NOTNULL',
                                                 'email'     => 'C(60) NOTNULL',
                                                 'login'     => 'C(10) NOTNULL',
                                                 'passwd'    => 'C(10) NOTNULL',
                                                 'extrainfo' => "X2 NOTNULL");
    // add standard data fields
    ObjectUtil::addStandardFieldsToTableDefinition ($pntable['bannersclient_column'], 'pn_');
    ObjectUtil::addStandardFieldsToTableDataDefinition($pntable['bannersclient_column_def']);

    // completed banners
    $pntable['bannersfinish'] = DBUtil::getLimitedTablename('bannersfinish');
    $pntable['bannersfinish_column'] = array('bid'         => 'pn_bid',
                                             'cid'         => 'pn_cid',
                                             'impressions' => 'pn_impressions',
                                             'clicks'      => 'pn_clicks',
                                             'datestart'   => 'pn_datestart',
                                             'dateend'     => 'pn_dateend');
    $pntable['bannersfinish_column_def'] = array('bid'         => 'I AUTOINCREMENT PRIMARY',
                                                 'cid'         => "I NOTNULL DEFAULT '0'",
                                                 'impressions' => "I NOTNULL DEFAULT '0'",
                                                 'clicks'      => "I NOTNULL DEFAULT '0'",
                                                 'datestart'   => 'T DEFAULT NULL',
                                                 'dateend'     => 'T DEFAULT NULL');
    // add standard data fields
    ObjectUtil::addStandardFieldsToTableDefinition ($pntable['bannersfinish_column'], 'pn_');
    ObjectUtil::addStandardFieldsToTableDataDefinition($pntable['bannersfinish_column_def']);

    // Return the table information
    return $pntable;

}
