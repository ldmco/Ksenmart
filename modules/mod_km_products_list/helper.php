<?php

/**
 *
 * $Id: helper.php 1.0.0 2013-04-17 09:04:59 Bereza Kirill $
 * @package	    Joomla! 
 * @subpackage	Список товаров

 * @verion     1.0.0
 * @description Отображает список товаров из компонента KsenMart
 * @copyright	  Copyright © 2013 - All rights reserved.
 * @license		  GNU General Public License v2.0
 * @author		  Bereza Kirill
 * @author mail	kirill.bereza@zebu.com
 * @website		  https://www.free-lance.ru/users/TakT0101/
 *
 * The module methods
 * -------------------------------
 * getItems()
 *
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Example Module Helper
 *
 * @package		  Joomla!
 * @subpackage	Список товаров
 * @since 		  1.0.0
 * @class       ModKsenmartbrandsHelper
 */

class ModKsenmartProductsListHelper {

    /**
     * Do something getItems method
     *
     * @param 	
     * @return
     */
    public static function getList($params) {

        $type = $params->get('type');
        $Itemid = JRequest::getVar('categories', null);

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('p.id');
        $query->from('#__ksenmart_products AS p');
        
        if($Itemid[0]){
            $query->leftjoin('#__ksenmart_products_categories AS c ON c.product_id=p.id');
            $query->where('c.category_id='.$Itemid[0]);
        }  
        $query->where('p.published = 1');
        
        if($type == 'hot'){
            $query->where('(p.hot = 1)');
        }
        if($type == 'new'){
            $query->where('(p.new = 1)');
        }
        if($type == 'recommendation'){
            $query->where('(p.recommendation = 1)');
        }
        if($type == 'promotion'){
            $query->where('(p.promotion = 1)');
        }
        
        $query->group('p.id');
        
        $db->setQuery($query, 0, $params->get('col'));
        $list = $db->loadObjectList();

        return $list;
    }

    public static function setOtherParams($products) {
        foreach ($products as &$product) {
            $product = KMProducts::getProduct($product->id);
        }
        return $products;
    }
}