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
function smarty_function_banners_pagejs_init($params, &$smarty)
{
	unset($params);
	PageUtil::addVar("javascript", "modules/Banners/pnjavascript/protofade.1.2.js");
	PageUtil::addVar("javascript", "modules/Banners/pnjavascript/fadeslideshow.js");
	PageUtil::addVar("javascript", "http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js");

	return;
}
