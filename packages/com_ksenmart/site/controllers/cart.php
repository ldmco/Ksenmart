<?php
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

class KsenMartControllerCart extends JControllerLegacy {

	function create_order() {
		$model = $this->getModel('cart');
		$model->addToCart();
		$response = '
		<script>
		if (window.parent.KSMUpdateMinicart)
		{
			window.parent.KSMUpdateMinicart();
		}		
		window.parent.KMShowCartMessage();
		window.parent.KMClosePopupWindow();	
		</script>		
		';
		JFactory::getApplication()->close($response);
	}

	function add_to_cart() {
		$model  = $this->getModel('cart');
		$return = $model->addToCart();
		$view   = $this->getView('cart', 'html');
		$view->setLayout('poppupcart');
		ob_start();
		$view->display();
		$html = ob_get_contents();
		ob_end_clean();
		$response = array(
			'html'   => $html,
			'status' => $return
		);
		JFactory::getApplication()->close(json_encode($response));
	}

	function update_cart() {
		$app      = JFactory::getApplication();
		$input    = $app->input;
		$items    = $input->get('items', array(), 'ARRAY');
		$layouts  = $input->get('layouts', array(), 'ARRAY');
		$response = array();
		$model    = $this->getModel('cart');

		$response['message'] = false;
		$return              = $model->updateCart($items);
		if ($return === false) {
			$errors = $model->getErrors();
			foreach ($errors as $error) {
				if (is_string($error)) {
					$response['message'] .= $error;
				} else {
					$response['message'] .= $error->getMessage();
				}

			}
		}

		$view = $this->getView('cart', 'html');
		$model->setModelFields();
		$view->setModel($model, true);

		$response['layouts'] = array();
		foreach ($layouts as $layout) {
			$view->setLayout($layout);

			ob_start();
			$view->display();
			$response['layouts'][$layout] = ob_get_contents();
			ob_end_clean();
		}

		$response['items'] = array();
		$view->cart = $model->getCart();
		foreach ($view->cart->items as $item) {
			$response['items'][$item->id] = array(
				'price_val' => KSMPrice::showPriceWithTransform($item->price),
				'sum_val'   => KSMPrice::showPriceWithTransform($item->price * $item->count),
			);
		}
		$response['products_sum_val'] = KSMPrice::showPriceWithTransform($view->cart->products_sum - $view->cart->discount_sum);
		$response['discount_sum_val'] = $view->cart->discount_sum_val;
		$response['total_sum_val']    = $view->cart->total_sum_val;

		$response = json_encode($response);
		JFactory::getDocument()->setMimeEncoding('application/json');
		$app->close($response);
	}

	function close_order() {
		$session  = JFactory::getSession();
		$order_id = $session->get('shop_order_id', 0);
		if ($order_id != 0) {
			$model = $this->getModel('cart');
			$model->closeOrder();
			$this->setRedirect(JRoute::_('index.php?option=com_ksenmart&view=cart&layout=congratulation&Itemid=' . KSSystem::getShopItemid(), false));
		}
	}

	public function updateOrderShippingField() {
		$jinput  = JFactory::getApplication()->input;
		$session = JFactory::getSession();
		$oid     = $session->get('shop_order_id', 0);

		$data              = new stdClass;
		$data->shipping_id = $jinput->get('shipping_id', 0, 'int');

		KSMOrders::updateOrderFields($oid, $data);
	}

	public function updateOrderRegionField() {
		$jinput  = JFactory::getApplication()->input;
		$session = JFactory::getSession();
		$oid     = $session->get('shop_order_id', 0);

		$data            = new stdClass;
		$data->region_id = $jinput->get('region_id', 0, 'int');

		KSMOrders::updateOrderFields($oid, $data);
	}

	public function updateOrderUserField() {
		$app     = JFactory::getApplication();
		$jinput  = $app->input;
		$session = JFactory::getSession();
		$oid     = $session->get('shop_order_id', 0);

		$field        = $jinput->get('name', null, 'string');
		$field_value = $jinput->get('field_value', null, 'string');

		if (strpos($field, '[')){
			list($type, $name) = explode('[', $field);
			list($name) = explode(']', $name);

			$fields = KSMOrders::getOrderField($oid, $type);
			$fields = json_decode($fields->{$type});
			if (empty($fields)) $fields = new stdClass();

			$fields->{$name} = $field_value;
			KSMOrders::setOrderField($fields, $type, true);
		} else {
			$type = $field;
			$fields = $field_value;
			KSMOrders::setOrderField($fields, $type, false);
		}

		$app->setUserState('com_ksenmart.' . $field, $field_value);
		$app->close();
	}

	public function updateOrderField() {
		$jinput  = JFactory::getApplication()->input;
		$session = JFactory::getSession();
		$oid     = $session->get('shop_order_id', 0);
		$column  = $jinput->get('column', null, 'string');

		if (!empty($column)) {
			$data            = new stdClass;
			$data->{$column} = $jinput->get('field', 0, 'int');

			KSMOrders::updateOrderFields($oid, $data);
		}
	}

	public function set_select_address_id() {
		$jinput   = JFactory::getApplication()->input;
		$model    = $this->getModel('profile');
		$id       = $jinput->get('id', 0, 'int');
		$city     = $jinput->get('city', 0, 'string');
		$zip      = $jinput->get('zip', 0, 'string');
		$street   = $jinput->get('street', 0, 'string');
		$house    = $jinput->get('house', 0, 'string');
		$entrance = $jinput->get('entrance', 0, 'string');
		$flat     = $jinput->get('flat', 0, 'string');
		$floor    = $jinput->get('floor', 0, 'string');

		$model->setSelectAddressId($id, $city, $zip, $street, $house, $entrance, $flat, $floor);

		return true;
	}

	function pay_order() {
		$dispatcher = JEventDispatcher::getInstance();
		$dispatcher->trigger('onPayOrder');

		JFactory::getApplication()->close();
	}

	function get_layout() {
		$layout = JRequest::getVar('layout', null);
		if (empty($layout)) {
			JFactory::getApplication()->close();
		}
		$model = $this->getModel('cart');
		$view  = $this->getView('cart', 'html');
		$view->setLayout($layout);
		$view->cart = $model->getCart();
		$view->display();
		JFactory::getApplication()->close();
	}

	public function get_order_id() {
		$session  = JFactory::getSession();
		$order_id = $session->get('shop_order_id', 0);

		JFactory::getApplication()->close($order_id);
	}
}