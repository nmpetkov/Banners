<?php
/**
 * @package      Banners
 * @version      $Id:
 * @author       Halbrook Technologies
 * @link         http://www.halbrooktech.com
 * @copyright    Copyright (C) 2010
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */

/**
 * initialise block
 *
 * @author       Halbrook Technologies
 */
function Banners_bannersfadeblock_init()
{
	// Security
	SecurityUtil::registerPermissionSchema('Banners:faderblock:', 'Block title::');
}

/**
 * get information on block
 *
 * @author       Halbrook Technologies
 * @return       array       The block information
 */
function Banners_bannersfadeblock_info()
{
	$dom = ZLanguage::getModuleDomain('Banners');
	return array('text_type'       => 'Fader',
                 'module'          => 'Banners',
                 'text_type_long'  => __('Fading Banner Display', $dom),
                 'allow_multiple'  => true,
                 'form_content'    => false,
                 'form_refresh'    => false,
                 'show_preview'    => true,
                 'admin_tableless' => true);
}

/**
 * display block
 *
 * @author       Halbrook Technologies
 * @param        array       $blockinfo     a blockinfo structure
 * @return       output      the rendered bock
 */
function Banners_bannersfadeblock_display($blockinfo)
{
	if (!SecurityUtil::checkPermission('Banners:faderblock:', "$blockinfo[title]::", ACCESS_READ)) {
		return;
	}

	// Get variables from content block
	$vars = BlockUtil::varsFromContent($blockinfo['content']);

	// Defaults
	if (!isset($vars['btype'])) {
		$vars['btype'] = 3;
	}

	// Check if the Banners module is available.
	if (!ModUtil::available('Banners')) {
		return false;
	}

	// Create output object
	$render = Renderer::getInstance('Banners');

	// assign the banner
	$render->assign('banner', ModUtil::func('Banners', 'user', 'rotate', array('type' => $vars['btype'])));
	// Populate block info and pass to theme
	$blockinfo['content'] = $render->fetch('banners_block_fade.htm');

	return BlockUtil::themeBlock($blockinfo);
}

/**
 * modify block settings
 *
 * @author       Halbrook Technologies
 * @param        array       $blockinfo     a blockinfo structure
 * @return       output      the block form
 */
function Banners_bannersfadeblock_modify($blockinfo)
{
	// Get current content
	$vars = BlockUtil::varsFromContent($blockinfo['content']);

	// Defaults
	if (!isset($vars['btype'])) {
		$vars['btype'] = 3;
	}

	// Create output object
	$render = Renderer::getInstance('Banners', false);

	// assign the approriate values
	$render->assign($vars);

	// Return the output that has been generated by this function
	return $render->fetch('banners_block_fade_modify.htm');
}

/**
 * update block settings
 *
 * @author       Halbrook Technologies
 * @param        array       $blockinfo     a blockinfo structure
 * @return       $blockinfo  the modified blockinfo structure
 */
function Banners_bannersfadeblock_update($blockinfo)
{
	// Get current content
	$vars = BlockUtil::varsFromContent($blockinfo['content']);

	// alter the corresponding variable
	$vars['btype'] = FormUtil::getPassedValue('btype', null, 'POST');

	// write back the new contents
	$blockinfo['content'] = BlockUtil::varsToContent($vars);

	// clear the block cache
	$render = Renderer::getInstance('Bannersfade');
	$render->clear_cache('banners_block_fade.htm');

	return $blockinfo;
}
