<?php
/**
 /**
 * @package      Banners
 * @version      $Id:
 * @author       Halbrook Technologies
 * @link         http://www.halbrooktech.com
 * @copyright    Copyright (C) 2010
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */

/**
 * This function is called internally by the core whenever the module is
 * loaded.  It adds in the information
 */
function Banners_tables() {
    // Initialise table array
    $table = array();

    // Set the Table Name
    $table['banners'] = DBUtil::getLimitedTablename('banners');

    // Set the column names.

    $table['banners_column'] = array(
            'bid'       => 'bid',
            'cid'       => 'cid',
            'type'      => 'type',
            'title'     => 'title',
            'imptotal'  => 'imptotal',
            'impmade'   => 'impmade',
            'clicks'    => 'clicks',
            'imageurl'  => 'imageurl',
            'clickurl'  => 'clickurl',
            'date'      => 'date');

    $table['banners_column_def'] = array(
            'bid'       => 'I PRIMARY AUTO',
            'cid'       => "I DEFAULT '0'",
            'type'      => "C(255) DEFAULT '0'",
            'title'     => "C(255) DEFAULT ''",
            'imptotal'	=> "I DEFAULT '0'",
            'impmade'	=> "I DEFAULT '0'",
            'clicks'	=> "I DEFAULT '0'",
            'imageurl'	=> "C(255) DEFAULT ''",
            'clickurl'	=> "C(255) DEFAULT ''",
            'date'      => 'T DEFAULT NULL');
    // add standard data fields
    ObjectUtil::addStandardFieldsToTableDefinition($table['banners_column'], '');
    ObjectUtil::addStandardFieldsToTableDataDefinition($table['banners_column_def']);
    


    // Advertising clients
    $table['bannersclient'] = DBUtil::getLimitedTablename('bannersclient');
    $table['bannersclient_column'] = array(
            'cid'       => 'cid',
            'name'      => 'name',
            'contact'   => 'contact',
            'email'     => 'email',
            'login'     => 'login',
            'passwd'    => 'passwd',
            'extrainfo' => 'extrainfo');
    $table['bannersclient_column_def'] = array(
            'cid'       => 'I AUTOINCREMENT PRIMARY',
            'name'      => 'C(60) NOTNULL',
            'contact'   => 'C(60) NOTNULL',
            'email'     => 'C(60) NOTNULL',
            'login'     => 'C(10) NOTNULL',
            'passwd'    => 'C(10) NOTNULL',
            'extrainfo' => "X2 NOTNULL");
    // add standard data fields
    ObjectUtil::addStandardFieldsToTableDefinition($table['bannersclient_column'], '');
    ObjectUtil::addStandardFieldsToTableDataDefinition($table['bannersclient_column_def']);

    // completed banners
    $table['bannersfinish'] = DBUtil::getLimitedTablename('bannersfinish');
    $table['bannersfinish_column'] = array(
            'bid'         => 'bid',
            'cid'         => 'cid',
            'impressions' => 'impressions',
            'clicks'      => 'clicks',
            'datestart'   => 'datestart',
            'dateend'     => 'dateend');
    $table['bannersfinish_column_def'] = array(
            'bid'         => 'I AUTOINCREMENT PRIMARY',
            'cid'         => "I NOTNULL DEFAULT '0'",
            'impressions' => "I NOTNULL DEFAULT '0'",
            'clicks'      => "I NOTNULL DEFAULT '0'",
            'datestart'   => 'T DEFAULT NULL',
            'dateend'     => 'T DEFAULT NULL');
    // add standard data fields
    ObjectUtil::addStandardFieldsToTableDefinition($table['bannersfinish_column'], '');
    ObjectUtil::addStandardFieldsToTableDataDefinition($table['bannersfinish_column_def']);

    // Return the table information
    return $table;
}