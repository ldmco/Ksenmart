<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

KSSystem::import('views.viewksadmin');
class KsenMartViewOrders extends JViewKSAdmin {
	
	function display($tpl = null) {
		$this->path->addItem(JText::_('ksm_trade') ,'index.php?option=com_ksen&widget_type=trade&extension=com_ksenmart');
		$this->path->addItem(JText::_('ksm_orders'));
		
		switch ($this->getLayout()) {
			case 'consignmentnote':
				$this->document->setTitle(JText::_('ksm_orders_order_consignmentnote'));
				$this->document->addScript(JURI::base() . 'components/com_ksenmart/js/inline_edit_printform.js');
				$this->document->addStyleSheet(JURI::base() . 'components/com_ksenmart/css/printforms.css');
				$this->order = $this->get('Order');
			break;
			case 'invoice':
				$this->document->setTitle(JText::_('ksm_orders_order_invoice'));
				$this->document->addScript(JURI::base() . 'components/com_ksenmart/js/inline_edit_printform.js');
				$this->order = $this->get('Order');
			break;
			case 'salesinvoice':
				$this->document->setTitle(JText::_('ksm_orders_order_salesinvoice'));
				$this->document->addScript(JURI::base() . 'components/com_ksenmart/js/inline_edit_printform.js');
				$this->document->addStyleSheet(JURI::base() . 'components/com_ksenmart/css/printforms.css');
				$this->order = $this->get('Order');
			break;
			case 'shippingsummary':
				$this->document->setTitle(JText::_('ksm_orders_order_shippingsummary'));
				$this->document->addScript('http://api-maps.yandex.ru/2.0/?load=package.full&lang=ru-RU', 'text/javascript', false);
				$this->document->addScript(JURI::base() . 'components/com_ksenmart/js/map.js', 'text/javascript', false);
				$this->document->addScript(JURI::base() . 'components/com_ksenmart/js/map_config.js', 'text/javascript', false);
				$this->document->addScript(JURI::base() . 'components/com_ksenmart/js/inline_edit_printform.js');
				$this->document->addStyleSheet(JURI::base() . 'components/com_ksenmart/css/printforms.css');
				$this->order = $this->get('Order');
			break;
			case 'orderstatus':
				$this->document->addScript(JURI::base() . 'components/com_ksenmart/js/orderstatus.js');
				$model = $this->getModel();
				$orderstatus = $model->getOrderStatus();
				$model->form = 'orderstatus';
				$form = $model->getForm();
				if ($form) $form->bind($orderstatus);
				$this->title = JText::_('ksm_orders_orderstatus_editor');
				$this->form = $form;
				
				break;
			case 'order':
				$this->document->addScript(JURI::base() . 'components/com_ksenmart/js/order.js');
				$this->document->addScript('//api-maps.yandex.ru/2.0/?load=package.full&lang=ru-RU', 'text/javascript', false);
				$this->document->addScript(JURI::base() . 'components/com_ksenmart/js/map.js', 'text/javascript', false);
				$this->document->addScript(JURI::base() . 'components/com_ksenmart/js/map_config.js', 'text/javascript', false);
				$model = $this->getModel();
				$order = $model->getOrder();
				$model->form = 'order';
				$form = $model->getForm();
				if ($form) $form->bind($order);
				if ($order->id > 0) $this->title = JText::sprintf('ksm_orders_order_title', $order->id);
				else $this->title = JText::_('ksm_orders_new_order_title');
				$this->form = $form;
				$this->order = $order;
				
				break;
			default:
				$this->document->addScript(JURI::base() . 'components/com_ksenmart/js/jquery.custom.min.js');
				$this->document->addScript(JURI::base() . 'components/com_ksenmart/js/jquery.ui.datepicker-ru.js');
				$this->document->addScript(JURI::base() . 'components/com_ksenmart/js/orders.js');
				$this->items = $this->get('ListItems');
				$this->total = $this->get('Total');
			}
			parent::display($tpl);
		}
	}
	