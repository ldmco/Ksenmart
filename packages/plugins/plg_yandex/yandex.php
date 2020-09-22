<?php
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

if (!class_exists('KMPaymentPlugin'))
{
	require(JPATH_ROOT . DIRECTORY_SEPARATOR . 'administrator' . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_ksenmart' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'kmpaymentplugin.php');
}

class plgKMPaymentYandex extends KMPaymentPlugin
{

	var $_params = array(
		'shopId'       => '',
		'scId'         => '',
		'ShopPassword' => '',
	);

	public function __construct(&$subject, $config = array())
	{
		parent::__construct($subject, $config);
	}

	public function onDisplayParamsForm($name = '', $params = null)
	{
		if ($name != $this->_name)
		{
			return;
		}

		if (empty($params))
		{
			$params = $this->_params;
		}

		$view         = new stdClass();
		$view->params = $params;
		$html         = KSSystem::loadPluginTemplate($this->_name, $this->_type, $view, 'params');

		return $html;
	}

	public function getPaymentContent($view)
	{
		if (empty($view->order)) return;
		if (empty($view->order->payment_id)) return;

		$db    = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('id,params,regions')->from('#__ksenmart_payments')->where('id=' . $view->order->payment_id)->where('type=' . $db->quote($this->_name))->where('published=1');
		$db->setQuery($query);
		$payment = $db->loadObject();

		if ($payment->id <= 0) return;
		if (empty($view->order->region_id)) return;
		if (!$this->checkRegion($payment->regions, $view->order->region_id)) return;

		$params = new JRegistry();
		$params->loadString($payment->params);
		$view->payment_params = $params;

		return KSSystem::loadPluginTemplate($this->_name, $this->_type, $view, 'paymentform');
	}

	public function onPayOrder()
	{
		$app   = JFactory::getApplication();
		$input = $app->input;

		$action                  = $input->get('action', null);
		$orderSumAmount          = $input->get('orderSumAmount', null);
		$orderSumCurrencyPaycash = $input->get('orderSumCurrencyPaycash', null);
		$orderSumBankPaycash     = $input->get('orderSumBankPaycash', null);
		$invoiceId               = $input->get('invoiceId', null);
		$customerNumber          = $input->get('customerNumber', null);
		$requestDatetime         = $input->get('requestDatetime', null);
		$hash                    = $input->get('hash', null);

		if (!empty($invoiceId) && !empty($orderSumAmount))
		{
			$db    = JFactory::getDBO();
			$query = $db->getQuery(true);
			$query->select('
                o.id,
                o.payment_id,
                o.region_id,
                o.cost,
                o.customer_fields
            ')->from('#__ksenmart_orders AS o')->where('o.id=' . $db->q($invoiceId));
			$db->setQuery($query);
			$order = $db->loadObject();

			if (empty($order)) return;
			if (empty($order->payment_id)) return;

			$db    = JFactory::getDBO();
			$query = $db->getQuery(true);
			$query->select('id,params,regions')->from('#__ksenmart_payments')->where('id=' . $order->payment_id)->where('type=' . $db->quote($this->_name))->where('published=1');
			$db->setQuery($query);
			$payment = $db->loadObject();

			$payment->params = json_decode($payment->params, true);
			$sign            = md5($action . ';' . $orderSumAmount . ';' . $orderSumCurrencyPaycash . ';' . $orderSumBankPaycash . ';' . $payment->params['shopId'] . ';' . $invoiceId . ';' . $customerNumber . ';' . $payment->params['ShopPassword']);

			if (strtolower($sign) != strtolower($hash))
			{
				$code = 1;
			}
			else
			{
				$code = 0;
				$this->_setState($orderId, 5);
			}

			$response = '';
			$response .= '<?xml version="1.0" encoding="UTF-8"?>';
			$response .= '<paymentAvisoResponse performedDatetime="' . $requestDatetime . '" code="' . $code . '" invoiceId="' . $invoiceId . '" shopId="' . $payment->params['shopId'] . '"/>';

			$app->close($response);
		}
	}

	public function onAjaxYandexCheckOrder()
	{
		$app   = JFactory::getApplication();
		$input = $app->input;

		$action                  = $input->get('action', null);
		$orderSumAmount          = $input->get('orderSumAmount', null);
		$orderSumCurrencyPaycash = $input->get('orderSumCurrencyPaycash', null);
		$orderSumBankPaycash     = $input->get('orderSumBankPaycash', null);
		$invoiceId               = $input->get('invoiceId', null);
		$customerNumber          = $input->get('customerNumber', null);
		$requestDatetime         = $input->get('requestDatetime', null);
		$hash                    = $input->get('hash', null);
		$code                    = 1;

		if (!empty($invoiceId) && !empty($orderSumAmount))
		{
			$db    = JFactory::getDBO();
			$query = $db->getQuery(true);
			$query->select('
                o.id,
                o.payment_id,
                o.region_id,
                o.cost,
                o.customer_fields
            ')->from('#__ksenmart_orders AS o')->where('o.id=' . $db->q($invoiceId));
			$db->setQuery($query);
			$order = $db->loadObject();

			if (empty($order)) return;
			if (empty($order->payment_id)) return;

			$db    = JFactory::getDBO();
			$query = $db->getQuery(true);
			$query->select('id,params,regions')->from('#__ksenmart_payments')->where('id=' . $order->payment_id)->where('type=' . $db->quote($this->_name))->where('published=1');
			$db->setQuery($query);
			$payment = $db->loadObject();

			$payment->params = json_decode($payment->params, true);
			$sign            = md5($action . ';' . $orderSumAmount . ';' . $orderSumCurrencyPaycash . ';' . $orderSumBankPaycash . ';' . $payment->params['shopId'] . ';' . $invoiceId . ';' . $customerNumber . ';' . $payment->params['ShopPassword']);

			if (strtolower($sign) != strtolower($hash))
			{
				$code = 1;
			}
			else
			{
				$code = 0;
			}
		}

		$response = '';
		$response .= '<?xml version="1.0" encoding="UTF-8"?>';
		$response .= '<checkOrderResponse  performedDatetime="' . $requestDatetime . '" code="' . $code . '" invoiceId="' . $invoiceId . '" shopId="' . $payment->params['shopId'] . '"/>';

		$app->close($response);
	}

	private function _setState($orderId, $state = 2)
	{
		if ($orderId > 0)
		{
			KSMOrders::setOrderStatus($orderId, $state);
		}

		return false;
	}

}