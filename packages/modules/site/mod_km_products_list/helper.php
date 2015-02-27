<?php
/**
 *
 * $Id: helper.php 1.0.0 2013-04-17 09:04:59 Bereza Kirill $
 * @package     Joomla!
 * @subpackage  Список товаров
 * @verion     1.0.0
 * @description Отображает список товаров из компонента KsenMart
 * @copyright     Copyright © 2013 - All rights reserved.
 * @license       GNU General Public License v2.0
 * @author        Bereza Kirill
 * @author mail kirill.bereza@zebu.com
 * @website       https://www.free-lance.ru/users/TakT0101/
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
 * @package       Joomla!
 * @subpackage  Список товаров
 * @since         1.0.0
 * @class       ModKsenmartbrandsHelper
 */

class ModKsenmartProductsListHelper {

    public static $pagination = null;
    /**
     * Do something getItems method
     *
     * @param
     * @return
     */
    public static function getList($params) {
        
        $Itemid = JRequest::getVar('categories', null);
        $db     = JFactory::getDbo();
        $query  = $db->getQuery(true);
        $type   = $params->get('type');
        
        $query->select('SQL_CALC_FOUND_ROWS ' . $db->qn('p.id'));
        $query->from($db->qn('#__ksenmart_products', 'p'));
        
        if ($Itemid[0]) {
            $query->leftjoin($db->qn('#__ksenmart_products_categories', 'c') . 'ON ' . $db->qn('c.product_id') . '=' . $db->qn('p.id'));
            $query->where($db->qn('c.category_id') . '=' . $db->q($Itemid[0]));
        }

        $query->where($db->qn('p.published') . '=1');
        $query->where('(' . $db->qn('p.parent_id') . ' = 0)');
        $query->group($db->qn('p.id'));

        switch ($type) {
            case 'recommendation':
                $query->where('(' . $db->qn('p.recommendation') . ' = 1)');
            break;
            case 'promotion':
                $query->where('(' . $db->qn('p.promotion') . ' = 1)');
            break;
            case 'hot':
                $query->where('(' . $db->qn('p.hot') . ' = 1)');
            break;
            case 'new':
                $query->where('(' . $db->qn('p.new') . ' = 1)');
            break;
        }

        switch ($type) {
            case 'recommendation':
            case 'promotion':
            case 'hot':
            case 'new':
                $query->order('RAND()');
            break;
            case 'hits':
                $query->order($db->qn('p.hits') . ' DESC');
            break;
            
            default:
                $query->order($db->qn('p.ordering') . ' ASC');
            break;
        }
        
        $db->setQuery($query, 0, $params->get('col', 10));
        $list = $db->loadObjectList();
        
        $db->setQuery('SELECT FOUND_ROWS();'); //no reloading the query! Just asking for total without limit
        jimport('joomla.html.pagination');
        self::$pagination = new JPagination($db->loadResult(), 0, $params->get('col', 10));
        
        return $list;
    }
    
    public static function setOtherParams($products) {
        foreach ($products as & $product) {
            $product = KSMProducts::getProduct($product->id);
        }
        return $products;
    }
}