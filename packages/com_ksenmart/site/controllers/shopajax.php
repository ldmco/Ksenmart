<?php
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

class KsenMartControllerShopAjax extends JControllerLegacy {

	function validate_in_stock() {
		$params  = JComponentHelper::getParams('com_ksenmart');
		$jinput  = JFactory::getApplication()->input;
		$id      = $jinput->getInt('id', 0);
		$count   = $jinput->getInt('count', 0);
		$product = KSMProducts::getProduct($id);
		if ($count > $product->in_stock && $params->get('use_stock', 1) == 1) echo 'Недостаточно количества на складе';
		JFactory::getApplication()->close();
	}

	function save_variable() {
		$app    = JFactory::getApplication();
		$jinput = $app->input;
		$name   = $jinput->getString('name', '');
		$value  = $jinput->getString('value', '');
		$session = JFactory::getSession();
		if ($name != '') $session->set('com_ksenmart.' . $name, $value);
		$app->close();
	}

	function get_transform_price() {
		$jinput = JFactory::getApplication()->input;
		$price  = $jinput->getInt('price', 0);
		echo KSMPrice::showPriceWithTransform($price);
	}

	function get_route_link() {
		$jinput = JFactory::getApplication()->input;
		$url    = $jinput->get('url', '', 'RAW');
		$url    = JRoute::_($url);
		$url    = str_replace('&amp;', '&', $url);
		echo $url;
		JFactory::getApplication()->close();
	}

}