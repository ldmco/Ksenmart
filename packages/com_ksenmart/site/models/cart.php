<?php
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

KSSystem::import('models.modelkslist');

class KsenMartModelCart extends JModelKSList
{

	var $order_id = null;

	private $_session = null;
	private $_step_id = null;
	private $_user = null;
	private $_system_fields = null;
	private $_customer_fields = null;
	private $_address_fields = null;
	private $_payments = null;
	private $cart = null;

	public function __construct()
	{
		$this->_session = JFactory::getSession();
		$this->order_id = $this->_session->get('shop_order_id', 0);

		$this->_user = KSUsers::getUser();
		parent::__construct();

		$this->context = 'com_ksenmart';

		$this->getDefaultStates();
	}

	private function getDefaultStates()
	{
		$this->_step_id = $this->getState('step_id', 0);
	}

	protected function populateState($ordering = null, $direction = null)
	{
		$this->onExecuteBefore('populateState', array(&$this));

		$app = JFactory::getApplication();

		$region_id       = (int) $app->getUserStateFromRequest($this->context . '.region_id', 'region_id', $this->_user->region_id);
		$shipping_id     = (int) $app->getUserStateFromRequest($this->context . '.shipping_id', 'shipping_id', 0);
		$payment_id      = (int) $app->getUserStateFromRequest($this->context . '.payment_id', 'payment_id', 0);
		$step_id         = (int) $app->getUserStateFromRequest($this->context . '.step_id', 'step_id', 0);
		$shipping_coords = $this->_session->get($this->context . '.shipping_coords', array());
		$shipping_coords = implode(',', $shipping_coords);
		if (empty($region_id))
		{
			$params    = JComponentHelper::getParams('com_ksenmart');
			$region_id = $params->get('region_id', 0);
		}
		$this->setState('region_id', $region_id);
		$this->setState('shipping_id', $shipping_id);
		$this->setState('payment_id', $payment_id);
		$this->setState('step_id', $step_id);
		$this->setState('shipping_coords', $shipping_coords);

		$this->onExecuteAfter('populateState', array(&$this));
	}

	public function getRegions()
	{
		$this->onExecuteBefore('getRegions');

		$show_regions = KSMShipping::checkRegions();
		if ($show_regions)
		{
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
				->order('r.ordering');
			$this->_db->setQuery($query);
			$regions = $this->_db->loadObjectList();
			foreach ($regions as &$region)
			{
				$region->selected = false;
				if ($region->id == $this->getState('region_id'))
				{
					$region->selected = true;
				}
			}
		}
		else
		{
			$regions = array();
		}

		$this->onExecuteAfter('getRegions', array(&$regions));

		return $regions;
	}

	public function getAddresses()
	{
		return KSUsers::getAddresses();
	}

	public function getShippings()
	{
		$this->onExecuteBefore('getShippings');

		$shipping_selected = $this->getState('shipping_id', 0);
		$shippings         = array();
		$show_regions      = KSMShipping::checkRegions();

		if (!empty($this->getState('region_id')) || !$show_regions)
		{
			$query = $this->_db->getQuery(true);
			$query
				->select('
                    s.id,
                    s.title,
					s.introcontent,
                    s.type,
                    s.regions,
                    s.days,
                    s.params as shipping_params,
                    s.ordering
                ')
				->from('#__ksenmart_shippings AS s')
				->where('s.published=1')
				->order('s.ordering');
			$query = KSMedia::setItemMainImageToQuery($query, 'shipping', 's.');

			$this->_db->setQuery($query);
			$rows = $this->_db->loadObjectList('id');

			$params = JComponentHelper::getParams('com_ksenmart');
			foreach ($rows as $row)
			{
				$row->icon            = !empty($row->filename) ? KSMedia::resizeImage($row->filename, $row->folder, $params->get('shipping_width'), $params->get('shipping_height'), json_decode($row->params, true)) : '';
				$row->regions         = json_decode($row->regions, true);
				$row->shipping_params = json_decode($row->shipping_params, true);
				$row->action          = 'KMCartChangeShipping(this);';
				$row->shipping_sum    = 0;
				if ($show_regions)
				{
					foreach ($row->regions as $country)
					{
						if (in_array($this->getState('region_id'), $country))
						{
							$row->selected = false;
							if (!$shipping_selected)
							{
								$shipping_selected = $row->id;
								$row->selected     = true;
							}
							if ($row->id == $this->getState('shipping_id'))
							{
								$row->selected     = true;
								$shipping_selected = $row->id;
							}
							$shippings[$row->id] = $row;
						}
					}
				}
				else
				{
					$row->selected = false;
					if ($row->id == $this->getState('shipping_id'))
					{
						$row->selected     = true;
						$shipping_selected = $row->id;
					}
					$shippings[$row->id] = $row;
				}
			}
		}
		$this->setState('shipping_id', $shipping_selected);

		$app = JFactory::getApplication();
		$app->setUserState($this->context . '.shipping_id', $shipping_selected);

		$this->onExecuteAfter('getShippings', array(&$shippings));

		return $shippings;
	}

	public function getShowRegions()
	{
		return KSMShipping::checkRegions();
	}

	public function getSteps()
	{
		$this->onExecuteBefore('getSteps');
		$steps                = [
			1 => true,
			2 => false,
			3 => false,
			4 => false
		];
		$address_fields_flag  = false;
		$customer_fields_flag = false;

		$show_regions    = $this->getShowRegions();
		$payments        = $this->getPayments();
		$customer_fields = $this->getCustomerFields();
		$address_fields  = $this->getAddressFields();
		$shippings       = $this->getShippings();

		$info               = new stdClass();
		$info->steps_counts = 1;
		if ($show_regions || count($payments) || count($shippings))
		{
			$steps[2] = true;
			$info->steps_counts++;
		}
		$shipping_step            = false;
		$info->shipping_step_name = 3;
		if (isset($shippings[$this->getState('shipping_id', 0)]))
		{
			$shipping_current = $shippings[$this->getState('shipping_id', 0)];
			if (isset($shipping_current->step))
			{
				$shipping_step            = true;
				$info->shipping_step_name = $shipping_current->step;
			}
		}

		foreach ($customer_fields as $customerField)
		{
			if ($customerField->default)
			{
				continue;
			}
			$customer_fields_flag = true;
		}
		foreach ($address_fields as $addressField)
		{
			if ($addressField->default)
			{
				continue;
			}
			$address_fields_flag = true;
		}
		if ($customer_fields_flag || $address_fields_flag || $shipping_step)
		{
			$steps[3] = true;
			$info->steps_counts++;
		}
		if (count($payments))
		{
			$steps[4] = true;
			$info->steps_counts++;
		}
		if ($this->_step_id > $info->steps_counts) $this->_step_id = 1;

		while ((!isset($steps[$this->_step_id]) || !$steps[$this->_step_id]) && $this->_step_id < 5) $this->_step_id++;

		$info->current_step = $this->_step_id;
		$info->last_step    = true;
		foreach ($steps as $key => $step)
		{
			if ($step && $key > $this->_step_id) $info->last_step = false;
		}
		$info->steps = $steps;

		$this->onExecuteAfter('getSteps', array(&$info));

		return $info;
	}

	public function getMessage()
	{
		$this->onExecuteBefore('getMessage');

		$params  = JComponentHelper::getParams('com_ksenmart');
		$message = $params->get('order_process_message', '');

		$this->onExecuteAfter('getMessage', array(&$message));

		return $message;
	}

	public function getPreorderFields()
	{
		$this->onExecuteBefore('getPreorderFields');

		$params = JComponentHelper::getParams('com_ksenmart');
		$fields = (array) $params->get('order_process_fields', array());
		if ($fields)
		{
			$fields_new = array();
			foreach ($fields as &$field)
			{
				if (!$field->published) continue;
				$field->class           = $field->required == 1 ? 'required' : '';
				$fields_new[$field->id] = $field;
			}
			$fields = $fields_new;
		}

		$this->onExecuteAfter('getPreorderFields', array(&$fields));

		return $fields;
	}

	public function getSystemFields($positions = array('customer', 'address'))
	{
		$this->onExecuteBefore('getSystemFields');

		$params = JComponentHelper::getParams('com_ksenmart');

		$fields = array();
		$app    = JFactory::getApplication();
		$order  = KSMOrders::getOrder($this->order_id, true);
		foreach ($positions as $position)
		{
			$fields[$position] = (array) $params->get($position . '_fields', array());
			if ($fields[$position])
			{
				$where      = array();
				$fields_new = array();
				$flag       = false;
				$field_o    = new stdClass;
				foreach ($fields[$position] as &$field)
				{
					if (!$field->published) continue;
					$user_value   = null;
					$field->class = $field->required == 1 ? 'required' : '';

					if (isset($order->{$position . '_fields'}->{$field->title}))
					{
						$user_value = $order->{$position . '_fields'}->{$field->title};
					}
					elseif (isset($this->_user->{$field->title}))
					{
						$user_value = $this->_user->{$field->title};
					}

					if ($field->type == 'select')
					{
						$flag    = true;
						$where[] = $field->id;
					}

					$field_name = $field->title;
					if (!$field->system)
					{
						$field_name = $field->id;
					}

					$field->default         = true;
					$field->value           = $app->getUserState($this->context . '.' . $position . '_fields' . '[' . $field_name . ']', $user_value);
					$fields_new[$field->id] = $field;
					$field_o->{$field_name} = $field->value;
				}
				unset($field);

				if ($flag)
				{
					$values = $this->getFieldsValuesOrder($where);
					if (!empty($values))
					{
						foreach ($values as $value)
						{
							if (isset($fields_new[$value->field_id]))
							{
								$fields_new[$value->field_id]->values[] = $value;
							}
							continue;
						}
					}
				}

				$data                          = new stdClass;
				$data->{$position . '_fields'} = json_encode($field_o, JSON_UNESCAPED_UNICODE);

				$fields[$position] = $fields_new;
			}
		}

		$this->onExecuteAfter('getSystemFields', array(&$fields));
		$this->_system_fields = $fields;

		return $fields;
	}

	public function getCustomerFields($system = true)
	{
		$this->onExecuteBefore('getCustomerFields');
		if (!empty($this->_customer_fields))
		{
			$customer_fields = $this->_customer_fields;
		}
		else
		{
			$customer_fields        = $this->getFieldsOrder('customer', 'customer_fields');
			$this->_customer_fields = $customer_fields;
		}
		if (!$system)
		{
			$customer_fields = array_filter($customer_fields, function ($field) {
				return ($field->default) ? false : true;
			});
		}

		$this->onExecuteAfter('getCustomerFields', array(&$customer_fields));

		return $customer_fields;
	}

	public function getAddressFields($system = true)
	{
		$this->onExecuteBefore('getAddressFields');
		if (!empty($this->_address_fields))
		{
			$address_fields = $this->_address_fields;
		}
		else
		{
			$address_fields        = $this->getFieldsOrder('address', 'address_fields');
			$this->_address_fields = $address_fields;
		}
		if (!$system)
		{
			$address_fields = array_filter($address_fields, function ($field) {
				return ($field->default) ? false : true;
			});
		}

		$this->onExecuteAfter('getAddressFields', array(&$address_fields));

		return $address_fields;
	}

	private function getFieldsCatOrder($position)
	{
		$this->onExecuteBefore('getFieldsCatOrder', array(&$position));

		if (!empty($position))
		{
			$fields = $this->_system_fields[$position];
			$query  = $this->_db->getQuery(true);
			if ($this->getState('shipping_id'))
			{
				$query
					->select('
						sf.id,
						sf.shipping_id,
						sf.position,
						sf.type,
						sf.title,
						sf.required,
						sf.system,
						sf.published,
						sf.ordering
					')
					->from('#__ksenmart_shipping_fields AS sf')
					->where('sf.shipping_id=' . (int) $this->getState('shipping_id'))
					->where('sf.position=' . $this->_db->quote($position))
					->where('sf.published=1')
					->order('sf.ordering');
				$this->_db->setQuery($query);
				$shipping_fields = $this->_db->loadObjectList();

				if (!empty($shipping_fields))
				{
					$shipping_fields = array_filter($shipping_fields, function ($shipping_field) use ($fields) {
						//TODO: в сборку
						$shipping_field->default = false;
						foreach ($fields as $system_field)
						{
							if ($system_field->title == $shipping_field->title) return false;
						}

						return true;
					});
					$fields          = array_merge($fields, $shipping_fields);
				}
			}
			else
			{
				$fields = array();
			}

			$this->onExecuteAfter('getFieldsCatOrder', array(&$fields));

			return $fields;
		}

		return new stdClass;
	}

	private function getFieldsValuesOrder($where)
	{
		$this->onExecuteBefore('getFieldsValuesOrder', array(&$where));

		if (!empty($where))
		{
			$query = $this->_db->getQuery(true);
			$query
				->select('
                    sfv.id,
                    sfv.field_id,
                    sfv.title
                ')
				->from('#__ksenmart_shipping_fields_values AS sfv')
				->where('(sfv.field_id IN(' . implode(', ', $where) . '))');
			$this->_db->setQuery($query);
			$values = $this->_db->loadObjectList();

			$this->onExecuteAfter('getFieldsValuesOrder', array(&$values));

			return $values;
		}

		return false;
	}

	private function getFieldsOrder($position, $type)
	{
		$this->onExecuteBefore('getFieldsOrder', array(&$position, &$type));
		if (!empty($position))
		{
			$fields = $this->getFieldsCatOrder($position);
			if ($fields)
			{
				$app        = JFactory::getApplication();
				$order      = KSMOrders::getOrder($this->order_id, true);
				$where      = array();
				$fields_new = array();
				$flag       = false;
				$field_o    = new stdClass;
				foreach ($fields as &$field)
				{
					if (!$field->published) continue;
					$user_value   = null;
					$field->class = $field->required == 1 ? 'required' : '';

					if (isset($order->{$type}->{$field->title}))
					{
						$user_value = $order->{$type}->{$field->title};
					}
					elseif (isset($this->_user->{$field->title}))
					{
						$user_value = $this->_user->{$field->title};
					}

					if ($field->type == 'select')
					{
						$flag    = true;
						$where[] = $field->id;
					}

					$field_name = $field->title;
					if (!$field->system)
					{
						$field_name = $field->id;
					}

					$field->value           = $app->getUserState($this->context . '.' . $type . '[' . $field_name . ']', $user_value);
					$fields_new[$field->id] = $field;
					$field_o->{$field_name} = $field->value;
				}

				if ($flag)
				{
					$values = $this->getFieldsValuesOrder($where);
					if (!empty($values))
					{
						foreach ($values as $value)
						{
							if (isset($fields_new[$value->field_id]))
							{
								$fields_new[$value->field_id]->values[] = $value;
							}
							continue;
						}
					}
				}

				$data          = new stdClass;
				$data->{$type} = json_encode($field_o, JSON_UNESCAPED_UNICODE);

				KSMOrders::updateOrderFields($this->order_id, $data);

				$this->onExecuteAfter('getFieldsOrder', array(&$fields_new));

				return $fields_new;
			}
		}

		return array();
	}

	public function getPayments()
	{
		if (!empty($this->_payments)) return $this->_payments;
		$this->onExecuteBefore('getPayments');

		$payment_selected = 0;
		$payments         = array();
		$show_regions     = KSMShipping::checkRegions();

		if (!empty($this->getState('region_id')) || !$show_regions)
		{
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
				->leftjoin('#__ksenmart_files AS f ON f.owner_type=' . $this->_db->quote('payment') . ' AND f.owner_id=p.id')
				->where('p.published=1')
				->order('p.ordering');

			$this->_db->setQuery($query);
			$rows = $this->_db->loadObjectList('id');

			$params = JComponentHelper::getParams('com_ksenmart');
			foreach ($rows as $row)
			{
				$row->icon    = !empty($row->filename) ? KSMedia::resizeImage($row->filename, $row->folder, $params->get('shipping_width'), $params->get('shipping_height'), json_decode($row->params_f, true)) : '';
				$row->regions = json_decode($row->regions, true);
				if ($show_regions)
				{
					foreach ($row->regions as $country)
					{
						if (in_array($this->getState('region_id'), $country))
						{
							$row->selected = false;
							if ($row->id == $this->getState('payment_id'))
							{
								$row->selected    = true;
								$payment_selected = $row->id;
							}
							$payments[] = $row;
						}
					}
				}
				else
				{
					$row->selected = false;
					if ($row->id == $this->getState('payment_id'))
					{
						$row->selected    = true;
						$payment_selected = $row->id;
					}
					$payments[] = $row;
				}
			}
			$this->setState('payment_id', $payment_selected);

			$app = JFactory::getApplication();
			$app->setUserState($this->context . '.payment_id', $payment_selected);
		}

		$this->onExecuteAfter('getPayments', array(&$payments));
		$this->_payments = $payments;

		return $payments;
	}

	public function getOrderInfo()
	{
		$this->onExecuteBefore('getOrderInfo');

		if (!empty($this->order_id))
		{
			$order = KSMOrders::getOrder($this->order_id, true);

			$this->onExecuteAfter('getOrderInfo', array(&$order));

			return $order;
		}

		return new stdClass;
	}

	private function setDefaultCartValues(&$cart)
	{
		$this->onExecuteBefore('setDefaultCartValues', array(&$cart));

		if (!$cart)
		{
			$cart = new stdClass;
		}

		if (!isset($cart->total_prds)) $cart->total_prds = 0;
		if (!isset($cart->products_sum)) $cart->products_sum = 0;
		if (!isset($cart->shipping_sum)) $cart->shipping_sum = 0;
		if (!isset($cart->discount_sum)) $cart->discount_sum = 0;
		if (!isset($cart->total_sum)) $cart->total_sum = 0;
		if (!isset($cart->products_sum_val)) $cart->products_sum_val = '';
		if (!isset($cart->shipping_sum_val)) $cart->shipping_sum_val = '';
		if (!isset($cart->discount_sum_val)) $cart->discount_sum_val = '';
		if (!isset($cart->items)) $cart->items = array();

		$this->onExecuteAfter('setDefaultCartValues', array(&$cart));

		return $cart;
	}

	public function getCart()
	{
		if (!empty($this->cart))
		{
			return $this->cart;
		}

		$this->onExecuteBefore('getCart');

		if (!empty($this->order_id))
		{
			$cart = KSMOrders::getOrder($this->order_id, true);
			$this->setDefaultCartValues($cart);
			if (empty($cart->shipping_id)) $cart->shipping_id = $this->getState('shipping_id');

			$cart->total_prds   = 0;
			$cart->products_sum = 0;
			for ($k = 0; $k < count($cart->items); $k++)
			{
				$cart->items[$k]->del_link = JRoute::_('index.php?option=com_ksenmart&view=cart&task=cart.update_cart&item_id=' . $cart->items[$k]->id . '&count=0&Itemid=' . KSSystem::getShopItemid());
				$cart->total_prds          += $cart->items[$k]->count;
				$cart->products_sum        += $cart->items[$k]->count * $cart->items[$k]->price;
			}
		}
		else
		{
			$this->setDefaultCartValues($cart);
		}

		$cart->products_sum_val = KSMPrice::showPriceWithTransform($cart->products_sum);
		$cart->total_sum        = $cart->products_sum;

		$cart->total_sum_val = KSMPrice::showPriceWithTransform($cart->total_sum);

		$this->onExecuteAfter('getCart', array(&$cart));

		$this->cart = $cart;

		return $cart;
	}

	public function getProperties($public = true)
	{
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
			->where('p.published=1');
		$this->_db->setQuery($query);
		$poperties = $this->_db->loadObjectList();

		$this->onExecuteAfter('getProperties', array(&$poperties));

		return $poperties;
	}

	public function addToCart()
	{
		$this->onExecuteBefore('addToCart');

		$app    = JFactory::getApplication();
		$jinput = $app->input;

		$count = $jinput->get('count', 1);
		$id    = $jinput->get('id', 0, 'int');
		$prd   = KSMProducts::getProduct($id);

		if (count($prd) == 0)
		{
			return JText::_('KSM_CART_UNDEFINED_PRODUCT');
		}

		if ($count > $prd->in_stock && $this->params->get('use_stock', 1) == 1)
		{
			return JText::_('KSM_CART_ADD_PRODUCT_OUT_OF_STOCK');
		}

		$customer_fields = $jinput->get('customer_fields', array(), 'ARRAY');

		if ($this->order_id == 0)
		{
			$params         = JComponentHelper::getParams('com_ksenmart');
			$this->_step_id = 0;
			$this->setState('step_id', 0);
			$app->setUserState($this->context . '.step_id', 0);
			$order_object                  = new stdClass();
			$order_object->user_id         = $this->_user->id;
			$order_object->region_id       = $this->getState('region_id', $params->get('region_id', 0));
			$order_object->shipping_id     = $this->getState('shipping_id');
			$order_object->payment_id      = $this->getState('payment_id');
			$order_object->customer_fields = json_encode($customer_fields);
			$order_object->status_id       = 2;

			try
			{
				$result         = $this->_db->insertObject('#__ksenmart_orders', $order_object);
				$this->order_id = $this->_db->insertid();
				$this->_session->set('shop_order_id', $this->order_id);
			}
			catch (Exception $e)
			{
			}
		}

		$price = KSMProducts::getProductPrices($prd->id)->price;
		if ($prd->type == 'set')
		{
			$related         = KSMProducts::getSetRelated($id);
			$properties      = $this->getProperties();
			$item_properties = array();

			foreach ($properties as $property)
			{
				foreach ($related as $r)
				{
					$value = $jinput->getCmd('property_' . $r->relative_id . '_' . $property->id, '');
					if (!empty($value))
					{
						$item_properties[$property->id] = array('value_id' => $value);
					}
				}
			}
		}
		else
		{
			$properties      = $this->getProperties();
			$item_properties = array();

			foreach ($properties as $property)
			{
				$value = $jinput->getCmd('property_' . $id . '_' . $property->id, '');
				if (!empty($value))
				{
					$item_properties[$property->id] = array('value_id' => $value);
				}
			}
			$price = KSMProducts::getProductPriceProperties($prd->id, $item_properties);
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
			->where('order_id=' . $this->order_id)
			->where('product_id=' . $id)
			->where('properties=\'' . json_encode($item_properties) . '\'');

		$this->_db->setQuery($query);
		$item = $this->_db->loadObject();
		$this->_db->transactionStart();
		if (count($item) > 0)
		{
			$order_item_object             = new stdClass();
			$order_item_object->id         = $item->id;
			$order_item_object->count      = $item->count + $count;
			$order_item_object->properties = json_encode($item_properties);

			if ($order_item_object->count > $prd->in_stock && $this->params->get('use_stock', 1) == 1)
			{
				return JText::_('KSM_CART_ADD_PRODUCT_OUT_OF_STOCK');
			}

			try
			{
				$result = $this->_db->updateObject('#__ksenmart_order_items', $order_item_object, 'id');
			}
			catch (Exception $e)
			{
				$this->_db->transactionRollback();
			}
		}
		else
		{
			$order_item_object             = new stdClass();
			$order_item_object->order_id   = $this->order_id;
			$order_item_object->product_id = $prd->id;
			$order_item_object->price      = $price;
			$order_item_object->count      = $count;
			$order_item_object->properties = json_encode($item_properties);

			try
			{
				$result = $this->_db->insertObject('#__ksenmart_order_items', $order_item_object);
			}
			catch (Exception $e)
			{
				$this->_db->transactionRollback();
			}
		}

		if ($this->params->get('use_stock', 1) == 1 && KSMOrders::getStatus(2)->withdraw)
		{
			$product_object           = new stdClass();
			$product_object->id       = $prd->id;
			$product_object->in_stock = $prd->in_stock - $count;

			try
			{
				$result = $this->_db->updateObject('#__ksenmart_products', $product_object, 'id');
			}
			catch (Exception $e)
			{
				$this->_db->transactionRollback();
			}
		}
		$cost = $price * $count;

		$order_object     = new stdClass();
		$order_object->id = $this->order_id;
		if (!empty($customer_fields)) $order_object->customer_fields = json_encode($customer_fields);
		$order_object->cost = $this->getOrderCost($this->order_id) + $cost;

		try
		{
			$result = $this->_db->updateObject('#__ksenmart_orders', $order_object, 'id');
			$this->_db->transactionCommit();
		}
		catch (Exception $e)
		{
			$this->_db->transactionRollback();
			$result = false;
		}

		$this->onExecuteAfter('addToCart', array(&$result));

		return '';
	}

	private function getOrderCost($order_id)
	{
		$this->onExecuteBefore('getOrderCost', array(&$order_id));

		if (!empty($order_id))
		{
			$query = $this->_db->getQuery(true);
			$query
				->select('
                    o.cost
                ')
				->from('#__ksenmart_orders AS o')
				->where('o.id=' . $this->_db->escape($order_id));
			$this->_db->setQuery($query);
			$cost = $this->_db->loadObject()->cost;

			$this->onExecuteAfter('getOrderCost', array(&$cost));

			return $cost;
		}

		return 0;
	}

	public function updateCart($items)
	{
		$this->onExecuteBefore('updateCart', array(&$items));

		$params      = JComponentHelper::getParams('com_ksenmart');
		$return      = true;
		$order_items = KSMOrders::getOrderItems($this->order_id);

		foreach ($order_items as $item)
		{
			$product = KSMProducts::getProduct($item->product_id);
			$count   = isset($items[$item->id]) ? $items[$item->id] : 0;
			$count   = $count < 0 ? 0 : $count;

			$diff_count = $count - $item->count;

			$this->_db->transactionStart();
			if ($count == 0)
			{
				try
				{
					$query = $this->_db->getQuery(true);
					$query
						->delete('#__ksenmart_order_items')
						->where('id = ' . $item->id);
					$this->_db->setQuery($query);
					$this->_db->execute();
				}
				catch (Exception $e)
				{
				}
			}

			if ($params->get('use_stock', 1))
			{
				if (KSMOrders::getStatus(2)->withdraw)
				{
					if ($diff_count > $product->in_stock)
					{
						$diff_count = $product->in_stock;
						$count      = $item->count + $diff_count;

						$this->setError(JText::sprintf('KSM_CART_PRODUCT_OUT_OF_STOCK', $product->title));
						$return = false;
					}

					$product_object           = new stdClass();
					$product_object->id       = $product->id;
					$product_object->in_stock = $product->in_stock - $diff_count;
					try
					{
						$this->_db->updateObject('#__ksenmart_products', $product_object, 'id');
					}
					catch (Exception $e)
					{
						$this->_db->transactionRollback();
					}
				}
				else
				{
					if ($count > $product->in_stock)
					{
						$count      = $product->in_stock;
						$diff_count = $count - $item->count;;

						$this->setError(JText::sprintf('KSM_CART_PRODUCT_OUT_OF_STOCK', $product->title));
						$return = false;
					}
				}
			}

			$order_item_object        = new stdClass();
			$order_item_object->id    = $item->id;
			$order_item_object->count = $count;
			try
			{
				$this->_db->updateObject('#__ksenmart_order_items', $order_item_object, 'id');
			}
			catch (Exception $e)
			{
				$this->_db->transactionRollback();
			}

			$diff_price = $diff_count * $item->price;

			$order_object       = new stdClass();
			$order_object->id   = $this->order_id;
			$order_object->cost = $this->getOrderCost($this->order_id) + $diff_price;
			try
			{
				$this->_db->updateObject('#__ksenmart_orders', $order_object, 'id');
				$this->_db->transactionCommit();
			}
			catch (Exception $e)
			{
				$this->_db->transactionRollback();
			}
		}

		$this->onExecuteAfter('updateCart', array(&$return));

		return $return;
	}

	public function getSystemCustomerFields()
	{
		return array(
			'phone',
			'name',
			'email',
			'lastname',
			'surname'
		);
	}

	public function closeOrder()
	{
		$this->onExecuteBefore('closeOrder');

		$order  = new stdClass();
		$jinput = JFactory::getApplication()->input;

		$order->id              = $this->order_id;
		$order                  = $this->setFieldsOrder($order);
		$order->shipping_coords = $jinput->get('shipping_coords', null, 'string');

		try
		{
			$this->_db->updateObject('#__ksenmart_orders', $order, 'id');
		}
		catch (exception $e)
		{
		}

		KSMOrders::setOrderStatus($order->id, 1);
		KSMOrders::sendOrderMail($this->order_id);
		KSMOrders::sendOrderMail($this->order_id, true);

		$this->onExecuteAfter('closeOrder');

		return true;
	}

	/**
	 * Возвращает информацию о компании из настроек компонента
	 *
	 * @return stdClass
	 *
	 * @since version 4.0.0
	 */
	public function getCompany()
	{
		$params           = JComponentHelper::getParams('com_ksenmart');
		$company          = new stdClass();
		$company->name    = $params->get('printforms_companyname', '');
		$company->address = $params->get('printforms_companyaddress', '');
		$company->phone   = $params->get('printforms_companyphone', '');

		return $company;
	}

	/**
	 * Обновляет информацию о заказе в БД
	 *
	 * @return bool
	 *
	 * @since version 4.0.0
	 */
	public function setModelFields()
	{
		$this->onExecuteBefore('setModelFields');

		$order     = new stdClass();
		$order->id = $this->order_id;
		$order     = $this->setFieldsOrder($order);

		try
		{
			$order->result = $this->_db->updateObject('#__ksenmart_orders', $order, 'id');
		}
		catch (exception $e)
		{
		}

		$this->onExecuteAfter('setModelFields');

		return $order;
	}

	/**
	 * Задает объекту заказа поля объединяя полученные данные с данными в БД
	 *
	 * @param null $order Объект заказа
	 *
	 * @return null
	 *
	 * @since version 4.0.0
	 */
	private function setFieldsOrder($order = null)
	{
		if (empty($order)) return null;

		$app                    = JFactory::getApplication();
		$jinput                 = $app->input;
		$order->region_id       = $jinput->get('region_id', null, 'int');
		$order->shipping_id     = $jinput->get('shipping_id', null, 'int');
		$order->payment_id      = $jinput->get('payment_id', null, 'int');
		$order->customer_fields = $jinput->get('customer_fields', null, 'array');
		$order->address_fields  = $jinput->get('address_fields', null, 'array');

		if (!empty($order->customer_fields) || !empty($order->address_fields))
		{
			$query = $this->_db->getQuery(true);
			$query->select('*')->from('#__ksenmart_orders')->where('id=' . (int) $this->order_id);
			$this->_db->setQuery($query);
			$old_order = $this->_db->loadObject();

			if (!empty($order->customer_fields))
			{
				$old_order->customer_fields = json_decode($old_order->customer_fields, true);
				foreach ($old_order->customer_fields as $name => &$field)
				{
					if (empty($order->customer_fields[$name])) continue;
					$field = $order->customer_fields[$name];
					$app->setUserState($this->context . '.customer_fields' . '[' . $name . ']', $field);
				}
				$order->customer_fields = json_encode($old_order->customer_fields, JSON_UNESCAPED_UNICODE);
			}

			if (!empty($order->address_fields))
			{
				$old_order->address_fields = json_decode($old_order->address_fields, true);
				foreach ($old_order->address_fields as $name => &$field)
				{
					if (empty($order->address_fields[$name])) continue;
					$field = $order->address_fields[$name];
					$app->setUserState($this->context . '.address_fields' . '[' . $name . ']', $field);
				}
				$order->address_fields = json_encode($old_order->address_fields, JSON_UNESCAPED_UNICODE);
			}
		}

		return $order;
	}

	/**
	 * Очищает корзину заказа
	 *
	 * @return bool
	 *
	 * @since version 2.0.0
	 */
	public function clearCart()
	{
		$this->onExecuteBefore('clearCart', array(&$this));

		$app     = JFactory::getApplication();
		$session = JFactory::getSession();
		$session->set('shopcart_discount', '');
		$session->set('shop_order_id', null);
		$session->set('step_id', 0);
		$app->setUserState($this->context . '.step_id', 0);

		$this->onExecuteAfter('clearCart', array(&$this));

		return true;
	}

	/**
	 * Завершает оформление заказа
	 *
	 * @return bool
	 *
	 * @since version
	 */
	public function completeOrder()
	{
		$this->onExecuteBefore('completeOrder', array(&$this));

		$params = JComponentHelper::getParams('com_ksenmart');

		if ($params->get('create_account_after_ordering', 0)
			&& JComponentHelper::getParams('com_users')->get('allowUserRegistration')
			&& !KSUsers::getUser()->id)
		{
			$cart = $this->getCart();
			$data = [];

			foreach ($cart->customer_fields as $key => $customerField)
			{
				switch ($key)
				{
					case 'phone':
						$data[$key] = $customerField;
						break;
					case 'email':
						$data['email1']   = $customerField;
						$data['email2']   = $customerField;
						$data['username'] = $customerField;
						break;
					case 'first_name':
						$data['name'] = $customerField;
						break;
					case 'last_name':
						if (empty($data['name']))
						{
							$data['name'] = $customerField;
						}
						break;
				}
			}
			$data['password1'] = JUserHelper::genRandomPassword(8);
			$data['password2'] = $data['password1'];

			$return = KSUsers::addUser($data);

			if ($return && is_numeric($return))
			{
				$order = (object) [
					'id'      => $this->order_id,
					'user_id' => (int) $return,
				];

				$this->_db->updateObject('#__ksenmart_orders', $order, 'id');
			}
		}

		$this->onExecuteAfter('completeOrder', array(&$this));

		return true;
	}
}