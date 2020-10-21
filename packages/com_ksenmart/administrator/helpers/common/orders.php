<?php
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

KSSystem::import('helpers.corehelper');

class KSMOrders extends KSCoreHelper
{
	private static $_orders = [];
	private static $statuses = [];
    private static $items;

    public static function getOrder($oid, $product_info = false)
	{
		if (empty($oid)) return new stdClass;
		if (!empty(self::$_orders[$oid][$product_info])) return self::$_orders[$oid][$product_info];
		self::onExecuteBefore(array(&$oid, $product_info));
		$db    = JFactory::getDbo();
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
                o.params,
                o.status_id,
                o.date_add,
                os.title AS status_name,
                os.system AS status_system
            ')->from('#__ksenmart_orders AS o')->leftJoin('#__ksenmart_order_statuses AS os ON o.status_id=os.id')->where('o.id=' . $db->escape($oid));
		$db->setQuery($query);
		$order         = $db->loadObject();
		$order->params = json_decode($order->params, true);
		self::setUserInfoField2Order($order);
		if (!empty($order->status_name)) $order->status_name = $order->status_system ? JText::_('ksm_orders_' . $order->status_name) : $order->status_name;

        if (empty(self::$items[$order->id])) {
            $query = $db->getQuery(true);
            $query->select('*')->from('#__ksenmart_order_items')->where('order_id='.$oid);
            $db->setQuery($query);

            $order->items = $db->loadObjectList();
        } else {
            $order->items = self::$items[$order->id];
        }

		if ($product_info) $order->items = self::getOrderItems($oid);

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

		$dispatcher = JEventDispatcher::getInstance();
		$dispatcher->trigger('onAfterGetOrder', array(&$order));
		self::onExecuteAfter(array(&$order));
		self::$_orders[$oid][$product_info] = $order;

		return $order;
	}

	private static function setOrderItemsProperties(&$order, $oid)
	{

		self::onExecuteBefore(array($order, &$oid));
		if (!empty($order))
		{
			$db = JFactory::getDbo();
			foreach ($order as & $item)
			{
				if (!empty($item->properties))
				{
					$item->properties = json_decode($item->properties);
					foreach ($item->properties as $key => & $property)
					{
						if (empty($property->value_id)) continue;
						$query = $db->getQuery(true);
						$query->select('
                            o.properties,
                            p.title AS prop_title,
                            pv.title AS prop_value_title
                        ');
						$query->from('#__ksenmart_order_items AS o');
						$query->leftJoin('#__ksenmart_properties AS p ON p.id=' . $db->q($key));
						$query->leftJoin('#__ksenmart_property_values AS pv ON pv.id=' . $db->q($property->value_id));
						$query->where('o.order_id=' . $db->q($oid));

						$db->setQuery($query);

						$property_tmp    = $db->loadObject();
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

	public static function getOrderItems($oid)
	{

		self::onExecuteBefore(array(&$oid));
		if (!empty($oid))
		{
			$db    = JFactory::getDbo();
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

			if (!empty($order))
			{
				self::setOrderItemsProperties($order, $oid);
				foreach ($order as $o_item)
				{
					$o_item->product   = KSMProducts::getProduct($o_item->product_id);
					$o_item->price_val = KSMPrice::showPriceWithTransform($o_item->price);
				}
			}
			self::onExecuteAfter(array($order));

			return $order;
		}

		return new stdClass;
	}

    public static function getOrdersItems($orderIds)
    {
        if (empty($orderIds)) {
            return [];
        }

        $db    = JFactory::getDbo();
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
        $query->where('o.order_id IN ('.implode(',', $orderIds).')');
        $db->setQuery($query);
        $items = $db->loadObjectList();

        foreach ($items as $item) {
            if (empty(self::$items[$item->order_id])) {
                self::$items[$item->order_id] = [];
            }

            if (!empty(self::$items[$item->order_id][$item->id])) {
                continue;
            }

            self::$items[$item->order_id][$item->id] = $item;
        }

        return self::$items;
    }


    public static function setUserInfoField2Order(&$order)
	{

		self::onExecuteBefore(array($order));
		if (!empty($order))
		{
			$order->customer_fields    = json_decode($order->customer_fields);
			$order->address_fields     = json_decode($order->address_fields);
			$order->address_fields_raw = $order->address_fields;
			$order->address_fields     = self::getAddressString($order->address_fields);

			self::onExecuteAfter(array($order));

			return $order;
		}

		return new stdClass;
	}

	static function getAddressString($address)
	{
		$addr_parts = array();
		$string     = '';

		if (!empty($address->zip))
		{
			$addr_parts[] = $address->zip;
		}
		if (!empty($address->city))
		{
			$addr_parts[] = JText::sprintf('KSM_USER_KSENMART_ADDRESSES_CITY_TXT', $address->city);
		}
		if (!empty($address->street))
		{
			$addr_parts[] = JText::sprintf('KSM_USER_KSENMART_ADDRESSES_STREET_TXT', $address->street);
		}
		if (!empty($address->house))
		{
			$addr_parts[] = JText::sprintf('KSM_USER_KSENMART_ADDRESSES_HOUSE_TXT', $address->house);
		}
		if (!empty($address->entrance))
		{
			$addr_parts[] = JText::sprintf('KSM_USER_KSENMART_ADDRESSES_ENTRANCE_TXT', $address->entrance);
		}
		if (!empty($address->floor))
		{
			$addr_parts[] = JText::sprintf('KSM_USER_KSENMART_ADDRESSES_FLOOR_TXT', $address->floor);
		}
		if (!empty($address->flat))
		{
			$addr_parts[] = JText::sprintf('KSM_USER_KSENMART_ADDRESSES_FLAT_TXT', $address->flat);
		}
		$string = implode(', ', $addr_parts);

		return $string;
	}

	public static function sendOrderMail($order_id, $admin = false)
	{

		self::onExecuteBefore(array(&$order_id, &$admin));
		$db = JFactory::getDbo();
		JRequest::setVar('id', $order_id);
		$model        = KSSystem::getModel('orders');
		$order        = $model->getOrder();
		$order->items = KSMOrders::getOrderItems($order_id);

		$query = $db->getQuery(true);
		$query->select('*')->from('#__ksenmart_shipping_fields')->where('shipping_id=' . $order->shipping_id)->where('position=' . $db->quote('address'))->where('published=1')->order('ordering');
		$db->setQuery($query);
		$address_fields = $db->loadObjectList();

		$address = array();
		foreach ($address_fields as $address_field)
		{
			if ($address_field->system && isset($order->address_fields[$address_field->title]))
				$value = $order->address_fields[$address_field->title];
			elseif (!$address_field->system && isset($order->address_fields[$address_field->id]))
				$value = $order->address_fields[$address_field->id];
			else
				$value = '';
			$title     = $address_field->system ? JText::_('ksm_cart_shipping_field_' . $address_field->title) : $address_field->title;
			$address[] = $title . ' - ' . $value;
		}
		$order->address_fields = implode(', ', $address);

		$order->customer_name = '';
		if (isset($order->customer_fields['name']) && !empty($order->customer_fields['name'])) $order->customer_name .= $order->customer_fields['name'];
		if (isset($order->customer_fields['last_name']) && !empty($order->customer_fields['last_name'])) $order->customer_name .= $order->customer_fields['last_name'] . ' ';
		if (isset($order->customer_fields['first_name']) && !empty($order->customer_fields['first_name'])) $order->customer_name .= $order->customer_fields['first_name'] . ' ';
		if (isset($order->customer_fields['middle_name']) && !empty($order->customer_fields['middle_name'])) $order->customer_name .= $order->customer_fields['middle_name'];

		$order->phone = isset($order->customer_fields['phone']) && !empty($order->customer_fields['phone']) ? $order->customer_fields['phone'] : '';

		$mail   = JFactory::getMailer();
		$params = JComponentHelper::getParams('com_ksenmart');
		$sender = array(
			$params->get('shop_email'),
			$params->get('shop_name')
		);

		$content = KSSystem::loadTemplate(array(
			'order' => $order
		), 'cart', 'default', 'mail');

		$mail->isHTML(true);
		$mail->setSender($sender);
		$mail->Subject = 'Новый заказ №' . $order_id;
		$mail->Body    = $content;

		$shop_email = $params->get('shop_email');
		if (!$admin)
		{
			if (!empty($order->customer_fields['email']) && strpos($order->customer_fields['email'], '@') !== false)
			{
				$mail->AddAddress($order->customer_fields['email'], $order->customer_fields['name']);
				$mail->Send();
			}
		}
		elseif (!empty($shop_email))
		{
			$mail->AddAddress($shop_email, $params->get('shop_name'));
			$mail->Send();
		}

		self::onExecuteAfter();

		return true;
	}

	public static function getPrintformDate($date)
	{

		self::onExecuteBefore(array(&$date));
		$date = date('d.m.Y', strtotime($date));

		self::onExecuteAfter(array(&$date));

		return $date;
	}

	public static function getPrintformCustomerName($customer_fields)
	{

		self::onExecuteBefore(array(&$customer_fields));
		$customer_name = '';
		if (isset($customer_fields['name']) && !empty($customer_fields['name'])) $customer_name .= $customer_fields['name'] . ' ';
		if (isset($customer_fields['last_name']) && !empty($customer_fields['last_name'])) $customer_name .= $customer_fields['last_name'] . ' ';
		if (isset($customer_fields['first_name']) && !empty($customer_fields['first_name'])) $customer_name .= $customer_fields['first_name'] . ' ';
		if (isset($customer_fields['middle_name']) && !empty($customer_fields['middle_name'])) $customer_name .= $customer_fields['middle_name'];
		if (empty($customer_name)) $customer_name = JText::_('ksm_orders_default_customer_info');

		self::onExecuteAfter(array(&$customer_name));

		return $customer_name;
	}

	public static function getPrintformCustomerAddress($address_fields)
	{

		self::onExecuteBefore(array(&$address_fields));
		$customer_address = '';
		if (isset($address_fields['city']) && !empty($address_fields['city'])) $customer_address .= $address_fields['city'] . ' ';
		if (isset($address_fields['street']) && !empty($address_fields['street'])) $customer_address .= $address_fields['street'] . ' ';
		if (isset($address_fields['house']) && !empty($address_fields['house'])) $customer_address .= $address_fields['house'];
		if (isset($address_fields['flat']) && !empty($address_fields['flat'])) $customer_address .= $address_fields['flat'];
		if (empty($customer_address)) $customer_address = JText::_('ksm_orders_default_customer_address');

		self::onExecuteAfter(array(&$customer_address));

		return $customer_address;
	}

	public static function updateOrderFields($oid, $data)
	{

		self::onExecuteBefore(array(&$oid, &$data));
		if (!empty($data) && !empty($oid) && $oid > 0)
		{
			$db = JFactory::getDbo();

			$data->id = $oid;

			try
			{
				$db->updateObject('#__ksenmart_orders', $data, 'id');

				return true;
			}
			catch (exception $e)
			{
			}
		}

		self::onExecuteAfter();

		return false;
	}

	public static function getOrderField($oid, $field)
	{

		self::onExecuteBefore(array(&$oid, &$field));
		if (!empty($oid) && !empty($field))
		{
			$db    = JFactory::getDBO();
			$query = $db->getQuery(true);
			$query->select(KSDb::quoteName('o.' . $field))->from('#__ksenmart_orders AS o')->where(KSDb::quoteName('o.id') . '=' . $db->escape($oid));
			$db->setQuery($query);
			$orderField = $db->loadObject();

			self::onExecuteAfter(array($orderField));

			return $orderField;
		}

		return false;
	}

	public static function setOrderField($fields, $type, $json = true)
	{

		self::onExecuteBefore(array(&$fields, &$type));
		if (empty($fields) || empty($type)) return false;

		$session = JFactory::getSession();
		$oid     = $session->get('shop_order_id', 0);
		$db      = JFactory::getDBO();
		$query   = $db->getQuery(true);
		if ($json) $fields = json_encode($fields, JSON_UNESCAPED_UNICODE);
		$query->update('#__ksenmart_orders')->set($db->quoteName($type) . '=' . $db->q($fields))->where('id=' . (int) $oid);
		$db->setQuery($query);
		$db->execute();

		return true;
	}

	/**
	 * @return array
	 *
	 * @since 4.1.6
	 */
	public static function getStatuses()
	{
		if (empty(self::$statuses))
		{
			self::loadStatuses();
		}

		return self::$statuses;
	}

	/**
	 * @param int $id
	 *
	 * @return object
	 *
	 * @since version
	 */
	public static function getStatus($id = 0)
	{
		if (empty(self::$statuses))
		{
			self:
			self::loadStatuses();
		}

		return isset(self::$statuses[$id]) ? self::$statuses[$id] : (object) ['withdraw' => 0];
	}

	public static function loadStatuses()
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')->from('#__ksenmart_order_statuses');
		$db->setQuery($query);
		self::$statuses = $db->loadObjectList('id');
	}

    public static function setOrderStatus($id = 0, $status_id = 0)
    {
        self::onExecuteBefore(array(&$id, &$status_id));

        if (empty($id) || empty($id)) {
            return false;
        }

        $params = JComponentHelper::getParams('com_ksenmart');
        $db     = JFactory::getDbo();
        $order  = self::getOrder($id);

        if ($order->id == $status_id) {
            return true;
        }

        if ($params->get('use_stock', 1)) {
            if (KSMOrders::getStatus($status_id)->withdraw != KSMOrders::getStatus($order->status_id)->withdraw) {
                foreach ($order->items as $item) {
                    $product = KSMProducts::getProduct($item->product_id);
                    if (KSMOrders::getStatus($status_id)->withdraw) {
                        $product->in_stock -= $item->count;
                    } else {
                        $product->in_stock += $item->count;
                    }

                    $product->save(['in_stock']);
                }
            }
        }

        $obj_order = (object) [
            'id'        => $id,
            'status_id' => $status_id,
        ];

        $db->updateObject('#__ksenmart_orders', $obj_order, 'id');

        self::onExecuteAfter(array($id));

        return true;
    }
}
