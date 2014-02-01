<?php	 		 		 	
defined( '_JEXEC' ) or die;
jimport('joomla.application.component.controlleradmin');

class KsenMartControllerOrders extends KsenMartController
{

    function get_order_items() {
        $id = JRequest::getInt('id');
        $html = '';

        $model = $this->getModel('orders');
        $view = $this->getView('orders', 'html');
        $view->setModel($model, true);
		$view->setLayout('order_items');
		$view->order = $model->getOrder();
		ob_start();
		$view->display();
		$html.=ob_get_contents();
		ob_end_clean();

        $response = array(
            'html' => $html,
            'message' => array(),
            'errors' => 0
		);
        $response = json_encode($response);
        JFactory::getDocument()->setMimeEncoding('application/json');
        echo $response;
        JFactory::getApplication()->close();
    }

	function display()
 	{
		parent::display(); 	
	}	
	
}
