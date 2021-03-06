<?php
/**
 /**
 * @package      Banners
 * @author       Michael Halbrook / Craig Heydenburg
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
    $table['banners'] = 'banners';

    // Set the column names.
    $table['banners_column'] = array(
            'bid'       => 'bid',
            'cid'       => 'cid',
            'type'      => 'type',                  // becomes obsolete in v3.0.0
            'title'     => 'title',                 // added in vers. 3.0.0
            'imptotal'  => 'imptotal',
            'impmade'   => 'impmade',
            'clicks'    => 'clicks',
            'imageurl'  => 'imageurl',
            'clickurl'  => 'clickurl',
            'date'      => 'ddate',                // not a typo! `date` is a reserved sql word
            'hovertext' => 'hovertext',            // added in vers. 3.0.0
            'active'    => 'active',               // added in vers. 3.0.0
            'enddate'   => 'enddate');              

    $table['banners_column_def'] = array(
            'bid'       => 'I PRIMARY AUTO',
            'cid'       => "I DEFAULT '0'",
            'type'      => "C(2) NOTNULL DEFAULT '0'",
            'title'     => "C(255) DEFAULT ''",
            'imptotal'	=> "I DEFAULT '0'",
            'impmade'	=> "I DEFAULT '0'",
            'clicks'	=> "I DEFAULT '0'",
            'imageurl'	=> "C(255) DEFAULT ''",
            'clickurl'	=> "C(255) DEFAULT ''",
            'date'      => 'T DEFAULT NULL',
            'hovertext' => "C(255) DEFAULT ''",
            'active'    => "I DEFAULT '1'",
            'enddate'   => "D DEFAULT NULL");
    // add standard data fields
    ObjectUtil::addStandardFieldsToTableDefinition($table['banners_column']);
    ObjectUtil::addStandardFieldsToTableDataDefinition($table['banners_column_def']);

    $table['banners_db_extra_enable_categorization'] = true;
    $table['banners_primary_key_column'] = 'bid';
    
    // Advertising clients
    $table['bannersclient'] = 'bannersclient';
    $table['bannersclient_column'] = array(
            'cid'       => 'cid',
            'name'      => 'name',
            'contact'   => 'contact',
            'email'     => 'email',                 // becomes obsolete in v3.0.0
            'login'     => 'login',                 // becomes obsolete in v3.0.0
            'passwd'    => 'passwd',                // becomes obsolete in v3.0.0
            'extrainfo' => 'extrainfo',
            'uid'       => 'uid');                  // added in vers. 3.0.0
            
    $table['bannersclient_column_def'] = array(
            'cid'       => 'I AUTOINCREMENT PRIMARY',
            'name'      => 'C(60) NOTNULL',
            'contact'   => 'C(60) NOTNULL',
            'email'     => 'C(60) NOTNULL',
            'login'     => 'C(10) NOTNULL',
            'passwd'    => 'C(10) NOTNULL',
            'extrainfo' => "XL NOTNULL",
            'uid'       => "I DEFAULT '0'");
    // add standard data fields
    ObjectUtil::addStandardFieldsToTableDefinition($table['bannersclient_column']);
    ObjectUtil::addStandardFieldsToTableDataDefinition($table['bannersclient_column_def']);

    // old tables for upgrade/renaming purposes
    $table['bannersfinish'] = DBUtil::getLimitedTablename('bannersfinish');

    // Return the table information
    return $table;
}