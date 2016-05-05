<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');
class KsenMartControllerShopAjax extends JControllerLegacy {
	
	function add_favorites() {
		$id = JRequest::getVar('id', 0);
		$user = KSUsers::getUser();
		if ($user->id != 0 && !in_array($id, $user->favorites)) {
			$user->favorites[] = $id;
			$db = JFactory::getDBO();
			echo $query = "update #__ksen_users set favorites='" . json_encode($user->favorites) . "' where id='$user->id'";
			$db->setQuery($query);
			$db->query();
		}
		exit();
	}
	
	function add_watched() {
		$id = JRequest::getVar('id', 0);
		$user = KSUsers::getUser();
		if ($user->id != 0 && !in_array($id, $user->watched)) {
			$user->watched[] = $id;
			$db = JFactory::getDBO();
			$query = "update #__ksen_users set watched='" . json_encode($user->watched) . "' where id='$user->id'";
			$db->setQuery($query);
			$db->query();
		}
		exit();
	}
	
	function subscribe() {
		$user = KSUsers::getUser();
		if ($user->id != 0 && !in_array(2, $user->groups)) {
			if ($user->email == '') {
				echo 'email';
				exit();
			}
			$user->groups[] = 2;
			$db = JFactory::getDBO();
			$query = "update #__ksen_users set groups='|" . implode('||', $user->groups) . "|' where id='$user->id'";
			$db->setQuery($query);
			$db->query();
		}
		exit();
	}
	
	function get_shipping_cost() {
		$cost = 0;
		$region_id = JRequest::getVar('region_id', 0);
		$shipping_id = JRequest::getVar('shipping_id', 0);
		$distance = JRequest::getVar('distance', 0);
		$db = JFactory::getDBO();
		$query = "select st.name from #__ksenmart_shippings as s,#__ksenmart_shipping_types as st where s.id='$shipping_id' and st.id=s.type";
		$db->setQuery($query);
		$shipping_type = $db->loadResult();
		include (JPATH_ROOT . '/administrator/components/com_ksenmart/helpers/shipping/' . $shipping_type . '.php');
		echo $cost;
		exit(0);
	}
	
	public function get_product_price_with_properties() {
		
		$pid                = $this->input->get('id', 0, 'int');
		$val_prop_id        = $this->input->get('val_prop_id', 0, 'int');
		$prop_id            = $this->input->get('prop_id', 0, 'int');
		$selectedProperties = $this->input->get('properties', array(), 'array');
		
		$db                = JFactory::getDBO();
		$app               = JFactory::getApplication();
		$properties        = KSMProducts::getProperties($pid, $prop_id, $val_prop_id);
		$productProperties = KSMProducts::getProperties($pid);
		$prices            = KSMProducts::getProductPrices($pid);
		
		$price              = $prices->price;
		$price_type         = $prices->price_type;
		$checked            = array();

		foreach ($productProperties as $property) {
			foreach ($selectedProperties as $selectedPropId => $selectedProperty) {
				foreach ($selectedProperty as $selectedValueId => $selectedValue) {
					if(isset($selectedValue['checked'])){
						$checked[$selectedValue['valueId']] = $selectedValue['checked'];
					}
					if($property->property_id == $selectedValue['propId'] && ($val_prop_id != $property->values[$selectedValueId]->id)){
						$edit_priceC = $property->values[$selectedValueId]->price;
						$edit_price_symC = substr($edit_priceC, 0, 1);
						$this->getCalcPriceAsProperties($edit_price_symC, $edit_priceC, $price);
						$property->values[$selectedValueId]->id . '-' .$price . "\n\t";
					}
				}
			}
		}

		foreach ($properties as $property) {
			$edit_price = null;
			if ($property->edit_price) {
				if ($property->view == 'checkbox') {
					$value = array_pop($property->values);
					if ($checked[$value->id]) {
						$edit_price = $value->price;
					}
				} elseif ($property->view == 'select' || $property->view == 'radio') {
					if ($val_prop_id != 0) {
						$edit_price = $property->values[$val_prop_id]->price;
					}
				}
			}
			
			if($edit_price){
				$edit_price_sym = substr($edit_price, 0, 1);
				$this->getCalcPriceAsProperties($edit_price_sym, $edit_price, $price);
			}
		}
		$price = KSMPrice::getPriceInCurrentCurrency($price, $price_type);
		$app->close($price . '^^^' . $price);
	}

	private function getCalcPriceAsProperties($edit_price_sym, $edit_price, &$price) {
		switch ($edit_price_sym) {
			case '+':
				$price += substr($edit_price, 1, strlen($edit_price) - 1);
			break;
			case '-':
				$price -= substr($edit_price, 1, strlen($edit_price) - 1);
			break;
			case '/':
				$price = $price / substr($edit_price, 1, strlen($edit_price) - 1);
			break;
			case '*':
				$price = $price * substr($edit_price, 1, strlen($edit_price) - 1);
			break;
			default:
				$price += $edit_price;
		}
		return $price;
	}
	
	function validate_in_stock() {
		$params = JComponentHelper::getParams('com_ksenmart');
		$db = JFactory::getDBO();
		$id = JRequest::getVar('id', 0);
		$count = JRequest::getVar('count', 0);
		$product = KSMProducts::getProduct($id);
		if ($count > $product->in_stock && $params->get('use_stock', 1) == 1) echo 'Недостаточно количества на складе';
		exit();
	}
	
	function save_variable() {
		$app = JFactory::getApplication();
		$name = JRequest::getVar('name', '');
		$value = JRequest::getVar('value', '');
		if ($name != '') $app->setUserState('com_ksenmart.' . $name, $value);
		$app->close();
	}
	
	function get_transform_price() {
		$price = JRequest::getVar('price', 0);
		echo KSMPrice::showPriceWithTransform($price);
	}
	
	function get_route_link() {
		$url = JRequest::getVar('url', '');
		$url = JRoute::_($url);
		$url = str_replace('&amp;', '&', $url);
		echo $url;
		JFactory::getApplication()->close();
	}
	
	function set_session_variable() {
		$session = JFactory::getSession();
		$name = JRequest::getVar('name', null);
		$value = JRequest::getVar('value', null);
		if (!empty($name)) {
			$name = 'com_ksenmart.' . $name;
			$session->set($name, $value);
		}
		JFactory::getApplication()->close();
	}
	
	function get_session_variable() {
		$session = JFactory::getSession();
		$name = JRequest::getVar('name', null);
		$value = '';
		if (!empty($name)) {
			$name = 'com_ksenmart.' . $name;
			$value = $session->get($name, null);
		}
		echo $value;
		JFactory::getApplication()->close();
	}
	
	function set_session_data() {
		$session_data = JRequest::getVar('session_data', '{}');
		$session_data = json_decode($session_data, true);
		if (!count($session_data)) $_SESSION = $session_data;
		JFactory::getApplication()->close();
	}
	
	function get_session_data() {
		$session_data = $_SESSION;
		$session_data = json_encode($session_data);
		echo $session_data;
		JFactory::getApplication()->close();
	}
	
	function set_user_activity() {
		$session = JFactory::getSession();
		$time = JRequest::getVar('time', time());
		$session->set('com_ksenmart.user_last_activity', $time);
		JFactory::getApplication()->close();
	}
}