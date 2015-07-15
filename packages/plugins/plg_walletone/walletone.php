<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

if (!class_exists('KMPaymentPlugin')) {
    require (JPATH_ROOT . DIRECTORY_SEPARATOR . 'administrator' . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_ksenmart' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'kmpaymentplugin.php');
}

class plgKMPaymentWalletone extends KMPaymentPlugin {
    
    private $_params = array('merchant_id' => null, 'secretKey' => null, 'payment_types' => array());
    private $_payment_fields = array(
        'paymentTypesList' => array(
            'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_WALLETONEGROUP' => array(
                'WalletOneRUB' => 'W1 RUB',
                'WalletOneUAH' => 'W1 UAH',
                'WalletOneUSD' => 'W1 USD',
                'WalletOneEUR' => 'W1 EUR',
                'WalletOneZAR' => 'W1 ZAR',
                'WalletOneBYR' => 'W1 BYR',
                'WalletOneTJS' => 'W1 TJS',
            ),
            'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_YANDEXMONEYGROUP' => array(
                'YandexMoneyRUB' => 'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_YANDEXMONEYRUB',
            ),
            'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_WEBMONEYGROUP' => array(
                'WMR' => 'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_WEBMONEYRUB',
                'WMU' => 'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_WEBMONEYWMU',
                'WMZ' => 'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_WEBMONEYWMZ',
            ),
            'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_QIWIGROUP' => array(
                'QiwiWalletRUB' => 'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_QIWIRUB',
            ),
            'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_UKASHGROUP' => array(
                'UkashEUR' => 'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_UKASHRUB',
            ),
            'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_MONEYMAILGROUP' => array(
                'MoneyMailRUB' => 'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_MONEYMAILRUB',
            ),
            'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_ZPAYMENTRUBGROUP' => array(
                'ZPaymentRUB' => 'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_ZPAYMENTRUB',
            ),
            'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_BPAYMDLGROUP' => array(
                'BPayMDL' => 'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_BPAYMDL',
            ),
            'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_CASHUGROUP' => array(
                'CashUUSD' => 'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_CASHUUSD',
            ),
            'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_WEBCREDSGROUP' => array(
                'WebCredsRUB' => 'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_WEBCREDSRUB',
            ),
            'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_EASYPAYSGROUP' => array(
                'EasyPayBYR' => 'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_EASYPAYBYR',
            ),
            'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_LIQPAYMONEYGROUP' => array(
                'LiqPayMoneyRUB' => 'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_LIQPAYMONEYRUB',
                'LiqPayMoneyUAH' => 'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_LIQPAYMONEYUAH',
                'LiqPayMoneyUSD' => 'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_LIQPAYMONEYUSD',
            ),
            'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_MOBILEGROUP' => array(
                'BeelineRUB' => 'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_BEELINERUB',
                'MtsRUB'     => 'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_MTSRUB',
                'MegafonRUB' => 'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_MEGAFONRUB',
            ),
            'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_CASHTERMINALGROUP' => array(
                'CashTerminalRUB' => 'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_CASHTERMINALRUB',
                'CashTerminalUAH' => 'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_CASHTERMINALUAH',
                'CashTerminalMDL' => 'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_CASHTERMINALMDL',
                'CashTerminalGEL' => 'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_CASHTERMINALGEL',
            ),
            'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_MOBILERETAILSGROUP' => array(
                'EurosetRUB'       => 'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_EUROSETRUB',
                'SvyaznoyRUB'      => 'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_SVYAZNOYRUB',
                'MobilElementRUB'  => 'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_MOBILELEMENTRUB',
                'AltTelecomRUB'    => 'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_ALTTELECOMRUB',
                'DixisRUB'         => 'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_DIXISRUB',
                'CifrogradRUB'     => 'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_CIFROGRADRUB',
                'CellularWorldRUB' => 'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_CELLULARWORLDRUB',
                'ForwardMobileRUB' => 'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_FORWARDMOBILERUB',
            ),
            'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_CASHTERMINALGROUP' => array(
                'SberbankRUB'    => 'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_SBERBANKRUB',
                'PrivatbankUAH'  => 'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_PRIVATBANKUAH',
                'PravexBankUAH'  => 'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_PRAVEXBANKUAH',
                'UkrsibBankUAH'  => 'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_UKRSIBBANKUAH',
                'LibertyBankGEL' => 'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_LIBERTYBANKGEL',
                'RussianPostRUB' => 'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_RUSSIANPOSTRUB',
            ),
            'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_MONEYTRANSFERGROUP' => array(
                'LiderRUB' => 'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_LIDERRUB',
                'ContactRUB' => 'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_CONTACTRUB',
                'UnistreamRUB' => 'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_UNISTREAMRUB',
                'AnelikRUB' => 'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_ANELIKRUB',
            ),
            'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_ONLINEBANKGROUP' => array(
                'AlfaclickRUB'  => 'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_ALFACLICKRUB',
                'Privat24UAH'   => 'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_PRIVAT24UAH',
                'PsbRetailRUB'  => 'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_PSBRETAILRUB',
                'QBankRUB'      => 'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_QBANKRUB',
                'SberOnlineRUB' => 'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_SBERONLINERUB',
            ),
            'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_BANKTRANSFERGROUP' => array(
                'BankTransferRUB' => 'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_BANKTRANSFERRUB',
                'BankTransferUAH' => 'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_BANKTRANSFERUAH',
                'BankTransferUSD' => 'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_BANKTRANSFERUSD',
            ),
            'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_VISAGROUP' => array(
                'BankTransferRUB' => 'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_BANKTRANSFERRUB',
                'BankTransferUAH' => 'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_BANKTRANSFERUAH',
                'BankTransferUSD' => 'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_BANKTRANSFERUSD',
            ),
            'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_VISAGROUP' => array(
                'CreditCardRUB' => 'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_CREDITCARDRUB',
                'CreditCardUAH' => 'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_CREDITCARDUAH',
                'CreditCardUSD' => 'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_CREDITCARDUSD',
                'CreditCardEUR' => 'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_CREDITCARDEUR',
            ),
            'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_MAESTROGROUP' => array(
                'CreditCardRUB'    => 'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_CREDITCARDRUB',
                'NsmepUAH'         => 'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_NSMEPUAH',
                'GiropayDeEUR'     => 'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_GIROPAYDEEUR',
                'PaysafecardEUR'   => 'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_PAYSAFECARDEUR',
                'IdealNlEUR'       => 'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_IDEALNLEUR',
                'Przelewy24PLN'    => 'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_PRZELEWY24PLN',
                'TeleingresoEsEUR' => 'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_TELEINGRESOESEUR',
                'ElvEUR'           => 'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_ELVEUR',
            ),
            'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_SOFORTBANKINGGROUP' => array(
                'SofortDeEUR' => 'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_SOFORTDEEUR',
                'SofortAtEUR' => 'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_SOFORTATEUR',
                'SofortBeEUR' => 'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_SOFORTBEEUR',
                'SofortFrEUR' => 'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_SOFORTFREUR',
                'SofortNlEUR' => 'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_SOFORTNLEUR',
                'SofortUkGBP' => 'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_SOFORTUKGBP',
            ),
            'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_POLIPAYMENTSGGROUP' => array(
                'PoliNzNZD' => 'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_POLINZNZD',
                'PoliAuAUD' => 'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_POLIAUAUD',
            ),
            'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_TRUSTPAYGROUP' => array(
                'TrustPayBaEUR' => 'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_TRUSTPAYBAEUR',
                'TrustPayHrEUR' => 'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_TRUSTPAYHREUR',
                'TrustPayBgEUR' => 'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_TRUSTPAYBGEUR',
                'TrustPaySiEUR' => 'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_TRUSTPAYSIEUR',
                'TrustPayLvEUR' => 'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_TRUSTPAYLVEUR',
                'TrustPayLtEUR' => 'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_TRUSTPAYLTEUR',
                'TrustPayEeEUR' => 'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_TRUSTPAYEEEUR',
                'TrustPayHuEUR' => 'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_TRUSTPAYHUEUR',
                'TrustPaySkEUR' => 'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_TRUSTPAYSKEUR',
                'TrustPayCzEUR' => 'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_TRUSTPAYCZEUR',
                'TrustPayPlEUR' => 'KSM_PAYMENT_WALLETONE_PAYMENT_TYPES_TRUSTPAYPLEUR',
            ),
        )
    );
    
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
        if (!isset($params['payment_types'])) {
            $params['payment_types'] = array();
        }
        $params['payment_types'] = array_flip($params['payment_types']);
        $params = array_merge($params, $this->_payment_fields);
        
        return KSSystem::loadPluginTemplate($this->_name, $this->_type, $params, 'default_settings');
    }

    private function _preparePaymentTypes($paymentTypes = array()){
        $paymentTypesTmp = array();
        foreach ($paymentTypes as $key => $paymentType) {
            $paymentTypesTmp['WMI_PTENABLED'][] = $paymentType;
        }
        return $paymentTypesTmp;
    }
    
    public function onAfterDisplayKSMCartDefault_congratulation($view, $tpl = null, &$html) {
        if (empty($view->order)) return;
        if (empty($view->order->payment_id)) return;
        
        $payment = KSMWalletone::getPayment($view->order->payment_id, $this->_name);
        if ($payment->id <= 0) return;
        if (empty($view->order->region_id)) return;
        if (!$this->checkRegion($payment->regions, $view->order->region_id)) return;
        
        $params = new JRegistry();
        $params->loadString($payment->params);
        $view->payment_params = $params;
        
        $view->payment_form_params        = new stdClass();
        $view->payment_form_params->title = 'Оплата заказа №' . $view->order->id . ' на сайте ' . JFactory::getConfig()->get('sitename');
        $paymentTypes                     = $this->_preparePaymentTypes($view->payment_params->get('payment_types', array()));
        
        $view->user = KSUsers::getUser();
        KSMWalletone::_setFields(array_merge($paymentTypes, array(
            'WMI_MERCHANT_ID'        => $view->payment_params->get('merchant_id', null), 
            'WMI_PAYMENT_AMOUNT'     => $view->order->costs['total_cost'], 
            'WMI_PAYMENT_NO'         => $view->order->id, 
            'WMI_CURRENCY_ID'        => 643, 
            'WMI_DESCRIPTION'        => $view->payment_form_params->title, 
            'WMI_CUSTOMER_FIRSTNAME' => $view->order->customer_fields->first_name,
            'WMI_CUSTOMER_LASTNAME'  => $view->order->customer_fields->last_name,
            'WMI_CUSTOMER_EMAIL'     => $view->order->customer_fields->email,
            'WMI_FAIL_URL'           => JRoute::_(JURI::base() . 'index.php?option=com_ksenmart&view=cart&layout=pay_error'),
            'WMI_SUCCESS_URL'        => JRoute::_(JURI::base() . 'index.php?option=com_ksenmart&view=cart&layout=pay_success'),
        )));
        $view->payment_form_params->sign = KSMWalletone::getHash($view->payment_params->get('secretKey', null));
        
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