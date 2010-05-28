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
 * @author       The Zikula Development Team
 */
function Banners_bannersrotateblock_init()
{
	// Security
	pnSecAddSchema('Bannersrotate::', 'Block title::');
}

/**
 * get information on block
 *
 * @author       The Zikula Development Team
 * @return       array       The block information
 */
function Banners_bannersrotateblock_info()
{
	return array('text_type'       => 'Rotating',
                 'module'          => 'Banners',
                 'text_type_long'  => 'Rotating Banner Display',
                 'allow_multiple'  => true,
                 'form_content'    => false,
                 'form_refresh'    => false,
                 'show_preview'    => true,
                 'admin_tableless' => true);
}

/**
 * display block
 *
 * @author       The Zikula Development Team
 * @param        array       $blockinfo     a blockinfo structure
 * @return       output      the rendered bock
 */
function Banners_bannersrotateblock_display($blockinfo)
{
	if (!SecurityUtil::checkPermission('Bannersrotate::', "$blockinfo[title]::", ACCESS_READ)) {
		return;
	}

	// Get variables from content block
	$vars = pnBlockVarsFromContent($blockinfo['content']);

	// Defaults
	if (!isset($vars['btype'])) {
		$vars['btype'] = 3;
	}
	if (!isset($vars['blocktemplate'])){
		$vars['blocktemplate']="banners_block_rotate.htm";
	}
	$blocktemplate = $vars['blocktemplate'];
	// Check if the Banners module is available.
	if (!pnModAvailable('Banners')) {
		return false;
	}

	// Create output object
	$render = Renderer::getInstance('Banners');
	$blocktemplate = $vars['blocktemplate'];
	// assign the banner
	$render->assign('banner', pnModFunc('Banners', 'user', 'rotate', array('type' => $vars['btype'])));
	$render->assign('banners', $banner);
	// Populate block info and pass to theme
	$blockinfo['content'] = $render->fetch($blocktemplate);

	return pnBlockThemeBlock($blockinfo);
}

/**
 * modify block settings
 *
 * @author       The Zikula Development Team
 * @param        array       $blockinfo     a blockinfo structure
 * @return       output      the block form
 */
function Banners_bannersrotateblock_modify($blockinfo)
{
	// Get current content
	$vars = pnBlockVarsFromContent($blockinfo['content']);

	// Defaults
	if (!isset($vars['btype'])) {
		$vars['btype'] = 3;
	}

	// Create output object
	$render = Renderer::getInstance('Banners', false);

	// assign the approriate values
	$render->assign($vars);

	// Return the output that has been generated by this function
	return $render->fetch('banners_block_rotate_modify.htm');
}

/**
 * update block settings
 *
 * @author       The Zikula Development Team
 * @param        array       $blockinfo     a blockinfo structure
 * @return       $blockinfo  the modified blockinfo structure
 */
function Banners_bannersrotateblock_update($blockinfo)
{
	// Get current content
	$vars = pnBlockVarsFromContent($blockinfo['content']);

	// alter the corresponding variable
	$vars['btype'] = FormUtil::getPassedValue('btype', null, 'POST');

	// write back the new contents
	$blockinfo['content'] = pnBlockVarsToContent($vars);

	// clear the block cache
	$render = Renderer::getInstance('Bannersrotate');
	$render->clear_cache('banners_block_fade.htm');

	return $blockinfo;
}
