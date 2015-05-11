<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

class ModuleKm_Shop_ReviewsHelper {

    /**
     * getData method
     * @param $params
     * @return array
     */
    static function getData($params) {
        $db = JFactory::getDbo();

        $query = $db->getQuery(true);
        $query->select('
            c.id,
            c.user_id AS user, 
            c.comment, 
            c.date_add,
            c.rate,  
            uf.filename AS logo, 
            u.name
        ');
        $query->from('#__ksenmart_comments AS c');
        $query->leftjoin('#__ksen_users AS kmu ON kmu.id=c.user_id');
        $query->leftjoin('#__users AS u ON kmu.id=u.id');
        $query->leftjoin('#__ksenmart_files AS uf ON uf.owner_id=u.id');
        $query->where("type='shop_review'");
        $query->where("published=1");
        $query->order('date_add DESC');

        $db->setQuery($query, 0, $params->get('count_review', 5));
        $reviews = $db->loadObjectList();
        $reviews = KSUsers::setAvatarLogoInObject($reviews);

        return $reviews;
    }
}