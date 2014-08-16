<?php defined('_JEXEC') or die;

class KSMOrders {
    public static function getOrder($oid) {
        if (!empty($oid)) {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->select('
                    o.id,
                    o.cost,
                    o.discounts,
                    o.user_id,
                    o.region_id,
                    o.shipping_id,
                    o.shipping_coords,
                    o.customer_fields,
                    o.address_fields, 
                    o.payment_id,
                    o.note,
                    o.status_id,
                    o.date_add,
                    os.title AS status_name
                ')->from('#__ksenmart_orders AS o')->leftjoin('#__ksenmart_order_statuses AS os ON o.status_id=os.id')->where('o.id=' . $db->escape($oid));
            $db->setQuery($query);
            $order = $db->loadObject();
            KSMOrders::setUserInfoField2Order($order);
            
            $query = $db->getQuery(true);
            $query->select('*')->from('#__ksenmart_order_items')->where('order_id=' . $oid);
            $db->setQuery($query);
            $order->items = $db->loadObjectList();
            
            $order->costs = array(
                'cost' => KSMPrice::getPriceInDefaultCurrency($order->cost), 
                'cost_val' => KSMPrice::showPriceWithTransform($order->cost), 
                'discount_cost' => 0, 
                'discount_cost_val' => KSMPrice::showPriceWithTransform(0), 
                'shipping_cost' => 0, 
                'shipping_cost_val' => KSMPrice::showPriceWithTransform(0), 
                'total_cost' => KSMPrice::getPriceInDefaultCurrency($order->cost), 
                'total_cost_val' => KSMPrice::showPriceWithTransform($order->cost)
            );
            
            $dispatcher = JDispatcher::getInstance();
            $dispatcher->trigger('onAfterGetOrder', array(&$order));
            
            return $order;
        }
        return new stdClass;
    }
    
    private static function setOrderItemsProperties(&$order, $oid) {
        if (!empty($order)) {
            foreach ($order as & $item) {
                if (!empty($item->properties)) {
                    $item->properties = json_decode($item->properties);
                    foreach ($item->properties as & $property) {
                        
                        $db = JFactory::getDBO();
                        $query = $db->getQuery(true);
                        
                        $query->select('
                            o.properties,
                            p.title AS prop_title,
                            pv.title AS prop_value_title
                        ');
                        $query->from('#__ksenmart_order_items AS o');
                        $query->leftjoin('#__ksenmart_properties AS p ON p.id=' . $db->escape($property->title));
                        $query->leftjoin('#__ksenmart_property_values AS pv ON pv.id=' . $db->escape($property->value));
                        $query->where('o.order_id=' . $db->escape($oid));
                        
                        $db->setQuery($query);
                        
                        $property_tmp = $db->loadObject();
                        $property->title = $property_tmp->prop_title;
                        $property->value = $property_tmp->prop_value_title;
                    }
                }
                continue;
            }
            return $order;
        }
        return new stdClass;
    }
    
    public static function getOrderItems($oid) {
        if (!empty($oid)) {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->select('
                o.id,
                o.order_id,
                o.product_id,
                o.price,
                o.count,
                o.properties
            ');
            $query->from('#__ksenmart_order_items AS o');
            $query->where('o.order_id=' . $db->escape($oid));
            $db->setQuery($query);
            $order = $db->loadObjectList();
            
            if (!empty($order)) {
                KSMOrders::setOrderItemsProperties($order, $oid);
                foreach ($order as $o_item) {
                    $o_item->product = KSMProducts::getProduct($o_item->product_id);
                }
            }
            
            return $order;
        }
        return new stdClass;
    }
    
    public static function setUserInfoField2Order(&$order) {
        if (!empty($order)) {
            $order->customer_fields = json_decode($order->customer_fields);
            $order->address_fields = (array )json_decode($order->address_fields);
            
            if (!empty($order->address_fields)) {
                $order->address_fields = implode(', ', $order->address_fields);
            }
            
            return $order;
        }
        return new stdClass;
    }
    
    public static function sendOrderMail($order_id, $admin = false) {
        JRequest::setVar('id', $order_id);
        $model = KSSystem::getModel('orders');
        $order = $model->getOrder();
        KSMOrders::setOrderItemsProperties($order, $order_id);
        if (!empty($order->address_fields)) {
            $order->address_fields = implode(', ', $order->address_fields);
        }
        $mail = JFactory::getMailer();
        $params = JComponentHelper::getParams('com_ksenmart');
        $sender = array($params->get('shop_email'), $params->get('shop_name'));
        
        $content = KSSystem::loadTemplate(array('order' => $order), 'order', 'default', 'mail');
        
        $mail->isHTML(true);
        $mail->setSender($sender);
        $mail->Subject = 'Новый заказ №' . $order_id;
        $mail->Body = $content;
        
        if (!$admin) {
            $mail->AddAddress($order->customer_fields['email'], $order->customer_fields['name']);
        } else {
            $mail->AddAddress($params->get('shop_email'), $params->get('shop_name'));
        }
        
        $mail->Send();
        return true;
    }
    
    public static function getPrintformDate($date) {
        return date('d.m.Y', strtotime($date));
    }
    
    public static function getPrintformCustomerName($customer_fields) {
        $customer_name = '';
        if (isset($customer_fields['name']) && !empty($customer_fields['name'])) $customer_name.= $customer_fields['name'] . ' ';
        if (isset($customer_fields['last_name']) && !empty($customer_fields['last_name'])) $customer_name.= $customer_fields['last_name'] . ' ';
        if (isset($customer_fields['first_name']) && !empty($customer_fields['first_name'])) $customer_name.= $customer_fields['first_name'] . ' ';
        if (isset($customer_fields['middle_name']) && !empty($customer_fields['middle_name'])) $customer_name.= $customer_fields['middle_name'];
        if (empty($customer_name)) $customer_name = JText::_('ksm_orders_default_customer_info');
        return $customer_name;
    }
    
    public static function getPrintformCustomerAddress($address_fields) {
        $customer_address = '';
        if (isset($address_fields['city']) && !empty($address_fields['city'])) $customer_address.= $address_fields['city'] . ' ';
        if (isset($address_fields['street']) && !empty($address_fields['street'])) $customer_address.= $address_fields['street'] . ' ';
        if (isset($address_fields['house']) && !empty($address_fields['house'])) $customer_address.= $address_fields['house'];
        if (isset($address_fields['flat']) && !empty($address_fields['flat'])) $customer_address.= $address_fields['flat'];
        if (empty($customer_address)) $customer_address = JText::_('ksm_orders_default_customer_address');
        return $customer_address;
    }
    
    public static function updateOrderFields($oid, $data) {
        
        if (!empty($data) && !empty($oid) && $oid > 0) {
            $db = JFactory::getDbo();
            
            $data->id = $oid;
            
            try {
                $db->updateObject('#__ksenmart_orders', $data, 'id');
                return true;
            }
            catch(exception $e) {
            }
        }
        return false;
    }
    
    public static function getOrderField($oid, $field) {
        if (!empty($oid) && !empty($field)) {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->select(KSDb::quoteName('o.' . $field))->from('#__ksenmart_orders AS o')->where(KSDb::quoteName('o.id') . '=' . $db->escape($oid));
            $db->setQuery($query);
            return $db->loadObject();
        }
        return false;
    }
    
    public function setOrderField() {
    }
}
