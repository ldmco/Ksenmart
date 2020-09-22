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

class plgKMShippingB2cpl extends KSMShippingPlugin
{
	private $_shippings = null;
	private $_shipping_id = null;
	private $_tarif = null;
	private $_b2cpl_shippings = null;
	private $_pvz_flag = false;
	protected $_params = array(
		'client' => '',
		'key'    => ''
	);

	public function onDisplayParamsForm($name = '', $params = null)
	{
		if ($name != $this->_name) return;

		if (empty($params))
		{
			$params = $this->_params;
		}

		$view         = new stdClass();
		$view->params = $params;
		$html         = KSSystem::loadPluginTemplate($this->_name, $this->_type, $view, 'params');

		return $html;
	}

	public function onBeforeViewKSMcart($view)
	{
		$document = JFactory::getDocument();
		$document->addScript(JUri::base() . '/plugins/' . $this->_type . DS . $this->_name . '/assets/js/b2cpl.js', 'text/javascript', false);
	}

	public function onAfterViewKSMcart($view)
	{
		$flag = false;
		if (empty($this->_b2cpl_shippings))
		{
			$this->getB2cplShippings($view->cart);
		}
		if (empty($this->_b2cpl_shippings)) return;
		if (!empty($this->_shipping_id))
		{
			foreach ($view->shippings as $key => $shipping)
			{
				$icon = $shipping->icon;
				if ($shipping->id == $this->_shipping_id) unset($view->shippings[$key]);
			}
		}
		if (!empty($this->_shipping_id) && $this->_shipping_id == $view->cart->shipping_id) $flag = true;

		$pvz_shipping                   = new stdClass();
		$pvz_shipping->id               = $this->_shipping_id;
		$pvz_shipping->title            = JText::_('KSM_KMSHIPPING_B2CPL_PVZ_TITLE');
		$pvz_shipping->type             = 'b2cpl';
		$pvz_shipping->action           = "KMCartChangeB2cplShipping(this, 'pvz_all')";
		$pvz_shipping->shipping_sum     = 0;
		$pvz_shipping->shipping_sum_val = KSMPrice::showPriceWithTransform($pvz_shipping->shipping_sum);
		$pvz_shipping->selected         = ($flag && !empty($view->cart->params['tarif_id']) && ($view->cart->params['tarif_id'] == 'pvz_all' || strpos($view->cart->params['tarif_id'], 'пвз') !== false));
		$pvz_shipping->step             = 'b2cpl';
		if (!empty($icon)) $pvz_shipping->icon = $icon;
		$pvz_flag = false;

		foreach ($this->_b2cpl_shippings as $b2CplShipping)
		{
			if ($b2CplShipping->Наименование == 'пвз')
			{
				$pvz_flag = true;
				if (!empty($view->cart->params['tarif_id']) && $view->cart->params['tarif_id'] === trim($b2CplShipping->Код))
				{
					$shipping        = new stdClass();
					$shipping->id    = $this->_shipping_id;
					$this->_tarif    = $shipping;
					$shipping->title = $b2CplShipping->Наименование;
					if (!empty($b2CplShipping->Адрес)) $shipping->title = '<small>' . $b2CplShipping->Адрес . '</small>';
				}
				continue;
			}
			$shipping        = new stdClass();
			$shipping->id    = $this->_shipping_id;
			$shipping->title = $b2CplShipping->Наименование;
			if (!empty($b2CplShipping->Адрес)) $shipping->title .= '<small> (' . $b2CplShipping->Адрес . ')</small>';
			$shipping->introcontent     = $b2CplShipping->Описание;
			$shipping->type             = 'b2cpl';
			$shipping->action           = "KMCartChangeB2cplShipping(this, '" . trim($b2CplShipping->Код) . "')";
			$shipping->shipping_sum     = $b2CplShipping->Стоимость;
			$shipping->shipping_sum_val = KSMPrice::showPriceWithTransform($shipping->shipping_sum);
			$shipping->selected         = ($flag && !empty($view->cart->params['tarif_id']) && $view->cart->params['tarif_id'] == trim($b2CplShipping->Код));
			if (!empty($icon)) $shipping->icon = $icon;
			if ($shipping->selected) $this->_tarif = $shipping;
			$view->shippings[] = $shipping;
		}
		if ($pvz_flag) $view->shippings[$this->_shipping_id] = $pvz_shipping;
	}

	public function onBeforeDisplayKSMcartdefault_steps_total(&$view, &$tpl = null, &$html)
	{
		$this->onBeforeDisplayKSMcartdefault_total($view, $tpl, $html);
	}

	function onBeforeDisplayKSMcartdefault_total(&$view, &$tpl = null, &$html)
	{
		if (empty($this->_shipping_id)) return;
		if (empty($this->_tarif)) return;
		if ($this->_shipping_id != $view->cart->shipping_id) return;
		if (empty($view->shippings[$view->cart->shipping_id])) return;

		if ($this->_pvz_flag)
		{
			$view->shippings[$view->cart->shipping_id]->title .= '<br />' . $this->_tarif->title;
		}
		else
		{
			$view->shippings[$view->cart->shipping_id]->title = $this->_tarif->title;
		}
	}

	public function onAfterExecuteKSMCartGetSteps($model, &$info = null)
	{
		if (empty($info)) return;
		if (empty($this->_shipping_id)) return;
		if (!$model->getState('shipping_id', 0)) return;
		if ($model->getState('shipping_id', 0) != $this->_shipping_id) return;
		if (!$this->_pvz_flag) return;
		$info->shipping_step_name = 'b2cpl';
	}

	public function onBeforeDisplayKSMcartdefault_steps_shipping_info($view, $tmpl, &$html)
	{
		$this->onAfterDisplayKSMcartdefault_shipping_info($view, $tmpl, $html);
	}

	public function onAfterDisplayKSMcartdefault_shipping_info($view, $tmpl, &$html)
	{
		if (empty($view->cart->params['tarif_id'])
			|| (strpos($view->cart->params['tarif_id'], 'пвз') === false && $view->cart->params['tarif_id'] != 'pvz_all')
		) return true;
		$shipping_current = array();
		foreach ($view->shippings as $shipping)
		{
			if ($shipping->type != $this->_name || !$shipping->selected) continue;
			$shipping_current = $shipping;
		}
		if (empty($shipping_current)) return true;
		$tarif_id = 0;
		if (!empty($view->cart->params['tarif_id'])) $tarif_id = $view->cart->params['tarif_id'];

		$shipping_current->selected_tarif = $tarif_id;
		if (empty($this->_b2cpl_shippings))
		{
			$this->getB2cplShippings($view->cart);
		}
		$shipping_current->pickups = array();
		foreach ($this->_b2cpl_shippings as $b2CplShipping)
		{
			if ($b2CplShipping->Наименование != 'пвз') continue;
			$pickup                   = new stdClass();
			$pickup->id               = trim($b2CplShipping->Код);
			$pickup->title            = '<small>' . $b2CplShipping->Адрес . '</small>';
			$pickup->introcontent     = $b2CplShipping->Описание;
			$pickup->shipping_sum     = $b2CplShipping->Стоимость;
			$pickup->shipping_sum_val = KSMPrice::showPriceWithTransform($pickup->shipping_sum);
			$pickup->selected         = ($shipping_current->selected_tarif === trim($b2CplShipping->Код));
			if ($pickup->selected) $this->_tarif = $pickup;
			$shipping_current->pickups[] = $pickup;
		}
		$html_temp = KSSystem::loadPluginTemplate($this->_name, $this->_type, $shipping_current, 'pickups');
		$html      = $html_temp . $html;
	}

	public function onAfterExecuteKSMCartSetModelFields($model, $cart = null)
	{
		$jinput   = JFactory::getApplication()->input;
		$tarif_id = $jinput->getString('tarif_id');
		if ($tarif_id === null) return true;
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('params')->from('#__ksenmart_orders')->where('id=' . (int) $model->order_id);
		$db->setQuery($query);
		$params = $db->loadResult();
		if (!empty($params)) $params = json_decode($params);
		if (!is_array($params)) $params = array();
		$params['tarif_id'] = $tarif_id;

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
		if (isset($cart->params['tarif_id']))
		{
			if ($cart->params['tarif_id'] == 'pvz_all' || strpos($cart->params['tarif_id'], 'пвз') !== false) $this->_pvz_flag = true;
			if (empty($this->_b2cpl_shippings))
			{
				$this->getB2cplShippings($cart);
			}
			foreach ($this->_b2cpl_shippings as $delivery)
			{
				if ($cart->params['tarif_id'] != trim($delivery->Код)) continue;
				$cart->shipping_sum     = (float) $delivery->Стоимость;
				$cart->shipping_sum_val = KSMPrice::showPriceWithTransform($cart->shipping_sum);
				$cart->total_sum        += $cart->shipping_sum;
				$cart->total_sum_val    = KSMPrice::showPriceWithTransform($cart->total_sum);

				break;
			}
		}

		return;
	}

	public function onAfterExecuteKSMOrdersGetorder($model, &$order = null)
	{
		$order->tarif_id = 0;
		if (!empty($order->params['tarif_id'])) $order->tarif_id = $order->params['tarif_id'];

		$this->onAfterExecuteHelperKSMOrdersGetOrder($order);
	}

	public function onAfterExecuteHelperKSMOrdersGetOrder($order = null)
	{
		if (empty($order)) return;
		if (empty($order->shipping_id)) return;
		if (empty($order->tarif_id)) return;

		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('id,params,regions')->from('#__ksenmart_shippings')->where('id=' . $order->shipping_id)->where('type=' . $db->quote($this->_name))->where('published=1');
		$db->setQuery($query);
		$shipping = $db->loadObject();

		if (empty($shipping)) return;
		if (empty($order->region_id)) return;
		if (!$this->checkRegion($shipping->regions, $order->region_id)) return;

		$temporder                     = clone $order;
		$temporder->items              = KSMOrders::getOrderItems($order->id);
		$temporder->address_fields_raw = json_decode(json_encode($order->address_fields));
		if (empty($this->_b2cpl_shippings))
		{
			$this->getB2cplShippings($temporder);
		}
		unset($temporder);

		$order->costs['shipping_cost'] = 0;
		if (!empty($this->_b2cpl_shippings))
		{
			foreach ($this->_b2cpl_shippings as $b2CplShipping)
			{
				if (trim($b2CplShipping->Код != $order->tarif_id)) continue;
				$order->costs['shipping_cost'] = (float) $b2CplShipping->Стоимость;
				$this->_tarif                  = $b2CplShipping;
			}
		}
		$order->costs['shipping_cost_val'] = KSMPrice::showPriceWithTransform($order->costs['shipping_cost']);
		$order->costs['total_cost']        += $order->costs['shipping_cost'];
		$order->costs['total_cost_val']    = KSMPrice::showPriceWithTransform($order->costs['total_cost']);

		return;
	}

	function onAfterGetKSMFormOrder($form, $instance)
	{
		if (empty($this->_tarif)) return;

		JKSForm::addFieldPath(JPATH_ROOT . '/plugins/' . $this->_type . DS . $this->_name . '/assets/fields');
		$xml     = '
			<field
				name="tarif_id"
				type="tarif"
				default=""
				label="KSM_SHIPPING_B2CPL_TARIF_FIELD_LBL"
				description ="KSM_SHIPPING_B2CPL_TARIF_FIELD_DESC"
				labelclass="inputname"
			/>
		';
		$element = new JXMLElement($xml);
		$instance->setField($element);
	}

	function onAfterGetKSMFormInputOrderShipping_id($form, &$field_name, &$html)
	{
		if (empty($this->_tarif)) return;

		$form->tarif = $this->_tarif;
		$html        .= '</div>';
		$html        .= '</div>';
		$html        .= '<div class="row">';
		$html        .= $form->getLabel('tarif_id');
		$html        .= '<div class="inputname">';
		$html        .= $form->getInput('tarif_id');
	}

	public function generateCSV($ids)
	{
		$header = array(
			JText::_('ksm_kmshipping_b2cpl_invoice_id'), // Обновляется раз в сутки
			JText::_('ksm_kmshipping_b2cpl_parcel_id'), // Номер заказа
			JText::_('ksm_kmshipping_b2cpl_customer_id'), // Уточнить
			JText::_('ksm_kmshipping_b2cpl_order_date'),
			JText::_('ksm_kmshipping_b2cpl_zip'),
			JText::_('ksm_kmshipping_b2cpl_city'),
			JText::_('ksm_kmshipping_b2cpl_address'),
			JText::_('ksm_kmshipping_b2cpl_fio'),
			JText::_('ksm_kmshipping_b2cpl_phone'),
			JText::_('ksm_kmshipping_b2cpl_email'),
			JText::_('ksm_kmshipping_b2cpl_weight'),
			JText::_('ksm_kmshipping_b2cpl_shipping_cost'),
			JText::_('ksm_kmshipping_b2cpl_shipping_full_cost'),
			JText::_('ksm_kmshipping_b2cpl_product_code'),
			JText::_('ksm_kmshipping_b2cpl_product_title'),
			JText::_('ksm_kmshipping_b2cpl_product_count'),
			JText::_('ksm_kmshipping_b2cpl_product_price'),
			JText::_('ksm_kmshipping_b2cpl_product_price_payment'),
			JText::_('ksm_kmshipping_b2cpl_product_weight'),
			JText::_('ksm_kmshipping_b2cpl_shipping_type'),
			JText::_('ksm_kmshipping_b2cpl_shipping_avia'),
			JText::_('ksm_kmshipping_b2cpl_product_fragile'),
			JText::_('ksm_kmshipping_b2cpl_parcel_cost'),
			JText::_('ksm_kmshipping_b2cpl_code'),
			JText::_('ksm_kmshipping_b2cpl_shipping_terms'),
			JText::_('ksm_kmshipping_b2cpl_package'),
			JText::_('ksm_kmshipping_b2cpl_sender'),
			JText::_('ksm_kmshipping_b2cpl_shipping_date'),
			JText::_('ksm_kmshipping_b2cpl_shipping_interval'),
			JText::_('ksm_kmshipping_b2cpl_comments'),
			JText::_('ksm_kmshipping_b2cpl_partial_failure'),
			JText::_('ksm_kmshipping_b2cpl_promotion_code')
		);

		$f = fopen(JPATH_ROOT . '/administrator/components/com_ksenmart/tmp/orders.csv', 'w');
		fputcsv($f, $header, ';');

		foreach ($ids as $id)
		{
			$order = KSMOrders::getOrder($id, true);
			if (empty($order)) continue;
			$products_info = array();
			$all_weight    = 0;
			foreach ($order->items as $item)
			{
				if (empty($item->product->weight) && $item->product->parent_id) $item->product->weight = $item->product->parent->weight;
				$item->product->weight = trim(str_replace(',', '.', $item->product->weight));
				$all_weight            += $item->product->weight * $item->count;
				$product_info          = array(
					'product_code' => $item->product->product_code,
					'title'        => $item->product->title,
					'count'        => $item->count,
					'price'        => $item->product->price,
					'weight'       => $item->product->weight * $item->count,
				);
				$products_info[]       = $product_info;
			}

			if (empty($order->customer_fields->first_name)) $order->customer_fields->first_name = '';
			if (empty($order->customer_fields->middle_name)) $order->customer_fields->middle_name = '';
			if (empty($order->customer_fields->last_name)) $order->customer_fields->last_name = '';
			$tarif_id = !empty($order->params['tarif_id']) ? $order->params['tarif_id'] : '';
			foreach ($products_info as $key => $item)
			{
				$invoice_id = date('dmY');
				$user_id    = $order->user_id ? $order->user_id : '';
				$date       = date('d.m.Y', strtotime($order->date_add));
				$arr        = array(
					!$key ? $invoice_id : '',
					$order->id,
					!$key ? $user_id : '',
					!$key ? $date : '',
					!$key ? $order->address_fields_raw->zip : '',
					!$key ? $order->address_fields_raw->city : '',
					!$key ? $order->address_fields : '',
					!$key ? $order->customer_fields->last_name . ' ' . $order->customer_fields->first_name . ' ' . $order->customer_fields->middle_name : '',
					!$key ? $order->customer_fields->phone : '',
					!$key ? $order->customer_fields->email : '',
					!$key ? $all_weight : '',
					!$key ? 0 : '',
					!$key ? 0 : '',
					$item['product_code'],
					$item['title'],
					$item['count'],
					$item['price'],
					$item['price'],
					$item['weight'],
					$tarif_id,
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					''
				);
				fputcsv($f, $arr, ';');
			}
		}
		fclose($f);
		//http://is.b2cpl.ru/portal/client_api.ashx?client=test&key=test&func=upload&file=http://site.ru/neworders.csv&report=0&stickers=1

		$shippings = $this->getShippings();
		foreach ($shippings as $shipping)
		{
			$post_data           = array();
			$post_data['client'] = $shipping->params['client'];
			$post_data['key']    = $shipping->params['key'];
			$post_data['func']   = 'upload';
			$post_data['file']   = 'http://scarpa.ldmco.ru/administrator/components/com_ksenmart/tmp/orders.csv';
		}

		if (empty($post_data)) return;
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, "http://is.b2cpl.ru/portal/client_api.ashx?" . http_build_query($post_data));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			"Content-Type: application/x-www-form-urlencoded"
		));

		$response = curl_exec($ch);
		$response = mb_convert_encoding($response, 'utf-8', 'cp-1251');
		$response = json_decode($response);

		JFactory::getApplication()->close();
	}

	function getB2cplShippings($cart = null)
	{
		if (!empty($this->_b2cpl_shippings)) return $this->_b2cpl_shippings;
		if (empty($cart)) return array();
		if (empty($cart->address_fields_raw->zip)) return array();

		$weight_sum = 0;
		$deliveries = array();
		foreach ($cart->items as $item)
		{
			$weight_sum += $item->product->weight * $item->count;
		}

		$shippings = $this->getShippings();
		foreach ($shippings as $shipping)
		{
			$post_data           = array();
			$post_data['client'] = $shipping->params['client'];
			$post_data['key']    = $shipping->params['key'];
			$post_data['func']   = 'tarif';
			$post_data['zip']    = $cart->address_fields_raw->zip;
			$post_data['weight'] = $weight_sum * 1000;
			$post_data['region'] = 77;
			$deliveries          = $this->getDeliveries($post_data);
			$this->_shipping_id  = $shipping->id;
		}

		//http://is.b2cpl.ru/portal/client_api.ashx?client=test&key=test&func=tarif&zip=190000&weight=1001&x=121&y=1&z=1&type=+post&price=1000&price_assess=1000&region=77&allpost=1

		return $deliveries;
	}

	private function getDeliveries($post_data = null)
	{
		if (empty($post_data)) return array();
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, "http://is.b2cpl.ru/portal/client_api.ashx?" . http_build_query($post_data));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			"Content-Type: application/x-www-form-urlencoded"
		));

		$response = curl_exec($ch);
		$response = mb_convert_encoding($response, 'utf-8', 'cp-1251');
		$response = json_decode($response);
		curl_close($ch);
		if (!empty($response) && $response->flag_delivery) $this->_b2cpl_shippings = $response->delivery_ways;

		return $response->delivery_ways;

	}

	private function getShippings()
	{
		if ($this->_shippings === null)
		{
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('*')->from('#__ksenmart_shippings')->where('type=' . $db->q($this->_name));
			$db->setQuery($query);
			$this->_shippings = $db->loadObjectList();

			foreach ($this->_shippings as &$shipping)
			{
				$shipping->regions = json_decode($shipping->regions, true);
				$shipping->params  = json_decode($shipping->params, true);
			}
			unset($shipping);
		}

		return $this->_shippings;
	}

	public function onAfterExecuteKSMCartCloseOrder($model)
	{
		if (empty($model->order_id)) return;

		$this->generateCSV(array($model->order_id));
	}

	function onBeforeViewKSMCatalog($view)
	{
		$app    = JFactory::getApplication();
		$jinput = $app->input;
		$plugin = $jinput->get('plugin', null);
		if ($plugin != 'testapi') return;

		$this->generateCSV(array(35));
	}
}