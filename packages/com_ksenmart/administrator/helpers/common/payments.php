<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

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
