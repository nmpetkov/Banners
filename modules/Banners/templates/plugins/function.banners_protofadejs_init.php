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
 * @param  element the id of the html element (UL) containing the images (LI)
 */
function smarty_function_banners_protofadejs_init ($params, &$smarty)
{
    $element  = $params['element'];
	unset($params);

    // protofade: http://cssrevolt.com/upload/files/protofade/
    // protofade license: MIT: http://www.opensource.org/licenses/mit-license.php
    
    PageUtil::addVar("javascript", "modules/Banners/javascript/protofade.1.2.js");
    $pagescript = "
        <script type='text/javascript'>
        <!--//
        function StartUp() {
	        new Protofade('{$element}', { randomize:true, delay:2.0 });
        }
        document.observe ('dom:loaded', StartUp);
        //-->
        </script>";
    PageUtil::addVar("rawtext", $pagescript);

	return;
}