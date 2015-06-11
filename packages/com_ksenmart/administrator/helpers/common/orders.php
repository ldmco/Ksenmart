<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

KSSystem::import('helpers.corehelper');
class KSMOrders extends KSCoreHelper {
    public static function getOrder($oid) {
        
        self::onExecuteBefore(array(&$oid));
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
            self::setUserInfoField2Order($order);
            
            $query = $db->getQuery(true);
            $query->select('*')->from('#__ksenmart_order_items')->where('order_id=' . $oid);
            $db->setQuery($query);
            $order->items = $db->loadObjectList();
            
            $order->costs = array(
                'cost'              => KSMPrice::getPriceInDefaultCurrency($order->cost),
                'cost_val'          => KSMPrice::showPriceWithTransform($order->cost),
                'discount_cost'     => 0,
                'discount_cost_val' => KSMPrice::showPriceWithTransform(0),
                'shipping_cost'     => 0,
                'shipping_cost_val' => KSMPrice::showPriceWithTransform(0),
                'total_cost'        => KSMPrice::getPriceInDefaultCurrency($order->cost),
                'total_cost_val'    => KSMPrice::showPriceWithTransform($order->cost),
            );
            
            $dispatcher = JDispatcher::getInstance();
            $dispatcher->trigger('onAfterGetOrder', array(&$order));
            self::onExecuteAfter(array(&$order));
            
            return $order;
        }
        return new stdClass;
    }
    
    private static function setOrderItemsProperties(&$order, $oid) {
        
        self::onExecuteBefore(array($order, &$oid));
        if (!empty($order)) {
            foreach ($order as & $item) {
                if (!empty($item->properties)) {
                    $item->properties = json_decode($item->properties);
                    foreach ($item->properties as $key => & $property) {
                        
                        $db = JFactory::getDBO();
                        $query = $db->getQuery(true);
                        
                        $query->select('
                            o.properties,
                            p.title AS prop_title,
                            pv.title AS prop_value_title
                        ');
                        $query->from('#__ksenmart_order_items AS o');
                        $query->leftjoin('#__ksenmart_properties AS p ON p.id=' . $db->q($key));
                        $query->leftjoin('#__ksenmart_property_values AS pv ON pv.id=' . $db->q($property->value_id));
                        $query->where('o.order_id=' . $db->q($oid));
                        
                        $db->setQuery($query);
                        
                        $property_tmp = $db->loadObject();
                        $property->title = $property_tmp->prop_title;
                        $property->value = $property_tmp->prop_value_title;
                    }
                }
                continue;
            }
            
            self::onExecuteAfter(array($order));
            return $order;
        }
        return new stdClass;
    }
    
    public static function getOrderItems($oid) {
        
        self::onExecuteBefore(array(&$oid));
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
                self::setOrderItemsProperties($order, $oid);
                foreach ($order as $o_item) {
                    $o_item->product = KSMProducts::getProduct($o_item->product_id);
                }
            }
            self::onExecuteAfter(array($order));
            return $order;
        }
        return new stdClass;
    }
    
    public static function setUserInfoField2Order(&$order) {
        
        self::onExecuteBefore(array($order));
        if (!empty($order)) {
            $order->customer_fields = json_decode($order->customer_fields);
            $order->address_fields = (array )json_decode($order->address_fields);
            
            if (!empty($order->address_fields)) {
                $order->address_fields = implode(', ', $order->address_fields);
            }
            
            self::onExecuteAfter(array($order));
            return $order;
        }
        return new stdClass;
    }
    
    public static function sendOrderMail($order_id, $admin = false) {

        self::onExecuteBefore(array(&$order_id, &$admin));
        JRequest::setVar('id', $order_id);
        $model = KSSystem::getModel('orders');
        $order = $model->getOrder();
		$order->items = KSMOrders::getOrderItems($order_id);
		
        if (!empty($order->address_fields)) {
            $order->address_fields = implode(', ', $order->address_fields);
        } else {
            $order->address_fields = '';
        }
		
        $order->customer_name = '';
        if (isset($order->customer_fields['name']) && !empty($order->customer_fields['name'])) $order->customer_name.= $order->customer_fields['name'];
        if (isset($order->customer_fields['last_name']) && !empty($order->customer_fields['last_name'])) $order->customer_name.= $order->customer_fields['last_name'] . ' ';
        if (isset($order->customer_fields['first_name']) && !empty($order->customer_fields['first_name'])) $order->customer_name.= $order->customer_fields['first_name'] . ' ';
        if (isset($order->customer_fields['middle_name']) && !empty($order->customer_fields['middle_name'])) $order->customer_name.= $order->customer_fields['middle_name'];
		
		$order->phone = isset($order->customer_fields['phone']) && !empty($order->customer_fields['phone']) ? $order->customer_fields['phone'] : '';
        
        $mail = JFactory::getMailer();
        $params = JComponentHelper::getParams('com_ksenmart');
        $sender = array(
            $params->get('shop_email') ,
            $params->get('shop_name')
        );
        
        $content = KSSystem::loadTemplate(array(
            'order' => $order
        ), 'order', 'default', 'mail');
        
        $mail->isHTML(true);
        $mail->setSender($sender);
        $mail->Subject = 'Новый заказ №' . $order_id;
        $mail->Body = $content;
        
        if (!$admin) {
            $mail->AddAddress($order->customer_fields['email'], $order->customer_fields['name']);
        } else {
            $mail->AddAddress($params->get('shop_email') , $params->get('shop_name'));
        }
        
        $mail->Send();

        self::onExecuteAfter();
        return true;
    }
    
    public static function getPrintformDate($date) {

        self::onExecuteBefore(array(&$date));
        $date = date('d.m.Y', strtotime($date));
        
        self::onExecuteAfter(array(&$date));
        return $date;
    }
    
    public static function getPrintformCustomerName($customer_fields) {

        self::onExecuteBefore(array(&$customer_fields));
        $customer_name = '';
        if (isset($customer_fields['name']) && !empty($customer_fields['name'])) $customer_name.= $customer_fields['name'] . ' ';
        if (isset($customer_fields['last_name']) && !empty($customer_fields['last_name'])) $customer_name.= $customer_fields['last_name'] . ' ';
        if (isset($customer_fields['first_name']) && !empty($customer_fields['first_name'])) $customer_name.= $customer_fields['first_name'] . ' ';
        if (isset($customer_fields['middle_name']) && !empty($customer_fields['middle_name'])) $customer_name.= $customer_fields['middle_name'];
        if (empty($customer_name)) $customer_name = JText::_('ksm_orders_default_customer_info');

        self::onExecuteAfter(array(&$customer_name));
        return $customer_name;
    }
    
    public static function getPrintformCustomerAddress($address_fields) {

        self::onExecuteBefore(array(&$address_fields));
        $customer_address = '';
        if (isset($address_fields['city']) && !empty($address_fields['city'])) $customer_address.= $address_fields['city'] . ' ';
        if (isset($address_fields['street']) && !empty($address_fields['street'])) $customer_address.= $address_fields['street'] . ' ';
        if (isset($address_fields['house']) && !empty($address_fields['house'])) $customer_address.= $address_fields['house'];
        if (isset($address_fields['flat']) && !empty($address_fields['flat'])) $customer_address.= $address_fields['flat'];
        if (empty($customer_address)) $customer_address = JText::_('ksm_orders_default_customer_address');
        
        self::onExecuteAfter(array(&$customer_address));
        return $customer_address;
    }
    
    public static function updateOrderFields($oid, $data) {
        
        self::onExecuteBefore(array(&$oid, &$data));
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

        self::onExecuteAfter();
        return false;
    }
    
    public static function getOrderField($oid, $field) {

        self::onExecuteBefore(array(&$oid, &$field));
        if (!empty($oid) && !empty($field)) {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->select(KSDb::quoteName('o.' . $field))->from('#__ksenmart_orders AS o')->where(KSDb::quoteName('o.id') . '=' . $db->escape($oid));
            $db->setQuery($query);
            $orderField = $db->loadObject();
            
            self::onExecuteAfter(array($orderField));
            return $orderField;
        }
        return false;
    }
    
    public function setOrderField() {
    }
}
