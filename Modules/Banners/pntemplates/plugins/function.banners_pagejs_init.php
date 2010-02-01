<?php
/**
 * @package     Banners
 * @author      $Author: craigh $
 * @version     $Id: function.banners_pagejs_init.php 472 2010-01-06 01:34:38Z craigh $
 * @copyright   Copyright (c) 2010, Michael Halbrook, Halbrook Technologies
 * @license     http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */
/**
 * banners_pagejs_init: include the required javascript in header if needed
 *
 * @author Michael Halbrook. 
 * @param  none
 */
function smarty_function_pc_pagejs_init($params, &$smarty)
{
    unset($params);
    if (_SETTING_USE_POPUPS) {
        PageUtil::addVar("javascript", "modules/Banners/pnjavascript/protofade.1.2.js");
    }
    if (_SETTING_OPEN_NEW_WINDOW && !_SETTING_USE_POPUPS) {
        PageUtil::addVar("javascript", "modules/Banners/pnjavascript/effects.js");
    }
    return;
}
