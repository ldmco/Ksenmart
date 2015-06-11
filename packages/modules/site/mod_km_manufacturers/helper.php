<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

class modKsenmartManufacturersHelper {
    
    function getManufacturers($group) {
        $db = JFactory::getDBO();
        $session_manufacturers = JRequest::getVar('manufacturers', array());
        JArrayHelper::toInteger($session_manufacturers);
        $query = $db->getQuery(true);
        $query
        	->select('
        		km.*,
        		kf.filename,
        		kf.folder,
        		kf.params
        	')
        	->from('#__ksenmart_manufacturers as km')
        	->leftjoin("#__ksenmart_countries as kc on km.country=kc.id")
        	->leftjoin("#__ksenmart_files as kf on kc.id=kf.owner_id and kf.owner_type='country'")
        	->where('km.published=1')
        	->order('km.title')
        ;

        if($group){
            $query
                ->select('
                    kc.id AS country_id,
                    kc.title AS country_title
                ')
            ;
        }else{
            $query->group('km.id');
        }

        $db->setQuery($query);
        $manufacturers = $db->loadObjectList('id');
        
        $tmpManufacturers = array();
        foreach ($manufacturers as &$manufacturer) {
            $manufacturer->selected = in_array($manufacturer->id, $session_manufacturers) ? true : false;
            if (!empty($manufacturer->folder)) {
                $manufacturer->small_img = KSMedia::resizeImage($manufacturer->filename, $manufacturer->folder, 25, 30, json_decode($manufacturer->params, true));
            }
            unset($manufacturer->filename);
            unset($manufacturer->folder);
            $manufacturer->link = JRoute::_('index.php?option=com_ksenmart&view=catalog&manufacturers[]=' . $manufacturer->id . '&Itemid=' . KSSystem::getShopItemid());
            if($group){
                $tmpManufacturers[$manufacturer->country_title][] = $manufacturer;
            }
        }
        if(!$tmpManufacturers){
            $tmpManufacturers = $manufacturers;
        }
        
        return $tmpManufacturers;
    }
}