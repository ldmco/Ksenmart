<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

class ModKSMShippingHelper {
    public static function getShippings() {
        $db     = JFactory::getDBO();
        $query  = $db->getQuery(true);
        $query
            ->select('
                s.id,
                s.title,
                s.type,
                s.regions,
                s.days,
                s.params,
                s.ordering
            ')
            ->from('#__ksenmart_shippings AS s')
            ->where('s.published=1')
            ->order('s.ordering')
        ;
        $db->setQuery($query);
        return $db->loadObjectList();
    }
    
    public static function getUserRegionIdByTitle($region_name){
        if(!empty($region_name)){
            $db     = JFactory::getDBO();
            $query  = $db->getQuery(true);
            $query
                ->select('
                    r.id AS user_region
                ')
                ->from('#__ksenmart_regions AS r')
                ->where('r.title='.$db->Quote($region_name))
            ;
    
            $db->setQuery($query, 0, 1);
            $region = $db->loadObject();
            if($region){
                return $region->user_region;
            }
        }
        return null;
    }
}