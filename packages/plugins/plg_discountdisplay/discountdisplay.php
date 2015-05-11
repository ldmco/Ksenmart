<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

if (!class_exists('KMPlugin')) {
    require (JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_ksenmart' . DS . 'classes' . DS . 'kmplugin.php');
}

class plgKMPluginsDiscountdisplay extends KMPlugin {
    
    function __construct(&$subject, $config) {
        parent::__construct($subject, $config);
    }
    
    function onBeforeDisplayKSMCartDefault_total($view, &$tpl = null, &$html) {
        if (empty($view->cart)) 
        return;
        $discount = 0;
        $discounts = array();
        
        foreach ($view->cart->items as $item) {
            if (isset($item->discounts)) {
                
                foreach ($item->discounts as $discount_id => $discount) {
                    if ($discount->sum) {
                        if (!isset($discounts[0])) $discounts[0] = 0;
                        $discounts[0]+= $discount->discount_value;
                    } else {
                        if (!isset($discounts[$discount_id])) $discounts[$discount_id] = 0;
                        $discounts[$discount_id]+= $discount->discount_value;
                    }
                }
            }
        }
        if (!count($discounts)) 
        return;
        $discount = max($discounts);
        if (!$discount) 
        return;
        $view->cart->discount_sum = $discount;
        $view->cart->discount_sum_val = KSMPrice::showPriceWithTransform($discount);
        $view->cart->total_sum = $view->cart->total_sum - $discount;
        if ($view->cart->total_sum < 0) $view->cart->total_sum = 0;
        $view->cart->total_sum_val = KSMPrice::showPriceWithTransform($view->cart->total_sum);
        
        
        return;
    }
    
    function onAfterExecuteKSMOrdersGetorder($model, $order = null) {
        if (empty($order)) 
        return;
        $discount_value = 0;
        
        foreach ($order->items as $item) {
            if (isset($item->discounts)) {
                
                foreach ($item->discounts as $discount_id => $discount) {
                    $discount_value+= $discount->discount_value;
                }
            }
        }
        $order->costs['discount_cost'] = $discount_value;
        $order->costs['discount_cost_val'] = KSMPrice::showPriceWithTransform($order->costs['discount_cost']);
        $order->costs['total_cost']-= $order->costs['discount_cost'];
        if ($order->costs['total_cost'] < 0) $order->costs['total_cost'] = 0;
        $order->costs['total_cost_val'] = KSMPrice::showPriceWithTransform($order->costs['total_cost']);
        
        
        return;
    }
    
    function onBeforeExecuteKSMCartCloseorder($model) {
        if (empty($model->order_id)) 
        return;
        $cart = $model->getCart();
        if (empty($cart)) 
        return;
        $discount_value = 0;
        $discounts = array();
        $order_discounts = array();
        
        foreach ($cart->items as $item) {
            if (isset($item->discounts)) {
                
                foreach ($item->discounts as $discount_id => $discount) {
                    if ($discount->sum) {
                        if (!isset($discounts[0])) $discounts[0] = array(
                            'discount_value' => 0,
                            'discounts' => array()
                        );
                        $discounts[0]['discount_value']+= $discount->discount_value;
                        $discounts[0]['discounts'][$discount_id] = $discount->params;
                    } else {
                        if (!isset($discounts[$discount_id])) $discounts[$discount_id] = array(
                            'discount_value' => 0,
                            'discounts' => array(
                                $discount_id => $discount->params
                            )
                        );
                        $discounts[$discount_id]['discount_value']+= $discount->discount_value;
                    }
                }
            }
        }
        
        foreach ($discounts as $discount) {
            if ($discount['discount_value'] > $discount_value) {
                $discount_value = $discount['discount_value'];
                $order_discounts = $discount['discounts'];
            }
        }
        $order_discounts = json_encode($order_discounts);
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->update('#__ksenmart_orders')->set('discounts=' . $db->quote($order_discounts))->where('id=' . $model->order_id);
        $db->setQuery($query);
        $db->Query();
    }
}