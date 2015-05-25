<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

class modKsenmartCategoriesHelper {
    
    private $tree = array();
    private $menu = array();
    
    function get_current_item() {
        $view = JRequest::getVar('view', '');
        if ($view == 'catalog') {
            $categories = JRequest::getVar('categories', array());
            if (count($categories) == 1) {
                $active_id = $categories[0];
                return $active_id;
            } else return false;
        } elseif ($view == 'product') {
            $product_id = JRequest::getInt('id', 0);
            $active_id = self::getProductCategory($product_id);
            if (!empty($active_id)) return $active_id;
            else return false;
        } else return false;
    }
    
    function get_path($active_id) {
        $path = array();
        $get_path = false;
        $level = false;
        
        for ($k = count($this->tree) - 1;$k >= 0;$k--) {
            if ($this->tree[$k]->id == $active_id) $get_path = true;
            if ($get_path && ($this->tree[$k]->level < $level || !$level)) {
                $path[] = $this->tree[$k]->id;
                $level = $this->tree[$k]->level;
            }
            if ($level == 1) $get_path = false;
        }
        return $path;
    }
    
    private function build_tree($params) {
        $db = JFactory::getDBO();
        $categories = $params->get('categories', array());
        $sql = $db->getQuery(true);
        $sql->select('kc.*')->from('#__ksenmart_categories as kc')->where('kc.published=1')->order('kc.ordering');
        if ($categories) {
            $sql->where('kc.id IN(' . implode(', ', $categories) . ') OR kc.parent_id IN(' . implode(', ', $categories) . ')');
        }
        KSMedia::setItemMainImageToQuery($sql, 'category', 'kc.');
        
        $db->setQuery($sql);
        
        $rows = $db->loadObjectList('id');
        $top_parent = (object)array(
            'id' => 0,
            'children' => array() ,
        );
        $menu = array(
            0 => $top_parent
        );
        foreach ($rows as $k => $v) {
            if (!empty($v->folder)) {
                $v->img = KSMedia::resizeImage($v->filename, $v->folder, $params->get('img_width', 200) , $params->get('img_height', 200) , json_decode($v->params, true));
            }
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
    }
    
    private function make_tree($category, $level = 1, $params) {
        if (isset($category->children) && !empty($category->children)) {
			$categories = $params->get('categories', array());
			$children = array_keys($category->children);
			$intersect = array_intersect($children, $categories);
			$filter = count($intersect) ? true : false;
            foreach ($category->children as $key => $child) {
				if ($filter && !in_array($key, $categories))
				{
					unset($category->children[$key]);
					continue;
				}
                $child->level = $level;
                $child->deeper = false;
                $child->shallower = false;
                $child->level_diff = 0;
                $child->link = JRoute::_('index.php?option=com_ksenmart&view=catalog&categories[0]=' . $child->id . '&Itemid=' . KSSystem::getShopItemid());
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
                $this->make_tree($this->menu[$child->id], $level + 1, $params);
            }
        }
    }
    
    public function view_tree(&$params, $active_id) {
        $user = JFactory::getUser();
        $levels = $user->getAuthorisedViewLevels();
        asort($levels);
        $key = 'categories_items' . $params . implode(',', $levels) . '.' . $active_id;
        $cache = JFactory::getCache('mod_ksenmart_categories', '');
        if (!($this->tree = $cache->get($key))) {
            $this->build_tree($params);
            
            if ($this->menu) {
                $this->make_tree($this->menu[0], 1, $params);
                $cache->store($this->tree, $key);
            } else {
                return false;
            }
        }
        
        return $this->tree;
    }
    
    private static function getDefaultCategory($product_id) {
        $db = JFactory::getDBO();
        $sql = $db->getQuery(true);
        $sql->select('category_id')->from('#__ksenmart_products_categories AS pc')->where('pc.product_id=' . $db->escape($product_id))->where('pc.is_default=1');
        $db->setQuery($sql);
        $category = $db->loadResult();
        
        return $category;
    }
    
    private static function getProductCategories($product_id) {
        $db = JFactory::getDBO();
        $sql = $db->getQuery(true);
        $sql->select('pc.category_id')->from('#__ksenmart_products_categories AS pc')->where('pc.product_id=' . $db->escape($product_id));
        $db->setQuery($sql);
        $categories = $db->loadObjectList();
        
        return $categories;
    }
    
    private static function getProductCategory($product_id) {
        $final_categories = array();
        $parent_ids = array();
        $default_category = self::getDefaultCategory($product_id);
        $product_categories = self::getProductCategories($product_id);
        
        foreach ($product_categories as $product_category) {
            if (!empty($default_category)) {
                $id_default_way = false;
            } else {
                $id_default_way = true;
            }
            $categories = array();
            $parent = $product_category->category_id;
            
            while ($parent != 0) {
                if ($parent == $default_category) {
                    $id_default_way = true;
                }
                $category = KSSystem::getTableByIds(array(
                    $parent
                ) , 'categories', array(
                    't.id',
                    't.parent_id'
                ) , true, false, true);
                $categories[] = $category->id;
                $parent = $category->parent_id;
            }
            if ($id_default_way && count($categories) > count($final_categories)) {
                $final_categories = $categories;
            }
        }
        
        $category_id = count($final_categories) ? $final_categories[0] : 0;
        
        return $category_id;
    }
}