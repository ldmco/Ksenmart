<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

KSSystem::import('models.modelkslist');
class KsenMartModelCart extends JModelKSList {

    var $order_id = null;
    
    private $_session       = null;
    private $_region_id     = null;
    private $_shipping_id   = null;
	private $_payment_id    = null;
    private $_user          = null;

    public function __construct() {
        $this->_session = JFactory::getSession();
        $this->order_id = $this->_session->get('shop_order_id', 0);
        
        $this->_user        = KSUsers::getUser();
        parent::__construct();
        
        $this->context  = 'com_ksenmart';
        $this->getDefaultStates();
    }
    
    private function getDefaultStates(){
        $this->_region_id   = $this->getState('region_id');
        $this->_shipping_id = $this->getState('shipping_id');
		$this->_payment_id  = $this->getState('payment_id');
    }

    protected function populateState($ordering = null, $direction = null){
        $this->onExecuteBefore('populateState', array(&$this));

        $app = JFactory::getApplication();

        $region_id       = (int)$app->getUserStateFromRequest($this->context . '.region_id', 'region_id', $this->_user->region_id);
        $shipping_id     = (int)$app->getUserStateFromRequest($this->context . '.shipping_id', 'shipping_id', 0);
		$payment_id      = (int)$app->getUserStateFromRequest($this->context . '.payment_id', 'payment_id', 0);
        $shipping_coords = $this->_session->get($this->context . '.shipping_coords', array());
        $shipping_coords = implode(',', $shipping_coords);
        
        $this->setState('region_id', $region_id);
        $this->setState('shipping_id', $shipping_id);
		$this->setState('payment_id', $payment_id);
        $this->setState('shipping_coords', $shipping_coords);
        
        $this->onExecuteAfter('populateState', array(&$this));
    }

    public function getRegions() {
        $this->onExecuteBefore('getRegions');
        
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
        $regions = $this->_db->loadObjectList();
        foreach($regions as &$region){
            $region->selected = false;
            if($region->id == $this->_region_id){
                $region->selected = true;
            }
        }
        
        $this->onExecuteAfter('getRegions', array(&$regions));
        return $regions;
    }

    public function getAddresses() {
        return KSUsers::getAddresses();
    }

    public function getShippings() {
        $this->onExecuteBefore('getShippings');

        $shipping_selected  = 0;
        $shippings          = array();

        if(!empty($this->_region_id)){
            $query = $this->_db->getQuery(true);
            $query
                ->select('
                    s.id,
                    s.title,
					s.introcontent,
                    s.type,
                    s.regions,
                    s.days,
                    s.ordering
                ')
                ->from('#__ksenmart_shippings AS s')
                ->where('s.published=1')
                ->order('s.ordering')
            ;
			$query = KSMedia::setItemMainImageToQuery($query, 'shipping', 's.');
            
            $this->_db->setQuery($query);
            $rows = $this->_db->loadObjectList();
            
            foreach($rows as $row) {
                $row->icon    = !empty($row->filename)?KSMedia::resizeImage($row->filename, $row->folder, 20, 20, json_decode($row->params, true)):'';
                $row->regions = json_decode($row->regions, true);
                foreach($row->regions as $country) {
                    if(in_array($this->_region_id, $country)) {
                        $row->selected      =  false;
                        $shipping_selected  = $shipping_selected;
                        if($row->id == $this->_shipping_id){
                            $row->selected      =  true;
                            $shipping_selected  = $row->id;
                        }
                        $shippings[] = $row;
                    }
                }
            }
        }
		$this->setState('shipping_id', $shipping_selected);
		$this->_session->set($this->context . '.shipping_id', $shipping_selected);		
        
        $this->onExecuteAfter('getShippings', array(&$shippings));
        return $shippings;
    }

    public function getCustomerFields() {
        $this->onExecuteBefore('getCustomerFields');
        
        $customer_fields = $this->getFieldsOrder('customer', 'customer_fields');
        
        $this->onExecuteAfter('getCustomerFields', array(&$customer_fields));
        return $customer_fields;
    }

    public function getAddressFields() {
        $this->onExecuteBefore('getAddressFields');
        
        $address_fields = $this->getFieldsOrder('address', 'address_fields');
        
        $this->onExecuteAfter('getAddressFields', array(&$address_fields));
        return $address_fields;
    }
    
    private function getFieldsCatOrder($position){
        $this->onExecuteBefore('getFieldsCatOrder', array(&$position));

        if(!empty($position)){       
            
            $query              = $this->_db->getQuery(true);
            $this->_shipping_id = $this->getState('shipping_id');
            
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
                ->where('sf.shipping_id=' . (int)$this->_shipping_id)
                ->where('sf.position=' . $this->_db->quote($position))
                ->where('sf.published=1')
                ->order('sf.ordering')
            ;
            $this->_db->setQuery($query);
            $fields = $this->_db->loadObjectList();
            
            $this->onExecuteAfter('getFieldsCatOrder', array(&$fields));
            return $fields;
        }
        return new stdClass;
    }
    
    private function getFieldsValuesOrder($where){
        $this->onExecuteBefore('getFieldsValuesOrder', array(&$where));

        if(!empty($where)){
            $query = $this->_db->getQuery(true);
            $query
                ->select('
                    sfv.id,
                    sfv.field_id,
                    sfv.title
                ')
                ->from('#__ksenmart_shipping_fields_values AS sfv')
                ->where('(sfv.field_id IN(' . implode(', ', $where).'))')
            ;
            $this->_db->setQuery($query);
            $values = $this->_db->loadObjectList();
            
            $this->onExecuteAfter('getFieldsValuesOrder', array(&$values));
            return $values;
        }
        return false;
    }
    
    private function getFieldsOrder($position, $type){
        $this->onExecuteBefore('getFieldsOrder', array(&$position, &$type));
		$app = JFactory::getApplication();
        if(!empty($position)){
            
            $fields     = $this->getFieldsCatOrder($position);
            if($fields){
                $where      = array();
                $fields_new = array();
                $flag       = false;
                $field_o    = new stdClass;
                
                foreach($fields as &$field) {
                    $user_value    = null;
                    $field->class  = $field->required == 1 ? 'required' : '';
                    
                    if(isset($this->_user->{$field->title})){
                        $user_value =  $this->_user->{$field->title};
                    }

                    if($field->type == 'select') {
                        $flag = true;
                        $where[] = $field->id;
                    }
                    
                    $field_name = $field->title;
                    if(!$field->system){
                        $field_name = $field->id;
                    }

                    $field->value             = $app->getUserState($this->context . '.'.$type.'[' . $field_name . ']', $user_value);
                    $fields_new[$field->id]   = $field;
                    $field_o->{$field_name}   = $field->value;
                }
                
                if($flag){
                    $values = $this->getFieldsValuesOrder($where);
                    if(!empty($values)){
                        foreach($values as $value){
                            if(isset($fields_new[$value->field_id])){
                                $fields_new[$value->field_id]->values[] = $value;
                            }
                            continue;
                        }
                    }
                }

                $data           = new stdClass;
                $data->{$type}  = json_encode($field_o);
        
                KSMOrders::updateOrderFields($this->order_id, $data);
                
                $this->onExecuteAfter('getFieldsOrder', array(&$fields_new));
                return $fields_new;
            }
        }
        return false;
    }

    public function getPayments() {
        $this->onExecuteBefore('getPayments');

        $payment_selected  = 0;
        $payments          = array();

        if(!empty($this->_region_id)){
            $query = $this->_db->getQuery(true);
            $query
                ->select('
                    p.id,
                    p.title,
                    p.type,
                    p.regions,
                    p.params,
                    f.filename,
                    f.folder,
                    f.params AS params_f,
                    p.ordering
                ')
                ->from('#__ksenmart_payments AS p')
                ->leftjoin('#__ksenmart_files AS f ON f.owner_type='.$this->_db->quote('payment').' AND f.owner_id=p.id')
                ->where('p.published=1')
                ->order('p.ordering')
            ;
            
            $this->_db->setQuery($query);
            $rows = $this->_db->loadObjectList();
            
            foreach($rows as $row) {
                $row->icon    = !empty($row->filename)?KSMedia::resizeImage($row->filename, $row->folder, 160, 80, json_decode($row->params_f, true)):'';
                $row->regions = json_decode($row->regions, true);
                foreach($row->regions as $country) {
                    if(in_array($this->_region_id, $country)) {
                        $row->selected      =  false;
                        $payment_selected  = $payment_selected;
                        if($row->id == $this->_payment_id){
                            $row->selected      =  true;
                            $payment_selected  = $row->id;
                        }
                        $payments[] = $row;
                    }
                }
            }
            $this->setState('payment_id', $payment_selected);
            $this->_session->set($this->context . '.payment_id', $payment_selected);
        }
		
        $this->onExecuteAfter('getPayments', array(&$payments));
        return $payments;
    }

    public function getOrderInfo() {
        $this->onExecuteBefore('getOrderInfo');

        if(!empty($this->order_id)) {
			$order = KSMOrders::getOrder($this->order_id);

			$this->onExecuteAfter('getOrderInfo', array(&$order));	
            return $order;
        }
        return new stdClass;
    }
    
    private function setDefaultCartValues(&$cart){
        $this->onExecuteBefore('setDefaultCartValues', array(&$cart));
        
        if(!$cart){
            $cart = new stdClass;
        }
        
        $cart->total_prds       = 0;
        $cart->products_sum     = 0;
        $cart->shipping_sum     = 0;
        $cart->discount_sum     = 0;
        $cart->total_sum        = 0;
        $cart->products_sum_val = '';
        $cart->shipping_sum_val = '';
        $cart->discount_sum_val = '';
        $cart->items            = array();
        
        $this->onExecuteAfter('setDefaultCartValues', array(&$cart));
        return $cart;
    }

    public function getCart() {
        $this->onExecuteBefore('getCart');

        $Itemid                 = KSSystem::getShopItemid();

        if(!empty($this->order_id)){
            $cart        = KSMOrders::getOrder($this->order_id);
            $this->setDefaultCartValues($cart);
            $cart->items = KSMOrders::getOrderItems($this->order_id);
            
            for($k = 0; $k < count($cart->items); $k++) {
                $cart->items[$k]->del_link   = JRoute::_('index.php?option=com_ksenmart&view=cart&task=cart.update_cart&item_id=' . $cart->items[$k]->id . '&count=0&Itemid=' . $Itemid);
                $cart->total_prds          += $cart->items[$k]->count;
                $cart->products_sum        += $cart->items[$k]->count * $cart->items[$k]->price;
            }
        }else{
            $this->setDefaultCartValues($cart);
        }
        
        $cart->products_sum_val = KSMPrice::showPriceWithTransform($cart->products_sum);
        $cart->total_sum        = $cart->products_sum;
        
        $cart->total_sum_val    = KSMPrice::showPriceWithTransform($cart->total_sum);

        $this->onExecuteAfter('getCart', array(&$cart));
        return $cart;
    }
    
    public function getProperties($public = true){
        $this->onExecuteBefore('getProperties');

        $query = $this->_db->getQuery(true);
        $query
            ->select('
                p.id, 
                p.alias,
                p.title,
                p.type,
                p.view,
                p.default,
                p.prefix,
                p.suffix,
                p.edit_price,
                p.ordering
            ')
            ->from('#__ksenmart_properties AS p')
            ->where('p.published=1')
        ;
        $this->_db->setQuery($query);
        $poperties = $this->_db->loadObjectList();
        
        $this->onExecuteAfter('getProperties', array(&$poperties));
        return $poperties;
    }

    public function addToCart() {
        $this->onExecuteBefore('addToCart');

        $jinput     = JFactory::getApplication()->input;
        
        $count      = $jinput->get('count', 1);
        $price      = $jinput->get('price', 0, 'float');
        $id         = $jinput->get('id', 0, 'int');
        $prd        = KSMProducts::getProduct($id);
        
        if($this->order_id == 0) {
            $order_object = new stdClass();
            $order_object->user_id   = $this->_user->id;
            $order_object->region_id = $this->_region_id;
            $order_object->shipping_id   = $this->_shipping_id;
            $order_object->payment_id = $this->_payment_id;				
            $order_object->status_id = 2;
    
            try{
                $result         = $this->_db->insertObject('#__ksenmart_orders', $order_object);
                $this->order_id = $this->_db->insertid();
                $this->_session->set('shop_order_id', $this->order_id);
            }catch(Exception $e){}
        }
        
        if(count($prd) > 0 && ($count <= $prd->in_stock || $this->params->get('use_stock', 1) == 0)) {
            if($price == 0) $price = $prd->val_price_wou;
            if($prd->type == 'set') {
                $related         = KSMProducts::getSetRelated($id);
                $properties      = $this->getProperties();
                $item_properties = array();
                
                foreach($properties as $property) {
                    foreach($related as $r) {
                        $value = JRequest::getVar('property_' . $r->relative_id . '_' . $property->id, '');
                        if(!empty($value)){
                            $item_properties[$property->id] = array('value_id' => $value);
                        }
                    }
                }
            } else {
                $properties      = $this->getProperties();
                $item_properties = array();
                
                foreach($properties as $property) {
                    $value = JRequest::getVar('property_' . $id . '_' . $property->id, '');
                    if(!empty($value)){
                        $item_properties[$property->id] = array('value_id' => $value);
                    }
                }
            }
            
            $query = $this->_db->getQuery(true);
            $query
                ->select('
                    p.id, 
                    p.order_id,
                    p.product_id,
                    p.basic_price,
                    p.price,
                    p.count,
                    p.properties
                ')
                ->from('#__ksenmart_order_items AS p')
                ->where('order_id='.$this->order_id)
                ->where('product_id='.$id)
                ->where('properties=\''.json_encode($item_properties).'\'')
            ;

            $this->_db->setQuery($query);
            $item = $this->_db->loadObject();
            if(count($item) > 0) {
                $order_item_object = new stdClass();
                $order_item_object->id          = $item->id;
                $order_item_object->count       = $item->count+$count;
                $order_item_object->properties  = json_encode($item_properties);
        
                try{
                    $result = $this->_db->updateObject('#__ksenmart_order_items', $order_item_object, 'id');
                }catch(Exception $e){}
            } else {
                $order_item_object = new stdClass();
                $order_item_object->order_id    = $this->order_id;
                $order_item_object->product_id  = $prd->id;
                $order_item_object->price       = $price;
                $order_item_object->count       = $count;
                $order_item_object->properties  = json_encode($item_properties);
        
                try{
                    $result = $this->_db->insertObject('#__ksenmart_order_items', $order_item_object);
                }catch(Exception $e){}
            }

            if($this->params->get('use_stock', 1) == 1) {
                $product_object = new stdClass();
                $product_object->id          = $prd->id;
                $product_object->in_stock    = $prd->in_stock-$count;
        
                try{
                    $result = $this->_db->updateObject('#__ksenmart_products', $product_object, 'id');
                }catch(Exception $e){}
            }
            $cost = $price * $count;
            
            $order_object = new stdClass();
            $order_object->id   = $this->order_id;
            $order_object->cost = $this->getOrderCost($this->order_id) + $cost;
    
            try{
                $result = $this->_db->updateObject('#__ksenmart_orders', $order_object, 'id');
            }catch(Exception $e){}
            
            $this->onExecuteAfter('addToCart', array(&$result));
            return true;
        }
        return false;
    }
    
    private function getOrderCost($order_id){
        $this->onExecuteBefore('getOrderCost', array(&$order_id));

        if(!empty($order_id)){
            $query = $this->_db->getQuery(true);
            $query
                ->select('
                    o.cost
                ')
                ->from('#__ksenmart_orders AS o')
                ->where('o.id='.$this->_db->escape($order_id))
            ;
            $this->_db->setQuery($query);
            $cost = $this->_db->loadObject()->cost;
            
            $this->onExecuteAfter('getOrderCost', array(&$cost));
            return $cost;
        }
        return 0;
    }

    public function updateCart() {
        $this->onExecuteBefore('updateCart');

        $params     = JComponentHelper::getParams('com_ksenmart');
        $count      = JRequest::getVar('count', 0);
        $price      = JRequest::getVar('price', 0);
        $item_id    = JRequest::getVar('item_id', 0);
        $item       = KSSystem::getTableByIds(array($item_id), 'order_items', array(
            't.id',
            't.order_id',
            't.price',
            't.count',
            't.product_id'
        ), false);
        $item       = $item[0];

        if($price != 0 && $price != $item->price) {
            $order_item_object          = new stdClass();
            $order_item_object->id      = $item_id;
            $order_item_object->price   = $price;
            $order_item_object->count   = $count;
    
            try{
                $result = $this->_db->updateObject('#__ksenmart_order_items', $order_item_object, 'id');
            }catch(Exception $e){}

            $diff        = ($price - $item->price) * $item->count;
            $item->price = $price;
            
            $order_object        = new stdClass();
            $order_object->id    = $item->order_id;
            $order_object->cost  = $this->getOrderCost($this->order_id) + $diff;
    
            try{
                $result = $this->_db->updateObject('#__ksenmart_orders', $order_object, 'id');
            }catch(Exception $e){}
        }

        $prd = KSMProducts::getProduct($item->product_id);
        $item_properties = array();

        foreach($prd->properties as $property) {
            $value = JRequest::getVar('property_' . $property->id, '');
            if(!empty($value)){
                $item_properties[] = $property->id . ':' . $value;
            }
        }

        $order_item_object             = new stdClass();
        $order_item_object->id         = $item_id;
        $order_item_object->properties = json_encode($item_properties);

        try{
            $result = $this->_db->updateObject('#__ksenmart_order_items', $order_item_object, 'id');
        }catch(Exception $e){}

        $diff_count = $count - $item->count;
        $diff       = ($count - $item->count) * $item->price;
        
        if($count == 0){
            $query = $this->_db->getQuery(true);
            $conditions = array(
                'id='.$this->_db->escape($item_id)
            );
            
            $query
                ->delete(KSDb::quoteName('#__ksenmart_order_items'))
                ->where($conditions);
            ;
            
            $this->_db->setQuery($query);
            
            try {
                $result = $this->_db->query(); // $this->_db->execute(); for Joomla 3.0.
            } catch (Exception $e) {} 
        }else{
            $order_item_object        = new stdClass();
            $order_item_object->id    = $item_id;
            $order_item_object->count = $count;
    
            try{
                $result = $this->_db->updateObject('#__ksenmart_order_items', $order_item_object, 'id');
            }catch(Exception $e){}
        }

        if($params->get('use_stock', 1)) {
            $product_object = new stdClass();
            $product_object->id          = $item->product_id;
            $product_object->in_stock    = $prd->in_stock-$diff_count;
    
            try{
                $result = $this->_db->updateObject('#__ksenmart_products', $product_object, 'id');
            }catch(Exception $e){}
        }
        
        $order_object        = new stdClass();
        $order_object->id    = $item->order_id;
        $order_object->cost  = $this->getOrderCost($item->order_id) + $diff;

        try{
            $result = $this->_db->updateObject('#__ksenmart_orders', $order_object, 'id');
        }catch(Exception $e){}
        
        $this->onExecuteAfter('updateCart', array(&$this));
        return true;
    }

    public function getSystemCustomerFields() {
        return array(
            'phone',
            'name',
            'email',
            'lastname',
            'surname'
        );
    }

    public function closeOrder() {
        $this->onExecuteBefore('closeOrder');

        $cart            = $this->getCart();
        $order           = new stdClass();
        $jinput          = JFactory::getApplication()->input;
        $customer_fields = $jinput->get('customer_fields', array(), 'array');
        $address_fields  = $jinput->get('address_fields', array(), 'array');

        $order->id              = $this->order_id;
        $order->status_id       = 1;
        $order->region_id       = $jinput->get('region_id', 0, 'int');
        $order->shipping_id     = $jinput->get('shipping_id', 0, 'int');
        $order->payment_id      = $jinput->get('payment_id', 0, 'int');
        $order->shipping_coords = $jinput->get('shipping_coords', 0, 'string');
        $order->customer_fields = json_encode($customer_fields);
        $order->address_fields  = json_encode($address_fields);
        $order->note            = $jinput->get('note', null, 'string');

        try {
            $result = $this->_db->updateObject('#__ksenmart_orders', $order, 'id');
        }
        catch (exception $e) {}

        try {
            $result = $this->_db->updateObject('#__ksenmart_orders', $order, 'id');
        }
        catch (exception $e) {}

        if(!empty($customer_fields['email'])) {
            KSMOrders::sendOrderMail($this->order_id);
        }
        KSMOrders::sendOrderMail($this->order_id, true);		
        $this->onExecuteAfter('closeOrder');
				
        return true;
    }

    public function clearCart() {
        $this->onExecuteBefore('clearCart', array(&$this));

        $this->_session = &JFactory::getSession();
        $this->_session->set('shopcart_discount', '');
        $this->_session->set('shop_order_id', null);
        
        $this->onExecuteAfter('clearCart', array(&$this));
        return true;
    }
}