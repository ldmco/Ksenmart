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

class plgKMDiscountDCShippings extends KMDiscountPlugin
{

	var $_params = array(
		'value' => 0,
		'type'  => 1
	);

	var $_costs = array();

	function __construct(&$subject, $config)
	{
		parent::__construct($subject, $config);
	}

	function onDisplayParamsForm($name = '', $params = null)
	{
		if ($name != $this->_name) return;
		if (empty($params)) $params = $this->_params;
		$currency_code = $this->getDefaultCurrencyCode();

		$db = JFactory::getDBO();
		//$query = $db->getQuery(true);
		//$query->select('extension_id')->from('#__extensions')->where('folder=' . $db->quote('kmshipping'))->where('ordering=0');
		//$db->setQuery($query);
		//$res = $db->loadResult();
		//var_dump($res);
		/*if(!empty($res)){
			$query = $db->getQuery(true);
			$query->update('#__extensions')->set('ordering=ordering+1')->where('folder=' . $db->quote('kmshipping'));
			$db->setQuery($query);
			$db->Query();
		}*/
		$query = $db->getQuery(true);
		$query
			->select('
				s.id,
				s.title
			')
			->from('#__ksenmart_shippings AS s')
			->where('s.published=1')
			->order('s.ordering');
		$db->setQuery($query);
		$shippings = $db->loadObjectList();
		$html      = '';
		$html .= '<div class="set">';
		$html .= '	<h3 class="headname">' . JText::_('ksm_discount_algorithm') . '</h3>';
		$html .= '	<div class="row">';
		$html .= '		<label class="inputname">' . JText::_('KSM_DISCOUNT_DCSHIPPINGS_SHIPPING_LBL') . '</label>';
		$html .= '		<select class="sel" name="jform[params][shipping]">';
		foreach ($shippings as $shipping)
		{
			$html .= '				<option value="' . $shipping->id . '" ' . ($params['shipping'] == $shipping->id ? 'selected' : '') . '>' . $shipping->title . '</option>';
		}
		$html .= '		</select>';
		$html .= '	</div>';
		$html .= '	<div class="row">';
		$html .= '		<label class="inputname">' . JText::_('KSM_DISCOUNT_DCSHIPPINGS_VALUE_LBL') . '</label>';
		$html .= '		<input type="text" class="inputbox" name="jform[params][value]" value="' . $params['value'] . '">';
		$html .= '		<select class="sel" name="jform[params][type]">';
		$html .= '			<option value="0" ' . ($params['type'] == 0 ? 'selected' : '') . '>' . JText::_('KSM_DISCOUNT_DCSHIPPINGS_FREE_LBL') . '</option>';
		$html .= '			<option value="1" ' . ($params['type'] == 1 ? 'selected' : '') . '>' . $currency_code . '</option>';
		$html .= '			<option value="2" ' . ($params['type'] == 2 ? 'selected' : '') . '>%</option>';
		$html .= '		</select>';
		$html .= '	</div>';
		$html .= '	<div class="row">';
		$html .= '		<label class="inputname">' . JText::_('KSM_DISCOUNT_DCSHIPPINGS_COST_LBL') . '</label>';
		$html .= '		<input type="text" class="inputbox" name="jform[params][cost]" value="' . $params['cost'] . '">';
		$html .= '		<label class="inputname">' . $currency_code . '</label>';
		$html .= '	</div>';
		$html .= '</div>';

		return $html;
	}

	public function onAfterExecuteKSMCartGetCart($model, $cart = null)
	{
		if (empty($cart)) return;
		if (empty($cart->shipping_id)) return;
		if (!isset($cart->costs) || empty($cart->costs['shipping_cost']) || $cart->costs['shipping_cost'] == 0) return;
		$discounts = KSMPrice::getDiscount($this->_name);
		foreach ($discounts as $discount)
		{
			$return = $this->onCheckDiscountDate($discount->id);
			if (!$return) continue;
			$return = $this->onCheckDiscountCountry($discount->id);
			if (!$return) continue;
			$return = $this->onCheckDiscountUserGroups($discount->id);
			if (!$return) continue;
			$return = $this->onCheckDiscountActions($discount->id);
			if ($return == 1) continue;
			$dparams = $discount->params;
			if ($dparams['shipping'] !== $cart->shipping_id) continue;
			if ($dparams['cost'] > $cart->cost) continue;
			switch ((int) $dparams['type'])
			{
				case 0:
					$shipping_cost = 0;
					break;
				case 1:
					$shipping_cost = $cart->shipping_sum - $dparams['value'];
					if ($shipping_cost < 0) $shipping_cost = 0;
					break;
				case 2:
					$shipping_cost = $cart->shipping_sum - $cart->shipping_sum * $dparams['value'] / 100;
					if ($shipping_cost < 0) $shipping_cost = 0;
					break;
				default:
					$shipping_cost = 0;
					break;
			}
			$dif_cost               = $cart->shipping_sum - $shipping_cost;
			$cart->shipping_sum     = $shipping_cost;
			$cart->shipping_sum_val = KSMPrice::showPriceWithTransform($cart->shipping_sum);
			$cart->total_sum -= $dif_cost;
			$cart->total_sum_val = KSMPrice::showPriceWithTransform($cart->total_sum);
			if (!$discount->sum) break;
		}

		return;
	}

	public function onAfterViewKSMcart($view)
	{
		if (isset($view->cart) && !empty($view->cart)) $this->onAfterExecuteKSMCartGetCart($view, $view->cart);
		if (isset($view->order) && !empty($view->order)) $this->onAfterExecuteHelperKSMOrdersGetOrder($view->order);
	}

	public function onAfterViewAdminKSMorders($view)
	{
		if (isset($view->cart) && !empty($view->cart)) $this->onAfterExecuteKSMCartGetCart($view, $view->cart);
		if (isset($view->order) && !empty($view->order)) $this->onAfterExecuteHelperKSMOrdersGetOrder($view->order);
	}

	public function onBeforeGetKSMFormInputOrderCosts($form)
	{
		$costs = $form->getValue('costs');
		if (!empty($this->_costs)) $form->setValue('costs', null, $this->_costs);
	}

	public function onAfterExecuteKSMOrdersGetorder($model, $order = null)
	{
		$this->onAfterExecuteHelperKSMOrdersGetOrder($order);
	}

	public function onAfterExecuteHelperKSMOrdersGetOrder($order = null)
	{
		if (empty($order)) return;
		if (empty($order->shipping_id)) return;
		if (!isset($order->costs) || empty($order->costs['shipping_cost']) || $order->costs['shipping_cost'] == 0) return;
		$discounts = KSMPrice::getDiscount($this->_name);
		foreach ($discounts as $discount)
		{
			$return = $this->onCheckDiscountDate($discount->id);
			if (!$return) continue;
			$return = $this->onCheckDiscountCountry($discount->id);
			if (!$return) continue;
			$return = $this->onCheckDiscountUserGroups($discount->id);
			if (!$return) continue;
			$return = $this->onCheckDiscountActions($discount->id);
			if ($return == 1) continue;
			$dparams = $discount->params;
			if ($dparams['shipping'] !== $order->shipping_id) continue;
			if ($dparams['cost'] > $order->cost) continue;
			switch ((int) $dparams['type'])
			{
				case 0:
					$shipping_cost = 0;
					break;
				case 1:
					$shipping_cost = $order->costs['shipping_cost'] - $dparams['value'];
					if ($shipping_cost < 0) $shipping_cost = 0;
					break;
				case 2:
					$shipping_cost = $order->costs['shipping_cost'] - $order->costs['shipping_cost'] * $dparams['value'] / 100;
					if ($shipping_cost < 0) $shipping_cost = 0;
					break;
				default:
					$shipping_cost = 0;
					break;
			}
			$dif_cost                          = $order->costs['shipping_cost'] - $shipping_cost;
			$order->costs['shipping_cost']     = $shipping_cost;
			$order->costs['shipping_cost_val'] = KSMPrice::showPriceWithTransform($order->costs['shipping_cost']);
			$order->costs['total_cost'] -= $dif_cost;
			$order->costs['total_cost_val'] = KSMPrice::showPriceWithTransform($order->costs['total_cost']);
			$this->_costs                   = $order->costs;
			if (!$discount->sum) break;
		}

		return;
	}
}
