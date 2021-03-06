<?php

/**
 * @package     Banners
 * @author      Michael Halbrook / Craig Heydenburg
 * @copyright   Copyright (c) 2010, Michael Halbrook, Halbrook Technologies
 * @license     http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */

/**
 * banners_protofadejs_init: include the required javascript in header if needed
 *
 * @param  string element the id of the html element (UL) containing the images (LI)
 * @param  array  banner  banners collected
 * @param  array  vars    the blockvars
 * @return null
 */
function smarty_function_banners_protofadejs_init($params, &$smarty)
{
    $element = $params['element'];
    $banner  = $params['banner'];
    $vars    = $params['vars'];
    unset($params);

    $delay = $banner['0']['__CATEGORIES__']['Main']['__ATTRIBUTES__']['time'];

    PageUtil::addVar("javascript", "modules/Banners/javascript/protofade.1.2.js");
    $pagescript = "
        <script type='text/javascript'>
        <!--//
        function StartUp() {
	        new Protofade('{$element}', {
                randomize:true,
                delay:{$delay},
                controls:{$vars['controls']},
                autostart:{$vars['autostart']},
                eSquare:{$vars['esquare']},
                eRows:{$vars['erows']},
                eCols:{$vars['ecols']},
                eColor:'{$vars['ecolor']}'
                });
        }
        document.observe ('dom:loaded', StartUp);
        //-->
        </script>";
    PageUtil::addVar("header", $pagescript);

    return;
}