<?php
/**
 * @package      Banners
 * @version      $Id:
 * @author       Halbrook Technologies
 * @author       Craig Heydenburg
 * @link         http://www.halbrooktech.com
 * @copyright    Copyright (C) 2010
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */
class Banners_Block_Bannersfade extends Zikula_Block
{
    /**
     * initialise block
     *
     */
    public function init()
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
    public function info()
    {
        return array('text_type'       => $this->__('Fader'),
                     'module'          => 'Banners',
                     'text_type_long'  => $this->__('Fading Banner Display'),
                     'allow_multiple'  => true,
                     'form_content'    => false,
                     'form_refresh'    => false,
                     'show_preview'    => true,
                     'admin_tableless' => true);
    }

    /**
     * display block
     *
     * @param        array       $blockinfo     a blockinfo structure
     * @return       output      the rendered bock
     */
    public function display($blockinfo)
    {
        if (!SecurityUtil::checkPermission('Banners:faderblock:', "$blockinfo[title]::", ACCESS_READ)) {
            return;
        }

        // Get variables from content block
        $vars = BlockUtil::varsFromContent($blockinfo['content']);

        // Check if the Banners module is available.
        if (!ModUtil::available('Banners')) {
            return false;
        }
        $banner = ModUtil::func('Banners', 'user', 'rotate', array('type' => $vars['type']));
        // assign the banner
        $this->view->assign('banner', $banner);
        // Populate block info and pass to theme
        $blockinfo['content'] = $this->view->fetch('blocks/fade.tpl');

        return BlockUtil::themeBlock($blockinfo);
    }

    /**
     * modify block settings
     *
     * @param        array       $blockinfo     a blockinfo structure
     * @return       output      the block form
     */
    public function modify($blockinfo)
    {
        // Get current content
        $vars = BlockUtil::varsFromContent($blockinfo['content']);

        // Defaults
        if (empty($vars['type'])) $vars['type'] = array();

        // load the category registry util
        $catregistry = CategoryRegistryUtil::getRegisteredModuleCategories('Banners', 'banners');
        $this->view->assign('catregistry', $catregistry);

        // assign the approriate values
        $this->view->assign('vars', $vars);

        // Return the output that has been generated by this function
        return $this->view->fetch('blocks/fade_modify.tpl');
    }

    /**
     * update block settings
     *
     * @param        array       $blockinfo     a blockinfo structure
     * @return       $blockinfo  the modified blockinfo structure
     */
    public function update($blockinfo)
    {
        // Get current content
        $vars = BlockUtil::varsFromContent($blockinfo['content']);

        // alter the corresponding variable
        $vars['type'] = FormUtil::getPassedValue('type', null, 'POST');

        // write back the new contents
        $blockinfo['content'] = BlockUtil::varsToContent($vars);

        $this->view->clear_cache('blocks/fade.tpl');

        return $blockinfo;
    }
}