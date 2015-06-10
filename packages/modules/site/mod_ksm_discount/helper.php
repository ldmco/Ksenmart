<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

class ModKMDiscountHelper {
	
	
	   /*
	    *   Возвращает конфигурацию скидок
		*   аргумент $discounts - массив текущих активных скидок
		*   возвращает $results - массив скидок с конфигурацией
		*/
       public static function getDiscounts($discounts, $params){
	        
			if(empty($discounts)) return array();
			$arr_params = $params->toArray();
			$types = isset($arr_params['types']) && is_array($arr_params['types']) && count($arr_params['types']) ? $arr_params['types'] : array('all');
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('d.id, d.title, d.content, d.from_date, d.to_date, d.info_methods');
			$query->from('#__ksenmart_discounts as d');
			$query->where("d.id IN (".  implode(',', $discounts)  .")");
			if (!in_array('all', $types)){
				foreach($types as &$type){
					$type = $db->quote($type);
				}
				$query->where('d.type in ('.implode(',', $types).')');
			}
			$query = KSMedia::setItemMainImageToQuery($query, 'discount', 'd.');
			$results = $db->setQuery($query)->loadObjectList('id');
			foreach($results as $key=>$res){
			    $info_methods = json_decode($res->info_methods, true);
				$results[$key]->image = !empty($results[$key]->filename) ? KSMedia::resizeImage($results[$key]->filename, $results[$key]->folder, $params->get('img_width', 200), $params->get('img_height', 100), json_decode($results[$key]->params, true)) : '';
				if(!in_array('module', $info_methods)) unset($results[$key]);
			}
			unset($res);
			
			return $results;
	   }
	   
}