<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

class ModKMCategoriesHelper {
	
	private $tree = array();
	private $menu = array();
	private $selected_categories = array();
	
	function buildCategoriesTree() {
		$app = JFactory::getApplication();
		$db = JFactory::getDBO();
		$view = JRequest::getVar('view', 'catalog');
		$context = 'com_ksenmart.' . $view;
		if ($layout = JRequest::getVar('layout', 'default')) {
			$context.= '.' . $layout;
		}
		$selected_categories = $app->getUserStateFromRequest($context . '.categories', 'categories', array());
		$query = $db->getQuery(true);
		$query->select('*')->from('#__ksenmart_categories')->order('ordering');
		$db->setQuery($query);
		$categories = $db->loadObjectList('id');
		$top_parent = (object)array(
			'id' => 0,
			'children' => array()
		);
		$menu = array(
			0 => $top_parent
		);
		
		foreach ($categories as $id => $category) {
			if (in_array($id, $selected_categories)) $category->selected = true;
			else $category->selected = false;
			if (isset($menu[$id])) $category->children = $menu[$id]->children;
			else $category->children = array();
			$menu[$id] = $category;
			if (!isset($menu[$category->parent_id])) {
				$menu[$category->parent_id] = new stdClass();
				$menu[$category->parent_id]->children = array();
			}
			$menu[$category->parent_id]->children[] = $category;
		}
		$this->menu = $menu;
		$this->selected_categories = $selected_categories;
	}
	
	function getPath() {
		$path = array();
		
		
		foreach ($this->selected_categories as $selected_category) {
			$get_path = false;
			$level = false;
			
			for ($k = count($this->tree) - 1;$k >= 0;$k--) {
				if ($get_path && ($this->tree[$k]->level < $level || !$level)) {
					$path[] = $this->tree[$k]->id;
					$level = $this->tree[$k]->level;
				}
				if ($this->tree[$k]->id == $selected_category) {
					$get_path = true;
					$level = $this->tree[$k]->level;
				}
				if ($level == 1) $get_path = false;
			}
		}
		
		return $path;
	}
	
	function makeCategoriesTree($category, $level = 1) {
		if (isset($category->children) && !empty($category->children)) {
			
			foreach ($category->children as $child) {
				$child->level = $level;
				$child->deeper = false;
				$child->shallower = false;
				$child->level_diff = 0;
				$child->class = in_array($child->id, $this->selected_categories) ? ' active' : '';
				if (isset($this->tree[count($this->tree) - 1])) {
					$this->tree[count($this->tree) - 1]->deeper = ($child->level > $this->tree[count($this->tree) - 1]->level);
					$this->tree[count($this->tree) - 1]->shallower = ($child->level < $this->tree[count($this->tree) - 1]->level);
					$this->tree[count($this->tree) - 1]->level_diff = ($this->tree[count($this->tree) - 1]->level - $child->level);
				}
				$this->tree[] = $child;
				if (isset($this->tree[count($this->tree) - 1])) {
					$this->tree[count($this->tree) - 1]->deeper = (1 > $this->tree[count($this->tree) - 1]->level);
					$this->tree[count($this->tree) - 1]->shallower = (1 < $this->tree[count($this->tree) - 1]->level);
					$this->tree[count($this->tree) - 1]->level_diff = ($this->tree[count($this->tree) - 1]->level - 1);
				}
				$this->makeCategoriesTree($this->menu[$child->id], $level + 1);
			}
		}
	}
	
	function getCategories() {
		$this->buildCategoriesTree();
		if ($this->menu) $this->makeCategoriesTree($this->menu[0]);
		
		return $this->tree;
	}
}
