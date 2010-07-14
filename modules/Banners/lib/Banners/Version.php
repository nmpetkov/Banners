<?php
/**
 * @package      Banners
 * @version      $Id:
 * @author       Halbrook Technologies
 * @link         http://www.halbrooktech.com
 * @copyright    Copyright (C) 2010
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */

class Banners_Version extends Zikula_Version
{
    public function getMetaData()
    {
        $meta = array();
        $meta['displayname']    = $this->__('Banners');
        $meta['url']            = $this->__(/*!used in URL - nospaces, no special chars, lcase*/'banners');
        $meta['description']    = $this->__('Banners Management');
        $meta['version']        = '3.0.0';

        $meta['securityschema'] = array(
            'Banners::' => 'Banner ID::',
            'Banners::Client' => 'Client ID::');
        $meta['core_min']       = '1.3.0'; // requires minimum 1.3.0 or later

        return $meta;
    }
}