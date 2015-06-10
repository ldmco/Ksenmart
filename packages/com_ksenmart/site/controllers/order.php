<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');
class KsenMartControllerOrder extends JControllerLegacy {

    public function get_order_id() {
        $session  = JFactory::getSession();
        $order_id = $session->get('shop_order_id', 0);
        echo $order_id;
        exit(0);
    }

    public function create_order() {
        $model = $this->getModel('order');
        $order_id = $model->createOrder();
        
        JRequest::setVar('order_id', $order_id);
        JRequest::setVar('layout', 'congratulation');
        parent::display();
    }

    public function close_order() {
        $model = $this->getModel('order');
        $order_id = $model->createOrder(1);
        JRequest::setVar('order_id', $order_id);
        JRequest::setVar('layout', 'close_congratulation');
        parent::display();
    }
}