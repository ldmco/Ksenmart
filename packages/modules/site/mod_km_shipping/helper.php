<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

class modKMShippingHelper 
{

    static function getRegions() 
	{
		$db = JFactory::getDBO();
		
        $query = $db->getQuery(true);
        $query
            ->select('*')
            ->from('#__ksenmart_regions')
            ->where('published = 1')
            ->order('ordering')
        ;

        $db->setQuery($query);
        $regions = $db->loadObjectList();		
		
		return $regions;
	}
	
    static function getShippings($region_id) 
	{
		$db = JFactory::getDBO();
        $shippings = array();
		
		if (empty($region_id))
		{
			return $shippings;
		}

		$query = $db->getQuery(true);
		$query
			->select('s.*')
			->from('#__ksenmart_shippings as s')
			->where('s.published = 1')
			->order('s.ordering')
		;
		$query = KSMedia::setItemMainImageToQuery($query, 'shipping', 's.');
		$db->setQuery($query);
		$db_shippings = $db->loadObjectList();
		
		foreach($db_shippings as $db_shipping) 
		{
			if (!self::checkRegion($db_shipping->regions, $region_id)) 
			{
				continue;
			}
							
			$db_shipping->icon = !empty($db_shipping->filename) ? KSMedia::resizeImage($db_shipping->filename, $db_shipping->folder, 20, 20, json_decode($db_shipping->params, true)) : '';			
			$cart = new stdClass();
			$cart->shipping_id = $db_shipping->id;
			$cart->region_id = $region_id;
			$cart->items = array();
			JDispatcher::getInstance()->trigger('onAfterExecuteKSMCartGetcart', array(null, &$cart));
			$db_shipping->sum = $cart->shipping_sum;
			$db_shipping->sum_val = $cart->shipping_sum_val;
			$shippings[] = $db_shipping;
		}
		
		return $shippings;
    }
	
    static function getPayments($region_id) 
	{
		$db = JFactory::getDBO();
        $payments = array();

		if (empty($region_id))
		{
			return $payments;
		}	
		
		$query = $db->getQuery(true);
		$query
			->select('p.*')
			->from('#__ksenmart_payments as p')
			->where('p.published = 1')
			->order('p.ordering')
		;
		$query = KSMedia::setItemMainImageToQuery($query, 'payment', 'p.');
		$db->setQuery($query);
		$db_payments = $db->loadObjectList();
		
		foreach($db_payments as $db_payment) 
		{
			if (!self::checkRegion($db_payment->regions, $region_id))
			{
				continue;					
			}				
		
			$db_payment->icon = !empty($db_payment->filename) ? KSMedia::resizeImage($db_payment->filename, $db_payment->folder, 20, 20, json_decode($db_payment->params, true)) : '';			
			$payments[] = $db_payment;
		}			

        return $payments;		
	}	
	
	static function checkRegion($regions, $region_id)
	{
		$regions = json_decode($regions, true);
		if (!is_array($regions) || !count($regions))
		{
			return true;			
		}
		
		foreach($regions as $country)
		{
			if (in_array($region_id, $country))
			{
				return true;			
			}
		}
					
		return false;
	}
	
	static function setRegionAjax()
	{
        $app = JFactory::getApplication();
		$region_id = $app->input->get('region_id', 0, 'int');
		
        $app->setUserState('com_ksenmart.region_id', $region_id);		
        $shippings = self::getShippings($region_id);
        $payments  = self::getPayments($region_id);
		
		require JModuleHelper::getLayoutPath('mod_km_shipping', 'default_shipping_info');
	}
}