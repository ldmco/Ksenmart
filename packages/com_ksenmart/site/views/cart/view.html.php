<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

KSSystem::import('views.viewks');
class KsenMartViewCart extends JViewKS {

    public function display($tpl = null) {
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
        
		if(!JFactory::getConfig()->get('config.caching', 0)){
			$path->addItem(JText::_('KSM_CART_YOUR_ORDER_PATHWAY_ITEM'));
		}
		
		$document->setTitle($doc_title);
		
		switch($layout) {
			case 'receipt':
			
				$document           = JFactory::getDocument();
				$model              = $this->getModel();
				$model->order_id   = JRequest::getVar('id', 0);
				$order              = $model->getOrderInfo();
				
				$document->setTitle(JText::_('KSM_CART_ORDER_INVOICE_DOC_TITLE'));
				$document->addStyleSheet(JURI::base() . 'administrator/components/com_ksenmart/css/printforms.css');
				
				$this->assignRef('order', $order);
				$this->setLayout('default_receipt');
				
				break;
			case 'pay_success':
				$this->setLayout('default_pay_success');
				break;
			case 'pay_error':
				$this->setLayout('default_pay_error');
			break;
			case 'congratulation':
				if($order_id) {
					$model              = $this->getModel();
					$model->_order_id   = $order_id;
					$order              = $model->getOrderInfo();
					$this->assignRef('order', $order);
					$this->setLayout('default_congratulation');
					$session->set('shop_order_id', null);					
				} else {
					$this->setLayout('default_empty');
				}				
				break;
			case 'minicart':
				$cart = $this->get('Cart');
				$this->assignRef('cart', $cart);
				if(empty($cart->items)) {
					$this->setLayout('minicart_empty');
				} else {
					$this->setLayout('minicart');
				}
				break;
			case 'content':
				$cart = $this->get('Cart');
				$this->assignRef('cart', $cart);
				if(!empty($cart->items)) {
					$this->setLayout('default_content');
				}
				break;
			case 'cart_shipping':
				
				$session = JFactory::getSession();
				
				$this->regions          = $this->get('Regions');
				$this->shippings        = $this->get('Shippings');
				$this->customer_fields  = $this->get('CustomerFields');
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
			default:
                
                $cart = $this->get('Cart');
				if(!$cart->total_prds){
					$this->setLayout('default_empty');	
					break;
				}
				$session = JFactory::getSession();
				
				$document->addScript(JURI::base() . 'components/com_ksenmart/js/jquery.inputmask.js', 'text/javascript', false);
				$document->addScript(JURI::base() . 'components/com_ksenmart/js/jquery.bind-first-0.1.min.js', 'text/javascript', false);
				$document->addScript(JURI::base() . 'components/com_ksenmart/js/jquery.inputmask-multi.js', 'text/javascript', false);
				$document->addScript(JURI::base() . 'components/com_ksenmart/js/opencart.js', 'text/javascript', false);
				$document->addStyleSheet(JURI::base() . 'components/com_ksenmart/css/map.css');
				$document->addScript('//api-maps.yandex.ru/2.0/?load=package.full&lang=ru-RU', 'text/javascript', false);
				$document->addScript(JURI::base() . 'administrator/components/com_ksenmart/js/map.js', 'text/javascript', false);
				$document->addScript(JURI::base() . 'components/com_ksenmart/js/map_config.js', 'text/javascript', false);
				
				$fields                         = $this->get('Fields');
				$regions                        = $this->get('Regions');
				$shippings                      = $this->get('Shippings');
				$payments                       = $this->get('Payments');
				$addresses                      = $this->get('Addresses');
				$selected_address               = $session->get('selected_address', 0);
				$this->customer_fields          = $this->get('CustomerFields');
				$this->address_fields           = $this->get('AddressFields');

				$this->assignRef('cart', $cart);
				$this->assignRef('fields', $fields);
				$this->assignRef('shippings', $shippings);
				$this->assignRef('payments', $payments);
				$this->assignRef('regions', $regions);
				$this->assignRef('addresses', $addresses);
				$this->assignRef('selected_address', $selected_address);
				
				$this->setLayout($layout);

				break;
		}
		
		$this->system_customer_fields   = $this->get('SystemCustomerFields');

        parent::display($tpl);
    }
}