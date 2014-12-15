<?php defined('_JEXEC') or die('Restricted access');

if (!class_exists('KMPaymentPlugin')) {
    require (JPATH_ROOT . DIRECTORY_SEPARATOR . 'administrator' . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_ksenmart' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'kmpaymentplugin.php');
}

class plgKMPaymentWalletone extends KMPaymentPlugin {
    
    private $_params = array('merchant_id' => null, 'secretKey' => null);
    private $_fields = [];
    
    public function __construct(&$subject, $config = array()) {
        parent::__construct($subject, $config);
        $this->loadLanguage('plg_kmpayment_walletone.sys');
    }
    
    public function onDisplayParamsForm($name = null, $params = null) {
        if ($name != $this->_name) {
            return;
        }
        if (empty($params)) {
            $params = $this->_params;
        }
        
        return KSSystem::loadPluginTemplate($this->_name, $this->_type, $params, 'default_settings');
    }
    
    public function onAfterDisplayKSMCartDefault_congratulation($view, $tpl = null, &$html) {
        if (empty($view->order)) return;
        if (empty($view->order->payment_id)) return;
        
        $payment = KSMWalletone::getPayment($view->order->payment_id, $this->_name);
        if (empty($payment)) return;
        if (empty($view->order->region_id)) return;
        if (!$this->checkRegion($payment->regions, $view->order->region_id)) return;
        
        $view->payment_params = $payment->params = json_decode($payment->params, true);
        
        $view->payment_form_params = new stdClass();
        $view->payment_form_params->title = 'Оплата заказа №' . $view->order->id . ' на сайте ' . JFactory::getConfig()->get('sitename');
        
        $view->user = KSUsers::getUser();
        KSMWalletone::_setFields([
        	'WMI_MERCHANT_ID' => $payment->params['merchant_id'], 
        	'WMI_PAYMENT_AMOUNT' => $view->order->cost, 
        	'WMI_PAYMENT_NO' => $view->order->id, 
        	'WMI_CURRENCY_ID' => 643, 
        	'WMI_DESCRIPTION' => $view->payment_form_params->title, 
        	'WMI_CUSTOMER_FIRSTNAME' => $view->order->customer_fields->first_name,
        	'WMI_CUSTOMER_LASTNAME' => $view->order->customer_fields->last_name,
        	'WMI_CUSTOMER_EMAIL' => $view->order->customer_fields->email,
        	'WMI_FAIL_URL' => JRoute::_(JURI::base() . 'index.php?option=com_ksenmart&view=cart&layout=pay_error'),
        	'WMI_SUCCESS_URL' => JRoute::_(JURI::base() . 'index.php?option=com_ksenmart&view=cart&layout=pay_success'),
        ]);
        $view->payment_form_params->sign = KSMWalletone::getHash($payment->params['secretKey']);
        
        $html.= KSSystem::loadPluginTemplate($this->_name, $this->_type, $view, 'default_paymentform');
        return true;
    }
    
    public function onPayOrder() {
        $app   = JFactory::getApplication();
        $input = $app->input;
        
        $postData    = $input->getArray($_POST);
        
        $merchant_id = $input->get('WMI_MERCHANT_ID', null, 'string');
        $cost        = $input->get('WMI_PAYMENT_AMOUNT', 0, 'float');
        $orderId     = $input->get('WMI_PAYMENT_NO', 0, 'int');

        $state       = $input->get('WMI_ORDER_STATE', null, 'string');
        $hash        = $input->get('WMI_SIGNATURE', null, 'string');

        if (!empty($orderId) && !empty($cost) && !empty($merchant_id) && strtoupper($state) == 'ACCEPTED') {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->select('
        		o.id,
        		o.payment_id,
        		o.region_id,
        		o.cost,
        		o.customer_fields
    		')->from('#__ksenmart_orders AS o')->where('o.id=' . $db->q($orderId));
            $db->setQuery($query);
            $order = $db->loadObject();
            
            if (empty($order)) return;
            if (empty($order->payment_id)) return;
            
            if ($order->cost == $cost) {
                $payment = KSMWalletone::getPayment($order->payment_id, $this->_name);
                
                if (empty($payment)) return;
                if (empty($order->region_id)) return;
                
                if ($this->checkRegion($payment->regions, $order->region_id)) {

					KSMWalletone::_setFields($postData);
                    $payment->params        = json_decode($payment->params, true);
                    $order->customer_fields = json_decode($order->customer_fields, true);
                    $sign                   = KSMWalletone::getHash($payment->params['secretKey']);

                    if ($sign === $hash && $payment->params['merchant_id'] == $merchant_id) {
                        $this->_setState($orderId, 5);
                		$app->close('WMI_RESULT=OK');
                    }
                }
            }
        }else{
        	$this->_setState($orderId, 2);
        	$app->close('WMI_RESULT=RETRY');
        }
    }
        
    private function _setState($orderId, $state = 2) {
        if ($orderId > 0) {
            JTable::addIncludePath(JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_ksenmart' . DS . 'tables');
            $table = JTable::getInstance('Orders', 'KsenMartTable');
            
            $table->load($orderId);
            $table->set('status_id', $state);
            
            if ($table->check()) {
                if (!$table->store()) {
                    $this->setError($this->_db->getError());
                } else {
                    return true;
                }
            }
        }
        return false;
    }
}