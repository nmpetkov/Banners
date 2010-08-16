<?php

/**
 * @package      Banners
 * @author       Halbrook Technologies
 * @author       Craig Heydenburg
 * @link         http://www.halbrooktech.com
 * @copyright    Copyright (C) 2010
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */
class Banners_Block_Banners extends Zikula_Block
{

    /**
     * initialise block
     *
     */
    public function init()
    {
        // Security
        SecurityUtil::registerPermissionSchema('Bannersblock::', 'Block title::');
    }

    /**
     * get information on block
     *
     * @author       The Zikula Development Team
     * @return       array       The block information
     */
    public function info()
    {
        return array('text_type'       => $this->__('banners'),
                     'module'          => 'Banners',
                     'text_type_long'  => $this->__('Random Banner Display (single image)'),
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
        if (!SecurityUtil::checkPermission('Bannersblock::', "$blockinfo[title]::", ACCESS_READ)) {
            return;
        }

        // Get variables from content block
        $vars = BlockUtil::varsFromContent($blockinfo['content']);

        // Check if the Banners module is available.
        if (!ModUtil::available('Banners')) {
            return false;
        }
        if (empty($vars['blocktype'])) {
            return false;
        }

        // assign the banner
        $this->view->assign('blockid', $blockinfo['bid']);
        $this->view->assign('banner', ModUtil::func('Banners', 'user', 'display', $vars));
        $this->view->assign('hovertext', $vars['hovertext']);

        // Populate block info and pass to theme
        $blockinfo['content'] = $this->view->fetch('blocks/banners.tpl');

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
        if (empty($vars['blocktype'])) {
            $vars['blocktype'] = array();
        }
        if (empty($vars['hovertext'])) {
            $vars['hovertext'] = 0;
        }

        // load the category registry util
        $catregistry = CategoryRegistryUtil::getRegisteredModuleCategories('Banners', 'banners');
        $this->view->assign('catregistry', $catregistry);

        // assign the approriate values
        $this->view->assign('vars', $vars);

        // Return the output that has been generated by this function
        return $this->view->fetch('blocks/banners_modify.tpl');
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
        $vars['blocktype'] = FormUtil::getPassedValue('blocktype', null, 'POST');
        $vars['hovertext'] = FormUtil::getPassedValue('hovertext', '', 'POST');

        // write back the new contents
        $blockinfo['content'] = BlockUtil::varsToContent($vars);

        $this->view->clear_cache('blocks/banners.tpl');

        return $blockinfo;
    }

}