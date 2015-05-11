<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

KSSystem::import('models.modelkslist');
class KsenMartModelOrder extends JModelKSList {
    var $_order_id = null;
    private $_session       = null;
    private $_user          = null;	

    private $_roistatFields = array(
        'id',
        'cost',
        'price',
        'status',
        'date_add',
        'date_update',
        'date_create',
        'roistat',
        'fields'
    );

    public function __construct() {
        parent::__construct();
        $this->_session = JFactory::getSession();
        $this->_user        = KSUsers::getUser();		
        $this->context = 'com_ksenmart';
    }

    public function getShippings() {
        $this->onExecuteBefore('getShippings');
        $query
            ->select('
                s.id,
                s.title,
                s.type,
                s.regions,
                s.days,
                s.params,
                s.ordering
            ')
            ->from('#__ksenmart_shippings AS s')
            ->where('s.published=1')
            ->order('s.ordering')
        ;
        $this->_db->setQuery($query);
        $shippings = $this->_db->loadObjectList();
        
        $this->onExecuteAfter('getShippings', array(&$shippings));
        return $shippings;
    }

    public function getPayments() {
        $this->onExecuteBefore('getPayments');

        $query = $this->_db->getQuery(true);
        $query->select('
                p.id,
                p.title,
                p.description
            ')
            ->from('#__ksenmart_payments AS p')
            ->where('published=1')
            ->order('p.ordering')
        ;
        $this->_db->setQuery($query);
        $payments = $this->_db->loadObjectList();
        
        $this->onExecuteAfter('getPayments', array(&$payments));
        return $payments;
    }

    public function getRegions() {
        $query = $this->_db->getQuery(true);
        $query
            ->select('
                r.id,
                r.title,
                r.country_id,
                r.ordering
            ')
            ->from('#__ksenmart_regions AS r')
            ->where('r.published=1')
            ->order('r.ordering')
        ;
        $this->_db->setQuery($query);
        return $this->_db->loadObjectList();
    }

    public function getUser() {
        return KSUsers::getUser();
    }

    public function getProduct() {
        $id = JRequest::getVar('id', 0);
        $this->_product = KSMProducts::getProduct($id);
        return $this->_product;
    }

    public function createOrder($flag = 2) {
        $this->onExecuteBefore('createOrder', array($flag));

        $params         = JComponentHelper::getParams('com_ksenmart');
        $session        = JFactory::getSession();
        $jinput         = JFactory::getApplication()->input;
        $user           = JFactory::getUser();

        $user_id        = $user->id;
        $order_id       = $session->get('shop_order_id', null);
        $name           = $jinput->get('name', null, 'string');
        $email          = $jinput->get('email', null, 'string');
        $sendEmail      = $jinput->get('sendEmail', null, 'string');
        $address        = $jinput->get('address', null, 'string');
        $deliverycost   = $jinput->get('deliverycost', 0, 'int');
        $phone          = $jinput->get('phone', null, 'string');
        $region_id      = $jinput->get('region', 0, 'int');
        $shipping_id    = $jinput->get('shipping_type', 0, 'int');
        $payment_id     = $jinput->get('payment_type', 0, 'int');
        $ymaphtml       = $jinput->get('ymaphtml', null, 'string');
        $note           = $jinput->get('note', null, 'string');
        $prd_id         = $jinput->get('id', 0, 'int');
        $count          = $jinput->get('count', 1, 'int');
        $roistat        = $jinput->get('roistat_visit', 0, 'int');

        if(!empty($prd_id)){
            $prd        = KSMProducts::getProduct($prd_id);
        }
        
        $shipping_coords = $jinput->get('shipping_coords', 0, 'int');
        $address_fields  = $jinput->get('address_fields', 0, 'int');
        $customer_fields = $jinput->get('customer_fields', 0, 'int');
        
        $status = 2;
        if($flag == 1){
            $status = 1;
        }

        if(empty($order_id)) {
            $orders             = new stdClass();
            $orders->user_id    = $user->id;
            $orders->region_id  = $this->_db->Quote($region_id);
            $orders->status_id  = 2;
            
            try{
                $this->_db->insertObject('#__ksenmart_orders', $orders);
                $order_id = $this->_db->insertid();
            }catch(Exception $e){}
            
            
        } else {
            $orders                  = new stdClass();
            $orders->id              = $this->_db->Quote($order_id);
            $orders->user_id         = $user->id;
            $orders->region_id       = $this->_db->Quote($region_id);
            $orders->shipping_id     = $this->_db->Quote($shipping_id);
            $orders->payment_id      = $this->_db->Quote($payment_id);
            $orders->shipping_coords = $this->_db->Quote($shipping_coords);
            $orders->customer_fields = json_encode($customer_fields);
            $orders->address_fields  = json_encode($address_fields);
            $orders->note            = $this->_db->Quote($note);
            $orders->status_id       = $this->_db->Quote($status);
            $orders->roistat         = $this->_db->q($roistat);

            try{
                $result = $this->_db->updateObject('#__ksenmart_orders', $orders, 'id');
            }catch(Exception $e){}
        }

        if($prd->type == 'set') {
            $properties      = $this->getPropertiesVByIds(KSMProducts::getSetRelatedIds($prd_id));
            $item_properties = array();
            
            foreach($properties as $property) {
                $value = $jinput->get('property_' . $prd_id . '_' . $property->property_id, null, 'string');
                if(!empty($value)){
                    $item_properties[$property->property_id] = array(
                        'value_id' => $value
                    );
                }
            }
        } else {
            $properties      = $this->getPropertiesVByIds(array($prd->id));
            $item_properties = array();
            
            foreach($properties as $property) {
                $value = $jinput->get('property_' . $property->property_id, null, 'string');
                if(!empty($value)){
                    $item_properties[$property->property_id] = array(
                        'value_id' => $value
                    );
                }
            }
        }
        $order_item             = new stdClass();
        $order_item->order_id   = $order_id;
        $order_item->product_id = $prd->id;
        $order_item->price      = $prd->price;
        $order_item->count      = $count;
        $order_item->properties = json_encode($item_properties);
        
        try{
            $this->_db->insertObject('#__ksenmart_order_items', $order_item);
        }catch(Exception $e){}
        
        if($params->get('use_stock', 1) == 1) {
            $prop_values                  = new stdClass();
            $prop_values->id              = $prd->id;
            $prop_values->in_stock         = $prd->in_stock - $count;

            try{
                $result = $this->_db->updateObject('#__ksenmart_product_properties_values', $orders, 'id');
            }catch(Exception $e){}
        }
        if($flag == 1) {
            if(!empty($email)){
                KSMOrders::sendOrderMail($order_id);
            }
            KSMOrders::sendOrderMail($order_id, true);
            $session->set('shopcart_discount', '');
            $session->set('shop_order_id', null);
        } else {
            $session->set('shop_order_id', $order_id);
        }

		$cost = $prd->price * $count;
		
		$order_object = new stdClass();
		$order_object->id   = $order_id;
		$order_object->cost = $cost;

		try{
			$result = $this->_db->updateObject('#__ksenmart_orders', $order_object, 'id');
		}catch(Exception $e){}
			
        $this->onExecuteAfter('createOrder', array(&$order_id));
        return $order_id;
    }
    
    private function getPropertiesVByIds(array $ids){
        if(!empty($ids)){
            $query = $this->_db->getQuery(true);
            $query
                ->select('
                    pv.id, 
                    pv.property_id
                ')
                ->from('#__ksenmart_product_properties_values AS pv')
                ->where('(pv.product_id IN ('.implode(', ', $ids).'))')
            ;
            $this->_db->setQuery($query);
            return $this->_db->loadObjectList();
        }
        return new stdClass;
    }

    public function getOrders($editDate){
        $this->onExecuteBefore('getOrders');

        $query = $this->_db->getQuery(true);
        $query
            ->select('
                o.id,
                o.cost AS order_price,
                o.roistat,
                o.discounts,
                o.user_id,
                o.shipping_coords,
                o.customer_fields,
                o.address_fields, 
                o.status_id AS status,
                o.date_add,
                r.title AS region,
                p.title AS payment,
                s.title AS shipping,
                SUM(oi.purchase_price) AS cost
            ')
            ->from($this->_db->qn('#__ksenmart_orders', 'o'))
            ->leftjoin($this->_db->qn('#__ksenmart_order_items', 'oi') . ' ON ' . $this->_db->qn('o.id') . '=' . $this->_db->qn('oi.order_id'))
            ->leftjoin($this->_db->qn('#__ksenmart_regions', 'r') . ' ON ' . $this->_db->qn('r.id') . '=' . $this->_db->qn('o.region_id'))
            ->leftjoin($this->_db->qn('#__ksenmart_payments', 'p') . ' ON ' . $this->_db->qn('p.id') . '=' . $this->_db->qn('o.payment_id'))
            ->leftjoin($this->_db->qn('#__ksenmart_shippings', 's') . ' ON ' . $this->_db->qn('s.id') . '=' . $this->_db->qn('o.shipping_id'))
            ->where('DATE_FORMAT(' . $this->_db->qn('o.date_add') . ', \'%Y-%m-%d\') > FROM_UNIXTIME(' . $this->_db->q($editDate) . ', \'%Y-%m-%d\')')
            ->group($this->_db->qn('oi.order_id'))
        ;
        $this->_db->setQuery($query);
        $orders = $this->_db->loadObjectList();
        
        foreach ($orders as &$order) {
            KSMOrders::setUserInfoField2Order($order);
            $date = new JDate($order->date_add);
            $order->date_create  = $date->toUnix();
            $order->date_update  = $order->date_create;
            $order->price        = $order->order_price;
            
            unset($order->order_price);
            unset($order->date_add);

            $order->fields = array();
            foreach ($order as $key => &$field) {
                if(!in_array($key, $this->_roistatFields)){
                    $key_name = JText::_('KSM_ROISTAT_FIELDS_' . strtoupper($key) . '_TITLE');
                    if(is_array($field)){
                        $field = implode(', ', $field);
                    }elseif(is_object($field)){
                        $field = JArrayHelper::fromObject($field);
                        $field = implode(', ', $field);
                    }
                    $order->fields[$key_name] = $field;
                    unset($order->{$key});
                }
            }
        }

        $this->onExecuteAfter('getOrders', array(&$orders));
        return $orders;
    }

    public function getOrderStatuses(){
        $this->onExecuteBefore('getOrderStatus');

        $statuses = new stdClass;
        $query = $this->_db->getQuery(true);
        $query
            ->select(
                $this->_db->qn(
                    array(
                        'os.id',
                        'os.title',
                        'os.system'
                    ),
                    array(
                        'id',
                        'name',
                        'system'
                    )
                )
            )
            ->from($this->_db->qn('#__ksenmart_order_statuses', 'os'))
        ;
        $this->_db->setQuery($query);
        $statuses = $this->_db->loadObjectList();

        $this->onExecuteAfter('getOrderStatus', array(&$statuses));
        return $statuses;
    }
	
	function getCustomerFields(){
		$app = JFactory::getApplication();
		$query = $this->_db->getQuery(true);
		$query
			->select('
				sf.id,
				sf.shipping_id,
				sf.position,
				sf.type,
				sf.title,
				sf.required,
				sf.system,
				sf.ordering
			')
			->from('#__ksenmart_shipping_fields AS sf')
			->where('sf.shipping_id=0')
			->where('sf.position=' . $this->_db->quote('customer'))
			->where('sf.published=1')
			->order('sf.ordering')
		;
		$this->_db->setQuery($query);
		$fields = $this->_db->loadObjectList();	
		
		$fields_new = array();
		$field_o    = new stdClass;		
		foreach($fields as &$field) {
			$user_value    = null;
			$field->class  = $field->required == 1 ? 'required' : '';
			
			if(isset($this->_user->{$field->title})){
				$user_value =  $this->_user->{$field->title};
			}
			
			$field_name = $field->title;
			if(!$field->system){
				$field_name = $field->id;
			}

			$field->value             = $app->getUserState($this->context . '.customer_fields[' . $field_name . ']', $user_value);
			$fields_new[$field->id]   = $field;
			$field_o->{$field_name}   = $field->value;
		}

		return $fields_new;
	}
}