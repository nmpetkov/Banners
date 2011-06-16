<?php

/**
 * @package     Banners
 * @author      Craig Heydenburg
 * @copyright   Copyright (c) 2010, Craig Heydenburg, Sound Web Development
 * @license     http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */
class Banners_ContentType_Banner extends Content_AbstractContentType
{

    protected $hovertext;
    protected $categories;

    public function getTitle() {
        return $this->__('Banner');
    }

    public function getDescription() {
        return $this->__('Displays banner.');
    }

    public function loadData(&$data) {
        $this->hovertext = (bool) $data['hovertext'];
        // Get the registrered categories for the module
        $catregistry = CategoryRegistryUtil::getRegisteredModuleCategories('Banners', 'banners');
        $properties = array_keys($catregistry);
        $this->categories = array();
        foreach ($properties as $prop) {
            if (!empty($data['category__' . $prop])) {
                $this->categories[$prop] = $data['category__' . $prop];
            }
        }
        return;
    }

    public function display() {
        // generate a unique 4 digit id
        $rand_id = substr(microtime() * 1000000, -4);
        $this->view->assign('blockid', $rand_id);

        $this->view->assign('banner', ModUtil::func('Banners', 'user', 'display', array(
                    'blocktype' => array('Main' => $this->categories))));

        $this->view->assign('hovertext', $this->hovertext);

        return $this->view->fetch($this->getTemplate());
    }

    public function startEditing() {
        $catregistry = CategoryRegistryUtil::getRegisteredModuleCategories('Banners', 'banners');
        $this->view->assign('catregistry', $catregistry);

        return;
    }

    public function displayEditing() {
        return $this->__('Display banner');
    }

    public function getDefaultData() {
        return array(
            'hovertext' => false,
            'categories' => null);
    }

    public function getSearchableText() {
        return;
    }

}