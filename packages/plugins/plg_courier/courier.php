<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

if (!class_exists('KMPaymentPlugin')) {
	require (JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_ksenmart' . DS . 'classes' . DS . 'kmpaymentplugin.php');
}

class plgKMPaymentCourier extends KMPaymentPlugin {
	
	var $_params = array();
	
	function __construct(&$subject, $config) {
		parent::__construct($subject, $config);
	}
	
	function onDisplayParamsForm($name = '', $params = null) {
		if ($name != $this->_name) 
		return;
		if (empty($params)) $params = $this->_params;
		$html = '';
		
		
		return $html;
	}
	
	function onAfterDisplayKSMCartDefault_congratulation($view, &$tpl = null, &$html) {
		if (empty($view->order)) 
		return;
		if (empty($view->order->payment_id)) 
		return;
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('id,params,regions')->from('#__ksenmart_payments')->where('id=' . $view->order->payment_id)->where('type=' . $db->quote($this->_name))->where('published=1');
		$db->setQuery($query);
		$payment = $db->loadObject();
		if (empty($payment)) 
		return;
		if (empty($view->order->region_id)) 
		return;
		if (!$this->checkRegion($payment->regions, $view->order->region_id)) 
		return;
		$payment->params = json_decode($payment->params, true);
	}
}