<?php defined('_JEXEC') or die('Restricted access');

if (!class_exists('KMPaymentPlugin')) {
	require (JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_ksenmart' . DS . 'classes' . DS . 'kmpaymentplugin.php');
}

class plgKMPaymentRobokassa extends KMPaymentPlugin {
	
	var $_params = array(
		'merchant' => '',
		'password1' => '',
		'password2' => ''
	);
	
	function __construct(&$subject, $config) {
		parent::__construct($subject, $config);
	}
	
	function onDisplayParamsForm($name = '', $params = null) {
		if ($name != $this->_name) 
		return;
		if (empty($params)) $params = $this->_params;
		$html = '';
		$html.= '<div class="set">';
		$html.= '	<h3 class="headname">' . JText::_('ksm_payment_algorithm') . '</h3>';
		$html.= '	<div class="row">';
		$html.= '		<label class="inputname">' . JText::_('ksm_payment_robokassa_merchant') . '</label>';
		$html.= '		<input type="text" style="width:250px;" class="inputbox" name="jform[params][merchant]" value="' . $params['merchant'] . '">';
		$html.= '	</div>';
		$html.= '	<div class="row">';
		$html.= '		<label class="inputname">' . JText::_('ksm_payment_robokassa_password_1') . '</label>';
		$html.= '		<input type="text" style="width:250px;" class="inputbox" name="jform[params][password1]" value="' . $params['password1'] . '">';
		$html.= '	</div>';
		$html.= '	<div class="row">';
		$html.= '		<label class="inputname">' . JText::_('ksm_payment_robokassa_password_2') . '</label>';
		$html.= '		<input type="text" style="width:250px;" class="inputbox" name="jform[params][password2]" value="' . $params['password2'] . '">';
		$html.= '	</div>';
		$html.= '</div>';
		
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
		$html.= '<center>';
		$html.= '	<form action="https://auth.robokassa.ru/Merchant/Index.aspx" method="post" class="payment_form">';
		$html.= '		<input type="hidden" name="MrchLogin" value="' . $payment->params['merchant'] . '">';
		$html.= '		<input type="hidden" name="OutSum" value="' . $view->order->costs['total_cost'] . '">';
		$html.= '		<input type="hidden" name="InvId" value="' . $view->order->id . '">';
		$html.= '		<input type="hidden" name="InvDesc" value="' . JText::sprintf('ksm_payment_robokassa_desc', $view->order->id) . '">';
		$html.= '		<input type="hidden" name="SignatureValue" value="' . md5($payment->params['merchant'] . ':' . $view->order->costs['total_cost'] . ':' . $view->order->id . ':' . $payment->params['password1']) . '">';
		$html.= '		<input type="hidden" name="IncCurrLabel" value="WMR">';
		$html.= '		<input type="hidden" name="Culture" value="ru">';
		$html.= '		<input type="submit" value="' . JText::_('ksm_payment_robokassa_pay') . '" class="button btn-success btn-large noTransition">';
		$html.= '	</form>';
		$html.= '</center>';
	}
	
	function onPayOrder() {
		$InvId = JRequest::getVar('InvId', null);
		$OutSum = JRequest::getVar('OutSum', null);
		$SignatureValue = JRequest::getVar('SignatureValue', null);
		
		if (empty($InvId) || empty($OutSum) || empty($SignatureValue)) 
		return;
		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('*')->from('#__ksenmart_orders')->where('id=' . $InvId);
		$db->setQuery($query);
		$order = $db->loadObject();
		
		if (empty($order)) 
		return;
		if (empty($order->payment_id)) 
		return;
		
		$query = $db->getQuery(true);
		$query->select('id,params,regions')->from('#__ksenmart_payments')->where('id=' . $order->payment_id)->where('type=' . $db->quote($this->_name))->where('published=1');
		$db->setQuery($query);
		$payment = $db->loadObject();
		if (empty($payment)) 
		return;
		if (empty($order->region_id)) 
		return;
		if (!$this->checkRegion($payment->regions, $order->region_id)) 
		return;
		$payment->params = json_decode($payment->params, true);
		if (md5($OutSum . ':' . $InvId . ':' . $payment->params['password2']) == strtolower($SignatureValue)) {
			$query = $db->getQuery(true);
			$query->update('#__ksenmart_orders')->set('status_id=5')->where('id=' . $InvId);
			$db->setQuery($query);
			$db->query();
			echo 'OK' . $InvId;
		}
		
		return true;
	}
}