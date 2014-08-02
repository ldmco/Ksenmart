<?php defined('_JEXEC') or die;

KSSystem::import('models.modelkslist');
class KsenMartModelOrder extends JModelKSList {
    var $_order_id = null;

    public function __construct() {
        parent::__construct();
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
        $row = KSMProducts::getProduct($id);
        $query = "select kp.*,kpp.value as `values` from #__ksenmart_properties as kp,#__ksenmart_product_properties as kpp where kpp.product='$row->id' and kp.id=kpp.property_id and kp.type!='text' and kpp.value!='' order by kp.ordering";
        $this->_db->setQuery($query);
        $properties = $this->_db->loadObjectList();
        for($i = 0; $i < count($properties); $i++) {
            if($properties[$i]->type == 'select' || $properties[$i]->type == 'radio') {
                $properties[$i]->values = str_replace('||', ',', $properties[$i]->values);
                $properties[$i]->values = str_replace('|', '', $properties[$i]->values);
                $query = "select * from #__ksenmart_property_values where id in ({$properties[$i]->values})";
                $this->_db->setQuery($query);
                $properties[$i]->values = $this->_db->loadObjectList();
                for($k = 0; $k < count($properties[$i]->values); $k++) {
                    if(JRequest::getVar('property_' . $properties[$i]->id, '') == $properties[$i]->values[$k]->id) $properties[$i]->values[$k]->selected = 1;
                    else  $properties[$i]->values[$k]->selected = 0;
                }
            } else {
                if(JRequest::getVar('property_' . $properties[$i]->id, '') == 1) $properties[$i]->selected = 1;
                else  $properties[$i]->selected = 0;
            }
        }
        $row->properties = $properties;
        $this->_product = $row;
        return $row;
    }

    public function createOrder($flag = 2) {
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
        if(!empty($prd_id)){
            $prd        = KSMProducts::getProduct($prd_id);
        }
        
        $shipping_coords = $jinput->get('shipping_coords', 0, 'int');
        $address_fields  = $jinput->get('address_fields', 0, 'int');
        $customer_fields = $jinput->get('customer_fields', 0, 'int');
        
        $session->set($this->context . '.customer_fields[name]', $name);
        $session->set($this->context . '.customer_fields[phone]', $phone);
        $session->set($this->context . '.customer_fields[email]', $email);
        
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
                $this->_db->query();
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
                    $item_properties[] = array(
                        'title' => $property->property_id, 
                        'value' => $value
                    );
                }
            }
        } else {
            $properties      = $this->getPropertiesVByIds(array($prd->id));
            $item_properties = array();
            
            foreach($properties as $property) {
                $value = $jinput->get('property_' . $property->property_id, null, 'string');
                if(!empty($value)){
                    $item_properties[] = array(
                        'title' => $property->property_id, 
                        'value' => $value
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
                KSMOrders::sendOrderMail($this->order_id);
            }
            KSMOrders::sendOrderMail($this->order_id, true);
            $session->set('shopcart_discount', '');
            $session->set('shop_order_id', null);
        } else {
            $session->set('shop_order_id', $order_id);
        }
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
}