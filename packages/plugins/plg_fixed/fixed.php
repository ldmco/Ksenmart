<?php
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

if (!class_exists('KMDiscountPlugin'))
{
	require(JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_ksenmart' . DS . 'classes' . DS . 'kmdiscountplugin.php');
}

class plgKMDiscountFixed extends KMDiscountPlugin
{

	var $_params = array(
		'value' => 0,
		'type'  => 1
	);

	function __construct(&$subject, $config)
	{
		parent::__construct($subject, $config);
	}

	function onDisplayParamsForm($name = '', $params = null)
	{
		if ($name != $this->_name) return;
		if (empty($params)) $params = $this->_params;
		$currency_code = $this->getDefaultCurrencyCode();
		$html          = '';
		$html .= '<div class="set">';
		$html .= '	<h3 class="headname">' . JText::_('ksm_discount_algorithm') . '</h3>';
		$html .= '	<div class="row">';
		$html .= '		<label class="inputname">' . JText::_('ksm_discount_fixed_value_lbl') . '</label>';
		$html .= '		<input type="text" class="inputbox" name="jform[params][value]" value="' . $params['value'] . '">';
		$html .= '		<select class="sel" name="jform[params][type]">';
		$html .= '			<option value="0" ' . ($params['type'] == 0 ? 'selected' : '') . '>%</option>';
		$html .= '			<option value="1" ' . ($params['type'] == 1 ? 'selected' : '') . '>' . $currency_code . '</option>';
		$html .= '		</select>';
		$html .= '	</div>';
		$html .= '</div>';

		return $html;
	}

	function onSetProductDiscount($prd = null, $discount_id = null)
	{
		if (empty($prd)) return $prd;
		if (empty($discount_id)) return $prd;
		$discount = KSMPrice::getDiscount($discount_id);
		if (empty($discount)) return $prd;
		$return = $this->onCheckDiscountCategories($discount_id, $prd->id);
		if (!$return) return $prd;
		$return = $this->onCheckDiscountManufacturers($discount_id, $prd->id);
		if (!$return) return $prd;
		if (!isset($prd->original_price)) $prd->original_price = $prd->price;
		$discount       = $this->calculateDiscountProduct($prd->price, $discount->params);
		$prd->old_price = $prd->original_price;
		$prd->price     = $prd->price - $discount;
		unset($discount);
		parent::onSetProductDiscount($prd);
	}

	function onSetCartDiscount($cart = null, $discount_id = null)
	{
		if (empty($cart)) return false;
		if (empty($discount_id)) return false;
		$discount_set_value = 0;
		foreach ($cart->items as &$item)
		{
			$discount = KSMPrice::getDiscount($discount_id);
			if (empty($discount)) return false;
			$discount->discount_value = 0;
			if (!isset($item->discounts)) $item->discounts = array();
			$return = $this->onCheckDiscountCategories($discount_id, $item->product_id);
			if (!$return) continue;
			$return = $this->onCheckDiscountManufacturers($discount_id, $item->product_id);
			if (!$return) continue;
			$item->discounts[$discount_id] = $this->calculateItemDiscount($item, $discount, $discount_set_value, $discount->params);
		}
		unset($item);

		return true;
	}

	function onSetOrderDiscount($order = null, $discount_id = null, $params = null)
	{
		if (empty($order)) return false;
		if (empty($discount_id)) return false;
		if (empty($params)) return false;
		$discount_set_value = 0;

		foreach ($order->items as & $item)
		{
			$discount = KSMPrice::getDiscount($discount_id);
			if (empty($discount)) return false;
			$discount->discount_value = 0;
			if (!isset($item->discounts)) $item->discounts = array();
			$return = $this->onCheckDiscountCategories($discount_id, $item->product_id);
			if (!$return) continue;
			$return = $this->onCheckDiscountManufacturers($discount_id, $item->product_id);
			if (!$return) continue;
			$item->discounts[$discount_id] = $this->calculateItemDiscount($item, $discount, $discount_set_value, $params);
		}

		return true;
	}

	function onGetDiscountContent($discount_id = null)
	{
		if (empty($discount_id)) return;
		$discount = KSMPrice::getDiscount($discount_id);
		if (empty($discount)) return true;
		$content = $discount->content;
		if (empty($content)) return;

		return $content;
	}
}
