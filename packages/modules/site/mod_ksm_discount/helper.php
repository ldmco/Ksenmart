<?php defined('_JEXEC') or die;
/*
 *   Хелпер модуля отображения скидок
 *   Содержит функцию, которая возвращает конфигурацию текущей скидки
 */
class ModKMDiscountHelper {
	
	
	   /*
	    *   Возвращает конфигурацию скидок
		*   аргумент $discounts - массив текущих активных скидок
		*   возвращает $results - массив скидок с конфигурацией
		*/
       public static function getDiscounts($discounts){
	        
			if(empty($discounts)) return array();
			
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('id, title, content, from_date, to_date, info_methods');
			$query->from('#__ksenmart_discounts');
			$query->where("id IN (".  implode(',', $discounts)  .")");
			$results = $db->setQuery($query)->loadObjectList('id');
			
			foreach($results as $key=>$res){
			    $info_methods = json_decode($res->info_methods, true);
				if(!in_array('module', $info_methods)) unset($results[$key]);
			}
			unset($res);
			
			return $results;
	   }
	   
}