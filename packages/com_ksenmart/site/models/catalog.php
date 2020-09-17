<?php
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

KSSystem::import('models.modelkslist');

/**
 *
 * @since       version 4.0.0
 */
class KsenMartModelcatalog extends JModelKSList
{

	private $_ids = array();
	private $_category = null;
	private $_categories = null;
	private $_manufacturers = null;
	private $_enabledsProperties = null;
	private $_properties = null;
	private $_range_properties = null;
	private $_countries = null;
	private $_title = null;
	private $_new = null;
	private $_promotion = null;
	private $_hot = null;
	private $_recommendation = null;
	private $_price_less = null;
	private $_price_more = null;
	private $_store_ids = array();

	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array();
		}
		parent::__construct($config);

		$this->getDefaultStates();

		$this->setState('params', $this->_params);
	}

	/**
	 *
	 *
	 * @since version 4.0.0
	 */
	private function getDefaultStates()
	{
		$this->onExecuteBefore('getDefaultStates', array(&$this));

		$this->_categories         = $this->getState('com_ksenmart.categories');
		$this->_manufacturers      = $this->getState('com_ksenmart.manufacturers');
		$this->_enabledsProperties = $this->getState('com_ksenmart.enabledsProperties');
		$this->_properties         = $this->getState('com_ksenmart.properties');
		$this->_range_properties   = $this->getState('com_ksenmart.range_properties');
		$this->_countries          = $this->getState('com_ksenmart.countries');
		$this->_title              = $this->getState('com_ksenmart.title');
		$this->_new                = $this->getState('com_ksenmart.new');
		$this->_promotion          = $this->getState('com_ksenmart.promotion');
		$this->_hot                = $this->getState('com_ksenmart.hot');
		$this->_recommendation     = $this->getState('com_ksenmart.recommendation');
		$this->_price_less         = $this->getState('com_ksenmart.price_less');
		$this->_price_more         = $this->getState('com_ksenmart.price_more');

		$this->onExecuteAfter('getDefaultStates', array(&$this));
	}

	/**
	 * @param string $ordering
	 * @param string $direction
	 *
	 *
	 * @since version 4.0.0
	 */
	protected function populateState($ordering = 'ordering', $direction = 'ASC')
	{

		$this->onExecuteBefore('populateState', array(&$this));

		if (empty($this->_params))
		{
			$this->_params = JComponentHelper::getParams('com_ksenmart');
			$this->setState('params', $this->_params);
		}

		$jinput     = JFactory::getApplication()->input;
		$categories = $jinput->get('categories', array(), 'ARRAY');
		\Joomla\Utilities\ArrayHelper::toInteger($categories);
		$categories = array_filter($categories, 'KSFunctions::filterArray');
		$this->setState('com_ksenmart.categories', $categories);

		$manufacturers = $jinput->get('manufacturers', array(), 'ARRAY');
		\Joomla\Utilities\ArrayHelper::toInteger($manufacturers);
		$manufacturers = array_filter($manufacturers, 'KSFunctions::filterArray');
		$this->setState('com_ksenmart.manufacturers', $manufacturers);

		$enabledsProperties = $jinput->get('enabledsProperties', array(), 'ARRAY');
		\Joomla\Utilities\ArrayHelper::toInteger($enabledsProperties);
		$enabledsProperties = array_filter($enabledsProperties, 'KSFunctions::filterArray');
		$this->setState('com_ksenmart.enabledsProperties', $enabledsProperties);

		$properties = $jinput->get('properties', array(), 'ARRAY');
		\Joomla\Utilities\ArrayHelper::toInteger($properties);
		$properties = array_filter($properties, 'KSFunctions::filterArray');
		$this->setState('com_ksenmart.properties', $properties);

		$range_properties = $jinput->get('range_properties', array(), 'ARRAY');
		\Joomla\Utilities\ArrayHelper::toInteger($range_properties);
		$range_properties = array_filter($range_properties, 'KSFunctions::filterArray');
		$this->setState('com_ksenmart.range_properties', $range_properties);

		$countries = $jinput->get('countries', array(), 'ARRAY');
		\Joomla\Utilities\ArrayHelper::toInteger($countries);
		$countries = array_filter($countries, 'KSFunctions::filterArray');
		$this->setState('com_ksenmart.countries', $countries);

		$price_less = $jinput->getInt('price_less', '');
		$this->setState('com_ksenmart.price_less', $price_less);
		$price_more = $jinput->getInt('price_more', '');
		$this->setState('com_ksenmart.price_more', $price_more);
		$title = $jinput->getString('title', '');
		$this->setState('com_ksenmart.title', $title);
		$order_type = $jinput->getString('order_type', 'ordering');
		$new        = $jinput->getString('new', '');
		$this->setState('com_ksenmart.new', $new);
		$promotion = $jinput->getString('promotion', '');
		$this->setState('com_ksenmart.promotion', $promotion);
		$hot = $jinput->getString('hot', '');
		$this->setState('com_ksenmart.hot', $hot);
		$recommendation = $jinput->getString('recommendation', '');
		$this->setState('com_ksenmart.recommendation', $recommendation);
		$this->setState('list.ordering', $order_type);
		$order_dir = $jinput->getString('order_dir', 'asc');
		$this->setState('list.direction', $order_dir);
		$limit = $jinput->getInt('limit', $this->_params->get('site_product_limit', 20));
		$this->setState('list.limit', $limit);
		$limitstart = $jinput->getInt('limitstart', 0);
		$this->setState('list.start', $limitstart);

		$this->onExecuteAfter('populateState', array(&$this));
	}

	/**
	 *
	 * @return array|mixed
	 *
	 * @since version 2.0.0
	 */
	public function getProductsIds()
	{

		$this->onExecuteBefore('getProductsIds', array(&$this->_ids));

		$this->_ids = array();

		$jinput = JFactory::getApplication()->input;
		$task   = $jinput->getCmd('task', '');
		if ($this->_price_less >= 0 && !empty($this->_price_more))
		{
			$this->_ids = $this->getIdsByMMPrices($this->_price_less, $this->_price_more);
		}
		if (count($this->_categories) > 0)
		{
			$this->_ids = $this->getIdsByCategories($this->_categories);
		}
		if (count($this->_countries) > 0)
		{
			$this->_ids = $this->getIdsByCountries($this->_countries);
		}
		if (count($this->_manufacturers) > 0)
		{
			$this->_ids = $this->getIdsByManufacturers($this->_manufacturers);
		}
		if (!empty($task))
		{
			$this->_store_ids['pccm'] = $this->_ids;
		}
		if (count($this->_range_properties) > 0)
		{
			$this->getRangeProperties($this->_range_properties);
		}
		if (count($this->_properties) > 0)
		{
			$this->_ids = $this->getIdsByProperties($this->_properties);
		}

		$this->onExecuteAfter('getProductsIds', array(&$this->_ids));

		return $this->_ids;
	}

	public function setProductsIds($ids)
	{
		$this->_ids = $ids;
	}

	public function getRangeProperties($range_properties)
	{

		$this->onExecuteBefore('getRangeProperties', array(&$range_properties));

		if (!empty($range_properties))
		{
			foreach ($range_properties as $property_id => $range_property)
			{
				$query = $this->_db->getQuery(true);
				$query
					->select('id')
					->from('#__ksenmart_property_values')
					->where('property_id=' . (int) $property_id)
					->where('title >= ' . (int) current($range_property));

				if (count($range_property) > 1)
				{
					$query->where('title <= ' . (int) end($range_property));
				}
				$this->_db->setQuery($query);
				$values = $this->_db->loadColumn();

				if (!empty($values)) $this->_properties = array_merge($this->_properties, $values);
			}

			$this->onExecuteAfter('getRangeProperties', array(&$this->_properties));
		}
	}

	/**
	 * @param $properties
	 *
	 * @return array
	 *
	 * @since version 4.0.0
	 */
	private function getIdsByProperties($properties)
	{

		$this->onExecuteBefore('getIdsByProperties', array(&$properties));

		if (!empty($properties))
		{
			$props      = $this->getIdsByPropertiesV($properties);
			$this->_ids = $this->getIdsByPropertiesPV($props);
			$this->_ids = $this->getProductsIdsBy($this->_ids);

			$this->onExecuteAfter('getIdsByProperties', array(&$this->_ids));

			return $this->_ids;
		}

		return array(0);
	}

	/**
	 * @param $properties
	 *
	 * @return array
	 *
	 * @since version 4.0.0
	 */
	private function getIdsByPropertiesV($properties)
	{
		$this->onExecuteBefore('getIdsByPropertiesV', array(&$properties));

		$props = array();
		if (!empty($properties))
		{
			$properties = KSSystem::getTableByIds($properties, 'property_values', array('t.id', 't.property_id'), false);
			foreach ($properties as $property)
			{
				if (!isset($props[$property->property_id]))
				{
					$props[$property->property_id] = array();
				}
				$props[$property->property_id][] = $property->id;
			}
		}

		$this->onExecuteAfter('getIdsByPropertiesV', array(&$props));

		return $props;
	}

	/**
	 * @param $properties
	 *
	 * @return array|mixed
	 *
	 * @since version 4.0.0
	 */
	private function getIdsByPropertiesPV($properties)
	{
		$this->onExecuteBefore('getIdsByPropertiesPV', array(&$properties));

		if (!empty($properties))
		{
			$where_pv = array();
			end($properties);

			$query = $this->_db->getQuery(true);
			foreach ($properties as $property_id => $property_values)
			{
				if (!empty($property_values))
				{
					$query->innerJoin('#__ksenmart_product_properties_values as kppv' . $property_id . ' on kppv' . $property_id . '.product_id=p.id');
					$where_pv[] = '(kppv' . $property_id . '.value_id IN (' . implode(',', $property_values) . '))';
				}
			}
			if (!empty($this->_ids))
			{
				$where_pv[] = "(p.id IN (" . implode(',', $this->_ids) . "))";
			}
			$query
				->select('p.id')
				->from('#__ksenmart_products as p')
				->group('p.id')
				->where($where_pv);
			$this->_db->setQuery($query);
			$this->_ids = $this->_db->loadColumn();
			$this->_ids = count($this->_ids) > 0 ? $this->_ids : array(0);

			$this->onExecuteAfter('getIdsByPropertiesPV', array(&$this->_ids));

			return $this->_ids;
		}

		return array(0);
	}

	/**
	 * @param array $ids
	 *
	 * @return array
	 *
	 * @since version 4.0.0
	 */
	private function getProductsIdsBy(array $ids)
	{
		$this->onExecuteBefore('getProductsIdsBy', array(&$ids));

		if (!empty($ids))
		{
			$products = KSSystem::getTableByIds($ids, 'products', array('t.id', 't.parent_id'));
			foreach ($products as $product)
			{
				$this->_ids[] = $product->id;
				if ($product->parent_id != 0)
				{
					$this->_ids[] = $product->parent_id;
				}
			}

			$this->_ids = array_unique($this->_ids);
			$this->_ids = count($this->_ids) > 0 ? $this->_ids : array(0);

			$this->onExecuteAfter('getProductsIdsBy', array(&$this->_ids));

			return $this->_ids;
		}

		return array(0);
	}

	/**
	 * @param array $where
	 *
	 * @return array
	 *
	 * @since version 4.0.0
	 */
	private function getProductsIdsByWhere(array $where)
	{
		$this->onExecuteBefore('getProductsIdsByWhere', array(&$where));

		if (!empty($where))
		{
			$query = $this->_db->getQuery(true);
			$query
				->select('
                    p.id,
                    p.parent_id
                ')
				->from('#__ksenmart_products as p')
				->where($where);

			$this->_db->setQuery($query);
			$products = $this->_db->loadObjectList();

			foreach ($products as $product)
			{
				$this->_ids[] = $product->id;
				if ($product->parent_id != 0)
				{
					$this->_ids[] = $product->parent_id;
				}
			}

			$this->_ids = array_unique($this->_ids);
			$this->_ids = count($this->_ids) > 0 ? $this->_ids : array(0);

			$this->onExecuteAfter('getProductsIdsByWhere', array(&$this->_ids));

			return $this->_ids;
		}

		return array(0);
	}

	/**
	 * @param $price_less
	 * @param $price_more
	 *
	 * @return array
	 *
	 * @since version 4.0.0
	 */
	private function getIdsByMMPrices($price_less, $price_more)
	{
		$this->onExecuteBefore('getIdsByMMPrices', array(&$price_less, &$price_more));

		if ($price_less >= 0 && !empty($price_more))
		{
			if (KSMPrice::getDefaultUserCurrency() != KSMPrice::_getDefaultCurrency())
			{
				$price_less = KSMPrice::getPriceInDefaultCurrency(($price_less - 1), KSMPrice::getDefaultUserCurrency());
				$price_more = ceil(KSMPrice::getPriceInDefaultCurrency(($price_more + 1), KSMPrice::getDefaultUserCurrency()));
			}
			$where         = array('p.published=1');
			$price_where_l = array();
			$price_where_m = array();

			$query = $this->_db->getQuery(true);
			$query
				->select('
                    c.id, 
                    c.rate
                ')
				->from('#__ksenmart_currencies AS c');
			$this->_db->setQuery($query);
			$currencies = $this->_db->loadObjectList('id');

			foreach ($currencies as $key => $value)
			{
				$cur_price_l = $price_less * $currencies[$key]->rate;
				$cur_price_m = $price_more * $currencies[$key]->rate;

				$price_where_l[] = '(p.price>=' . $this->_db->escape($cur_price_l) . ' AND p.price_type=' . $this->_db->escape($currencies[$key]->id) . ')';
				$price_where_m[] = '(p.price<=' . $this->_db->escape($cur_price_m) . ' AND p.price_type=' . $this->_db->escape($currencies[$key]->id) . ')';
			}
			if (count($price_where_l))
			{
				$where[] = '(' . implode(' OR ', $price_where_l) . ')';
			}
			if (count($price_where_m))
			{
				$where[] = '(' . implode(' OR ', $price_where_m) . ')';
			}
			$this->onExecuteAfter('getIdsByMMPrices', array(&$where));

			return $this->getProductsIdsByWhere($where);
		}

		return array(0);
	}

	/**
	 * @param $categories
	 *
	 * @return array|mixed
	 *
	 * @since version 4.0.0
	 */
	private function getIdsByCategories($categories)
	{
		$this->onExecuteBefore('getIdsByCategories', array(&$categories));
		if (!empty($categories))
		{
			$where        = array();
			$this->_ids_l = count($this->_ids);
			if ($this->_params->get('show_products_from_subcategories', 1) == 1)
			{
				$cats       = $categories;
				$categories = array();
				foreach ($cats as $cat)
				{
					$c          = $this->getChildCats($cat);
					$categories = array_merge($categories, $c);
				}
			}
			$where[] = "(category_id IN (" . implode(',', $categories) . "))";
			if (count($categories) < 20 || empty($this->_ids))
			{
				if ($this->_ids_l > 0)
				{
					$where[] = "(product_id IN (" . implode(',', $this->_ids) . "))";
				}
				$query = $this->_db->getQuery(true);
				$query
					->select('DISTINCT product_id')
					->from('#__ksenmart_products_categories')
					->where($where)
					->group('product_id');
				$this->_db->setQuery($query, 0, $this->_ids_l);
				$this->_ids = $this->_db->loadColumn();
			}
			else
			{
				$query = $this->_db->getQuery(true);
				$query
					->select('DISTINCT product_id')
					->from('#__ksenmart_products_categories')
					->where($where)
					->group('product_id');
				$this->_db->setQuery($query);
				$ids = $this->_db->loadColumn();

				$this->_ids = array_intersect($this->_ids, $ids);
			}

			$this->_ids = count($this->_ids) > 0 ? $this->_ids : array(0);

			$this->onExecuteAfter('getIdsByCategories', array(&$this->_ids));

			return $this->_ids;
		}

		return array(0);
	}

	/**
	 * @param $countries
	 *
	 * @return array|mixed
	 *
	 * @since version 4.0.0
	 */
	private function getIdsByCountries($countries)
	{
		$this->onExecuteBefore('getIdsByCountries', array(&$countries));

		if (!empty($countries))
		{
			$where = array();
			if (count($this->_ids) > 0)
			{
				$where[] = "(p.id IN (" . implode(',', $this->_ids) . "))";
			}

			$query = $this->_db->getQuery(true);
			$query->select('m.id')->from('#__ksenmart_manufacturers as m')->where('m.country in (' . implode(',', $countries) . ')');
			$this->_db->setQuery($query);
			$manufacturers = $this->_db->loadColumn();
			if (count($manufacturers) > 0)
				$where[] = "(p.manufacturer IN (" . implode(',', $manufacturers) . "))";

			$query = $this->_db->getQuery(true);
			$query->select('p.id')->from('#__ksenmart_products as p')->where($where);
			$this->_db->setQuery($query);
			$this->_ids = $this->_db->loadColumn();
			$this->_ids = count($this->_ids) > 0 ? $this->_ids : array(0);

			$this->onExecuteAfter('getIdsByCountries', array(&$this->_ids));

			return $this->_ids;
		}

		return array(0);
	}

	/**
	 * @param $manufacturers
	 *
	 * @return array|mixed
	 *
	 * @since version 4.0.0
	 */
	private function getIdsByManufacturers($manufacturers)
	{
		$this->onExecuteBefore('getIdsByManufacturers', array(&$manufacturers));

		if (!empty($manufacturers))
		{
			$where = array();
			if (count($this->_ids) > 0)
			{
				$where[] = "(p.id IN (" . implode(',', $this->_ids) . "))";
			}
			if (count($manufacturers) > 0)
			{
				$where[] = "(p.manufacturer IN (" . implode(',', $manufacturers) . "))";
			}
			$query = $this->_db->getQuery(true);
			$query->select('p.id')->from('#__ksenmart_products as p')->where($where);
			$this->_db->setQuery($query);
			$this->_ids = $this->_db->loadColumn();
			$this->_ids = count($this->_ids) > 0 ? $this->_ids : array(0);

			$this->onExecuteAfter('getIdsByManufacturers', array(&$this->_ids));

			return $this->_ids;
		}

		return array(0);
	}

	/**
	 *
	 * @return JDatabaseQuery
	 *
	 * @since version 4.0.0
	 */
	public function getListQuery()
	{
		$this->onExecuteBefore('getListQuery');

		if (empty($this->_ids))
		{
			$this->_ids = $this->getProductsIds();
		}

		$where = $this->getFilterDefaultParams();

		if ($this->_params->get('show_out_stock') != 1)
		{
			$where[] = "(p.in_stock>0)";
		}

		$where[] = "(p.parent_id=0)";

		$query = $this->_db->getQuery(true);
		$query
			->select('p.id as id')
			->from('#__ksenmart_products AS p')
			->where($where)
			->order('p.' . $this->getState('list.ordering') . ' ' . $this->getState('list.direction'))
			->group('p.id');

		// Ждём Joomla 3.7
		/*if ($this->_params->get('search_child') == 1 || false) {
			$subwhere = $this->getFilterDefaultParams('pc');
			$subwhere[] = "(pc.parent_id>0)";
			$subquery   = $this->_db->getQuery(true);
			$subquery
				->select('pc.parent_id as id')
				->from('#__ksenmart_products AS pc')
				->where($subwhere);

			$query->union($subquery);
		}*/

		$this->onExecuteAfter('getListQuery', array(&$query));

		return $query;
	}

	/**
	 *
	 * @return array
	 *
	 * @since version 2.0.0
	 */
	public function getFilterProperties()
	{
		$this->onExecuteBefore('getFilterProperties');

		$this->_ids = array();
		$ids        = array();

		if (empty($this->_store_ids['pccm']))
		{
			if ($this->_price_less >= 0 && !empty($this->_price_more))
			{
				$ids = $this->getIdsByMMPrices($this->_price_less, $this->_price_more);
			}
			if (count($this->_categories) > 0)
			{
				$ids = $this->getIdsByCategories($this->_categories);
			}
			if (count($this->_countries) > 0)
			{
				$ids = $this->getIdsByCountries($this->_countries);
			}
			if (count($this->_manufacturers) > 0)
			{
				$ids = $this->getIdsByManufacturers($this->_manufacturers);
			}
		}
		else
		{
			$ids = $this->_store_ids['pccm'];
		}
		$request_props = $this->getIdsByPropertiesV($this->_properties);

		$query = $this->_db->getQuery(true);
		$query->select('id')->from('#__ksenmart_properties')->where('published=1');
		$this->_db->setQuery($query);
		$db_props = $this->_db->loadColumn();

		$properties = array();
		foreach ($db_props as $property_id)
		{
			if (!empty($this->_enabledsProperties) && !in_array($property_id, $this->_enabledsProperties))
			{
				continue;
			}
			$this->_ids = $ids;
			$props      = $request_props;
			unset($props[$property_id]);
			if (count($props))
			{
				$this->_ids = $this->getIdsByPropertiesPV($props);
			}

			$where   = $this->getFilterDefaultParams();
			$where[] = 'ppv1.property_id = ' . $property_id;
			$query   = $this->_db->getQuery(true);
			$query
				->select('ppv1.value_id')
				->from('#__ksenmart_product_properties_values AS ppv1')
				->leftJoin('#__ksenmart_products AS p on p.id=ppv1.product_id');
			$query->where($where);
			$query->group('ppv1.value_id');
			$this->_db->setQuery($query);
			$values = $this->_db->loadColumn();

			$properties[$property_id] = $values;
		}

		$this->onExecuteAfter('getFilterProperties', array(&$properties));

		return $properties;
	}

	/**
	 *
	 * @return array
	 *
	 * @since version 2.0.0
	 */
	public function getFilterManufacturers()
	{
		$this->onExecuteBefore('getFilterManufacturers');

		$this->_ids = array();
		if (empty($this->_store_ids['pcp']))
		{
			if ($this->_price_less >= 0 && !empty($this->_price_more))
			{
				$this->_ids = $this->getIdsByMMPrices($this->_price_less, $this->_price_more);
			}
			if (count($this->_categories) > 0)
			{
				$this->_ids = $this->getIdsByCategories($this->_categories);
			}
			if (count($this->_properties) > 0)
			{
				$this->_ids = $this->getIdsByProperties($this->_properties);
			}
			$this->_store_ids['pcp'] = $this->_ids;
		}
		else
		{
			$this->_ids = $this->_store_ids['pcp'];
		}
		if (count($this->_countries) > 0)
		{
			$this->_ids = $this->getIdsByCountries($this->_countries);
		}

		$where = $this->getFilterDefaultParams();

		$query = $this->_db->getQuery(true);
		$query
			->select('p.manufacturer')
			->from('#__ksenmart_products as p')
			->where('p.manufacturer != 0')
			->group('p.manufacturer');
		if ($this->_params->get('show_out_stock') != 1)
		{
			$where[] = "(p.in_stock>0)";
		}
		$query->where($where);
		$this->_db->setQuery($query);
		$values = $this->_db->loadObjectList();

		$manufacturers = array();
		foreach ($values as $value)
		{
			$manufacturers[] = $value->manufacturer;
		}

		$this->onExecuteAfter('getFilterManufacturers', array(&$manufacturers));

		return $manufacturers;
	}

	/**
	 *
	 * @return array
	 *
	 * @since version 2.0.0
	 */
	private function getFilterDefaultParams($alias = 'p')
	{
		$this->onExecuteBefore('getFilterDefaultParams', array(&$this));

		$where = array($alias . '.published=1');
		if (count($this->_ids) > 0) $where[] = "(" . $alias . ".id in (" . implode(',', $this->_ids) . "))";
		if (!empty($this->_title))
		{
			$title   = $this->_db->quote('%' . $this->_title . '%');
			$where[] = '(' . $alias . '.title LIKE ' . $title . '  OR ' . $alias . '.product_code LIKE ' . $title . '  OR ' . $alias . '.introcontent LIKE ' . $title . ' OR ' . $alias . '.content LIKE ' . $title . ')';
		}
		if (!empty($this->_new))
		{
			$where[] = "(" . $alias . ".new=1)";
		}
		if (!empty($this->_promotion))
		{
			$where[] = "(" . $alias . ".promotion=1)";
		}
		if (!empty($this->_hot))
		{
			$where[] = "(" . $alias . ".hot=1)";
		}
		if (!empty($this->_recommendation))
		{
			$where[] = "(" . $alias . ".recommendation=1)";
		}

		$this->onExecuteAfter('getFilterDefaultParams', array(&$where));

		return $where;
	}

	/**
	 *
	 * @return array
	 *
	 * @since version
	 */
	public function getFilterCountries()
	{
		$this->onExecuteBefore('getFilterCountries', array(&$this));

		$this->_ids = array();
		if (empty($this->_store_ids['pcp']))
		{
			if ($this->_price_less >= 0 && !empty($this->_price_more))
			{
				$this->_ids = $this->getIdsByMMPrices($this->_price_less, $this->_price_more);
			}
			if (count($this->_categories) > 0)
			{
				$this->_ids = $this->getIdsByCategories($this->_categories);
			}
			if (count($this->_properties) > 0)
			{
				$this->_ids = $this->getIdsByProperties($this->_properties);
			}
			$this->_store_ids['pcp'] = $this->_ids;
		}
		else
		{
			$this->_ids = $this->_store_ids['pcp'];
		}
		if (count($this->_manufacturers) > 0)
		{
			$this->_ids = $this->getIdsByManufacturers($this->_manufacturers);
		}

		$where = $this->getFilterDefaultParams();

		$query = $this->_db->getQuery(true);
		$query
			->select('m.country')
			->from('#__ksenmart_manufacturers as m')
			->leftJoin('#__ksenmart_products as p on p.manufacturer=m.id')
			->group('m.country');
		if ($this->_params->get('show_out_stock') != 1)
		{
			$where[] = "(p.in_stock>0)";
		}
		$query->where($where);
		$this->_db->setQuery($query);
		$values = $this->_db->loadObjectList();

		$countries = array();
		foreach ($values as $value)
		{
			$countries[] = $value->country;
		}

		$this->onExecuteAfter('getFilterCountries', array(&$countries));

		return $countries;
	}

	/**
	 *
	 * @return mixed
	 *
	 * @since version 2.0.0
	 */
	public function getItems()
	{
		$this->onExecuteBefore('getItems');

		$items = parent::getItems();
		$ids   = array();
		foreach ($items as &$item)
		{
			$ids[] = $item->id;
		}
		$items = KSMProducts::getProducts($ids);

		$this->onExecuteAfter('getItems', array(&$items));

		return $items;
	}

	/**
	 *
	 * @return mixed|stdClass
	 *
	 * @since version 2.0.0
	 */
	public function getCategory()
	{
		$this->onExecuteBefore('getCategory');

		if (empty($this->_categories)) return new stdClass;
		if (!empty($this->_category)) return $this->_category;
		$query = $this->_db->getQuery(true);
		$query
			->select('
                    c.*,
                    f.filename,
                    f.folder,
                    f.params
                ')
			->from('#__ksenmart_categories AS c')
			->leftJoin('#__ksenmart_files AS f ON c.id=f.owner_id AND f.owner_type=' . $this->_db->quote('category'))
			->where('c.published=1')
			->where('c.id=' . $this->_categories[0]);
		$this->_db->setQuery($query);
		$category = $this->_db->loadObject();
		if (!empty($category))
		{
			$category->image = KSMedia::resizeImage($category->filename, $category->folder, $this->_params->get('thumb_width'), $this->_params->get('thumb_height'));
		}
		$category->edit = false;
		$user           = JFactory::getUser();
		if ($user->id > 0)
		{
			$isroot = $user->authorise('core.admin');
			if ($isroot)
			{
				$category->edit            = true;
				$category->editlink        = '/administrator/index.php?option=com_ksenmart&view=catalog&layout=category&id=' . $category->id . '&tmpl=component';
				$category->addcategorylink = '/administrator/index.php?option=com_ksenmart&view=catalog&layout=category&id=0&tmpl=component';
				$category->addproductlink  = '/administrator/index.php?option=com_ksenmart&view=catalog&layout=product&id=0&categories[]=' . $category->id . '&tmpl=component';
			}
		}

		$this->onExecuteAfter('getCategory', array(&$category));
		$this->_category = $category;

		return $category;
	}

	/**
	 *
	 * @return string
	 *
	 * @since version 2.0.0
	 */
	public function getCategoryTitle()
	{
		$this->onExecuteBefore('getCategoryTitle');

		$shop_name      = $this->_params->get('shop_name', '');
		$path_separator = $this->_params->get('path_separator', ' ');
		$category       = $this->getCategory();
		$config         = KSSystem::getSeoTitlesConfig('category');
		$title          = array();

		if (empty($category->metatitle))
		{
			if (!empty($shop_name))
			{
				$title[] = $shop_name;
			}

			if ($config)
			{
				foreach ($config as $key => $val)
				{
					if ($val->user == 0)
					{
						if ($val->active == 1)
						{
							if ($key == 'seo-parent-category')
							{
								$categories = array();
								$parent     = $category->id;
								while ($parent != 0)
								{
									$query = $this->_db->getQuery(true);
									$query->select('title,parent_id')
										->from('#__ksenmart_categories')
										->where('id=' . $parent);
									$this->_db->setQuery($query);
									$db_category = $this->_db->loadObject();
									if ($db_category->title != '' && $parent != $category->id)
										$categories[] = $db_category->title;
									$parent = $db_category->parent_id;
								}
								$categories = array_reverse($categories);
								foreach ($categories as $category_title)
									$title[] = $category_title;
							}
							elseif ($key == 'seo-category')
							{
								$title[] = $category->title;
							}
						}
					}
					else
					{
						$title[] = $val->title;
					}
				}
			}
		}
		else
			$title[] = $category->metatitle;

		$this->onExecuteAfter('getCategoryTitle', array(&$path_separator, &$title));

		return implode($path_separator, $title);
	}

	/**
	 *
	 * @return bool|mixed
	 *
	 * @since version 2.0.0
	 */
	public function getCountry()
	{
		$this->onExecuteBefore('getCountry');

		if (!empty($this->_countries))
		{
			$query = $this->_db->getQuery(true);
			$query
				->select('
                    c.id,
                    c.title,
                    c.code,
                    c.alias,
                    c.content,
                    c.introcontent,
                    c.metatitle,
                    c.metadescription,
                    c.metakeywords
                ')
				->from('#__ksenmart_countries AS c')
				->where('c.published=1')
				->where('c.id=' . $this->_countries[0]);
			$this->_db->setQuery($query);
			$country = $this->_db->loadObject();

			$this->onExecuteAfter('getCountry', array(&$country));

			return $country;
		}

		return false;
	}

	/**
	 *
	 * @return string
	 *
	 * @since version 2.0.0
	 */
	public function getCountryTitle()
	{
		$this->onExecuteBefore('getCountryTitle');

		$shop_name      = $this->_params->get('shop_name', '');
		$path_separator = $this->_params->get('path_separator', ' ');
		$country        = $this->getCountry();
		$config         = KSSystem::getSeoTitlesConfig('country');
		$title          = array();

		if (empty($country->metatitle))
		{
			if ($shop_name != '')
			{
				$title[] = $shop_name;
			}

			if ($config)
			{
				foreach ($config as $key => $val)
				{
					if ($val->user == 0)
					{
						if ($val->active == 1)
						{
							if ($key == 'seo-country')
							{
								$title[] = $country->title;
							}
						}
					}
					else
					{
						$title[] = $val->title;
					}
				}
			}
		}
		else
			$title[] = $country->metatitle;

		$this->onExecuteAfter('getCountryTitle', array(&$path_separator, &$title));

		return implode($path_separator, $title);
	}

	/**
	 *
	 * @return mixed
	 *
	 * @since version 2.0.0
	 */
	public function getManufacturers()
	{
		$this->onExecuteBefore('getManufacturers');

		$where[] = "(m.published=1)";
		$query   = $this->_db->getQuery(true);
		$query
			->select('
                m.id,
                m.title,
                m.alias,
                m.content,
                m.introcontent,
                m.country,
                m.metatitle,
                m.metadescription,
                m.metakeywords,
                f.filename,
                f.folder,
                f.params
            ')
			->from('#__ksenmart_manufacturers AS m')
			->order('m.title');
		if (count($this->_countries) > 0)
		{
			$query->innerJoin("#__ksenmart_countries AS c ON m.country=c.id");
			$where[] = "(c.id in (" . implode(',', $this->_countries) . "))";
		}
		$query
			->leftJoin("#__ksenmart_files AS f ON m.id=f.owner_id AND f.owner_type='manufacturer'")
			->where(implode(' AND ', $where))
			->order('m.ordering')
			->group('m.id');
		$this->_db->setQuery($query);
		$manufacturers = $this->_db->loadObjectList();

		foreach ($manufacturers as &$manufacturer)
		{
			if (!empty($manufacturer->folder))
			{
				$manufacturer->img_link = JUri::root() . 'media/com_ksenmart/images/' . $manufacturer->folder . '/original/' . $manufacturer->filename;
			}
			else
			{
				$manufacturer->img_link = JUri::root() . 'media/com_ksenmart/images/manufacturers/no.jpg';
			}
			$manufacturer->small_img = KSMedia::resizeImage($manufacturer->filename, $manufacturer->folder, $this->_params->get('thumb_width'), $this->_params->get('thumb_height'));
			$manufacturer->link      = JRoute::_('index.php?option=com_ksenmart&view=catalog&manufacturers[]=' . $manufacturer->id . '&Itemid=' . KSSystem::getShopItemid());
		}

		$this->onExecuteAfter('getManufacturers', array(&$manufacturers));

		return $manufacturers;
	}

	/**
	 * @param $brands
	 *
	 * @return stdClass
	 *
	 * @since version 2.0.0
	 */
	public function getLetters($brands)
	{

		$this->onExecuteBefore('getLetters', array(&$brands));

		$letters     = new stdClass;
		$letters->en = range('A', 'Z');
		$letters->ru = array('Рђ', 'Р‘', 'Р’', 'Р“', 'Р”', 'Р•', 'РЃ', 'Р–', 'Р—', 'Р', 'Р™', 'Рљ', 'Р›', 'Рњ', 'Рќ', 'Рћ', 'Рџ', 'Р ', 'РЎ', 'Рў', 'РЈ', 'Р¤', 'РҐ', 'Р¦', 'Р§', 'Р©', 'РЁ', 'Р¬', 'Р«', 'РЄ', 'Р­', 'Р®', 'РЇ');

		$letters_tmp = new stdClass;

		foreach ($letters as $key => $lang)
		{
			$i = 0;
			foreach ($lang as $letter)
			{
				$letters_tmp->{$key}[$i]         = new stdClass;
				$letters_tmp->{$key}[$i]->letter = $letter;
				$letters_tmp->{$key}[$i]->state  = true;

				if (!array_key_exists($letter, $brands))
				{
					$letters_tmp->{$key}[$i]->state = false;
				}
				$i++;
			}
		}

		$this->onExecuteAfter('getLetters', array(&$letters_tmp));

		return $letters_tmp;
	}

	/**
	 * @param $brands
	 *
	 * @return array|stdClass
	 *
	 * @since version 2.0.0
	 */
	public function groupBrandsByLet($brands)
	{
		$this->onExecuteBefore('groupBrandsByLet', array(&$brands));

		if (!empty($brands))
		{
			$group_brands = array();
			foreach ($brands as $brand)
			{
				$letter                  = mb_substr($brand->title, 0, 1);
				$group_brands[$letter][] = $brand;
			}

			$this->onExecuteAfter('groupBrandsByLet', array(&$group_brands));

			return $group_brands;
		}

		return new stdClass;
	}

	/**
	 *
	 * @return bool|mixed
	 *
	 * @since version 2.0.0
	 */
	public function getManufacturer()
	{
		$this->onExecuteBefore('getManufacturer');

		if (!empty($this->_manufacturers))
		{
			$query = $this->_db->getQuery(true);
			$query
				->select('
                    m.id,
                    m.title,
                    m.alias,
                    m.content,
                    m.introcontent,
                    m.country,
                    m.metatitle,
                    m.metadescription,
                    m.metakeywords,
                    f.filename,
                    f.folder,
                    f.params
                ')
				->from('#__ksenmart_manufacturers AS m')
				->leftJoin('#__ksenmart_files AS f ON m.id=f.owner_id AND f.owner_type=' . $this->_db->quote('manufacturer'))
				->where('m.published=1')
				->where('m.id=' . $this->_manufacturers[0]);
			$this->_db->setQuery($query);
			$manufacturer = $this->_db->loadObject();
			if (!empty($manufacturer))
			{
				$manufacturer->image = KSMedia::resizeImage($manufacturer->filename, $manufacturer->folder, $this->_params->get('thumb_width'), $this->_params->get('thumb_height'));
			}

			$this->onExecuteAfter('getManufacturer', array(&$manufacturer));

			return $manufacturer;
		}

		return false;
	}

	/**
	 *
	 * @return string
	 *
	 * @since version 2.0.0
	 */
	public function getManufacturerTitle()
	{
		$this->onExecuteBefore('getManufacturerTitle');

		$config         = KSSystem::getSeoTitlesConfig('manufacturer');
		$shop_name      = $this->_params->get('shop_name', '');
		$path_separator = $this->_params->get('path_separator', ' ');
		$manufacturer   = $this->getManufacturer();
		$title          = array();

		if (empty($manufacturer->metatitle))
		{
			if ($shop_name != '')
			{
				$title[] = $shop_name;
			}
			if ($config)
			{
				foreach ($config as $key => $val)
				{
					if ($val->user == 0)
					{
						if ($val->active == 1)
						{
							if ($key == 'seo-manufacturer')
							{
								$title[] = $manufacturer->title;
							}
							if ($key == 'seo-country')
							{
								$query = $this->_db->getQuery(true);
								$query
									->select('c.title')
									->from('#__ksenmart_manufacturers as m')
									->leftJoin('#__ksenmart_countries as c on m.country=c.id')
									->where('m.id=' . $manufacturer->id);
								$this->_db->setQuery($query);
								$country_title = $this->_db->loadResult();
								if (!empty($country_title))
								{
									$title[] = $country_title;
								}
							}
						}
					}
					else
					{
						$title[] = $val->title;
					}
				}
			}
		}
		else
		{
			$title[] = $manufacturer->metatitle;
		}

		$this->onExecuteAfter('getManufacturerTitle', array(&$path_separator, &$title));

		return implode($path_separator, $title);
	}

	/**
	 * @param $catid
	 *
	 * @return array
	 *
	 * @since version 2.0.0
	 */
	public function getChildCats($catid)
	{
		$this->onExecuteBefore('getChildCats', array(&$catid));

		$query = $this->_db->getQuery(true);
		$query
			->select('c.id')
			->from('#__ksenmart_categories AS c')
			->order('c.ordering');

		if (is_array($catid))
		{
			\Joomla\Utilities\ArrayHelper::toInteger($catid);
			$return = $catid;
			$query->where('c.parent_id IN (' . implode(',', $catid) . ')');
		}
		else
		{
			$return = array($catid);
			$query->where('c.parent_id=' . (int) $catid);
		}

		$this->_db->setQuery($query);
		$cats = $this->_db->loadColumn();

		if (count($cats) > 0)
		{
			$return1 = $this->getChildCats($cats);
			$return  = array_merge($return, $return1);
		}

		$this->onExecuteAfter('getChildCats', array(&$return));

		return $return;
	}

	/**
	 *
	 * @return array
	 *
	 * @since version
	 */
	public function getCatalogPath()
	{
		$this->onExecuteBefore('getCatalogPath');

		$path = array();

		if (!empty($this->_categories))
		{
			$catid = $this->_categories[0];
			while ((int) $catid != 0)
			{
				$query = $this->_db->getQuery(true);
				$query
					->select('
                        c.id,
                        c.parent_id,
                        c.title,
                        c.alias
                    ')
					->from('#__ksenmart_categories AS c')
					->where('id=' . $this->_db->escape($catid));

				$this->_db->setQuery($query);
				$cat       = $this->_db->loadObject();
				$cat->link = JRoute::_('index.php?option=com_ksenmart&view=catalog&categories[]=' . $cat->id . ':' . $cat->alias . '&Itemid=' . KSSystem::getShopItemid($cat->id));
				if (count(explode('/', $cat->link)) > 2)
				{
					$path[] = array(
						'title' => $cat->title,
						'link'  => $cat->link
					);
				}
				$catid = $cat->parent_id;
			}
			$path = array_reverse($path);
		}
		elseif (!empty($this->_manufacturers))
		{
			$query = $this->_db->getQuery(true);
			$query
				->select('c.title')
				->from('#__ksenmart_manufacturers AS c')
				->where('id=' . $this->_db->escape($this->_manufacturers[0]));
			$this->_db->setQuery($query);
			$title  = $this->_db->loadResult();
			$path[] = array(
				'title' => $title,
				'link'  => ''
			);
		}
		elseif ($this->_countries)
		{
			$query = $this->_db->getQuery(true);
			$query
				->select('c.title')
				->from('#__ksenmart_countries AS c')
				->where('c.id=' . $this->_db->escape($this->_countries[0]));
			$this->_db->setQuery($query);
			$title  = $this->_db->loadResult();
			$path[] = array(
				'title' => $title,
				'link'  => ''
			);
		}
		else
		{
			$path[] = array(
				'title' => JText::_('KSM_CATALOG_TITLE'),
				'link'  => ''
			);
		}

		$this->onExecuteAfter('getCatalogPath', array(&$path));

		return $path;
	}

	/**
	 *
	 * @return string
	 *
	 * @since version 2.0.0
	 */
	public function getCatalogTitle()
	{
		$this->onExecuteBefore('getCatalogTitle');

		$shop_name      = $this->_params->get('shop_name', '');
		$path_separator = $this->_params->get('path_separator', ' ');
		$title[]        = JText::_('KSM_CATALOG_TITLE');

		if (!empty($shop_name))
		{
			$title[] = $shop_name;
		}

		if (!empty($this->_categories))
		{
			$categories = KSSystem::getTableByIds($this->_categories, 'categories', array('t.id', 't.title'));
			foreach ($categories as $category)
			{
				if (!empty($category->title))
				{
					$title[] = $category->title;
				}
			}
		}

		if (!empty($this->_countries))
		{
			$countries = KSSystem::getTableByIds($this->_countries, 'countries', array('t.id', 't.title'));
			foreach ($countries as $country)
			{
				if (!empty($country->title))
				{
					$title[] = $country->title;
				}
			}
		}

		if (!empty($this->_manufacturers))
		{
			$manufactureries = KSSystem::getTableByIds($this->_manufacturers, 'manufacturers', array('t.id', 't.title'));
			foreach ($manufactureries as $manufacturer)
			{
				if (!empty($manufacturer->title))
				{
					$title[] = $manufacturer->title;
				}
			}
		}

		if (!empty($this->_properties))
		{
			$properties = KSSystem::getTableByIds($this->_properties, 'property_values', array('t.id', 't.title', 't.property_id'), false);
			if (!empty($properties))
			{
				$props = array();
				foreach ($properties as $property)
				{
					if (!isset($props[$property->property_id]))
					{
						$props[$property->property_id] = array();
					}
					if (!empty($property->title))
					{
						$props[$property->property_id][] = $property->title;
					}
				}

				$property = KSSystem::getTableByIds($props, 'properties', array('t.id', 't.title'), true, true);
				foreach ($props as $key => $property_values)
				{
					if (!empty($property->title))
					{
						$title[] = $property->title . '=' . implode('+', $property_values);
					}
				}
			}
		}

		$this->onExecuteAfter('getCatalogTitle', array(&$path_separator, &$title));

		return implode($path_separator, $title);
	}

	/**
	 *
	 * @return bool
	 *
	 * @since version 2.0.0
	 */
	public function setCategoryMetaData()
	{
		$this->onExecuteBefore('setCategoryMetaData');

		$document        = JFactory::getDocument();
		$metatitle       = '';
		$metadescription = '';
		$metakeywords    = '';
		$category        = $this->getCategory();
		$config          = KSSystem::getSeoTitlesConfig('category', 'meta');

		if (empty($category->metatitle))
		{
			$metatitle = $category->title;
		}
		else
		{
			$metatitle = $category->metatitle;
		}
		if (empty($category->metadescription))
		{
			if ($config->description->flag == 1)
			{
				if ($config->description->type == 'seo-type-mini-description')
				{
					$metadescription = strip_tags($category->introcontent);
				}
				elseif ($config->description->type == 'seo-type-description')
				{
					$metadescription = strip_tags($category->content);
				}
				$metadescription = mb_substr($metadescription, 0, $config->description->symbols);
			}
		}
		else
		{
			$metadescription = $category->metadescription;
		}
		if (empty($category->metakeywords))
		{
			if ($config->keywords->flag == 1)
			{
				if ($config->keywords->type == 'seo-type-properties')
				{
					$query = $this->_db->getQuery(true);
					$query
						->select('p.title')
						->from('#__ksenmart_product_categories_properties as pcp')
						->leftJoin('#__ksenmart_properties as p on p.id=pcp.property_id')
						->where('pcp.category_id=' . $this->_db->q($category->id));
					$this->_db->setQuery($query);
					$properties = $this->_db->loadObjectList();
					$titles     = array();
					foreach ($properties as $property)
					{
						$titles[] = $property->title;
					}
					$metakeywords = implode(',', $titles);
				}
				elseif ($config->keywords->type == 'seo-type-title')
				{
					$metakeywords = strip_tags($category->title);
				}
			}
		}
		else
		{
			$metakeywords = $category->metakeywords;
		}
		if (!empty($metatitle))
		{
			//$document->setMetaData('title', $metatitle);
		}
		if (!empty($metadescription))
		{
			$document->setMetaData('description', $metadescription);
		}
		if (!empty($metakeywords))
		{
			$document->setMetaData('keywords', $metakeywords);
		}
		$document->addHeadLink(JRoute::_('index.php?option=com_ksenmart&view=catalog&categories[]=' . $category->id . ':' . $category->alias . '&Itemid=' . KSSystem::getShopItemid()), 'canonical');

		$this->onExecuteAfter('setCategoryMetaData', array(&$this));

		return true;
	}

	/**
	 *
	 * @return bool
	 *
	 * @since version 2.0.0
	 */
	public function setManufacturerMetaData()
	{
		$this->onExecuteBefore('setManufacturerMetaData');

		$document        = JFactory::getDocument();
		$metadescription = '';
		$metakeywords    = '';
		$manufacturer    = $this->getManufacturer();
		$config          = KSSystem::getSeoTitlesConfig('manufacturer', 'meta');

		if (empty($manufacturer->metatitle))
		{
			$metatitle = $manufacturer->title;
		}
		else
		{
			$metatitle = $manufacturer->metatitle;
		}
		if (empty($manufacturer->metadescription))
		{
			if ($config->description->flag == 1)
			{
				if ($config->description->type == 'seo-type-mini-description')
				{
					$metadescription = strip_tags($manufacturer->introcontent);
				}
				elseif ($config->description->type == 'seo-type-description')
				{
					$metadescription = strip_tags($manufacturer->content);
				}
				$metadescription = mb_substr($metadescription, 0, $config->description->symbols);
			}
		}
		else
		{
			$metadescription = $manufacturer->metadescription;
		}
		if (empty($manufacturer->metakeywords))
		{
			if ($config->keywords->flag == 1)
			{
				if ($config->keywords->type == 'seo-type-country')
				{
					$countries = KSSystem::getTableByIds(array($manufacturer->country), 'countries', array('t.title'));
					if (!empty($countries[0]->title))
					{
						$metakeywords = $countries[0]->title;
					}
				}
				elseif ($config->keywords->type == 'seo-type-title')
				{
					$metakeywords = strip_tags($manufacturer->title);
				}
			}
		}
		else
		{
			$metakeywords = $manufacturer->metakeywords;
		}
		if (!empty($metatitle))
		{
			$document->setMetaData('title', $metatitle);
		}
		if (!empty($metadescription))
		{
			$document->setMetaData('description', $metadescription);
		}
		if (!empty($metakeywords))
		{
			$document->setMetaData('keywords', $metakeywords);
		}
		$document->addHeadLink(JRoute::_('index.php?option=com_ksenmart&view=catalog&manufacturers[]=' . $manufacturer->id . '&Itemid=' . KSSystem::getShopItemid()), 'canonical');


		$this->onExecuteAfter('setManufacturerMetaData', array(&$this));

		return true;
	}

	/**
	 *
	 * @return mixed
	 *
	 * @since version 4.1.2
	 */
	public function getCategories()
	{
		$this->onExecuteBefore('getCategories');

		$params = JComponentHelper::getParams('com_ksenmart');
		if (count($this->_categories) == 1)
		{
			$parent = $this->_categories[0];
		}
		else
		{
			$parent = 0;
		}
		$query = $this->_db->getQuery(true);
		$query
			->select('
                    c.*,
                    f.filename,
                    f.folder,
                    f.params
                ')
			->from('#__ksenmart_categories AS c')
			->leftJoin('#__ksenmart_files AS f ON c.id=f.owner_id AND f.owner_type=' . $this->_db->quote('category'))
			->where('c.published=1')
			->where('c.parent_id=' . (int) $parent)
			->order('c.ordering');

		$this->_db->setQuery($query);
		$categories = $this->_db->loadObjectList();

		foreach ($categories as &$category)
		{
			$category->link      = JRoute::_('index.php?option=com_ksenmart&view=catalog&categories[]=' . $category->id . ':' . $category->alias . '&Itemid=' . KSSystem::getShopItemid($category->id));
			$category->small_img = KSMedia::resizeImage($category->filename, $category->folder, $params->get('category_width', 193), $params->get('category_height', 193), json_decode($category->params, true));
		}

		$this->onExecuteAfter('getCategories', array(&$categories));

		return $categories;
	}

	/**
	 *
	 * @return bool
	 *
	 * @since version 2.0.0
	 */
	public function setCountryMetaData()
	{
		$this->onExecuteBefore('setCountryMetaData');

		$document        = JFactory::getDocument();
		$metadescription = '';
		$metakeywords    = '';
		$country         = $this->getCountry();
		$config          = KSSystem::getSeoTitlesConfig('country', 'meta');

		if (empty($country->metatitle))
		{
			$metatitle = $country->title;
		}
		else
		{
			$metatitle = $country->metatitle;
		}
		if (empty($country->metadescription))
		{
			if ($config->description->flag == 1)
			{
				if ($config->description->type == 'seo-type-mini-description')
				{
					$metadescription = strip_tags($country->introcontent);
				}
				elseif ($config->description->type == 'seo-type-description')
				{
					$metadescription = strip_tags($country->content);
				}
				$metadescription = mb_substr($metadescription, 0, $config->description->symbols);
			}
		}
		else
		{
			$metadescription = $country->metadescription;
		}
		if ($country->metakeywords == '')
		{
			if ($config->keywords->flag == 1)
			{
				if ($config->keywords->type == 'seo-type-title')
				{
					$metakeywords = strip_tags($country->title);
				}
			}
		}
		else
		{
			$metakeywords = $country->metakeywords;
		}
		if (!empty($metatitle))
		{
			$document->setMetaData('title', $metatitle);
		}
		if (!empty($metadescription))
		{
			$document->setMetaData('description', $metadescription);
		}
		if (!empty($metakeywords))
		{
			$document->setMetaData('keywords', $metakeywords);
		}
		$document->addHeadLink(JRoute::_('index.php?option=com_ksenmart&view=catalog&countries[]=' . $country->id . '&Itemid=' . KSSystem::getShopItemid()), 'canonical');

		$this->onExecuteAfter('setCountryMetaData', array(&$this));

		return true;
	}

	/**
	 *
	 * @return array
	 *
	 * @since version 2.0.0
	 */
	public function getSortLinks()
	{
		$this->onExecuteBefore('getSortLinks');

		$order_type = $this->getState('list.ordering');
		$order_dir  = $this->getState('list.direction');
		$params_get = '';
		if (!empty($this->_categories))
		{
			foreach ($this->_categories as $category)
			{
				$params_get .= '&categories[]=' . $category;
			}
		}
		if (!empty($this->_manufacturers))
		{
			foreach ($this->_manufacturers as $manufacturer)
			{
				$params_get .= '&manufacturers[]=' . $manufacturer;
			}
		}
		if (!empty($this->_properties))
		{
			foreach ($this->_properties as $property)
			{
				$params_get .= '&properties[]=' . $property;
			}
		}
		if (!empty($this->_countries))
		{
			foreach ($this->_countries as $country)
			{
				$params_get .= '&countries[]=' . $country;
			}
		}
		if (!empty($this->_title))
		{
			$params_get .= '&title=' . $this->_title;
		}
		if (!empty($this->_price_less))
		{
			$params_get .= '&price_less=' . $this->_price_less;
		}
		if (!empty($this->_price_more))
		{
			$params_get .= '&price_more=' . $this->_price_more;
		}
		if (!empty($this->_new))
		{
			$params_get .= '&new=1';
		}
		if (!empty($this->_promotion))
		{
			$params_get .= '&promotion=1';
		}
		if (!empty($this->_hot))
		{
			$params_get .= '&hot=1';
		}
		if (!empty($this->_recommendation))
		{
			$params_get .= '&recommendation=1';
		}
		if (!empty($this->_categories) && count($this->_categories) == 1)
		{
			$itemId = KSSystem::getShopItemid(current($this->_categories));
		}
		else
		{
			$itemId = KSSystem::getShopItemid();
		}
		$params_get .= '&Itemid=' . $itemId;

		$sort       = array(
			array(
				'order_type' => 'price',
				'name'       => JText::_('KSM_CATALOG_SORT_BY_PRICE_TEXT')
			),
			array(
				'order_type' => 'hits',
				'name'       => JText::_('KSM_CATALOG_SORT_BY_HITS_TEXT')
			)
		);
		$sort_links = array();
		foreach ($sort as $s)
		{
			$sort_links[$s['order_type']]['asc_link']  = '<a type="' . $s['order_type'] . '" dir="asc" href="' . JRoute::_('index.php?option=com_ksenmart&view=catalog&order_type=' . $s['order_type'] . '&order_dir=asc' . $params_get) . '"><img src="' . JUri::base() . 'components/com_ksenmart/images/bottomb.png"></a>';
			$sort_links[$s['order_type']]['desc_link'] = '<a type="' . $s['order_type'] . '" dir="desc" href="' . JRoute::_('index.php?option=com_ksenmart&view=catalog&order_type=' . $s['order_type'] . '&order_dir=desc' . $params_get) . '"><img src="' . JUri::base() . 'components/com_ksenmart/images/topb.png"></a>';
			if ($s['order_type'] == $order_type)
			{
				$class = "active";
				if ($order_dir == 'asc')
				{
					$sort_links[$s['order_type']]['asc_link'] = '<a type="' . $s['order_type'] . '" dir="asc" class="' . $class . '" href="' . JRoute::_('index.php?option=com_ksenmart&view=catalog&order_type=' . $s['order_type'] . '&order_dir=asc' . $params_get) . '"><img src="' . JUri::base() . 'components/com_ksenmart/images/bottomba.png"></a>';
					$class                                    .= " down";
					$dir                                      = 'desc';
				}
				else
				{
					$sort_links[$s['order_type']]['desc_link'] = '<a type="' . $s['order_type'] . '" dir="desc" class="' . $class . '" href="' . JRoute::_('index.php?option=com_ksenmart&view=catalog&order_type=' . $s['order_type'] . '&order_dir=desc' . $params_get) . '"><img src="' . JUri::base() . 'components/com_ksenmart/images/topba.png"></a>';
					$dir                                       = 'asc';
					$class                                     .= " up";
				}
			}
			else
			{
				$dir   = 'asc';
				$class = "";
			}
			$sort_links[$s['order_type']]['link'] = '<a type="' . $s['order_type'] . '" dir="' . $dir . '" class="' . $class . '" href="' . JRoute::_('index.php?option=com_ksenmart&view=catalog&order_type=' . $s['order_type'] . '&order_dir=' . $dir . $params_get) . '">' . $s['name'] . '</a>';
		}

		$this->onExecuteAfter('getSortLinks', array(&$sort_links));

		return $sort_links;
	}

	/**
	 * @param $a
	 * @param $b
	 *
	 * @return int
	 *
	 * @since version 2.0.0
	 */
	function sortOnPriceAsc($a, $b)
	{
		$this->onExecuteBefore('sortOnPriceAsc', array(&$a, &$b));

		if ($a->val_price_wou == $b->val_price_wou)
		{
			return 0;
		}

		$result = ($a->val_price_wou < $b->val_price_wou) ? -1 : 1;
		$this->onExecuteAfter('sortOnPriceAsc', array(&$result));

		return $result;
	}

	/**
	 * @param $a
	 * @param $b
	 *
	 * @return int
	 *
	 * @since version 2.0.0
	 */
	function sortOnPriceDesc($a, $b)
	{
		$this->onExecuteBefore('sortOnPriceDesc', array(&$a, &$b));

		if ($a->val_price_wou == $b->val_price_wou)
		{
			return 0;
		}

		$result = ($a->val_price_wou > $b->val_price_wou) ? -1 : 1;
		$this->onExecuteAfter('sortOnPriceDesc', array(&$result));

		return $result;
	}

	/**
	 *
	 * @return mixed
	 *
	 * @since version 2.0.0
	 */
	public function getStart()
	{
		return $this->getState('list.start');
	}

	/**
	 * @param $layout
	 *
	 * @return bool
	 *
	 * @since version 2.0.0
	 */
	public function setLayoutCatalog($layout)
	{
		$this->onExecuteBefore('setLayoutCatalog', array(&$layout));

		if (!empty($layout))
		{
			$user                           = KSUsers::getUser();
			$user_update                    = new stdClass();
			$user->settings->catalog_layout = $layout;

			$user_update->id       = $user->id;
			$user_update->settings = json_encode($user->settings);

			try
			{
				$result = $this->_db->updateObject('#__ksen_users', $user_update, 'id');
				$this->onExecuteAfter('setLayoutCatalog', array(&$result));

				return true;
			}
			catch (Exception $e)
			{
			}
		}

		return false;
	}
}