<?php
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

if (!class_exists('KSMShippingPlugin'))
{
	require(JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_ksenmart' . DS . 'classes' . DS . 'kmshippingplugin.php');
}

class plgKMShippingFixedRegions extends KSMShippingPlugin
{

	public $_params = array();

	public function onDisplayParamsForm($name = '', $params = null)
	{
		if ($name != $this->_name)
		{
			return;
		}
		if (empty($params)) $params = $this->_params;
		$db            = JFactory::getDbo();
		$currency_code = $this->getDefaultCurrencyCode();


		$query = $db->getQuery(true);
		$query->select('id,title,country_id')->from('#__ksenmart_regions');
		$db->setQuery($query);
		$regions             = $db->loadObjectList('id');
		$view                = new stdClass();
		$view->params        = $params;
		$view->currency_code = $currency_code;
		$view->regions       = $regions;
		$html                = KSSystem::loadPluginTemplate($this->_name, $this->_type, $view, 'params');

		$document = JFactory::getDocument();
		$document->addScript('/plugins/' . $this->_type . DS . $this->_name . '/assets/js/fixedregions.js');

		return $html;
	}

	public function onAfterExecuteKSMCartgetShippings($model, $shippings = array())
	{
		$region_id = $model->getState('region_id');
		if (empty($region_id)) return;

		$cart       = $model->getCart();
		$weight_sum = 0;
		foreach ($cart->items as $item)
		{
			$weight_sum += $item->product->weight;
		}

		foreach ($shippings as &$shipping)
		{
			if ($shipping->type != $this->_name) continue;
			if (isset($shipping->shipping_params[$region_id]))
			{
				$shipping->shipping_sum = $shipping->shipping_params[$region_id]['cost'];
				if (!empty($shipping->shipping_params[$region_id]['weight_cost']) && $weight_sum > 0.5)
				{
					$overweight             = ceil(($weight_sum - 0.5) / 0.5);
					$shipping->shipping_sum += (float) $shipping->shipping_params[$region_id]['weight_cost'] * $overweight;
				}
				$shipping->shipping_sum_val = KSMPrice::showPriceWithTransform($shipping->shipping_sum);
			}
		}
		unset($shipping);
	}

	public function onAfterExecuteKSMCartGetCart($model, $cart = null)
	{
		if (empty($cart)) return;
		if (empty($cart->shipping_id)) return;
		$db    = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('id,params,regions')->from('#__ksenmart_shippings')->where('id=' . $cart->shipping_id)->where('type=' . $db->quote($this->_name))->where('published=1');
		$db->setQuery($query);
		$shipping = $db->loadObject();
		if (empty($shipping)) return;
		if (empty($cart->region_id)) return;
		if (!$this->checkRegion($shipping->regions, $cart->region_id)) return;
		$shipping->params = json_decode($shipping->params, true);
		if (!isset($shipping->params[$cart->region_id]))
		{
			$cart->shipping_sum     = 0;
			$cart->shipping_sum_val = KSMPrice::showPriceWithTransform(0);

			return;
		}
		$weight_sum = 0;
		foreach ($cart->items as $item)
		{
			$weight_sum += $item->product->weight;
		}

		$cart->shipping_sum = (float) $shipping->params[$cart->region_id]['cost'];
		if (!empty($shipping->params[$cart->region_id]['weight_cost']) && $weight_sum > 0.5)
		{
			$overweight         = ceil(($weight_sum - 0.5) / 0.5);
			$cart->shipping_sum += (float) $shipping->params[$cart->region_id]['weight_cost'] * $overweight;
		}
		$cart->shipping_sum_val = KSMPrice::showPriceWithTransform($cart->shipping_sum);
		$cart->total_sum        += $cart->shipping_sum;
		$cart->total_sum_val    = KSMPrice::showPriceWithTransform($cart->total_sum);

		return;
	}

	public function onAfterExecuteKSMOrdersGetorder($model, $order = null)
	{
		$this->onAfterExecuteHelperKSMOrdersGetOrder($order);
	}

	public function onAfterExecuteHelperKSMOrdersGetOrder($order = null)
	{

		if (empty($order)) return;
		if (empty($order->shipping_id)) return;
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('id,params,regions')->from('#__ksenmart_shippings')->where('id=' . $order->shipping_id)->where('type=' . $db->quote($this->_name))->where('published=1');
		$db->setQuery($query);
		$shipping = $db->loadObject();
		if (empty($shipping)) return;
		if (empty($order->region_id)) return;
		if (!$this->checkRegion($shipping->regions, $order->region_id)) return;
		$shipping->params = json_decode($shipping->params, true);
		if (!isset($shipping->params[$order->region_id])) return;
		$weight_sum = 0;
		foreach ($order->items as $item)
		{
			if (empty($item->product)) continue;
			$weight_sum += $item->product->weight;
		}
		$order->costs['shipping_cost'] = $shipping->params[$order->region_id]['cost'];
		if (!empty($shipping->params[$order->region_id]['weight_cost']) && $weight_sum > 0.5)
		{
			$overweight                    = ceil(($weight_sum - 0.5) / 0.5);
			$order->costs['shipping_cost'] += (float) $shipping->params[$order->region_id]['weight_cost'] * $overweight;
		}
		$order->costs['shipping_cost_val'] = KSMPrice::showPriceWithTransform($order->costs['shipping_cost']);
		$order->costs['total_cost']        += $order->costs['shipping_cost'];
		$order->costs['total_cost_val']    = KSMPrice::showPriceWithTransform($order->costs['total_cost']);

		return;
	}
}