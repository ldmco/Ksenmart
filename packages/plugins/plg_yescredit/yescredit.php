<?php defined('_JEXEC') or die('Restricted access');

if (!class_exists('KMPaymentPlugin')) {
	require (JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_ksenmart' . DS . 'classes' . DS . 'kmpaymentplugin.php');
}

class plgKMPaymentYescredit extends KMPaymentPlugin {
	
	var $_params = array(
		'merchant' => '',
		'underprice' => '5',
		'min_orderprice' => '',
		'activated' => 0,
		'company_name' => '',
		'company_ogrn' => '',
		'company_head' => '',
		'company_legal_address' => '',
		'company_post_address' => '',
		'company_inn' => '',
		'company_kpp' => '',
		'company_bank_account' => '',
		'company_bik' => '',
		'company_email' => '',
		'company_phone' => ''
	);
	
	function __construct(&$subject, $config) {
		parent::__construct($subject, $config);
	}
	
	function onDisplayParamsForm($name = '', $params = null) {
		if ($name != $this->_name) 
		return;
		if (empty($params)) $params = $this->_params;
		$currency_code = $this->getDefaultCurrencyCode();
		$html = '';
		
		$html.= '<div class="set ksm_payment_yescredit_algorithm" style="' . (!$params['activated'] ? 'display:none;' : '') . '">';
		$html.= '	<h3 class="headname">' . JText::_('ksm_payment_algorithm') . '</h3>';
		$html.= '	<div class="row">';
		$html.= '		<label class="inputname">' . JText::_('ksm_payment_yescredit_merchant') . '</label>';
		$html.= '		<input type="text" class="inputbox" name="jform[params][merchant]" value="' . $params['merchant'] . '">';
		$html.= '	</div>';
		$html.= '	<div class="row">';
		$html.= '		<label class="inputname">' . JText::_('ksm_payment_yescredit_underprice') . '</label>';
		$html.= '		<input type="text" class="inputbox" name="jform[params][underprice]" value="' . $params['underprice'] . '">';
		$html.= '		<span class="two">' . JText::_('ksm_payment_yescredit_underprice_percent') . '</span>';
		$html.= '	</div>';
		$html.= '	<div class="row">';
		$html.= '		<label class="inputname">' . JText::_('ksm_payment_yescredit_min_orderprice') . '</label>';
		$html.= '		<input type="text" class="inputbox" name="jform[params][min_orderprice]" value="' . $params['min_orderprice'] . '">';
		$html.= '		<span class="two">' . $currency_code . '</span>';
		$html.= '	</div>';
		$html.= '	<input type="hidden" name="jform[params][activated]" value="' . $params['activated'] . '">';
		$html.= '</div>';
		$html.= '<div class="set ksm_payment_yescredit_request" style="' . ($params['activated'] ? 'display:none;' : '') . '">';
		$html.= '	<h3 class="headname">' . JText::_('ksm_payment_yescredit_request') . '</h3>';
		$html.= '	<div class="row">';
		$html.= '		<label class="inputname">' . JText::_('ksm_payment_yescredit_company_name') . '</label>';
		$html.= '		<input type="text" class="inputbox width360px" name="jform[params][company_name]" value="' . $params['company_name'] . '">';
		$html.= '	</div>';
		$html.= '	<div class="row">';
		$html.= '		<label class="inputname">' . JText::_('ksm_payment_yescredit_company_ogrn') . '</label>';
		$html.= '		<input type="text" class="inputbox width360px" name="jform[params][company_ogrn]" value="' . $params['company_ogrn'] . '">';
		$html.= '	</div>';
		$html.= '	<div class="row">';
		$html.= '		<label class="inputname">' . JText::_('ksm_payment_yescredit_company_head') . '</label>';
		$html.= '		<input type="text" class="inputbox width360px" name="jform[params][company_head]" value="' . $params['company_head'] . '">';
		$html.= '	</div>';
		$html.= '	<div class="row">';
		$html.= '		<label class="inputname">' . JText::_('ksm_payment_yescredit_company_legal_address') . '</label>';
		$html.= '		<input type="text" class="inputbox width360px" name="jform[params][company_legal_address]" value="' . $params['company_legal_address'] . '">';
		$html.= '	</div>';
		$html.= '	<div class="row">';
		$html.= '		<label class="inputname">' . JText::_('ksm_payment_yescredit_company_post_address') . '</label>';
		$html.= '		<input type="text" class="inputbox width360px" name="jform[params][company_post_address]" value="' . $params['company_post_address'] . '">';
		$html.= '	</div>';
		$html.= '	<div class="row">';
		$html.= '		<label class="inputname">' . JText::_('ksm_payment_yescredit_company_inn') . '</label>';
		$html.= '		<input type="text" class="inputbox width360px" name="jform[params][company_inn]" value="' . $params['company_inn'] . '">';
		$html.= '	</div>';
		$html.= '	<div class="row">';
		$html.= '		<label class="inputname">' . JText::_('ksm_payment_yescredit_company_kpp') . '</label>';
		$html.= '		<input type="text" class="inputbox width360px" name="jform[params][company_kpp]" value="' . $params['company_kpp'] . '">';
		$html.= '	</div>';
		$html.= '	<div class="row">';
		$html.= '		<label class="inputname">' . JText::_('ksm_payment_yescredit_company_bank_account') . '</label>';
		$html.= '		<input type="text" class="inputbox width360px" name="jform[params][company_bank_account]" value="' . $params['company_bank_account'] . '">';
		$html.= '	</div>';
		$html.= '	<div class="row">';
		$html.= '		<label class="inputname">' . JText::_('ksm_payment_yescredit_company_bik') . '</label>';
		$html.= '		<input type="text" class="inputbox width360px" name="jform[params][company_bik]" value="' . $params['company_bik'] . '">';
		$html.= '	</div>';
		$html.= '	<div class="row">';
		$html.= '		<label class="inputname">' . JText::_('ksm_payment_yescredit_company_email') . '</label>';
		$html.= '		<input type="text" class="inputbox width360px" name="jform[params][company_email]" value="' . $params['company_email'] . '">';
		$html.= '	</div>';
		$html.= '	<div class="row">';
		$html.= '		<label class="inputname">' . JText::_('ksm_payment_yescredit_company_phone') . '</label>';
		$html.= '		<input type="text" class="inputbox width360px" name="jform[params][company_phone]" value="' . $params['company_phone'] . '">';
		$html.= '	</div>';
		$html.= '	<div class="row">';
		$html.= '		<a href="#" class="send-request">' . JText::_('ksm_payment_yescredit_send_request') . '</a>';
		$html.= '		<a href="#" class="activate-payment">' . JText::_('ksm_payment_yescredit_activate_payment') . '</a>';
		$html.= '	</div>';
		$html.= '</div>';
		$html.= '<input type="hidden" name="jform[params][activated]" value="' . $params['activated'] . '">';
		$html.= '
		<script>
		jQuery("body").on("click",".send-request",function(){
			var data={};
			data["company_name"]=jQuery("input[name=\'jform[params][company_name]\']").val();
			data["company_ogrn"]=jQuery("input[name=\'jform[params][company_ogrn]\']").val();
			data["company_head"]=jQuery("input[name=\'jform[params][company_head]\']").val();
			data["company_legal_address"]=jQuery("input[name=\'jform[params][company_legal_address]\']").val();
			data["company_post_address"]=jQuery("input[name=\'jform[params][company_post_address]\']").val();
			data["company_inn"]=jQuery("input[name=\'jform[params][company_inn]\']").val();
			data["company_kpp"]=jQuery("input[name=\'jform[params][company_kpp]\']").val();
			data["company_bank_account"]=jQuery("input[name=\'jform[params][company_bank_account]\']").val();
			data["company_bik"]=jQuery("input[name=\'jform[params][company_bik]\']").val();
			data["company_email"]=jQuery("input[name=\'jform[params][company_email]\']").val();
			data["company_phone"]=jQuery("input[name=\'jform[params][company_phone]\']").val();
			
			if (data["company_name"]=="")
			{
				KMShowMessage("' . JText::_('ksm_payment_yescredit_print_company_name') . '");
				return false;
			}
			if (data["company_head"]=="")
			{
				KMShowMessage("' . JText::_('ksm_payment_yescredit_print_company_head') . '");
				return false;
			}
			if (data["company_email"]=="")
			{
				KMShowMessage("' . JText::_('ksm_payment_yescredit_print_company_email') . '");
				return false;
			}	
			
			jQuery.ajax({
				url:"' . JURI::root() . 'plugins/kmpayment/yescredit/ajax.php?task=send_request",
				type:"post",
				data:data,
				dataType:"json",
				success:function(responce){
					KMShowMessage(responce.message);
				}
			});			
			
			return false;
		});
		jQuery("body").on("click",".activate-payment",function(){
			jQuery(".ksm_payment_yescredit_request").hide();
			jQuery(".ksm_payment_yescredit_algorithm").show();
			jQuery("input[name=\'jform[params][activated]\']").val("1");
			
			return false;
		});		
		</script>

		<style>
		a.send-request {
			float: left;
			padding: 11px 20px;
			font-size: 14px;
			background: #525252;
			text-decoration: none;
			color: white!important;
			height: 18px;
		}
		a.activate-payment {
			color: #1d86ba;
			font-size: 15px;
			float: left;
			margin: 10px 0px 0 20px;
			text-decoration: none;
			border-bottom: 1px dashed #1d86ba;
			height: 18px;
		}			
		</style>
		';
		
		return $html;
	}
	
	function onAfterDisplayShopopencartDefault_congratulation($view, $tpl = null, $html) {
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
		
		if (isset($payment->params['merchant']) && !empty($payment->params['merchant'])) {
			$document = JFactory::getDocument();
			$document->addScript('http://yes-credit.su/crem/js/jquery-1.8.0.min.js', 'text/javascript', true);
			$document->addScript('http://yes-credit.su/crem/js/jquery-ui-1.8.23.custom.min.js', 'text/javascript', true);
			$document->addScript('http://yes-credit.su/crem/js/crem.js', 'text/javascript', true);
			$document->addStyleSheet('http://yes-credit.su/crem/css/blizter.css');
			
			$yescredit_onlick = '';
			$yescredit_onlick.= 'yescreditmodul([';
			$i = 0;
			$order_items = KSMOrders::getOrderItems($view->order->id);
			
			foreach ($order_items as $item) {
				$i++;
				$yescredit_onlick.= '{MODEL: "' . str_replace("&", "-", $item->product->title) . '", COUNT:"' . $item->count . '", PRICE:"' . ($item->price * $item->count) . '"}';
				$yescredit_onlick.= $i < count($order_items) ? ',' : '';
			}
			$yescredit_onlick.= '],' . $payment->params['merchant'] . ',"' . $view->order->id . '")';
			$view->yescredit_onlick = $yescredit_onlick;
			$html.= KSSystem::loadPluginTemplate($this->_name, $this->_type, $view, 'payment_form');
		}
		
		return true;
	}
	
	function onBeforeViewShopopencart($view) {
		$document = JFactory::getDocument();
		$db = JFactory::getDBO();
		$script = 'var yescredit_payment_min_orderprice = {};';
		
		$document->addScript(JURI::base() . 'plugins/' . $this->_type . '/' . $this->_name . '/assets/js/style.js');
		$query = $db->getQuery(true);
		$query->select('id,params,regions')->from('#__ksenmart_payments')->where('type=' . $db->quote($this->_name))->where('published=1');
		$db->setQuery($query);
		$payments = $db->loadObjectList();
		
		foreach ($payments as $payment) {
			$payment->params = json_decode($payment->params, true);
			$script.= 'yescredit_payment_min_orderprice[' . $payment->id . '] = {};';
			$script.= 'yescredit_payment_min_orderprice[' . $payment->id . '][\'price\'] = ' . (int)$payment->params['min_orderprice'] . ';';
			$script.= 'yescredit_payment_min_orderprice[' . $payment->id . '][\'message\'] = \'' . JText::sprintf('ksm_payment_yescredit_min_orderprice_message', KSMPrice::showPriceWithTransform((int)$payment->params['min_orderprice'])) . '\';';
		}
		$document->addScriptDeclaration($script);
		
		return true;
	}
	
	function onBeforeDisplayShopopencartDefault_total($view, $tpl = null, $html) {
		if (empty($view->cart)) 
		return;
		
		$payment_id = $view->state->get('payment_id');
		$region_id = $view->state->get('region_id');
		if (empty($payment_id)) 
		return;
		if (empty($region_id)) 
		return;
		
		$db = JFactory::getDBO();
		
		$query = $db->getQuery(true);
		$query->select('id,params,regions')->from('#__ksenmart_payments')->where('id=' . $payment_id)->where('type=' . $db->quote($this->_name))->where('published=1');
		$db->setQuery($query);
		$payment = $db->loadObject();
		if (empty($payment)) 
		return;
		if (!$this->checkRegion($payment->regions, $region_id)) 
		return;
		
		$payment->params = json_decode($payment->params, true);
		$view->cart->total_sum_val = KSMPrice::showPriceWithTransform($view->cart->total_sum * (100 + $payment->params['underprice']) / 100);
		
		return true;
	}
}
