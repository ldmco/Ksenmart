<?php
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

if (!class_exists('KMPlugin')) {
	require(JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_ksenmart' . DS . 'classes' . DS . 'kmplugin.php');
}

class plgKMPluginsDiscountdisplay extends KMPlugin {

	protected $_discounts = array();

	function __construct(&$subject, $config) {
		parent::__construct($subject, $config);
	}

	function onAfterExecuteKSMCartGetCart($model, $cart = null) {
		if (empty($cart)) return;
		$discounts = array();
		$prices = array();

		foreach ($cart->items as &$item) {
			if (isset($item->discounts)) {
				$item_discount = 0;
				foreach ($item->discounts as $discount_id => $discount) {
					if ($discount->sum) {
						if (!isset($discounts[0])) $discounts[0] = 0;
						$discounts[0] += $discount->discount_value;
						$item_discount += $discount->discount_value / $item->count;
					} else {
						if (!isset($discounts[$discount_id])) $discounts[$discount_id] = 0;
						$discounts[$discount_id] += $discount->discount_value;
					}
				}
				$prices[$item->id] = $item->price;
				if ($item_discount > 0) {
					if (empty($item->old_price) || $item->old_price < $item->price)
						$item->old_price = $item->price;
					$item->price -= $item_discount;
					$item->old_price_val = KSMPrice::showPriceWithTransform($item->old_price);
					$item->price_val     = KSMPrice::showPriceWithTransform($item->price);
				}
			}
		}
		unset($item);
		if (!count($discounts)) return;
		$discount = max($discounts);
		if (!$discount) return;
		$discount_id = array_search($discount, $discounts);
		if ($discount_id > 0) {
			foreach ($cart->items as &$item) {
				if (!count($item->discounts)) continue;
				$item->price = $prices[$item->id];
				$discount_value = $item->discounts[$discount_id]->discount_value / $item->count;
				if (empty($discount_value)) continue;
				$item->old_price = $item->price;
				$item->price -= $discount_value;
				$item->old_price_val = KSMPrice::showPriceWithTransform($item->old_price);
				$item->price_val     = KSMPrice::showPriceWithTransform($item->price);
			}
		}
		$cart->discount_sum     = $discount;
		$cart->discount_sum_val = KSMPrice::showPriceWithTransform($discount);
		$cart->total_sum        = $cart->total_sum - $discount;
		if ($cart->total_sum < 0) $cart->total_sum = 0;
		$cart->total_sum_val = KSMPrice::showPriceWithTransform($cart->total_sum);

		return;
	}

	function onAfterExecuteKSMOrdersGetorder($model, $order = null) {
		if (empty($order))
			return;
		$discount_value   = 0;
		$order->discounts = json_decode($order->discounts, true);

		foreach ($order->items as $item) {
			if (isset($item->discounts)) {
				foreach ($item->discounts as $discount_id => $discount) {
					if (!isset($order->discounts[$discount_id]))
						$order->discounts[$discount_id] = array();
					$order->discounts[$discount_id]['sum'] = $discount->discount_value;
					$discount_value += $discount->discount_value;
				}
			}
		}
		if (isset($order->discounts['-1'])) $discount_value += $order->discounts['-1']['value'];
		$order->discounts                  = json_encode($order->discounts);
		$order->costs['discount_cost']     = $discount_value;
		$order->costs['discount_cost_val'] = KSMPrice::showPriceWithTransform($order->costs['discount_cost']);
		$order->costs['total_cost'] -= $order->costs['discount_cost'];
		if ($order->costs['total_cost'] < 0) $order->costs['total_cost'] = 0;
		$order->costs['total_cost_val'] = KSMPrice::showPriceWithTransform($order->costs['total_cost']);

		return;
	}

	protected function getDiscounts() {
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')->from('#__ksenmart_discounts');
		$db->setQuery($query);
		$this->_discounts = $db->loadObjectList('id');
	}

	function onAfterExecuteKSMOrdersgetListItems($model, $orders = array()) {
		foreach ($orders as &$order) {
			if (empty($order)) continue;
			$discount_value = 0;

			foreach ($order->items as $item) {
				if (isset($item->discounts)) {
					foreach ($item->discounts as $discount_id => $discount) {
						$discount_value += $discount->discount_value;
					}
				}
			}
			if (is_string($order->discounts)) $order->discounts = json_decode($order->discounts, true);
			if (isset($order->discounts['-1'])) $discount_value += $order->discounts['-1']['value'];
			$order->cost -= $discount_value;
			if ($order->cost < 0) $order->cost = 0;
			$order->cost_val = KSMPrice::showPriceWithTransform($order->cost);
		}
		unset($order);

		return;
	}

	function onAfterExecuteHelperKSMOrdersGetOrder($order = null) {
		$this->onAfterExecuteKSMOrdersGetorder(null, $order);
	}

	function onBeforeExecuteKSMCartCloseorder($model) {
		if (empty($model->order_id))
			return;
		$cart = $model->getCart();
		if (empty($cart))
			return;
		$discount_value  = 0;
		$discounts       = array();
		$order_discounts = array();

		foreach ($cart->items as $item) {
			if (isset($item->discounts)) {

				foreach ($item->discounts as $discount_id => $discount) {
					if ($discount->sum) {
						if (!isset($discounts[0])) $discounts[0] = array(
							'discount_value' => 0,
							'discounts'      => array()
						);
						$discounts[0]['discount_value'] += $discount->discount_value;
						$discounts[0]['discounts'][$discount_id] = $discount->params;
					} else {
						if (!isset($discounts[$discount_id])) $discounts[$discount_id] = array(
							'discount_value' => 0,
							'discounts'      => array(
								$discount_id => $discount->params
							)
						);
						$discounts[$discount_id]['discount_value'] += $discount->discount_value;
					}
				}
			}
		}

		foreach ($discounts as $discount) {
			if ($discount['discount_value'] > $discount_value) {
				$discount_value  = $discount['discount_value'];
				$order_discounts = $discount['discounts'];
			}
		}
		$order_discounts = json_encode($order_discounts);
		$db              = JFactory::getDbo();
		$query           = $db->getQuery(true);
		$query->update('#__ksenmart_orders')->set('discounts=' . $db->quote($order_discounts))->where('id=' . $model->order_id);
		$db->setQuery($query);
		$db->execute();
	}
}