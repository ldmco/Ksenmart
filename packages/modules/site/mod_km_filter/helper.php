<?php
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class modKMFilterHelper
{

	public $price_min = 0;
	public $price_max = 0;
	public $manufacturers = array();
	public $countries = array();
	public $properties = array();
	public $categories = array();
	public $tree = array();
	public $mod_params = array();
	private $_params = null;
	private $_db = null;
	private $_jinput = null;
	private $_cats = array();

	public function __construct()
	{
		$this->_db     = JFactory::getDbo();
		$this->_params = JComponentHelper::getParams('com_ksenmart');
		$this->_jinput = JFactory::getApplication()->input;
	}

	public function init($mod_params)
	{
		$this->initParams($mod_params);
		$option = $this->_jinput->get('option', null, 'string');
		$view   = $this->_jinput->get('view', null, 'string');

		$categories = $this->_jinput->get('categories', array(), 'array');
		if (!count($categories) && $option == 'com_ksenmart' && $view == 'product')
		{
			$product_id       = $this->_jinput->get('id', 0, 'int');
			$default_category = KSMProducts::getProduct($product_id)->categories[0];
			if (!empty($default_category)) $categories[] = $default_category;
		}

		$this->categories         = $categories;
		$session_properties       = $this->_jinput->get('properties', array(), 'array');
		$session_range_properties = $this->_jinput->get('range_properties', array(), 'array');

		if ($this->_params->get('show_products_from_subcategories', 1) == 1)
		{
			foreach ($categories as $cat)
			{
				$tmp         = $this->getChildCats($cat);
				$this->_cats = array_merge($this->_cats, $tmp);
			}
		}

		if ($this->mod_params['price']['view'] != 'none') $this->getPrices();
		if ($this->mod_params['manufacturer']['view'] != 'none') $this->getManufacturers();
		if ($this->mod_params['country']['view'] != 'none') $this->getCountries();
		//if ($this->_params->get('show_categories', 0)) $this->getTree();

		$properties       = $this->mod_params['properties'];
		$this->properties = self::getProperties($this->categories);

		foreach ($this->properties as $key => & $property)
		{
			if (isset($properties[$property->property_id]) && $properties[$property->property_id]['view'] == 'none')
			{
				unset($this->properties[$key]);
				continue;
			}
			else
			{
				$this->properties[$key]->view    = isset($properties[$property->property_id]) ? $properties[$property->property_id]['view'] : 'select';
				$this->properties[$key]->display = isset($properties[$property->property_id]) ? $properties[$property->property_id]['display'] : 'row';
				if ($this->properties[$key]->range && count($this->properties[$key]->values))
				{
					foreach ($this->properties[$key]->values as $value)
					{
						if (!isset($this->properties[$key]->min) || $this->properties[$key]->min > $value->title) $this->properties[$key]->min = $value->title;
						if (!isset($this->properties[$key]->max) || $this->properties[$key]->max < $value->title) $this->properties[$key]->max = $value->title;
					}
					$this->properties[$key]->current_min = $this->properties[$key]->min;
					$this->properties[$key]->current_max = $this->properties[$key]->max;
					if (isset($session_range_properties[$key]))
					{
						if (isset($session_range_properties[$key][0]) && $session_range_properties[$key][0] > $this->properties[$key]->min) $session_range_properties[$key][0];
						if (isset($session_range_properties[$key][1]) && $session_range_properties[$key][1] < $this->properties[$key]->max) $session_range_properties[$key][1];
					}
					continue;
				}
			}

			if (!empty($property->values))
			{
				foreach ($property->values as & $value)
				{
					$value->selected = false;
					if (in_array($value->id, $session_properties))
					{
						$value->selected = true;
					}
				}
				unset($value);
			}
		}
		unset($property);
	}

	function getPrices()
	{
		$where = array(
			'p.published=1'
		);

		if ($this->_params->get('show_out_stock') != 1) $where[] = "(p.in_stock>0)";
		$sql = $this->_db->getQuery(true);
		$sql->select('min(p.price/c.rate) as min, max(p.price/c.rate) as max')->from('#__ksenmart_products as p');
		if (count($this->_cats) > 0)
		{
			$sql->join("INNER", "#__ksenmart_products_categories as pc on p.id=pc.product_id");
			$where[] = "(pc.category_id in (" . implode(',', $this->_cats) . "))";
		}
		$sql->join("LEFT", "#__ksenmart_currencies as c on p.price_type=c.id");
		$sql->where(implode(' and ', $where));
		$this->_db->setQuery($sql);
		$prices = $this->_db->loadObject();
		if ($prices)
		{
			$this->price_min = $prices->min;
			$this->price_max = $prices->max;
		}

		return true;
	}

	function getManufacturers()
	{
		$session_manufacturers = $this->_jinput->get('manufacturers', array(), 'array');
		$where                 = array('m.published=1');
		if ($this->_params->get('show_out_stock') != 1) $where[] = "(p.in_stock>0)";
		$sql = $this->_db->getQuery(true);
		$sql->select('m.*')->from('#__ksenmart_manufacturers as m');
		$sql->join("INNER", "#__ksenmart_products as p on p.manufacturer=m.id");
		if (count($this->_cats) > 0)
		{
			$sql->join("INNER", "#__ksenmart_products_categories as pc on p.id=pc.product_id");
			$where[] = "(pc.category_id in (" . implode(',', $this->_cats) . "))";
		}
		$sql->where(implode(' and ', $where));
		$sql->group('m.id');
		if ($this->mod_params['manufacturer']['show_image'] == 'yes') $sql = KSMedia::setItemMainImageToQuery($sql, 'manufacturer', 'm.');
		$this->_db->setQuery($sql);
		$this->manufacturers = $this->_db->loadObjectList('id');

		foreach ($this->manufacturers as & $manufacturer)
		{
			$manufacturer->image    = !empty($manufacturer->filename) ? KSMedia::resizeImage($manufacturer->filename, $manufacturer->folder, 20, 20, json_decode($manufacturer->params, true)) : '';
			$manufacturer->selected = false;
			if (in_array($manufacturer->id, $session_manufacturers)) $manufacturer->selected = true;
		}

		return true;
	}

	function getCountries()
	{
		$session_countries = $this->_jinput->get('countries', array(), 'array');
		$where             = array('c.published=1');
		$sql               = $this->_db->getQuery(true);
		$sql->select('c.*')->from('#__ksenmart_countries as c');
		$sql->join("INNER", "#__ksenmart_manufacturers as m on m.country=c.id");
		if ($this->mod_params['manufacturer']['view'] == 'none')
		{
			if ($this->_params->get('show_out_stock') != 1) $where[] = "(p.in_stock>0)";
			$where[] = "(m.published=1)";
			$sql->join("INNER", "#__ksenmart_products as p on p.manufacturer=m.id");
			if (count($this->_cats) > 0)
			{
				$sql->join("INNER", "#__ksenmart_products_categories as pc on p.id=pc.product_id");
				$where[] = "(pc.category_id in (" . implode(',', $this->_cats) . "))";
			}
		}
		else
		{
			if (empty($this->manufacturers))
			{
				$this->countries = array();

				return true;
			}
			$manufacturers = array();
			foreach ($this->manufacturers as $manufacturer)
			{
				$manufacturers[] = $manufacturer->id;
			}
			$where[] = 'm.id IN (' . implode(',', $manufacturers) . ')';
		}
		$sql->where(implode(' and ', $where));
		$sql->group('c.id');
		if ($this->mod_params['country']['show_image'] == 'yes') $sql = KSMedia::setItemMainImageToQuery($sql, 'country', 'c.');
		$this->_db->setQuery($sql);
		$this->countries = $this->_db->loadObjectList();
		foreach ($this->countries as & $country)
		{
			$country->image    = !empty($country->filename) ? KSMedia::resizeImage($country->filename, $country->folder, 20, 20, json_decode($country->params, true)) : '';
			$country->selected = false;
			if (in_array($country->id, $session_countries)) $country->selected = true;
		}

		return true;
	}

	/*function getTree(){
		$sql = $this->db->getQuery(true);
		$sql->select('kc.*')->from('#__ksenmart_categories as kc')->where('kc.published=1')->order('kc.ordering');
		if ($categories) {
			$sql->where('kc.id IN(' . implode(', ', $categories) . ') OR kc.parent_id IN(' . implode(', ', $categories) . ')');
		}

		$this->db->setQuery($sql);

		$rows = $this->db->loadObjectList('id');
		$top_parent = (object)array(
			'id' => 0,
			'children' => array() ,
		);
		$menu = array(
			0 => $top_parent
		);
		foreach ($rows as $k => $v) {
			if (isset($menu[$k])) $v->children = $menu[$k]->children;
			else $v->children = array();
			$menu[$k] = $v;
			if (!isset($menu[$v->parent_id])) {
				$menu[$v->parent_id] = new stdClass();
				$menu[$v->parent_id]->children = array();
			}
			$menu[$v->parent_id]->children[$v->id] = $v;
		}
		$this->menu = $menu;
	}*/

	function getChildCats($catid)
	{
		$db       = JFactory::getDBO();
		$return   = array();
		$return[] = $catid;
		$sql      = $db->getQuery(true);
		$sql->select('id')->from('#__ksenmart_categories');
		if (is_array($catid))
		{
			$return = $catid;
			$sql->where('parent_id IN (' . $this->_db->q(implode(',', $catid)) . ')');
		}
		else
		{
			$return = array($catid);
			$sql->where('parent_id=' . (int) $catid);
		}
		$db->setQuery($sql);
		$cats = $db->loadColumn();
		if (count($cats) > 0)
		{
			$return1 = $this->getChildCats($cats);
			$return  = array_merge($return, $return1);
		}

		return $return;
	}

	public static function getProperties($categories = array())
	{
		$db = JFactory::getDBO();

		$prids = array();
		if (!empty($categories))
		{
			$query = $db->getQuery(true);
			$query->select('property_id')->from('#__ksenmart_product_categories_properties')->where('category_id IN (' . implode(',', $categories) . ')');
			$db->setQuery($query);
			$prids = $db->loadColumn();

			if (empty($prids)) return array();
		}

		$query = $db->getQuery(true);
		$query->select('
                ppv.id,
                ppv.property_id,
                ppv.value_id,
                ppv.price,
                p.edit_price,
                p.range,
                p.title,
                p.type,
                p.view,
                p.default,
                p.prefix,
                p.suffix,
                pv.title as value_title,
                pv.image
            ')->from('#__ksenmart_product_properties_values AS ppv')->leftjoin('#__ksenmart_properties AS p ON p.id=ppv.property_id')->leftjoin('#__ksenmart_property_values AS pv ON pv.id=ppv.value_id');
		$query->where('p.published=1');
		if (!empty($categories))
		{
			$query->where('p.id IN (' . implode(',', $prids) . ')');
		}

		$query->group('ppv.value_id');
		$query->order('p.ordering,pv.ordering');
		$db->setQuery($query);
		$properties = $db->loadObjectList();
		$props      = array();
		foreach ($properties as $property)
		{
			if (!isset($props[$property->property_id]))
			{
				$props[$property->property_id]              = new stdClass();
				$props[$property->property_id]->id          = $property->id;
				$props[$property->property_id]->property_id = $property->property_id;
				$props[$property->property_id]->value_id    = $property->value_id;
				$props[$property->property_id]->range       = $property->range;
				$props[$property->property_id]->edit_price  = $property->edit_price;
				$props[$property->property_id]->title       = $property->title;
				$props[$property->property_id]->type        = $property->type;
				$props[$property->property_id]->view        = $property->view;
				$props[$property->property_id]->default     = $property->default;
				$props[$property->property_id]->prefix      = $property->prefix;
				$props[$property->property_id]->suffix      = $property->suffix;
				$props[$property->property_id]->values      = array();
			}
			$props[$property->property_id]->values[$property->value_id]              = new stdClass();
			$props[$property->property_id]->values[$property->value_id]->id          = $property->value_id;
			$props[$property->property_id]->values[$property->value_id]->title       = $property->value_title;
			$props[$property->property_id]->values[$property->value_id]->image       = $property->image;
			$props[$property->property_id]->values[$property->value_id]->property_id = $property->property_id;
			$props[$property->property_id]->values[$property->value_id]->price       = $property->price;
		}

		return $props;
	}

	public function getSelected()
	{
		$selected   = array();
		$properties = $this->_jinput->get('properties', array(), 'array');
		if (!empty($properties)) $selected['properties'] = $properties;
		$manufacturers = $this->_jinput->get('manufacturers', array(), 'array');
		if (!empty($manufacturers)) $selected['manufacturers'] = $manufacturers;

		//$selected['countries'] = $this->_jinput->get('countries', array(), 'array');

		return $selected;
	}

	public static function getSelectedAjax()
	{
		$jinput     = JFactory::getApplication()->input;
		$db         = JFactory::getDbo();
		$selected   = array();
		$properties = $jinput->get('properties', array(), 'array');
		if (!empty($properties))
		{
			$query = $db->getQuery(true);
			$query->select('pv.*, p.title as property_title')
				->from('#__ksenmart_property_values as pv')
				->leftJoin('#__ksenmart_properties as p ON p.id=pv.property_id')
				->where('pv.id IN (' . implode(',', $properties) . ')');
			$db->setQuery($query);
			$values     = $db->loadObjectList();
			$properties = array();
			foreach ($values as $value)
			{
				if (!isset($properties[$value->property_id]))
				{
					$properties[$value->property_id]         = new stdClass();
					$properties[$value->property_id]->title  = $value->property_title;
					$properties[$value->property_id]->values = array();
				}
				$properties[$value->property_id]->values[] = $value;
			}
			$selected['properties'] = $properties;
		}
		$manufacturers = $jinput->get('manufacturers', array(), 'array');
		if (!empty($manufacturers))
		{
			$query = $db->getQuery(true);
			$query->select('*')
				->from('#__ksenmart_manufacturers')
				->where('id IN (' . implode(',', $manufacturers) . ')');
			$db->setQuery($query);
			$manufacturers             = $db->loadObjectList();
			$selected['manufacturers'] = $manufacturers;
		}

		ob_start();
		require JModuleHelper::getLayoutPath('mod_km_filter', 'selected');
		$html = ob_get_contents();
		ob_end_clean();
		$return = array(
			'html' => $html
		);

		return $return;
	}

	function initParams($mod_params)
	{

		$mod_params = $mod_params->toArray();
		if (!isset($mod_params['price']))
		{
			$mod_params['price'] = array(
				'view'    => 'slider',
				'display' => 'row'
			);
		}
		if (!isset($mod_params['manufacturer']))
		{
			$mod_params['manufacturer'] = array(
				'view'       => 'checkbox',
				'display'    => 'row',
				'show_image' => 'no'
			);
		}
		if (!isset($mod_params['country']))
		{
			$mod_params['country'] = array(
				'view'       => 'checkbox',
				'display'    => 'row',
				'show_image' => 'no'
			);
		}
		if (!isset($mod_params['properties']))
		{
			$mod_params['properties'] = array();
		}
		$this->mod_params = $mod_params;
	}
}
