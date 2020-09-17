<?php
/**
 * @copyright   Copyright (C) 2016. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class KSMProduct
{

	private $_child_properties = null;

	public function __construct()
	{
	}

	public function __get($name)
	{
		if (empty($this->id)) return null;
		switch ($name)
		{
			case 'properties':
				$this->properties = KSMProducts::getProperties($this->id);
				foreach ($this->properties as $prop)
				{
					if ($prop->type == 'select' && ($prop->view == 'select' || $prop->view == 'checkbox' || $prop->view == 'radio'))
					{
						if (count($prop->values) > 1)
						{
							$this->catalog_buy = false;
							break;
						}
					}
				}
				KSMProducts::setProduct($this);

				return $this->properties;
				break;
			case 'tags':
				$this->tags = new JHelperTags;
				$this->tags->getItemTags('com_ksenmart.product', $this->id);
				KSMProducts::setProduct($this);

				return $this->tags;
				break;
			case 'rate':
				$this->rate = KSMProducts::getProductRate($this->id);
				KSMProducts::setProduct($this);

				return $this->rate;
				break;
			case 'links':
				$this->links = KSMProducts::getLinks($this->id, $this->parent_id);
				KSMProducts::setProduct($this);

				return $this->links;
				break;
			case 'categories':
				$this->categories = KSMProducts::getProductCategory($this->id);
				KSMProducts::setProduct($this);

				return $this->categories;
				break;
		}
	}

	public function getChildProperties($prid = 0, $val_id = 0, $group = 'property', $stock = true, $active_values = array())
	{
		if (empty($this->id))
		{
			return array();
		}
		if ($this->parent_id == 0 && !$this->is_parent)
		{
			return array();
		}
		if (!empty($prid) && !empty($this->_child_properties[$prid]))
		{
			return $this->_child_properties[$prid];
		}
		if (!is_null($this->_child_properties)) return $this->_child_properties;
		$childs = $this->getChildsIds($stock);
		if (empty($childs)) return array();
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('
                ppv.id,
                ppv.property_id,
                ppv.value_id,
                ppv.text,
                p.*
            ')->from('#__ksenmart_properties AS p')->leftJoin('#__ksenmart_product_properties_values AS ppv ON p.id=ppv.property_id');

		$query->where('ppv.product_id IN (' . implode(',', $childs) . ')');
		$query->where('p.published=1')->group('ppv.property_id');
		$query->order('p.ordering');

		if ($prid)
		{
			$query->where('ppv.property_id=' . $prid);
		}

		if ($val_id)
		{
			$query->where('ppv.id=' . $val_id);
		}

		$db->setQuery($query);
		$products_properties = $db->loadObjectList('property_id');
		if (empty($products_properties)) return array();

		$query = $db->getQuery(true);
		$query->select('
                    pv.id,
                    pv.title,
                    pv.image,
                    ppv.product_id,
                    ppv.property_id,
                    ppv.price,
                    ppv.text
                ')->from('#__ksenmart_property_values AS pv')->leftJoin('#__ksenmart_product_properties_values AS ppv ON ppv.value_id=pv.id');

		$query->where('ppv.product_id IN (' . implode(',', $childs) . ')');
		$query->order('pv.ordering');
		$query->group('pv.id');

		if ($prid)
		{
			$query->where('ppv.property_id=' . $prid);
		}

		if ($val_id)
		{
			$query->where('pv.id=' . $val_id);
		}

		$db->setQuery($query);
		$values = $db->loadObjectList();

		$enableds = array();
		if (!$stock)
		{
			$query = $db->getQuery(true);
			$query->select('id')->from('#__ksenmart_products')->where('in_stock > 0')->where('id IN (' . implode(',', $childs) . ')');
			$db->setQuery($query);
			$enableds = $db->loadColumn();
		}
		$active_properties = array();
		if (!empty($active_values) && $this->parent_id && empty($val_id))
		{
			$childs = $this->getChildsIds($stock, $active_values);

			$active_properties = array();
			foreach ($values as $value)
			{
				if (in_array($value->id, $active_values)) $active_properties[] = $value->property_id;
			}

			$query = $db->getQuery(true);
			$query->select('
                    pv.id,
                    pv.title,
                    pv.image,
                    ppv.product_id,
                    ppv.property_id,
                    ppv.price,
                    ppv.text
                ')->from('#__ksenmart_property_values AS pv')->leftJoin('#__ksenmart_product_properties_values AS ppv ON ppv.value_id=pv.id');

			$query->where('ppv.product_id IN (' . implode(',', $childs) . ')');
			$query->order('pv.ordering');
			$query->group('pv.id');

			if ($prid)
			{
				$query->where('ppv.property_id=' . $prid);
			}

			$db->setQuery($query);
			$enabled_values = $db->loadObjectList('id');
		}
		$current_values = array();
		foreach ($this->properties as $property)
		{
			foreach ($property->values as $value)
			{
				$current_values[] = $value->id;
			}
		}

		$properties = array();
		switch ($group)
		{
			case 'product':
				foreach ($values as $value)
				{
					$value->class = '';
					if ($value->product_id == $this->id)
					{
						$value->class = 'active';
					}
					if (!$stock)
					{
						if (!in_array($value->product_id, $enableds)) $value->class = 'disable';
					}
					if (!isset($properties[$value->product_id]))
					{
						$properties[$value->product_id] = array();
					}
					if (!isset($properties[$value->product_id][$value->property_id]))
					{
						$properties[$value->product_id][$value->property_id]         = $products_properties[$value->property_id];
						$properties[$value->product_id][$value->property_id]->values = array();
					}
					$properties[$value->product_id][$value->property_id]->values[$value->id] = $value;
				}
				break;
			case 'property':
			default:
				foreach ($products_properties as $property)
				{
					if (!isset($properties[$property->property_id]))
					{
						$properties[$property->property_id]         = $property;
						$properties[$property->property_id]->values = array();
					}
				}
				foreach ($values as $value)
				{
					$value->class = '';
					if (!empty($enabled_values))
					{
						if (!empty($enabled_values[$value->id]))
						{
							$value->product_id = $enabled_values[$value->id]->product_id;
						}
						else
						{
							if (!in_array($value->property_id, $active_properties)) $value->class = 'disable';
						}
					}
					if (in_array($value->id, $current_values))
					{
						$value->class = 'active';
					}
					if (!$stock)
					{
						if (!in_array($value->product_id, $enableds)) $value->class = 'disable';
					}
					$properties[$value->property_id]->values[$value->id] = $value;
				}
				break;
		}

		return $properties;
	}

	public function getChildsIds($stock = true, $enabled_values = array())
	{
		if (empty($this->id))
		{
			return [];
		}

		$params = JComponentHelper::getParams('com_ksenmart');
		if ($stock)
		{
			$stock = $params->get('use_stock', 1);
		}

		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('p.id')->from('#__ksenmart_products as p');
		$query->where('p.published=1');
		if ($this->parent_id > 0)
		{
			$query->where('p.parent_id=' . $this->parent_id);
			if (!empty($enabled_values))
			{
				$query->leftJoin('#__ksenmart_product_properties_values as ppv ON ppv.product_id=p.id')
					->where('ppv.value_id IN (' . implode(',', $enabled_values) . ')');
			}
		}
		else
		{
			$query->where('p.parent_id=' . $this->id);
		}
		if ($stock)
		{
			$query->where('p.in_stock > 0');
		}
		$db->setQuery($query);
		$ids = $db->loadColumn();

		return $ids;
	}

	/*public function update(){
		$user   = JFactory::getUser();
		$isroot = $user->authorise('core.admin');
		if ($isroot){

		}
	}*/

}
