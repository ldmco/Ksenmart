<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

class modKMProductsListHelper 
{

    public static function getList($params) 
	{
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
			->select('SQL_CALC_FOUND_ROWS  p.id')
			->from('#__ksenmart_products AS p')
			->where('p.published = 1')
			->where('p.parent_id = 0')
			->order('p.ordering ASC')
			->group('p.id')
		;        
		
        if ($type == 'hot') {
            $query->where('(p.hot = 1)');
        }
        if ($type == 'new') {
            $query->where('(p.new = 1)');
        }
        if ($type == 'recommendation') {
            $query->where('(p.recommendation = 1)');
        }
        if ($type == 'promotion') {
            $query->where('(p.promotion = 1)');
        }
        
        $db->setQuery($query, 0, $params->get('col', 10));
        $products = $db->loadColumn();
        
        foreach($products as &$product) 
		{
            $product = KSMProducts::getProduct($product);
        }
        
        return $products;
    }
    
}