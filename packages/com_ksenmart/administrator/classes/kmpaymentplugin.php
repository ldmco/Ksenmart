<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

if (!class_exists ('KMPlugin')) {
	require(JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_ksenmart'.DS.'classes'.DS.'kmplugin.php');
}

abstract class KMPaymentPlugin extends KMPlugin {

	function __construct (& $subject, $config) {
		parent::__construct ($subject, $config);
	}

	public function onAfterDisplayKSMcartdefault_congratulation_message($view, $tpl = null, &$html)
	{
		if (empty($view->cart) || empty($view->cart->payment_id))
		{
			return;
		}

		$params = JComponentHelper::getParams('com_ksenmart');

		if ($params->get('payment_after_confirmation', 0) && $view->cart->status_id != 6)
		{
			return;
		}

		$html .= $this->getPaymentContent($view);
	}

	public function onAfterExecuteHelperKSMOrdersGetOrder($order = null)
	{
		if (empty($order))
		{
			return;
		}

		$view = (object) [
			'cart' => $order,
		];

		$params = JComponentHelper::getParams('com_ksenmart');

		if (!in_array($order->status_id, [1, 6])
			|| ($params->get('payment_after_confirmation', 0) && $order->status_id != 6))
		{
			return;
		}

		$order->payment_content = $this->getPaymentContent($view);
	}

	public function getPaymentContent($view)
	{
		return '';
	}
	
}