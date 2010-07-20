<?php
/**
 * @package     Banners
 * @author      $Author: halbrooktech $
 * @version     $Id: function.banners_pagejs_init.php 472 2010-01-06 01:34:38Z halbrooktech $
 * @copyright   Copyright (c) 2010, Michael Halbrook, Halbrook Technologies
 * @license     http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */
/**
 * banners_pagejs_init: include the required javascript in header if needed
 *
 * @author Michael Halbrook.
 * @param  none
 */
function smarty_function_banners_fadejs_init ($params, &$smarty)
{
    $banner = $params['banner'];
    $divid  = $params['divid'];
	unset($params);
    
	PageUtil::addVar("javascript", "http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js");
	PageUtil::addVar("javascript", "modules/Banners/javascript/fadeslideshow.js");
    $license = "<!-- /***********************************************
        * Ultimate Fade In Slideshow v2.0- (c) Dynamic Drive DHTML code library (www.dynamicdrive.com)
        * This notice MUST stay intact for legal use
        * Visit Dynamic Drive at http://www.dynamicdrive.com/ for this script and 100s more
        ***********************************************/ -->";
  	PageUtil::addVar("rawtext", $license);
    $imageheight = $banner[0]['__CATEGORIES__']['Main']['__ATTRIBUTES__']['width'];
    $imagelength = $banner[0]['__CATEGORIES__']['Main']['__ATTRIBUTES__']['length'];

    $infoarray = array();
    foreach ($banner as $bannerinfo) {
        $infoarray[] = "['" . $bannerinfo['imageurl'] . "', '" . $bannerinfo['clickurl'] . "', '', '']";
    }
    $imagestring = implode(', ', $infoarray);
    $pagescript = "
        <script type='text/javascript'>
        <!--//
        var mygallery=new fadeSlideShow({
            wrapperid: '{$divid}', //ID of blank DIV on page to house Slideshow
            dimensions: [{$imagelength}, {$imageheight}], //width/height of gallery in pixels. Should reflect dimensions of largest image
            imagearray: [{$imagestring}],
            displaymode: {type:'auto', pause:5000, cycles:5, wraparound:false,randomize:true},
            persist: false, //remember last viewed slide and recall within same session?
            fadeduration: 1000, //transition duration (milliseconds)
            descreveal: 'ondemand',
            togglerid: ''
        })
        //-->
        </script>";
    PageUtil::addVar("rawtext", $pagescript);

	return;
}