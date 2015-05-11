<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

KSSystem::import('views.viewks');
class KsenMartViewOrder extends JViewKS {
    public function display($tpl = null) {
        $app             = JFactory::getApplication();
        $document        = JFactory::getDocument();
        $this->params    = JComponentHelper::getParams('com_ksenmart');
        $pref            = $this->params->get('path_separator');
        $id              = JRequest::getVar('id', 0);
        $names_komponent = $this->params->get('shop_name');
        $path            = $app->getPathway();        
        $doc_title       = $names_komponent . $pref . 'Оформление заказа';
        
        if(!JFactory::getConfig()->get('config.caching', 0)){
            $path->addItem('Оформление заказа');
        }
        
        $document->setTitle($doc_title);
        
        if($id == 0){
            $this->setLayout('choose_product');
        }

        switch($this->getLayout()) {
            case 'congratulation':
                $order_id = JRequest::getVar('order_id', 0);
                
                $this->assignRef('order_id', $order_id);
                $this->setLayout('order_congratulation');
                $document->addScript(JURI::base() . 'components/com_ksenmart/js/openorder_close.js', 'text/javascript', false);
                
                break;
            case 'close_congratulation':
                $order_id = JRequest::getVar('order_id', 0);
                $this->assignRef('order_id', $order_id);
                $this->setLayout('order_close_congratulation');
                break;
            case 'choose_product':
                $this->setLayout('choose_product');
                break;

            default:
                $close_order = JRequest::getVar('close_order', 0);
                if($close_order == 1) {
                    
                    $document->addScript(JURI::base() . 'components/com_ksenmart/js/openorder_close.js', 'text/javascript', false);
                    $document->addStyleSheet(JURI::base() . 'components/com_ksenmart/css/map.css');
                    $document->addScript('http://api-maps.yandex.ru/2.0/?load=package.full&lang=ru-RU', 'text/javascript', false);
                    $document->addScript(JURI::base() . 'administrator/components/com_ksenmart/js/map.js', 'text/javascript', false);
                    $document->addScript(JURI::base() . 'components/com_ksenmart/js/map_config.js', 'text/javascript', false);
                    
                    $product    = $this->get('Product');
                    $user       = $this->get('User');
                    $fields     = $this->get('Fields');
                    $regions    = $this->get('Regions');
                    $shippings  = $this->get('Shippings');
                    $payments   = $this->get('Payments');
                    
                    $this->assignRef('product', $product);
                    $this->assignRef('user', $user);
                    $this->assignRef('fields', $fields);
                    $this->assignRef('regions', $regions);
                    $this->assignRef('shippings', $shippings);
                    $this->assignRef('payments', $payments);
                    $this->setLayout('openorder_close');
                    
                }else{
                    $document->addScript(JURI::base() . 'components/com_ksenmart/js/jquery.inputmask.js', 'text/javascript', false);
                    $document->addScript(JURI::base() . 'components/com_ksenmart/js/jquery.bind-first-0.1.min.js', 'text/javascript', false);
                    $document->addScript(JURI::base() . 'components/com_ksenmart/js/jquery.inputmask-multi.js', 'text/javascript', false);
                    $document->addScript(JURI::base() . 'components/com_ksenmart/js/openorder.js', 'text/javascript', false);
                    $document->addStyleSheet(JURI::base() . 'components/com_ksenmart/css/openorder.css');
                    
                    $product = $this->get('Product');
                    $user    = KSUsers::getUser();
					$customer_fields    = $this->get('CustomerFields');

                    $this->assignRef('product', $product);
					$this->assignRef('customer_fields', $customer_fields);
                    $this->assignRef('user', $user);
                    $this->setLayout('openorder');
                    
                }
                break;
        }

        parent::display($tpl);
    }
}
