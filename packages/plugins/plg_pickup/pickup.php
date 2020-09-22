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

class plgKMShippingPickup extends KSMShippingPlugin
{

	public $_params = array();
	private $_pickup_id = null;

	public function onDisplayParamsForm($name = '', $params = null)
	{
		if ($name != $this->_name) return;
		if (empty($params)) $params = $this->_params;
		$currency_code       = $this->getDefaultCurrencyCode();
		$view                = new stdClass();
		$view->params        = $params;
		$view->currency_code = $currency_code;
		$form                = $this->getForm();
		$form->bind($view);
		$view->form = $form;
		$html       = KSSystem::loadPluginTemplate($this->_name, $this->_type, $view, 'params');

		return $html;
	}

	public function onAfterExecuteKSMCartgetShippings($model, $shippings = array())
	{
		$region_id = $model->getState('region_id');
		if (empty($region_id)) return;
		$session     = JFactory::getSession();
		$pickup_id   = $session->get('pickup_id', null);
		$document    = JFactory::getDocument();
		$script_path = '';

		foreach ($shippings as &$shipping)
		{
			if ($shipping->type != $this->_name) continue;
			if (empty($script_path))
			{
				$script_path = '/plugins/' . $this->_type . DS . $this->_name . '/assets/js/pickup.js';
				$document->addScript($script_path);
			}
			$prefix       = '';
			$prices       = array();
			$min_price_id = 0;
			foreach ($shipping->shipping_params as $param)
			{
				$prices[$param['price']] = true;
				if (!isset($shipping->shipping_sum) || $param['price'] < $shipping->shipping_sum)
				{
					$shipping->shipping_sum = $param['price'];
					$min_price_id           = $param['id'];
				}
			}
			if (count($prices)) $prefix = JText::_('KSM_SHIPPING_PICKUP_PREFIX_PRICE');
			if ($pickup_id === null)
			{
				$pickup_id = $min_price_id;
				$session->set('pickup_id', $pickup_id);
			}
			if (!isset($shipping->shipping_sum)) $shipping->shipping_sum = 0;
			$shipping->shipping_sum_val = $prefix . ' ' . KSMPrice::showPriceWithTransform($shipping->shipping_sum);
			$shipping->step             = 'pickup';
		}
		unset($shipping);
	}

	public function onBeforeExecuteKSMshippingssaveShipping($model, &$data)
	{
		if ($data['type'] != 'pickup') return true;

		foreach ($data['params'] as $key => &$param)
		{
			$key         = str_replace('params', '', $key);
			$param['id'] = $key;
		}
		unset($param);
	}

	public function onBeforeDisplayKSMcartdefault_steps_shipping_info($view, $tmpl, &$html)
	{
		$this->onAfterDisplayKSMcartdefault_shipping_info($view, $tmpl, $html);
	}

	public function onAfterDisplayKSMcartdefault_shipping_info($view, $tmpl, &$html)
	{
		$shipping_current = array();
		foreach ($view->shippings as $shipping)
		{
			if ($shipping->type != $this->_name || !$shipping->selected) continue;
			$shipping_current = $shipping;
		}
		if (empty($shipping_current)) return true;
		if (!isset($view->cart->params['pickup_id']))
		{
			$session   = JFactory::getSession();
			$pickup_id = $session->get('pickup_id', 0);
		} else
		{
			$pickup_id = $view->cart->params['pickup_id'];
		}

		$shipping_current->selected_pickup = $pickup_id;
		foreach ($shipping_current->shipping_params as &$param)
		{
			$param['price_val'] = KSMPrice::showPriceWithTransform($param['price']);
			$param['selected']  = false;
			if ($param['id'] == $pickup_id) $param['selected'] = true;
		}
		unset($param);
		$html_temp = KSSystem::loadPluginTemplate($this->_name, $this->_type, $shipping_current, 'pickups');
		$html      = $html_temp . $html;
	}

	public function onAfterExecuteKSMCartSetModelFields($model, $cart = null)
	{
		$jinput    = JFactory::getApplication()->input;
		$pickup_id = $jinput->get('pickup_id', null, 'int');
		if ($pickup_id === null) return true;
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('params')->from('#__ksenmart_orders')->where('id=' . (int) $model->order_id);
		$db->setQuery($query);
		$params = $db->loadResult();
		if (!empty($params)) $params = json_decode($params);
		if (!is_array($params)) $params = array();
		$params['pickup_id'] = $pickup_id;

		$query = $db->getQuery(true);
		$query->update('#__ksenmart_orders')->set('params=' . $db->q(json_encode($params)))->where('id=' . (int) $model->order_id);
		$db->setQuery($query);
		$db->execute();
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
		$shipping->params   = json_decode($shipping->params, true);
		$cart->shipping_sum = 0;
		if (isset($cart->params['pickup_id']))
		{
			if (isset($shipping->params['params' . $cart->params['pickup_id']]))
			{
				$pickup             = $shipping->params['params' . $cart->params['pickup_id']];
				$cart->shipping_sum = (float) $pickup['price'];
			}
		}
		$cart->shipping_sum_val = KSMPrice::showPriceWithTransform($cart->shipping_sum);
		$cart->total_sum        += $cart->shipping_sum;
		$cart->total_sum_val    = KSMPrice::showPriceWithTransform($cart->total_sum);

		return;
	}

	public function onAfterExecuteKSMOrdersGetorder($model, &$order = null)
	{
		$order->pickup_id = 0;
		if (!empty($order->params['pickup_id'])) $order->pickup_id = $order->params['pickup_id'];

		$this->onAfterExecuteHelperKSMOrdersGetOrder($order);
	}

	public function onBeforeExecuteKSMOrdersSaveorder($model, &$data = null)
	{
		if (!isset($data['pickup_id'])) return true;
		$data['params']['pickup_id'] = $data['pickup_id'];
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
		$shipping->params              = json_decode($shipping->params, true);
		$order->costs['shipping_cost'] = 0;
		if (!empty($order->params['pickup_id']))
		{
			$this->_pickup_id = $order->params['pickup_id'];
			if (isset($shipping->params['params' . $order->params['pickup_id']]))
			{
				$pickup                        = $shipping->params['params' . $order->params['pickup_id']];
				$order->costs['shipping_cost'] = (float) $pickup['price'];
			}
		}
		$order->costs['shipping_cost_val'] = KSMPrice::showPriceWithTransform($order->costs['shipping_cost']);
		$order->costs['total_cost']        += $order->costs['shipping_cost'];
		$order->costs['total_cost_val']    = KSMPrice::showPriceWithTransform($order->costs['total_cost']);

		return;
	}

	function onAfterGetKSMFormOrder($form, $instance)
	{
		if (empty($this->_pickup_id)) return;

		JKSForm::addFieldPath(JPATH_ROOT . '/plugins/' . $this->_type . DS . $this->_name . '/assets/fields');
		$xml = '
			<field
				name="pickup_id"
				type="pickup"
				default=""
				label="KSM_SHIPPING_PICKUP_FIELD_LBL"
				description ="KSM_SHIPPING_PICKUP_FIELD_DESC"
				labelclass="inputname"
			/>
		';
		$element = new JXMLElement($xml);
		$instance->setField($element);
	}

	function onAfterGetKSMFormInputOrderShipping_id($form, &$field_name, &$html)
	{
		if (empty($this->_pickup_id)) return;

		$html.= '</div>';
		$html.= '<div class="row">';
		$html.= 	$form->getLabel('pickup_id');
		$html.= 	$form->getInput('pickup_id');
	}


	function getForm()
	{
		JKSForm::addFormPath(JPATH_ROOT . '/plugins/' . $this->_type . DS . $this->_name . '/assets/forms');
		JKSForm::addFieldPath(JPATH_ROOT . '/administrator/components/com_ksenmart/models/fields');

		$form = JKSForm::getInstance('com_ksenmart.pickup', 'pickup', array(
			'control'   => 'jform',
			'load_data' => true
		));

		if (empty($form)) return false;

		return $form;
	}
}