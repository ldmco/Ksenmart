<?php
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

KSSystem::import('views.viewks');

class KsenMartViewCart extends JViewKS
{

	public function display($tpl = null)
	{
		$app             = JFactory::getApplication();
		$document        = JFactory::getDocument();
		$this->params    = JComponentHelper::getParams('com_ksenmart');
		$session         = JFactory::getSession();
		$path            = $app->getPathway();
		$names_komponent = $this->params->get('shop_name');
		$pref            = $this->params->get('path_separator');
		$doc_title       = $names_komponent . $pref . JText::_('KSM_CART_YOUR_ORDER_DOC_TITLE');
		$this->state     = $this->get('State');
		$order_id        = $session->get('shop_order_id', 0);
		$layout          = $this->getLayout();

		if (!JFactory::getConfig()->get('config.caching', 0))
		{
			$path->addItem(JText::_('KSM_CART_YOUR_ORDER_PATHWAY_ITEM'));
		}

		$document->setTitle($doc_title);

		switch ($layout)
		{
			case 'receipt':

				$document        = JFactory::getDocument();
				$model           = $this->getModel();
				$model->order_id = $app->input->getInt('id', 0);
				$this->order     = $model->getOrderInfo();

				$document->setTitle(JText::_('KSM_CART_ORDER_INVOICE_DOC_TITLE'));
				$document->addStyleSheet(JUri::base() . 'administrator/components/com_ksenmart/css/printforms.css');
				$this->setLayout('default_receipt');

				break;
			case 'pay_success':
				$this->setLayout('default_pay_success');
				break;
			case 'pay_error':
				$this->setLayout('default_pay_error');
				break;
			case 'congratulation':
				if ($order_id)
				{
					$model            = $this->getModel();
					$model->_order_id = $order_id;
					$this->shippings = $this->get('Shippings');
					$this->payments = $this->get('Payments');
					$cart            = $this->get('Cart');
					if ($cart->status_id != 1)
					{
						$Itemid = KSSystem::getShopItemid();
						$url    = JRoute::_('index.php?option=com_ksenmart&view=cart&Itemid=' . $Itemid);
						header('Location: ' . $url);
					} else
					{
						$this->system_fields             = $this->get('SystemFields');
						$this->stepsinfo                 = $this->get('Steps');
						$this->stepsinfo->congratulation = true;
						$this->customer_fields           = $model->getCustomerFields(false);
						$this->address_fields            = $model->getAddressFields(false);
						$this->cart                      = $cart;
						$this->setLayout('default_congratulation');
						$model->completeOrder();
						$session->set('shop_order_id', null);
						$model->clearCart();
					}
				} else
				{
					$this->setLayout('default_empty');
				}
				break;
			case 'privacy':
				$this->company       = $this->get('Company');
				$this->setLayout('default_privacy');

				break;
			case 'content':
				$cart       = $this->get('Cart');
				$this->cart = $cart;
				if (!empty($cart->items))
				{
					$this->setLayout('default_content');
				}
				break;
			case 'cart_shipping':

				$session = JFactory::getSession();

				$this->regions          = $this->get('Regions');
				$this->shippings        = $this->get('Shippings');
				$this->customer_fields  = $this->getCustomerFields(false);
				$this->address_fields   = $this->get('AddressFields');
				$this->cart             = $this->get('Cart');
				$this->addresses        = $this->get('Addresses');
				$this->selected_address = $session->get('selected_address', 0);

				break;
			case 'cart_payments':
				$this->payments = $this->get('Payments');
				break;
			case 'cart_total':
				$this->cart = $this->get('Cart');
				break;
			case 'poppupcart':
				$this->cart = $this->get('Cart');
				$this->setLayout('default_' . $layout);
				break;
			case 'preorder':
				$document->addScript(JUri::base() . 'components/com_ksenmart/js/jquery.inputmask.js', 'text/javascript', false);
				$document->addScript(JUri::base() . 'components/com_ksenmart/js/jquery.bind-first-0.1.min.js', 'text/javascript', false);
				$document->addScript(JUri::base() . 'components/com_ksenmart/js/jquery.inputmask-multi.js', 'text/javascript', false);
				$document->addScript(JUri::base() . 'components/com_ksenmart/js/preorder.js', 'text/javascript', false);

				$this->customer_fields    = $this->get('PreorderFields');
				$this->message            = $this->get('Message');
				$this->product_id         = $app->input->get('id', 0);
				$this->product_count      = $app->input->get('count', 0);
				$this->product_properties = array();

				$properties = $this->get('Properties');
				foreach ($properties as $property)
				{
					$value = $app->input->get('property_' . $this->product_id . '_' . $property->id, 0);
					if (!empty($value))
					{
						$this->product_properties['property_' . $this->product_id . '_' . $property->id] = $value;
					}
				}

				break;
			default:
				$model           = $this->getModel();
				$this->shippings = $this->get('Shippings');
				$cart            = $this->get('Cart');
				if (!$cart->total_prds)
				{
					$this->setLayout('default_empty');
					break;
				}
				$session = JFactory::getSession();

				$document->addScript(JUri::base() . 'components/com_ksenmart/js/jquery.inputmask.js', 'text/javascript', false);
				$document->addScript(JUri::base() . 'components/com_ksenmart/js/jquery.bind-first-0.1.min.js', 'text/javascript', false);
				$document->addScript(JUri::base() . 'components/com_ksenmart/js/jquery.inputmask-multi.js', 'text/javascript', false);
				$document->addScript(JUri::base() . 'components/com_ksenmart/js/jquery.validate.min.js', 'text/javascript', false);
				JHtml::script('com_ksenmart/cart.js', false, true);
				$document->addScript('//api-maps.yandex.ru/2.0/?load=package.full&lang=ru-RU', 'text/javascript', false);
				$document->addScript(JUri::base() . 'administrator/components/com_ksenmart/js/map.js', 'text/javascript', false);
				//$document->addScript(JUri::base() . 'components/com_ksenmart/js/map_config.js', 'text/javascript', false);

				$this->system_fields    = $this->get('SystemFields');
				$this->stepsinfo        = $this->get('Steps');
				$this->show_regions     = $this->get('ShowRegions');
				$this->regions          = $this->get('Regions');
				$this->payments         = $this->get('Payments');
				$this->addresses        = $this->get('Addresses');
				$this->selected_address = $session->get('selected_address', 0);
				$this->customer_fields  = $model->getCustomerFields(false);
				$this->address_fields   = $model->getAddressFields(false);
				$this->cart             = $cart;

				$this->setLayout($layout);

				break;
		}

		$this->system_customer_fields = $this->get('SystemCustomerFields');
		parent::display($tpl);
	}
}