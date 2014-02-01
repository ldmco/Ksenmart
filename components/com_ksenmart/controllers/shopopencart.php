<?php defined('_JEXEC') or die;
jimport('joomla.application.component.controller');

class KsenMartControllerShopOpenCart extends JController {

    function add_to_cart() {
        $model = $this->getModel('shopopencart');
        $model->addToCart();
        parent::display();
    }

    function update_cart() {
        $model = $this->getModel('shopopencart');
        $model->updateCart();
        parent::display();
    }

    function set_discount() {
        $cart_model = $this->getModel('shopopencart');
        $cart_model->setDiscount();
        parent::display();
    }

    function close_order() {
        $session = JFactory::getSession();
        $order_id = $session->get('shop_order_id', 0);
        if ($order_id != 0) {
            $model = $this->getModel('shopopencart');
            $model->closeOrder();
            $this->setRedirect(JRoute::_('index.php?option=com_ksenmart&view=shopopencart&layout=congratulation&Itemid=' . KMSystem::getShopItemid(), false));
        }
        parent::display();
    }
    
    public function updateOrderShippingField(){
        $jinput         = JFactory::getApplication()->input;
        $session        = JFactory::getSession();
        $oid            = $session->get('shop_order_id', 0);
        
        $data = new stdClass;
        $data->shipping_id  = $jinput->get('shipping_id', 0, 'int');
        
        KMOrders::updateOrderFields($oid, $data);
    }
    
    public function updateOrderRegionField(){
        $jinput         = JFactory::getApplication()->input;
        $session        = JFactory::getSession();
        $oid            = $session->get('shop_order_id', 0);
        
        $data = new stdClass;
        $data->region_id    = $jinput->get('region_id', 0, 'int');
        
        KMOrders::updateOrderFields($oid, $data);
    }
    
    public function updateOrderUserField(){
        $jinput         = JFactory::getApplication()->input;
        $session        = JFactory::getSession();
        $oid            = $session->get('shop_order_id', 0);
        $model          = $this->getModel('shopopencart');
        
        $name        = $jinput->get('name', null, 'string');
        $field_value = $jinput->get('field_value', null, 'string');
        
        list($type, $name) = explode('[', $name);
        list($name) = explode(']', $name);

        $fields = KMOrders::getOrderField($oid, $type);
        $fields = json_decode($fields->{$type});
        
        $fields->{$name} = $field_value;

        $session->set('com_ksenmart.'.$type.'[' . $name . ']', $field_value);
    }
    
    public function updateOrderField(){
        $jinput         = JFactory::getApplication()->input;
        $session        = JFactory::getSession();
        $oid            = $session->get('shop_order_id', 0);
        $column         = $jinput->get('column', null, 'string');
        
        if(!empty($column)){
            $data = new stdClass;
            $data->{$column}    = $jinput->get('field', 0, 'int');
            
            KMOrders::updateOrderFields($oid, $data);
        }
    }
    
    public function set_select_address_id() {
        $jinput  = JFactory::getApplication()->input;
        $model   = $this->getModel('shopprofile');
        $id      = $jinput->get('id', 0, 'int');
        $city    = $jinput->get('city', 0, 'string');
        $street  = $jinput->get('street', 0, 'string');
        $house   = $jinput->get('house', 0, 'string');
        $flat    = $jinput->get('flat', 0, 'int');
        $floor   = $jinput->get('floor', 0, 'int');
        
        $model->setSelectAddressId($id, $city, $street, $house, $flat, $floor);
        return true;
    }

    function pay_order() {
        $dispatcher = JDispatcher::getInstance();
		$dispatcher->trigger('onPayOrder');
		
		JFactory::getApplication()->close();
    }

    function get_layout() {
        $layout = JRequest::getVar('layout', null);
        if(empty($layout)){
            JFactory::getApplication()->close();
        }
        $model = $this->getModel('shopopencart');
        $view = $this->getView('shopopencart', 'html');
        $view->setLayout($layout);
        $view->cart = $model->getCart();
        $view->display();
        JFactory::getApplication()->close();
    }
	
    function renew_shipping() {
        $model = $this->getModel('shopopencart');
        $view = $this->getView('shopopencart', 'html');
		$view->setModel($model,true);
        $view->setLayout('cart_shipping');
        $view->display();
        JFactory::getApplication()->close();
    }	

    function display() {
        parent::display();
    }

}
