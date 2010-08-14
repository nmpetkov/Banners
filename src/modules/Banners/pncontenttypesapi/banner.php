<?php
/**
 * @package     Banners
 * @author      Craig Heydenburg
 * @copyright   Copyright (c) 2010, Craig Heydenburg, Sound Web Development
 * @license     http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */

class banners_contenttypesapi_bannerPlugin extends contentTypeBase
{
    var $hovertext;
    var $categories;

    function getModule() {
        return 'Banners';
    }
    function getName() {
        return 'banner';
    }
    function getTitle() {
        $dom = ZLanguage::getModuleDomain('Banners');
        return __('Banner', $dom);
    }
    function getDescription() {
        $dom = ZLanguage::getModuleDomain('Banners');
        return __('Displays banner.', $dom);
    }

    function loadData(&$data) {
        $this->hovertext = (bool) $data['hovertext'];
        // Get the registrered categories for the module
        $catregistry = CategoryRegistryUtil::getRegisteredModuleCategories ('Banners', 'banners');
        $properties = array_keys($catregistry);
        $this->categories = array();
        foreach($properties as $prop) {
            if (!empty($data['category__'.$prop])) {
                $this->categories[$prop] = $data['category__'.$prop];
            }
        }
        return;
    }

    function display() {
        $view = Zikula_View::getInstance('Banners');

        // generate a unique 4 digit id
        $rand_id = substr(microtime() * 1000000, -4);
        $view->assign('blockid', $rand_id);

        $view->assign('banner', ModUtil::func('Banners', 'user', 'display', array(
            'blocktype' => array('Main' => $this->categories))));
        
        $view->assign('hovertext', $this->hovertext);
    
        return $view->fetch('contenttype/banner_view.html');
    }

    function startEditing(&$view) {
        $dom = ZLanguage::getModuleDomain('Banners');

        $catregistry  = CategoryRegistryUtil::getRegisteredModuleCategories('Banners', 'banners');
        $view->assign('catregistry', $catregistry);
        
        return;
    }

    function displayEditing() {
        $dom = ZLanguage::getModuleDomain('Banners');
        return __('Display banner', $dom);
    }

    function getDefaultData() {
        return array(
            'hovertext'  => false,
            'categories' => null);
    }

    function getSearchableText() {
        return;
    }

}

function banners_contenttypesapi_banner($args) {
    return new banners_contenttypesapi_bannerPlugin($args['data']);
}