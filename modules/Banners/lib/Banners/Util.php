<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Banners_Util
{
    /**
     * Create Banner Type Category Entries
     *
     * @author Craig Heydenburg
     * @return boolean
     */
    public static function createCategories()
    {
        $dom = ZLanguage::getModuleDomain('Banners');
        $categories = array();
//        example array
//        $categories[] = array(
//            'rootpath'    => '',     // Path to create within
//            'name'        => '',     // hard string
//            'value'       => null,   // value (unneeded)
//            'displayname' => '',     // translated string
//            'description' => '',     // translated string
//            'attributes'  => array(  // array ('key' hard string => 'value' hard string/int)
//                'length' => 0,       // graphic length in pixels (int)
//                'width'  => 0,       // graphic width in pixels (int)
//                'time'   => 0        // time in seconds for display (int)
//                )
//        );
        // root category
        $categories[] = array(
            'rootpath'    => '/__SYSTEM__/General',
            'name'        => 'IAB_Ad_Units',
            'value'       => null,
            'displayname' => __('IAB Ad Units', $dom),
            'description' => __('Dimensions of IAB Advertising Banners', $dom),
            'attributes'  => null
        );
        // sub-categories
        // data taken from http://www.iab.net/iab_products_and_industry_services/1421/1443/1452
        $categories[] = array(
            'rootpath'    => '/__SYSTEM__/General/IAB_Ad_Units',
            'name'        => 'MediumRectangle',
            'value'       => null,
            'displayname' => __('Medium Rectangle', $dom),
            'description' => __('Medium Rectangle 300x250 IMU', $dom),
            'attributes'  => array(
                'length' => 300,
                'width'  => 250,
                'time'   => 15
                )
        );
        $categories[] = array(
            'rootpath'    => '/__SYSTEM__/General/IAB_Ad_Units',
            'name'        => 'SquarePopUp',
            'value'       => null,
            'displayname' => __('Square Pop-up', $dom),
            'description' => __('Square Pop-up 250x250 IMU', $dom),
            'attributes'  => array(
                'length' => 250,
                'width'  => 250,
                'time'   => 15
                )
        );
        $categories[] = array(
            'rootpath'    => '/__SYSTEM__/General/IAB_Ad_Units',
            'name'        => 'VerticalRectangle',
            'value'       => null,
            'displayname' => __('Vertical Rectangle', $dom),
            'description' => __('Vertical Rectangle 240x400 IMU', $dom),
            'attributes'  => array(
                'length' => 240,
                'width'  => 400,
                'time'   => 15
                )
        );
        $categories[] = array(
            'rootpath'    => '/__SYSTEM__/General/IAB_Ad_Units',
            'name'        => 'LargeRectangle',
            'value'       => null,
            'displayname' => __('Large Rectangle', $dom),
            'description' => __('Large Rectangle 336x280 IMU', $dom),
            'attributes'  => array(
                'length' => 336,
                'width'  => 280,
                'time'   => 15
                )
        );
        $categories[] = array(
            'rootpath'    => '/__SYSTEM__/General/IAB_Ad_Units',
            'name'        => 'Rectangle',
            'value'       => null,
            'displayname' => __('Rectangle', $dom),
            'description' => __('Rectangle 180x150 IMU', $dom),
            'attributes'  => array(
                'length' => 180,
                'width'  => 150,
                'time'   => 15
                )
        );
        $categories[] = array(
            'rootpath'    => '/__SYSTEM__/General/IAB_Ad_Units',
            'name'        => '31Rectangle',
            'value'       => null,
            'displayname' => __('3:1 Rectangle', $dom),
            'description' => __('3:1 Rectangle 300x100 IMU', $dom),
            'attributes'  => array(
                'length' => 300,
                'width'  => 100,
                'time'   => 15
                )
        );
        $categories[] = array(
            'rootpath'    => '/__SYSTEM__/General/IAB_Ad_Units',
            'name'        => 'PopUnder',
            'value'       => null,
            'displayname' => __('Pop-Under', $dom),
            'description' => __('Pop-Under 720x300 IMU', $dom),
            'attributes'  => array(
                'length' => 720,
                'width'  => 300,
                'time'   => 15
                )
        );
        $categories[] = array(
            'rootpath'    => '/__SYSTEM__/General/IAB_Ad_Units',
            'name'        => 'FullBanner',
            'value'       => null,
            'displayname' => __('Full Banner', $dom),
            'description' => __('Full Banner 468x60 IMU', $dom),
            'attributes'  => array(
                'length' => 468,
                'width'  => 60,
                'time'   => 15
                )
        );
        $categories[] = array(
            'rootpath'    => '/__SYSTEM__/General/IAB_Ad_Units',
            'name'        => 'HalfBanner',
            'value'       => null,
            'displayname' => __('Half Banner', $dom),
            'description' => __('Half Banner 234x60 IMU', $dom),
            'attributes'  => array(
                'length' => 234,
                'width'  => 60,
                'time'   => 15
                )
        );
        $categories[] = array(
            'rootpath'    => '/__SYSTEM__/General/IAB_Ad_Units',
            'name'        => 'MicroBar',
            'value'       => null,
            'displayname' => __('Micro Bar', $dom),
            'description' => __('Micro Bar 88x31 IMU', $dom),
            'attributes'  => array(
                'length' => 88,
                'width'  => 31,
                'time'   => 15
                )
        );
        $categories[] = array(
            'rootpath'    => '/__SYSTEM__/General/IAB_Ad_Units',
            'name'        => 'Button1',
            'value'       => null,
            'displayname' => __('Button 1', $dom),
            'description' => __('Button 1 120x90 IMU', $dom),
            'attributes'  => array(
                'length' => 120,
                'width'  => 90,
                'time'   => 15
                )
        );
        $categories[] = array(
            'rootpath'    => '/__SYSTEM__/General/IAB_Ad_Units',
            'name'        => 'Button 2',
            'value'       => null,
            'displayname' => __('Button 2', $dom),
            'description' => __('Button 2 120x60 IMU', $dom),
            'attributes'  => array(
                'length' => 120,
                'width'  => 60,
                'time'   => 15
                )
        );
        $categories[] = array(
            'rootpath'    => '/__SYSTEM__/General/IAB_Ad_Units',
            'name'        => 'VerticalBanner',
            'value'       => null,
            'displayname' => __('Vertical Banner', $dom),
            'description' => __('Vertical Banner 120x240 IMU', $dom),
            'attributes'  => array(
                'length' => 120,
                'width'  => 240,
                'time'   => 15
                )
        );
        $categories[] = array(
            'rootpath'    => '/__SYSTEM__/General/IAB_Ad_Units',
            'name'        => 'SquareButton',
            'value'       => null,
            'displayname' => __('Square Button', $dom),
            'description' => __('Square Button 125x125 IMU', $dom),
            'attributes'  => array(
                'length' => 125,
                'width'  => 125,
                'time'   => 15
                )
        );
        $categories[] = array(
            'rootpath'    => '/__SYSTEM__/General/IAB_Ad_Units',
            'name'        => 'Leaderboard',
            'value'       => null,
            'displayname' => __('Leaderboard', $dom),
            'description' => __('Leaderboard 728x90 IMU', $dom),
            'attributes'  => array(
                'length' => 728,
                'width'  => 90,
                'time'   => 15
                )
        );
        $categories[] = array(
            'rootpath'    => '/__SYSTEM__/General/IAB_Ad_Units',
            'name'        => 'WideSkyscraper',
            'value'       => null,
            'displayname' => __('Wide Skyscraper', $dom),
            'description' => __('Wide Skyscraper 160x600 IMU', $dom),
            'attributes'  => array(
                'length' => 160,
                'width'  => 600,
                'time'   => 15
                )
        );
        $categories[] = array(
            'rootpath'    => '/__SYSTEM__/General/IAB_Ad_Units',
            'name'        => 'Skyscraper',
            'value'       => null,
            'displayname' => __('Skyscraper', $dom),
            'description' => __('Skyscraper 120x600 IMU', $dom),
            'attributes'  => array(
                'length' => 120,
                'width'  => 600,
                'time'   => 15
                )
        );
        $categories[] = array(
            'rootpath'    => '/__SYSTEM__/General/IAB_Ad_Units',
            'name'        => 'HalfPageAd',
            'value'       => null,
            'displayname' => __('Half Page Ad', $dom),
            'description' => __('Half Page Ad 300x600 IMU', $dom),
            'attributes'  => array(
                'length' => 300,
                'width'  => 600,
                'time'   => 15
                )
        );

        // enter categories into category table
        $catresults = array();
        foreach ($categories as $cat) {
            $catresults[$cat['name']] = CategoryUtil::createCategory(
                    $cat['rootpath'],
                    $cat['name'],
                    $cat['value'],
                    $cat['displayname'],
                    $cat['description'],
                    $cat['attributes']);
        }
        // bind registry Entry for Banners
        $catresults['reg_bind'] = CategoryRegistryUtil::insertEntry ('Banners', 'banners', 'Main', $catresults['IAB_Ad_Units']);
    
        return $catresults;
    }
} // end class def