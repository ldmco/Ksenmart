<?php defined('_JEXEC') or die;

class KSMPayments {
	
	public static function getPaymentName($payment_id) {
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('title')->from('#__ksenmart_payments')->where('id=' . $payment_id);
		$db->setQuery($query);
		$payment_name = $db->loadResult();
		
		return $payment_name;
	}
}
