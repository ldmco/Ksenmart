<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');
class KsenMartControllerOrders extends KsenMartController {
    
    public function get_order_items() {
        $id = JRequest::getInt('id');
        $html = '';
        
        $model = $this->getModel('orders');
        $view = $this->getView('orders', 'html');
        $view->setModel($model, true);
        $view->setLayout('order_items');
        $view->order = $model->getOrder();

        ob_start();
        $view->display();
        $html.= ob_get_contents();
        ob_end_clean();
        
        $response = array('html' => $html, 'message' => array(), 'errors' => 0);
        $response = json_encode($response);

        JFactory::getDocument()->setMimeEncoding('application/json');
        JFactory::getApplication()->close($response);
    }
}