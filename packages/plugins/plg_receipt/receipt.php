<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

if (!class_exists('KMPaymentPlugin')) {
	require (JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_ksenmart' . DS . 'classes' . DS . 'kmpaymentplugin.php');
}

class plgKMPaymentReceipt extends KMPaymentPlugin {
	
	var $_params = array(
		'companyname' => '',
		'bank_account_number' => '',
		'inn' => '',
		'kpp' => '',
		'bankname' => '',
		'bank_kor_number' => '',
		'bik' => ''
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
		$html.= '		<label class="inputname">' . JText::_('ksm_payment_receipt_companyname') . '</label>';
		$html.= '		<input type="text" style="width:250px;" class="inputbox" name="jform[params][companyname]" value="' . $params['companyname'] . '">';
		$html.= '	</div>';
		$html.= '	<div class="row">';
		$html.= '		<label class="inputname">' . JText::_('ksm_payment_receipt_bank_account_number') . '</label>';
		$html.= '		<input type="text" style="width:250px;" class="inputbox" name="jform[params][bank_account_number]" value="' . $params['bank_account_number'] . '">';
		$html.= '	</div>';
		$html.= '	<div class="row">';
		$html.= '		<label class="inputname">' . JText::_('ksm_payment_receipt_inn') . '</label>';
		$html.= '		<input type="text" style="width:250px;" class="inputbox" name="jform[params][inn]" value="' . $params['inn'] . '">';
		$html.= '	</div>';
		$html.= '	<div class="row">';
		$html.= '		<label class="inputname">' . JText::_('ksm_payment_receipt_kpp') . '</label>';
		$html.= '		<input type="text" style="width:250px;" class="inputbox" name="jform[params][kpp]" value="' . $params['kpp'] . '">';
		$html.= '	</div>';
		$html.= '	<div class="row">';
		$html.= '		<label class="inputname">' . JText::_('ksm_payment_receipt_bankname') . '</label>';
		$html.= '		<input type="text" style="width:250px;" class="inputbox" name="jform[params][bankname]" value="' . $params['bankname'] . '">';
		$html.= '	</div>';
		$html.= '	<div class="row">';
		$html.= '		<label class="inputname">' . JText::_('ksm_payment_receipt_bank_kor_number') . '</label>';
		$html.= '		<input type="text" style="width:250px;" class="inputbox" name="jform[params][bank_kor_number]" value="' . $params['bank_kor_number'] . '">';
		$html.= '	</div>';
		$html.= '	<div class="row">';
		$html.= '		<label class="inputname">' . JText::_('ksm_payment_receipt_bik') . '</label>';
		$html.= '		<input type="text" style="width:250px;" class="inputbox" name="jform[params][bik]" value="' . $params['bik'] . '">';
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
		$html.= '<br>';
		$html.= '<br>';
		$html.= '<center>';
		$html.= '	<h4 align="center">' . JText::_('ksm_payment_receipt_details') . '</h4>';
		$html.= '</center>';
		$html.= '<table width="100%" cellspacing="5" cellpadding="5">';
		$html.= '	<tr>';
		$html.= '		<td width="50%" align="right">' . JText::_('ksm_payment_receipt_companyname') . '</td>';
		$html.= '		<td width="50%" align="left"><b>' . $payment->params['companyname'] . '</b></td>';
		$html.= '	</tr>';
		$html.= '	<tr>';
		$html.= '		<td align="right">' . JText::_('ksm_payment_receipt_bank_account_number') . '</td>';
		$html.= '		<td align="left"><b>' . $payment->params['bank_account_number'] . '</b></td>';
		$html.= '	</tr>';
		$html.= '	<tr>';
		$html.= '		<td align="right">' . JText::_('ksm_payment_receipt_inn') . '</td>';
		$html.= '		<td align="left"><b>' . $payment->params['inn'] . '</b></td>';
		$html.= '	</tr>';
		$html.= '	<tr>';
		$html.= '		<td align="right">' . JText::_('ksm_payment_receipt_kpp') . '</td>';
		$html.= '		<td align="left"><b>' . $payment->params['kpp'] . '</b></td>';
		$html.= '	</tr>';
		$html.= '	<tr>';
		$html.= '		<td align="right">' . JText::_('ksm_payment_receipt_bankname') . '</td>';
		$html.= '		<td align="left"><b>' . $payment->params['bankname'] . '</b></td>';
		$html.= '	</tr>';
		$html.= '	<tr>';
		$html.= '		<td align="right">' . JText::_('ksm_payment_receipt_bank_kor_number') . '</td>';
		$html.= '		<td align="left"><b>' . $payment->params['bank_kor_number'] . '</b></td>';
		$html.= '	</tr>';
		$html.= '	<tr>';
		$html.= '		<td align="right">' . JText::_('ksm_payment_receipt_bik') . '</td>';
		$html.= '		<td align="left"><b>' . $payment->params['bik'] . '</b></td>';
		$html.= '	</tr>';
		$html.= '	<tr>';
		$html.= '		<td align="right">' . JText::_('ksm_payment_receipt_topay') . '</td>';
		$html.= '		<td align="left"><b>' . $view->order->costs['total_cost_val'] . '</b></td>';
		$html.= '	</tr>	';
		$html.= '</table>';
		$html.= '<center>';
		$html.= '	<form action="" method="post" class="payment_form">	';
		$html.= '		<input type="button" value="' . JText::_('ksm_payment_receipt_print_receipt') . '" class="button btn-success btn-large noTransition">';
		$html.= '	</form>	';
		$html.= '</center>';
		$html.= '<script>';
		$html.= '	jQuery(".payment_form .button").click(function(){';
		$html.= '		window.open(URI_ROOT+"index.php?option=com_ksenmart&view=cart&layout=receipt&id=' . $view->order->id . '&tmpl=component","","width=750,height=650,menubar=no,location=no,resizable=yes,scrollbars=no");';
		$html.= '		return false;';
		$html.= '	});';
		$html.= '</script>';
	}
	
	function onAfterExecuteKSMCartGetorderinfo($model, $order = null) {
		if (empty($order)) 
		return;
		if (empty($order->payment_id)) 
		return;
		$db = JFactory::getDBO();
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
		$order->payment = $payment;
		
		
		return true;
	}
}