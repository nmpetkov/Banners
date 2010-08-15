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
 *
 * please note the 'pn_' prefix, while undesired, is required because old versions
 * of the module contained it and therefore in order to upgrade, the prefix must
 * remain intact.
 */
function Banners_tables() {
    // Initialise table array
    $table = array();

    // Set the Table Name
    $table['banners'] = DBUtil::getLimitedTablename('banners');

    // Set the column names.

    $table['banners_column'] = array(
            'bid'       => 'pn_bid',
            'cid'       => 'pn_cid',
            'type'      => 'pn_type',                  // becomes obsolete in v3.0.0
            'title'     => 'pn_title',                 // added in vers. 3.0.0
            'imptotal'  => 'pn_imptotal',
            'impmade'   => 'pn_impmade',
            'clicks'    => 'pn_clicks',
            'imageurl'  => 'pn_imageurl',
            'clickurl'  => 'pn_clickurl',
            'date'      => 'pn_date',
            'hovertext' => 'pn_hovertext',            // added in vers. 3.0.0
            'active'    => 'pn_active');              // added in vers. 3.0.0

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
            'active'    => "I DEFAULT '1'");
    // add standard data fields
    ObjectUtil::addStandardFieldsToTableDefinition($table['banners_column'], 'pn_');
    ObjectUtil::addStandardFieldsToTableDataDefinition($table['banners_column_def']);

    $table['banners_db_extra_enable_categorization'] = true;
    $table['banners_primary_key_column'] = 'bid';
    
    // Advertising clients
    $table['bannersclient'] = DBUtil::getLimitedTablename('bannersclient');
    $table['bannersclient_column'] = array(
            'cid'       => 'pn_cid',
            'name'      => 'pn_name',
            'contact'   => 'pn_contact',
            'email'     => 'pn_email',                 // becomes obsolete in v3.0.0
            'login'     => 'pn_login',                 // becomes obsolete in v3.0.0
            'passwd'    => 'pn_passwd',                // becomes obsolete in v3.0.0
            'extrainfo' => 'pn_extrainfo',
            'uid'       => 'pn_uid');                  // added in vers. 3.0.0
    $table['bannersclient_column_def'] = array(
            'cid'       => 'I AUTOINCREMENT PRIMARY',
            'name'      => 'C(60) NOTNULL',
            'contact'   => 'C(60) NOTNULL',
            'email'     => 'C(60) NOTNULL',
            'login'     => 'C(10) NOTNULL',
            'passwd'    => 'C(10) NOTNULL',
            'extrainfo' => "X2 NOTNULL",
            'uid'       => "I DEFAULT '0'");
    // add standard data fields
    ObjectUtil::addStandardFieldsToTableDefinition($table['bannersclient_column'], 'pn_');
    ObjectUtil::addStandardFieldsToTableDataDefinition($table['bannersclient_column_def']);

    // old tables for upgrade/renaming purposes
    $table['bannersfinish'] = DBUtil::getLimitedTablename('bannersfinish');

    // Return the table information
    return $table;
}