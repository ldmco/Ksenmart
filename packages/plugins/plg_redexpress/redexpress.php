<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

if (!class_exists('KSMShippingPlugin')) {
	require (JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_ksenmart' . DS . 'classes' . DS . 'kmshippingplugin.php');
}

class plgKMShippingRedexpress extends KSMShippingPlugin {

    public function __construct(&$subject, $config = array()) {
        parent::__construct($subject, $config);
        $this->loadLanguage('plg_kmshipping_redexpress.sys');
    }

	public function onAfterExecuteKSMcartgetOrderInfo($model, $order = null) {

        $payment   = KSMWalletone::getPayment($order->payment_id, 'walletone');
        $shipping  = KSMWalletone::getShipping($order->shipping_id, 'redexpress');

        if($payment->id > 0 && $shipping->id > 0){

            $orderInfo = KSMWalletone::getOrder($order->id);
            $region    = KSMWalletone::getRegion($order->region_id);
            $country   = KSMWalletone::getCountry($region->country_id);

    		KSMWalletone::_setFields(array(
                'WMI_DELIVERY_REQUEST'     => true,
                'WMI_DELIVERY_COUNTRY'     => $country->title,
                'WMI_DELIVERY_REGION'      => $region->title,
                'WMI_DELIVERY_ADDRESS'     => $order->address_fields,
                'WMI_DELIVERY_CITY'        => $orderInfo->address_fields->city,
                'WMI_DELIVERY_CONTACTINFO' => $order->customer_fields->phone,
                'WMI_DELIVERY_COMMENTS'    => $order->note,
                'WMI_DELIVERY_ORDERID'     => $order->id,
            ));
        }
	}
}