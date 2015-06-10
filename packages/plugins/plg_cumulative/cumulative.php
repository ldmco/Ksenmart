<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

if (!class_exists('KMDiscountPlugin')) {
	require (JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_ksenmart' . DS . 'classes' . DS . 'kmdiscountplugin.php');
}

class plgKMDiscountCumulative extends KMDiscountPlugin {
	
	var $_params = array();
	
	function __construct(&$subject, $config) {
		parent::__construct($subject, $config);
	}
	
	function onDisplayParamsForm($name = '', $params = null) {
		if ($name != $this->_name) 
		return;
		if (empty($params)) $params = $this->_params;
		$currency_code = $this->getDefaultCurrencyCode();
		$html = '';
		$html.= '<div class="set">';
		$html.= '	<h3 class="headname">' . JText::_('ksm_discount_algorithm') . '</h3>';
		$html.= '	<div class="lists">';
		$html.= '		<div class="row">';
		$html.= '			<ul class="cumulative-params-ul">';
		
		foreach ($params as $key => $line) {
			$html.= '			<li>';
			$html.= '				<div class="line">';
			$html.= '					<label class="inputname" style="width:160px;">' . JText::_('ksm_discount_cumulative_orders_cost') . '</label>';
			$html.= '					<input type="text" class="inputbox" name="jform[params][' . $key . '][orders_cost]" value="' . $line['orders_cost'] . '">';
			$html.= '					<label class="inputname" style="width:60px;margin-left:60px;">' . JText::_('ksm_discount_cumulative_discount') . '</label>';
			$html.= '					<input type="text" class="inputbox" name="jform[params][' . $key . '][value]" value="' . $line['value'] . '">';
			$html.= '					<select class="sel" name="jform[params][' . $key . '][type]" style="width:50px;">';
			$html.= '						<option value="0" ' . ($line['type'] == 0 ? 'selected' : '') . '>%</option>';
			$html.= '						<option value="1" ' . ($line['type'] == 1 ? 'selected' : '') . '>' . $currency_code . '</option>';
			$html.= '					</select>';
			$html.= '					<p>&nbsp</p>';
			$html.= '				</div>';
			$html.= '				<a href="#" onclick="deleteOnorderParam(this);return false;"></a>';
			$html.= '			</li>';
		}
		$html.= '			</ul>';
		$html.= '		</div>';
		$html.= '		<div class="row">';
		$html.= '			<a href="#" class="add" onclick="addCumulativeParam(this);return false;">' . JText::_('ksm_discount_cumulative_add') . '</a>';
		$html.= '		</div>';
		$html.= '		<div class="cumulative-param-mask" style="display:none;">';
		$html.= '			<li>';
		$html.= '				<div class="line">';
		$html.= '					<label class="inputname" style="width:160px;">' . JText::_('ksm_discount_cumulative_orders_cost') . '</label>';
		$html.= '					<input type="text" class="inputbox" name="[key][orders_cost]" value="0">';
		$html.= '					<label class="inputname" style="width:60px;margin-left:60px;">' . JText::_('ksm_discount_cumulative_discount') . '</label>';
		$html.= '					<input type="text" class="inputbox" name="[key][value]" value="0">';
		$html.= '					<select class="sel" name="[key][type]" style="width:50px;">';
		$html.= '						<option value="0">%</option>';
		$html.= '						<option value="1">' . $currency_code . '</option>';
		$html.= '					</select>';
		$html.= '					<p>&nbsp</p>';
		$html.= '				</div>';
		$html.= '				<a href="#" onclick="deleteCumulativeParam(this);return false;"></a>';
		$html.= '			</li>';
		$html.= '		</div>';
		$html.= '	</div>';
		$html.= '</div>';
		$html.= '<script>';
		$html.= 'function addCumulativeParam(obj)';
		$html.= '{';
		$html.= '	var html=jQuery(".cumulative-param-mask").html();';
		$html.= '	var date=new Date();';
		$html.= '	var time=date.getTime();';
		$html.= '	html=html.split("[key]").join("jform[params]["+time+"]");';
		$html.= '	jQuery(".cumulative-params-ul").append(html);';
		$html.= '}';
		$html.= 'function deleteCumulativeParam(obj)';
		$html.= '{';
		$html.= '	jQuery(obj).parents("li").remove();';
		$html.= '}';
		$html.= '</script>';
		
		return $html;
	}
	
	function onSetCartDiscount($cart = null, $discount_id = null) {
		if (empty($cart)) 
		return false;
		if (empty($discount_id)) 
		return false;
		$db = JFactory::getDBO();
		$orders_sum = 0;
		if (JFactory::getUser()->id != 0) {
			$query = $db->getQuery(true);
			$query->select('sum(cost)')->from('#__ksenmart_orders')->where('user_id=' . JFactory::getUser()->id);
			$db->setQuery($query);
			$orders_sum = $db->loadObject();
		} else 
		return false;
		$discount_set_value = 0;
		
		foreach ($cart->items as & $item) {
			$query = $db->getQuery(true);
			$query->select('params,sum')->from('#__ksenmart_discounts')->where('type=' . $db->quote($this->_name))->where('id=' . $discount_id)->where('enabled=1');
			$db->setQuery($query);
			$discount = $db->loadObject();
			if (empty($discount)) 
			return false;
			$discount->params = json_decode($discount->params, true);
			$discount->discount_value = 0;
			$discount->orders_cost = 0;
			if (!isset($item->discounts)) $item->discounts = array();
			$return = $this->onCheckDiscountCategories($discount_id, $item->product_id);
			if (!$return) 
			continue;
			$return = $this->onCheckDiscountManufacturers($discount_id, $item->product_id);
			if (!$return) 
			continue;
			$need_key = - 1;
			
			foreach ($discount->params as $key => $param) {
				if ($orders_sum >= $param['orders_cost'] && $param['orders_cost'] > $discount->orders_cost) {
					$discount->orders_cost = $param['orders_cost'];
					$need_key = $key;
				}
			}
			if ($need_key != - 1) {
				$item->discounts[$discount_id] = $this->calculateItemDiscount($item, $discount, $discount_set_value, $discount->params[$need_key]);
			}
		}
		
		return true;
	}
	
	function onSetOrderDiscount($order = null, $discount_id = null, $params = null) {
		if (empty($order)) 
		return false;
		if (empty($discount_id)) 
		return false;
		if (empty($params)) 
		return false;
		$db = JFactory::getDBO();
		$orders_sum = 0;
		if ($order->user_id != 0) {
			$query = $db->getQuery(true);
			$query->select('sum(cost)')->from('#__ksenmart_orders')->where('user_id=' . $order->user_id);
			$db->setQuery($query);
			$orders_sum = $db->loadObject();
		} else 
		return false;
		$discount_set_value = 0;
		
		foreach ($order->items as & $item) {
			$query = $db->getQuery(true);
			$query->select('sum')->from('#__ksenmart_discounts')->where('type=' . $db->quote($this->_name))->where('id=' . $discount_id)->where('enabled=1');
			$db->setQuery($query);
			$discount = $db->loadObject();
			if (empty($discount)) 
			return false;
			$discount->discount_value = 0;
			$discount->orders_cost = 0;
			if (!isset($item->discounts)) $item->discounts = array();
			$return = $this->onCheckDiscountCategories($discount_id, $item->product_id);
			if (!$return) 
			continue;
			$return = $this->onCheckDiscountManufacturers($discount_id, $item->product_id);
			if (!$return) 
			continue;
			$need_key = - 1;
			
			foreach ($params as $key => $param) {
				if ($orders_sum >= $param['orders_cost'] && $param['orders_cost'] > $discount->orders_cost) {
					$discount->orders_cost = $param['orders_cost'];
					$need_key = $key;
				}
			}
			if ($need_key != - 1) {
				$item->discounts[$discount_id] = $this->calculateItemDiscount($item, $discount, $discount_set_value, $params[$need_key]);
			}
		}
		
		return true;
	}
	
	function onGetDiscountContent($discount_id = null) {
		if (empty($discount_id)) 
		return;
		$db = JFactory::getDBO();
		$session = JFactory::getSession();
		$query = $db->getQuery(true);
		$query->select('content')->from('#__ksenmart_discounts')->where('type=' . $db->quote($this->_name))->where('id=' . $discount_id)->where('enabled=1');
		$db->setQuery($query);
		$content = $db->loadResult();
		if (empty($content)) 
		return;
		
		return $content;
	}
}
