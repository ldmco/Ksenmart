<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

class modKMMinicartHelper 
{

    public function getCart() 
	{
		if (!class_exists('KsenMartModelCart')) 
		{
			include (JPATH_ROOT . '/components/com_ksenmart/models/cart.php');
		}
		$cart_model = new KsenMartModelCart();
		$cart = $cart_model->getCart(); 	
		
		return $cart;
	}
	
    public function getCartLink() 
	{
		$Itemid = KSSystem::getShopItemid();
		$link = JRoute::_('index.php?option=com_ksenmart&view=cart&Itemid=' . $Itemid);
		
		return $link;
    }
	
	public function updateMinicartAjax()
	{
        $cart = self::getCart();
        $link  = self::getCartLink();
		
		require JModuleHelper::getLayoutPath('mod_km_minicart', 'default');
	}
}