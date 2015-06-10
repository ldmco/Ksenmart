<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

KSSystem::import('models.modelksadmin');
class KsenMartModelOrders extends JModelKSAdmin {

    function populateState() {
        $this->onExecuteBefore('populateState');

        $app = JFactory::getApplication();

        if($layout = JRequest::getVar('layout', 'default')) {
            $this->context .= '.' . $layout;
        }

        $value = $app->getUserStateFromRequest($this->context . 'list.limit', 'limit', $this->params->get('admin_product_limit', 30), 'uint');
        $limit = $value;
        $this->setState('list.limit', $limit);

        $value = $app->getUserStateFromRequest($this->context . '.limitstart', 'limitstart', 0);
        $limitstart = ($limit != 0 ? (floor($value / $limit) * $limit) : 0);
        $this->setState('list.start', $limitstart);

        $order_dir = $app->getUserStateFromRequest($this->context . '.order_dir', 'order_dir', 'desc');
        $this->setState('order_dir', $order_dir);
        $order_type = $app->getUserStateFromRequest($this->context . '.order_type', 'order_type', 'date_add');
        $this->setState('order_type', $order_type);

        $searchword = $app->getUserStateFromRequest($this->context . '.searchword', 'searchword', null);
        $this->setState('searchword', $searchword);

        $statuses = $app->getUserStateFromRequest($this->context . '.statuses', 'statuses', array());
        JArrayHelper::toInteger($statuses);
        $statuses = array_filter($statuses, 'KSFunctions::filterArray');
        $this->setState('statuses', $statuses);

        $from_date = $app->getUserStateFromRequest($this->context . '.from_date', 'from_date', null);
        $this->setState('from_date', $from_date);
        $to_date = $app->getUserStateFromRequest($this->context . '.to_date', 'to_date', null);
        $this->setState('to_date', $to_date);

        $this->onExecuteAfter('populateState');
    }

    function getListItems() {
        $this->onExecuteBefore('getListItems');

        $statuses = $this->getState('statuses');
        $searchword = $this->getState('searchword');
        $order_dir = $this->getState('order_dir');
        $order_type = $this->getState('order_type');
        $from_date = $this->getState('from_date');
        $to_date = $this->getState('to_date');
        $query = $this->_db->getQuery(true);
        $query->select('SQL_CALC_FOUND_ROWS *')->from('#__ksenmart_orders')->order($order_type . ' ' . $order_dir);
        if(count($statuses) > 0) $query->where('status_id in (' . implode(',', $statuses) . ')');
        if(!empty($searchword)) $query->where('customer_fields like ' . $this->_db->quote('%' . $searchword . '%') . ' or address_fields like ' . $this->_db->quote('%' . $searchword . '%') . ' or date_add like ' . $this->_db->quote('%' . $searchword . '%'));
        if(!empty($from_date)) {
            $from_date = date('Y-m-d', strtotime($from_date)) . ' 00:00:00';
            $query->where('date_add>' . $this->_db->quote($from_date));
        }
        if(!empty($to_date)) {
            $to_date = date('Y-m-d', strtotime($to_date)) . ' 23:59:59';
            $query->where('date_add<' . $this->_db->quote($to_date));
        }
        $this->_db->setQuery($query, $this->getState('list.start'), $this->getState('list.limit'));
        $orders = $this->_db->loadObjectList();
        $query = $this->_db->getQuery(true);
        $query->select('FOUND_ROWS()');
        $this->_db->setQuery($query);
        $this->total = $this->_db->loadResult();
        foreach($orders as &$order) {
            $order->user = KSUsers::getUser($order->user_id);
            $order->cost_val = KSMPrice::showPriceWithTransform($order->cost);
            $order->date = date('d.m.Y', strtotime($order->date_add));
            $order->status_name = '';
            $order->customer_info = '';
            $query = $this->_db->getQuery(true);
            $query->select('*')->from('#__ksenmart_order_statuses')->where('id=' . $order->status_id);
            $this->_db->setQuery($query);
            $status = $this->_db->loadObject();
            if(!empty($status)) $order->status_name = $status->system ? JText::_('ksm_orders_' . $status->title) : $status->title;
            $order->customer_fields = json_decode($order->customer_fields, true);
            if(isset($order->customer_fields['name']) && !empty($order->customer_fields['name'])) $order->customer_info .= $order->customer_fields['name'];
            if(isset($order->customer_fields['last_name']) && !empty($order->customer_fields['last_name'])) $order->customer_info .= $order->customer_fields['last_name'] . ' ';
            if(isset($order->customer_fields['first_name']) && !empty($order->customer_fields['first_name'])) $order->customer_info .= $order->customer_fields['first_name'] . ' ';
            if(isset($order->customer_fields['middle_name']) && !empty($order->customer_fields['middle_name'])) $order->customer_info .= $order->customer_fields['middle_name'];
            if(isset($order->customer_fields['email']) && !empty($order->customer_fields['email'])) $order->customer_info .= '<br>' . $order->customer_fields['email'];
            if(isset($order->customer_fields['phone']) && !empty($order->customer_fields['phone'])) $order->customer_info .= '<br>' . $order->customer_fields['phone'];
            if(empty($order->customer_info)) $order->customer_info = JText::_('ksm_orders_default_customer_info');
        }

        $this->onExecuteAfter('getListItems', array(&$orders));
        return $orders;
    }

    function getTotal() {
        $this->onExecuteBefore('getTotal');

        $total = $this->total;

        $this->onExecuteAfter('getTotal', array(&$total));
        return $total;
    }

    function deleteListItems($ids) {
        $this->onExecuteBefore('deleteListItems', array(&$ids));

        foreach($ids as $id) {
            $query = $this->_db->getQuery(true);
            $query->delete('#__ksenmart_order_items')->where('order_id=' . $id);
            $this->_db->setQuery($query);
            $this->_db->query();
            $query = $this->_db->getQuery(true);
            $query->delete('#__ksenmart_orders')->where('id=' . $id);
            $this->_db->setQuery($query);
            $this->_db->query();
        }

        $this->onExecuteAfter('deleteListItems', array(&$ids));
        return true;
    }

    function getOrder($vars = array()) {
        $this->onExecuteBefore('getOrder', array(&$value));

        $id = JRequest::getInt('id');
        $order = KSSystem::loadDbItem($id, 'orders');
        if(isset($vars['user_id'])) $order->user_id = $vars['user_id'];
        if(isset($vars['region_id'])) $order->region_id = $vars['region_id'];
        if(isset($vars['shipping_id'])) $order->shipping_id = $vars['shipping_id'];
        if(isset($vars['payment_id'])) $order->payment_id = $vars['payment_id'];
        $order->cost = 0;
        $order->discounts = property_exists($order, 'discounts') ? $order->discounts : '[]';

        $order->customer_fields = json_decode($order->customer_fields, true);
        $order->address_fields = json_decode($order->address_fields, true);

        if(!isset($vars['items'])) {
            $query = $this->_db->getQuery(true);
            $query->select('*')->from('#__ksenmart_order_items')->where('order_id=' . $id);
            $this->_db->setQuery($query);
            $order->items = $this->_db->loadObjectList();
        } else {
            $items = array();
            if(count($vars['items']) == 1 && !is_array($vars['items'][0])) $vars['items'] = array();
            foreach($vars['items'] as $item) {
                $item['properties'] = isset($item['properties']) ? $item['properties'] : array();
                $item = (object)$item;
                $item->price = KSMProducts::getPriceWithProperties($item->product_id, $item->properties, $item->basic_price);
                $item->properties = json_encode($item->properties);
                $items[] = $item;
            }
            $order->items = $items;
        }
        foreach($order->items as &$item) {
            $query = $this->_db->getQuery(true);
            $query->select('p.*')->from('#__ksenmart_products as p')->where('p.id=' . $item->product_id);
            $query = KSMedia::setItemMainImageToQuery($query);
            $this->_db->setQuery($query);
            $product = $this->_db->loadObject();
            if(count($product)) {
                $item->title = $product->title;
                $item->alias = $product->alias;
                $item->product_code = $product->product_code;
                $item->product_packaging = $product->product_packaging;
                $item->properties = json_decode($item->properties, true);
                $query = $this->_db->getQuery(true);
                $query->select('kp.*')->from('#__ksenmart_properties as kp')->innerjoin('#__ksenmart_product_properties_values as kppv on kp.id=kppv.property_id')->where('kppv.product_id=' . $item->product_id)->where('kp.type=' . $this->_db->quote('select'))->order('kp.ordering')->group('kp.id');
                $this->_db->setQuery($query);
                $properties = $this->_db->loadObjectList('id');
                foreach($properties as &$property) {
                    $query = $this->_db->getQuery(true);
                    $query->select('kpv.*')->from('#__ksenmart_property_values as kpv')->innerjoin('#__ksenmart_product_properties_values as kppv on kpv.id=kppv.value_id')->where('kppv.product_id=' . $item->product_id)->where('kpv.property_id=' . $property->id)->order('kpv.ordering')->group('kpv.id');
                    $this->_db->setQuery($query);
                    $property->values = $this->_db->loadObjectList('id');
                    foreach($property->values as $value)
                        if(isset($item->properties[$property->id]) && is_array($item->properties[$property->id]) && in_array($value->id, $item->properties[$property->id])) $value->selected = true;
                        else  $value->selected = false;
                }
                $item->properties = $properties;
                $item->small_img = KSMedia::resizeImage($product->filename, 'products', $this->params->get('admin_product_medium_image_width', 36), $this->params->get('admin_product_medium_image_heigth', 36), json_decode($product->params, true));
            } else {
                $item->title = JText::_('ksm_orders_order_deleteditem_title');
                $item->alias = '';
                $item->product_code = '';
                $item->product_packaging = 1;
                $item->properties = array();
                $item->small_img = KSMedia::resizeImage('', 'products', $this->params->get('admin_product_medium_image_width', 36), $this->params->get('admin_product_medium_image_heigth', 36));
            }
            $item->val_price = KSMPrice::showPriceWithTransform($item->price);
            $item->val_total_price = KSMPrice::showPriceWithTransform($item->price * $item->count);
            $order->cost += $item->price * $item->count;
        }

        $order->costs = array(
            'cost' => $order->cost,
            'cost_val' => KSMPrice::showPriceWithTransform($order->cost),
            'discount_cost' => 0,
            'discount_cost_val' => KSMPrice::showPriceWithTransform(0),
            'shipping_cost' => 0,
            'shipping_cost_val' => KSMPrice::showPriceWithTransform(0),
            'total_cost' => $order->cost,
            'total_cost_val' => KSMPrice::showPriceWithTransform($order->cost));

        $this->onExecuteAfter('getOrder', array(&$order));
        return $order;
    }

    public function saveOrder($data) {
        $this->onExecuteBefore('saveOrder', array(&$data));

        $data['customer_fields'] = isset($data['customer_fields']) ? $data['customer_fields'] : array();
        $data['customer_fields'] = json_encode($data['customer_fields']);
        $data['address_fields'] = isset($data['address_fields']) ? $data['address_fields'] : array();
        $data['address_fields'] = json_encode($data['address_fields']);
        $data['cost'] = 0;

        $in = array();
        if(isset($data['items']) && $data['items']) {
            foreach($data['items'] as $k => $v) {
                $data['cost'] += $v['price'] * $v['count'];
            }
        }

        $table = $this->getTable('orders');
        if(empty($data['id'])) {
            $data['date_add'] = JFactory::getDate()->toSql();
        } else {
			$order = KSSystem::loadDbItem($data['id'], 'orders');
			$old_status = $order->status_id;
		}

        if(!$table->bindCheckStore($data)) {
            $this->setError($table->getError());
            return false;
        }
        $id = $table->id;

        $in = array();
        if(isset($data['items']) && $data['items']) {
            foreach($data['items'] as $k => $v) {
                $v['order_id'] = $id;
                if($v['id'] < 0) unset($v['id']);
                $v['properties'] = isset($v['properties']) ? $v['properties'] : array();
                $v['properties'] = json_encode($v['properties']);
                $table = $this->getTable('orderitems');

                if(!$table->bindCheckStore($v)) {
                    $this->setError($table->getError());
                    return false;
                }
				
				if(($data['status_id'] == 4 && $old_status != 4) || ($old_status == 4 && $data['status_id'] != 4) && $this->params->get('use_stock', 1)){
					$query = $this->_db->getQuery(true);
					if($data['status_id'] == 4)
						$query->update('#__ksenmart_products')->set('in_stock=in_stock+'.$v['count'])->where('id='.$v['product_id']);
					else
						$query->update('#__ksenmart_products')->set('in_stock=in_stock-'.$v['count'])->where('id='.$v['product_id']);
					$this->_db->setQuery($query);
					$this->_db->query();
				}

                $in[] = $table->id;
            }
        }

        $query = $this->_db->getQuery(true);
        $query->delete('#__ksenmart_order_items')->where('order_id=' . $id);
        if(count($in)) {
            $query->where('id not in (' . implode(', ', $in) . ')');
        }
        $this->_db->setQuery($query);
        $this->_db->query();

        $on_close = 'window.parent.OrdersList.refreshList();';
        $return = array('id' => $id, 'on_close' => $on_close);

        $this->onExecuteAfter('saveOrder', array(&$return));
        return $return;
    }

    function getOrderStatus() {
        $this->onExecuteBefore('getOrderStatus');

        $id = JRequest::getInt('id');
        $orderstatus = KSSystem::loadDbItem($id, 'orderstatuses');

        $this->onExecuteAfter('getOrderStatus', array(&$orderstatus));
        return $orderstatus;
    }

    function saveOrderStatus($data) {
        $this->onExecuteBefore('saveOrderStatus', array(&$data));

        $table = $this->getTable('orderstatuses');
        if(!$table->bindCheckStore($data)) {
            $this->setError($table->getError());
            return false;
        }
        $id = $table->id;

        $on_close = 'window.parent.OrderStatusesModule.refresh();';
        $return = array('id' => $id, 'on_close' => $on_close);

        $this->onExecuteAfter('saveOrderStatus', array(&$return));
        return $return;
    }

    function deleteOrderStatus($id) {
        $this->onExecuteBefore('deleteOrderStatus', array(&$id));

        $table = $this->getTable('orderstatuses');
        $table->delete($id);
        $query = $this->_db->getQuery(true);
        $query->update('#__ksenmart_orders')->set('status_id=0')->where('status_id=' . $id);
        $this->_db->setQuery($query);
        $this->_db->query();

        $this->onExecuteAfter('deleteOrderStatus', array(&$id));
        return true;
    }
}