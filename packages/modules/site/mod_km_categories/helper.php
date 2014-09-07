<?php
// No direct access.
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
        $sql
            ->select('kc.*')
            ->from('#__ksenmart_categories as kc')
            ->where('kc.published=1')
            ->order('kc.ordering')
        ;
        if($categories){
            $sql->where('kc.id IN(' . implode(', ', $categories) . ') OR kc.parent_id IN(' . implode(', ', $categories) . ')');
        }
		KSMedia::setItemMainImageToQuery($sql, 'category', 'kc.');

        $db->setQuery($sql);
        
        $rows = $db->loadObjectList('id');
        $top_parent = (object)array('id' => 0, 'children' => array(),);
        $menu = array(0 => $top_parent);
        foreach ($rows as $k => $v) {
            if (!empty($v->folder)) {
                $v->img = KSMedia::resizeImage($v->filename, $v->folder, $params->get('img_width', 200),  $params->get('img_height', 200), json_decode($v->params, true));
            }		
            if (isset($menu[$k])) $v->children = $menu[$k]->children;
            else $v->children = array();
            $menu[$k] = $v;
            if (!isset($menu[$v->parent_id])) {
                $menu[$v->parent_id] = new stdClass();
                $menu[$v->parent_id]->children = array();
            }
            $menu[$v->parent_id]->children[] = $v;
        }
        $this->menu = $menu;
    }
    
    private function make_tree($category, $level = 1) {
        if (isset($category->children) && !empty($category->children)) {
            foreach ($category->children as $child) {
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
                $this->make_tree($this->menu[$child->id], $level + 1);
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
                $this->make_tree($this->menu[0]);
                $cache->store($this->tree, $key);
            } else {
                return false;
            }
        }
        
        return $this->tree;
    }
}
