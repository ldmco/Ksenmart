<?php
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

KSSystem::import('models.modelksadmin');

class KsenMartModelCategories extends JModelKSAdmin
{

	private $menu = array();
	private $tree = array();

	function populateState()
	{
		$this->onExecuteBefore('populateState');

		$app = JFactory::getApplication();

		$order_dir = $app->getUserStateFromRequest($this->context . '.order_dir', 'order_dir', 'asc');
		$this->setState('order_dir', $order_dir);
		$order_type = $app->getUserStateFromRequest($this->context . '.order_type', 'order_type', 'ordering');
		$this->setState('order_type', $order_type);

		$searchword = $app->getUserStateFromRequest($this->context . '.searchword', 'searchword', null);
		$this->setState('searchword', $searchword);

		$this->onExecuteAfter('populateState');
	}

	function getListItems()
	{
		$this->onExecuteBefore('getListItems');

		$order_dir  = $this->getState('order_dir');
		$order_type = $this->getState('order_type');
		$searchword = $this->getState('searchword');
		$order_type = 'c.' . $order_type;
		$query      = $this->_db->getQuery(true);
		$query->select('SQL_CALC_FOUND_ROWS c.*')->from('#__ksenmart_categories as c')->order($order_type . ' ' . $order_dir);
		if (!empty($searchword)) $query->where('c.title like ' . $this->_db->quote('%' . $searchword . '%'));
		$query = KSMedia::setItemMainImageToQuery($query, 'category', 'c.');
		$this->_db->setQuery($query);
		$items = $this->_db->loadObjectList();
		$query = $this->_db->getQuery(true);
		$query->select('FOUND_ROWS()');
		$this->_db->setQuery($query);
		$this->total = $this->_db->loadResult();
		foreach ($items as &$item)
		{
			$item->folder     = 'categories';
			$item->small_img  = KSMedia::resizeImage($item->filename, $item->folder, $this->params->get('admin_product_thumb_image_width', 36), $this->params->get('admin_product_thumb_image_heigth', 36), json_decode($item->params, true));
			$item->medium_img = KSMedia::resizeImage($item->filename, $item->folder, $this->params->get('admin_product_medium_image_width', 120), $this->params->get('admin_product_medium_image_heigth', 120), json_decode($item->params, true));
		}
		$items = $this->buildCategoriesTree($items);

		$this->onExecuteAfter('getListItems', array(&$items));

		return $items;
	}

	function copyListItems($categories)
	{
		$this->onExecuteBefore('copyListItems', array(&$categories));

		foreach ($categories as $category)
		{
			$table = $this->getTable('categories');
			$table->load($category);
			$table->id         = null;
			$same_title        = false;
			$i                 = 1;
			$title             = $table->title;
			while (!$same_title)
			{
				$title = $table->title . ' (' . $i . ')';
				$query = $this->_db->getQuery(true);
				$query->select('count(id)')->from('#__ksenmart_categories')->where('title=' . $this->_db->quote($title));
				$this->_db->setQuery($query);
				$same_title = !$this->_db->loadResult();
				$i++;
			}
			$table->title = $title;
			$table->alias = KSFunctions::GenAlias($table->title);
			if ($table->check())
			{
				if (!$table->store())
				{
					return false;
				}
			}
			$category_id = $table->id;

			$query = $this->_db->getQuery(true);
			$query->select('*')->from('#__ksenmart_files')->where('owner_id=' . $category)->where('owner_type="category"');
			$this->_db->setQuery($query);
			$images = $this->_db->loadObjectList();
			foreach ($images as $image)
			{
				$ptable = $this->getTable('Files');
				$ptable->load($image->id);
				$old_filename     = $filename = $ptable->filename;
				$filename         = $ptable->filename;
				$filename         = explode('.', $filename);
				$filename         = microtime(true) . '.' . $filename[count($filename) - 1];
				$ptable->filename = $filename;
				$ptable->owner_id = $category_id;
				$ptable->id       = null;
				if ($ptable->check())
				{
					if ($ptable->store())
					{
						copy(JPATH_ROOT . DS . 'media' . DS . 'com_ksenmart' . DS . 'images' . DS . $ptable->folder . DS . 'original' . DS . $old_filename, JPATH_ROOT . DS . 'media' . DS . 'com_ksenmart' . DS . 'images' . DS . $ptable->folder . DS . 'original' . DS . $filename);
					}
					else  return false;
				}
			}
			$query = $this->_db->getQuery(true);
			$query->select('*')->from('#__ksenmart_product_categories_properties')->where('category_id=' . $category);
			$this->_db->setQuery($query);
			$properties = $this->_db->loadObjectList();
			foreach ($properties as $property)
			{
				$ppvtable = $this->getTable('ProductCategoriesProperties');
				$ppvtable->load($property->id);
				$ppvtable->category_id = $category_id;
				$ppvtable->id          = null;
				if ($ppvtable->check())
				{
					if (!$ppvtable->store())
					{
						return false;
					}
				}
			}
		}

		$this->onExecuteAfter('copyListItems', array(&$categories));

		return true;
	}

	function buildCategoriesTree($categories)
	{
		$top_parent = (object) array(
			'id'       => 0,
			'children' => array()
		);
		$menu       = array(
			0 => $top_parent
		);

		foreach ($categories as $id => $category)
		{
			if (isset($menu[$id])) $category->children = $menu[$id]->children;
			else $category->children = array();
			$menu[$id] = $category;
			if (!isset($menu[$category->parent_id]))
			{
				$menu[$category->parent_id]           = new stdClass();
				$menu[$category->parent_id]->children = array();
			}
			$menu[$category->parent_id]->children[] = $category;
		}
		$this->menu = $menu;
		$this->makeCategoriesTree($menu[0]);

		return $this->tree;
	}

	function makeCategoriesTree($category, $level = 1)
	{
		if (isset($category->children) && !empty($category->children))
		{

			foreach ($category->children as $child)
			{
				$child->level      = $level;
				$child->deeper     = false;
				$child->shallower  = false;
				$child->level_diff = 0;
				if (isset($this->tree[count($this->tree) - 1]))
				{
					$this->tree[count($this->tree) - 1]->deeper     = ($child->level > $this->tree[count($this->tree) - 1]->level);
					$this->tree[count($this->tree) - 1]->shallower  = ($child->level < $this->tree[count($this->tree) - 1]->level);
					$this->tree[count($this->tree) - 1]->level_diff = ($this->tree[count($this->tree) - 1]->level - $child->level);
				}
				$this->tree[] = $child;
				if (isset($this->tree[count($this->tree) - 1]))
				{
					$this->tree[count($this->tree) - 1]->deeper     = (1 > $this->tree[count($this->tree) - 1]->level);
					$this->tree[count($this->tree) - 1]->shallower  = (1 < $this->tree[count($this->tree) - 1]->level);
					$this->tree[count($this->tree) - 1]->level_diff = ($this->tree[count($this->tree) - 1]->level - 1);
				}
				if (isset($this->menu[$child->id])) $this->makeCategoriesTree($this->menu[$child->id], $level + 1);
			}
		}
	}

	function getTotal()
	{
		$this->onExecuteBefore('getTotal');

		$total = $this->total;

		$this->onExecuteAfter('getTotal', array(&$total));

		return $total;
	}

	function deleteListItems($ids)
	{
		$this->onExecuteBefore('deleteListItems', array(&$ids));

		$table = $this->getTable('categories');
		foreach ($ids as $id)
		{
			$table->load($id);
			$parent_id = $table->parent_id;
			$table->delete($id);
			KSMedia::deleteItemMedia($id, 'category');

			$query = $this->_db->getQuery(true);
			$query->delete('#__ksenmart_products_categories')->where('category_id=' . $id);
			$this->_db->setQuery($query);
			$this->_db->execute();

			$query = $this->_db->getQuery(true);
			$query->update('#__ksenmart_categories')->set('parent_id = ' . $parent_id)->where('parent_id = ' . $id);
			$this->_db->setQuery($query);
			$this->_db->execute();
		}

		$this->onExecuteAfter('deleteListItems', array(&$ids));

		return true;
	}

	function getCategory()
	{
		$this->onExecuteBefore('getCategory');

		$id       = JRequest::getInt('id');
		$category = KSSystem::loadDbItem($id, 'categories');
		$category = KSMedia::setItemMedia($category, 'category');
		$query    = $this->_db->getQuery(true);
		$query->select('property_id')->from('#__ksenmart_product_categories_properties')->where('category_id=' . $id);
		$this->_db->setQuery($query);
		$category->properties = $this->_db->loadColumn();

		$this->onExecuteAfter('getCategory', array(&$category));

		return $category;
	}

	public function saveCategory($data)
	{
		$this->onExecuteBefore('saveCategory', array(&$data));

		$data['parent_id']  = isset($data['parent_id']) ? $data['parent_id'] : 0;
		$data['published']  = isset($data['published']) ? 1 : 0;
		$data['properties'] = isset($data['properties']) ? $data['properties'] : array();

		$data['alias'] = KSFunctions::CheckAlias($data['alias'], $data['id']);
		if ($data['alias'] == '')
		{
			$data['alias'] = KSFunctions::GenAlias($data['title']);
		}

		$query = $this->_db->getQuery(true);
		$query->select('ordering')->from('#__ksenmart_categories')->order('ordering DESC');
		$this->_db->setQuery($query, 0, 1);
		$ordering         = $this->_db->loadResult();
		$data['ordering'] = $ordering + 1;

		$table = $this->getTable('categories');
		if (!$table->bindCheckStore($data))
		{
			$this->setError($table->getError());

			return false;
		}
		$id = $table->id;
		KSMedia::saveItemMedia($id, $data, 'category', 'categories');

		$in = array();
		foreach ($data['properties'] as $property)
		{
			$table = $this->getTable('ProductCategoriesProperties');
			$v     = array('property_id' => $property, 'category_id' => $id);
			if (!$table->bindCheckStore($v))
			{
				$this->setError($table->getError());

				return false;
			}
			$in[] = $table->id;
		}
		$query = $this->_db->getQuery(true);
		$query->delete('#__ksenmart_product_categories_properties')->where('category_id=' . $id);
		if (count($in) > 0) $query->where('id not in (' . implode(',', $in) . ')');
		$this->_db->setQuery($query);
		$this->_db->execute();

		$on_close = 'window.parent.CategoriesList.refreshList();';
		$return   = array('id' => $id, 'on_close' => $on_close);

		$this->onExecuteAfter('saveCategory', array(&$return));

		return $return;
	}
}