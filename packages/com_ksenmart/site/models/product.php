<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

KSSystem::import('models.modelksform');
class KsenMartModelProduct extends JModelKSForm {
    var $_id = null;
    var $_product = null;
    
    public function __construct() {
        parent::__construct();
        $this->_id = JFactory::getApplication()->input->get('id', null, 'int');
    }
    
    protected function populateState($ordering = null, $direction = null) {
        $this->onExecuteBefore('populateState', array(&$this));
        $this->onExecuteAfter('populateState', array(&$this));
    }
    
    public function getProduct() {
        $this->onExecuteBefore('getProduct');
        
        $row = KSMProducts::getProduct($this->_id);
        if (!empty($row)) {
            $row->rate = KSMProducts::getProductRate($row->id);
            $row->manufacturer = KSMProducts::getProductManufacturer($row->manufacturer);
            $row->add_link_cart = KSFunctions::getAddToCartLink();
            $row->comments = $this->getProductComments($row->id);
            $row->sets = $this->getProductRelations($row->id);
            $this->_product = $row;
            
            KSMProducts::incProductHit($this->_id);
            
            $this->onExecuteAfter('getProduct', array(&$row));
            
            return $row;
        }
        JError::raiseError(404, 'Товара не существует');
    }
    
    private function getProductRelations($pid) {
        $this->onExecuteBefore('getProductRelations', array(&$pid));
        
        if (!empty($pid) && $pid > 0) {
            $query = $this->_db->getQuery(true);
            
            $query->select('
                    p.id,
                    p.parent_id,
                    p.childs_group,
                    p.title,
                    p.alias,
                    p.price,
                    p.old_price,
                    p.price_type,
                    p.content,
                    p.introcontent,
                    p.product_code,
                    p.in_stock,
                    p.product_unit,
                    p.product_packaging,
                    p.manufacturer,
                    p.promotion,
                    p.recommendation,
                    p.hot,
                    p.new,
                    p.hits,
                    p.carted,
                    p.ordering,
                    p.metatitle,
                    p.metadescription,
                    p.metakeywords,
                    p.date_added,
                    p.is_parent,
                    p.type,
                    p.tag
                ')->from('#__ksenmart_products AS p')->innerjoin('#__ksenmart_products_relations AS pr ON p.id=pr.product_id')->where('p.type=\'set\'')->where('pr.relation_type=\'set\'')->where('pr.relative_id=' . $this->_db->escape($pid));
            $this->_db->setQuery($query);
            $sets = $this->_db->loadObjectList();
            
            foreach ($sets as & $set) {
                $set = KSMProducts::getProduct($set->id);
            }
            
            $this->onExecuteAfter('getProductRelations', array(&$sets));
            
            return $sets;
        }
    }
    
    public function getProductComments($pid) {
        $this->onExecuteBefore('getProductComments', array(&$pid));
        
        if (!empty($pid) && $pid > 0) {
            $query = $this->_db->getQuery(true);
            $query->select('
                    c.id, 
                    c.user_id AS user, 
                    c.parent_id, 
                    c.product_id AS product, 
                    c.comment, 
                    c.name AS comment_name, 
                    c.good, 
                    c.bad, 
                    c.rate, 
                    c.date_add,
                    uf.filename AS logo, 
                    u.name
                ')->from('#__ksenmart_comments AS c')->leftjoin('#__ksen_users AS kmu ON kmu.id=c.user_id')->leftjoin('#__users AS u ON kmu.id=u.id')->leftjoin('#__ksenmart_files AS uf ON uf.owner_id=u.id')->where('c.product_id=' . $this->_db->escape($pid))->where('c.published=1')->where('c.parent_id=0')->order('c.date_add DESC');
            
            $this->_db->setQuery($query);
            
            $comments = $this->_db->loadObjectList();
            if (!empty($comments)) {
                $comments = $this->getProductCommentsChild($comments);
                $comments = KSUsers::setAvatarLogoInObject($comments);
            }
            
            $this->onExecuteAfter('getProductComments', array(&$comments));
            
            return $comments;
        }
        
        return new sdtObject;
    }
    
    public function getProductCommentsChild($comments) {
        $this->onExecuteBefore('getProductCommentsChild', array(&$comments));
        
        if (!empty($comments)) {
            $i = 0;
            $where = '';
            $comments_l = count($comments) - 1;
            $query = $this->_db->getQuery(true);
            
            $query->select('
                    c.id, 
                    c.user_id AS user, 
                    c.parent_id, 
                    c.product_id AS product, 
                    c.comment, 
                    c.name AS comment_name, 
                    c.good, 
                    c.bad, 
                    c.rate, 
                    c.date_add,
                    uf.filename AS logo, 
                    u.name
                ')->from('#__ksenmart_comments AS c')->leftjoin('#__ksen_users AS kmu ON kmu.id=c.user_id')->leftjoin('#__users AS u ON kmu.id=u.id')->leftjoin('#__ksenmart_files AS uf ON uf.owner_id=u.id')->where('c.published=1');
            
            foreach ($comments as $comment) {
                if (!empty($comment->id) && $comment->id > 0) {
                    $where.= 'c.parent_id=' . $this->_db->escape($comment->id);
                    if ($comments_l != $i) {
                        $where.= ' OR ';
                    }
                }
                $i++;
                
                continue;
            }
            $query->where($where);
            $query->order('c.date_add desc');
            
            $this->_db->setQuery($query);
            $children = $this->_db->loadObjectList();
            $this->_db->query();
            
            if ($this->_db->getNumRows() > 0) {
                $children = KSUsers::setAvatarLogoInObject($children);
                $children_l = count($children);
                
                for ($i = 0;$i < $children_l;$i++) {
                    
                    for ($j = 0;$j <= $comments_l;$j++) {
                        if ($children[$i]->parent_id == $comments[$j]->id) {
                            $comments[$j]->children = $children[$i];
                        }
                        
                        continue;
                    }
                }
            }
        }
        
        $this->onExecuteAfter('getProductCommentsChild', array(&$comments));
        
        return $comments;
    }
    
    public function getChilds($id = 0) {
        $this->onExecuteBefore('getChilds', array(&$id));
        
        if ($id == 0) {
            $id = $this->_id;
        }
        $childs = array();
        $query = $this->_db->getQuery(true);
        $query->select('id')->from('#__ksenmart_products')->where('parent_id=' . $this->_db->escape($id));
        $this->_db->setQuery($query);
        $ids = $this->_db->loadObjectList();
        if (!empty($ids)) {
            
            foreach ($ids as $id) {
                $childs[] = KSMProducts::getProduct($id->id);
            }
        }
        
        $this->onExecuteAfter('getChilds', array(&$childs));
        
        return $childs;
    }
    
    public function getChildsTitles($id = 0) {
        $this->onExecuteBefore('getChildsTitles', array(&$id));
        
        if ($id == 0) {
            $id = $this->_id;
        }

        $query = $this->_db->getQuery(true);
        $query
            ->select($this->_db->qn(array(
                'p.id',
                'p.alias',
                'p.title',
            )))
            ->from($this->_db->qn('#__ksenmart_products', 'p'))
            ->where($this->_db->qn('p.parent_id') . '=' . $this->_db->q($id))
        ;
        $this->_db->setQuery($query);
        $childs_titles = $this->_db->loadObjectList();
        
        $this->onExecuteAfter('getChildsTitles', array(&$childs_titles));
        return $childs_titles;
    }
    
    public function getChildsGroups() {
        $this->onExecuteBefore('getChildsGroups');
        
        $childs_groups = array();
        if ($this->_product->type == 'product' && $this->_product->parent_id == 0) {
            $empty_group = new stdClass();
            $empty_group->id = 0;
            $empty_group->title = JText::_('KSM_PRODUCT_CHILDS_EMPTY_GROUP');
            $empty_group->product_id = $this->_id;
            $empty_group->ordering = 0;
            
            $query = $this->_db->getQuery(true);
            $query->select('
                    cg.id,
                    cg.title,
                    cg.product_id,
                    cg.ordering
                ')->from('#__ksenmart_products_child_groups AS cg')->where('cg.product_id=' . $this->_db->escape($this->_id))->order('cg.ordering');
            $this->_db->setQuery($query);
            $childs_groups = $this->_db->loadObjectList('id');
            array_unshift($childs_groups, $empty_group);
            
            $where = array();
            
            foreach ($childs_groups as & $child) {
                $where[] = $child->id;
            }
            $sql = $this->_db->getQuery(true);
            $sql->select('p.id, p.parent_id, p.childs_group')->from("#__ksenmart_products as p")->where('p.parent_id =' . $this->_db->escape($this->_id))->where('(p.childs_group IN(' . implode(', ', $where) . '))')->order('p.ordering');
            $this->_db->setQuery($sql);
            $products = $this->_db->loadObjectList('id');
            
            foreach ($childs_groups as & $child) {
                $child->products = array();
                
                foreach ($products as & $product) {
                    if ($product->childs_group == $child->id) {
                        $product = KSMProducts::getProduct($product->id);
                        $child->products[$product->id] = $product;
                    }
                    
                    continue;
                }
            }
        }
        
        $this->onExecuteAfter('getChildsGroups', array(&$childs_groups));
        
        return $childs_groups;
    }
    
    public function getChildsTitle($id = 0) {
        $this->onExecuteBefore('getChildsTitle', array(&$id));
        
        if ($id == 0) {
            $id = $this->_id;
        }
        $title = '';
        $query = $this->_db->getQuery(true);
        $query->select('category_id')->from('#__ksenmart_products_categories')->where('product_id=' . $this->_db->escape($id));
        $this->_db->setQuery($query);
        $categories = $this->_db->loadObjectList();
        $clevel = 0;
        
        foreach ($categories as $category) {
            $query = $this->_db->getQuery(true);
            $query->select('childs_title')->from('#__ksenmart_categories')->where('id=' . $category->category_id);
            $this->_db->setQuery($query);
            $childs_title = $this->_db->loadResult();
            $level = $this->getCategoryLevel($category->category_id);
            if ($clevel < $level && !empty($childs_title)) {
                $title = $childs_title;
            }
        }
        
        $this->onExecuteAfter('getChildsTitle', array(&$title));
        
        return $title;
    }
    
    public function getCategoryLevel($id = 0) {
        $this->onExecuteBefore('getCategoryLevel', array(&$id));
        
        $level = 0;
        
        while ((int)$id != 0) {
            $query = $this->_db->getQuery(true);
            $query->select('parent_id')->from('#__ksenmart_categories')->where('id=' . $id);
            $this->_db->setQuery($query);
            $id = $this->_db->loadResult();
            $level++;
        }
        
        $this->onExecuteAfter('getCategoryLevel', array(&$level));
        
        return $level;
    }
    
    public function getLinks() {
        $this->onExecuteBefore('getLinks');
        
        $query = $this->_db->getQuery(true);
        $query->select('id')->from('#__ksenmart_products')->where('id<' . $this->_id)->order('id DESC');
        $this->_db->setQuery($query, 0, 1);
        $prev_id = $this->_db->loadResult();
        if (empty($prev_id)) {
            $query = $this->_db->getQuery(true);
            $query->select('max(id)')->from('#__ksenmart_products');
            $this->_db->setQuery($query, 0, 1);
            $prev_id = $this->_db->loadResult();
        }
        $query = $this->_db->getQuery(true);
        $query->select('id')->from('#__ksenmart_products')->where('id>' . $this->_id)->order('id ASC');
        $this->_db->setQuery($query, 0, 1);
        $next_id = $this->_db->loadResult();
        if (empty($next_id)) {
            $query = $this->_db->getQuery(true);
            $query->select('min(id)')->from('#__ksenmart_products');
            $this->_db->setQuery($query, 0, 1);
            $next_id = $this->_db->loadResult();
        }
        $prev_link = KSMProducts::generateProductLink($prev_id);
        $next_link = KSMProducts::generateProductLink($next_id);
        
        $this->onExecuteAfter('getLinks', array(&$prev_link, &$next_link));
        
        return array($prev_link, $next_link);
    }
    
    public function getImages() {
        $this->onExecuteBefore('getImages');
        
        $query = $this->_db->getQuery(true);
        $query->select($this->_db->qn(array(
                'f.id',
                'f.owner_id',
                'f.media_type',
                'f.owner_type',
                'f.folder',
                'f.filename',
                'f.mime_type',
                'f.title',
                'f.ordering',
                'f.params',
            )))
            ->from($this->_db->qn('#__ksenmart_files', 'f'))
            ->where($this->_db->qn('f.owner_id') . '=' . $this->_db->q($this->_id))
            ->where($this->_db->qn('f.owner_type') . '=' . $this->_db->q('product'))
            ->where($this->_db->qn('f.media_type') . '=' . $this->_db->q('image'))
            ->order('ordering')
        ;
        $this->_db->setQuery($query);
        $rows = $this->_db->loadObjectList();
        
        for ($k = 0;$k < count($rows);$k++) {
            $rows[$k]->img_small = KSMedia::resizeImage($rows[$k]->filename, $rows[$k]->folder, $this->params->get('mini_thumb_width', 130), $this->params->get('mini_thumb_height', 80), json_decode($rows[$k]->params, true));
            $rows[$k]->img       = KSMedia::resizeImage($rows[$k]->filename, $rows[$k]->folder, $this->params->get('middle_width', 200), $this->params->get('middle_height', 200));
            $rows[$k]->img_link  = KSMedia::resizeImage($rows[$k]->filename, $rows[$k]->folder, $this->params->get('full_width', 900), $this->params->get('full_height', 900));
        }
        
        $this->onExecuteAfter('getImages', array(&$rows));
        return $rows;
    }
    
    public function getDefaultCategory() {
        $this->onExecuteBefore('getDefaultCategory');
        
        $sql = $this->_db->getQuery(true);
        $sql->select('category_id')->from('#__ksenmart_products_categories AS pc')->where('pc.product_id=' . $this->_db->escape($this->_id))->where('pc.is_default=1');
        $this->_db->setQuery($sql);
        $category = $this->_db->loadResult();
        
        $this->onExecuteAfter('getDefaultCategory', array(&$category));
        
        return $category;
    }
    
    public function getProductCategories() {
        $this->onExecuteBefore('getProductCategories');
        
        $sql = $this->_db->getQuery(true);
        $sql->select('pc.category_id')->from('#__ksenmart_products_categories AS pc')->where('pc.product_id=' . $this->_db->escape($this->_id));
        $this->_db->setQuery($sql);
        $categories = $this->_db->loadObjectList();
        
        $this->onExecuteAfter('getProductCategories', array(&$categories));
        
        return $categories;
    }
    
    public function getCategoriesPath() {
        $this->onExecuteBefore('getCategoriesPath');
        
        $path               = array();
        $final_categories   = array();
        $parent_ids         = array();
        $default_category   = $this->getDefaultCategory();
        $product_categories = $this->getProductCategories();
        
        foreach ($product_categories as $product_category) {
            if (!empty($default_category)) {
                $id_default_way = false;
            } else {
                $id_default_way = true;
            }
            $categories = array();
            $parent     = $product_category->category_id;
            
            while ($parent != 0) {
                if ($parent == $default_category) {
                    $id_default_way = true;
                }
                $category = KSSystem::getTableByIds(array($parent), 'categories', array('t.id', 't.parent_id'), true, false, true);
                $parent   = 0;
                if($category->id > 0) {
                    $categories[] = $category->id;
                    $parent       = $category->parent_id;
                }
            }
            if ($id_default_way && count($categories) > count($final_categories)) {
                $final_categories = $categories;
            }
        }
        
        $final_categories = array_reverse($final_categories);
        $categories       = KSSystem::getTableByIds($final_categories, 'categories', array('t.title', 't.id'), false);
        
        foreach ($categories as $category) {
            $category->link = JRoute::_('index.php?option=com_ksenmart&view=catalog&categories[]=' . $category->id . '&Itemid=' . KSSystem::getShopItemid());
            $path[]         = $category;
        }
        
        $this->onExecuteAfter('getCategoriesPath', array(&$path));
        return $path;
    }
    
    public function getProductTitle() {
        $this->onExecuteBefore('getProductTitle');
        
        $params = JComponentHelper::getParams('com_ksenmart');
        $config = KSSystem::getSeoTitlesConfig('product');
        $shop_name = $params->get('shop_name', '');
        $path_separator = $params->get('path_separator', ' ');
        $title = array();
        
        if (empty($this->_product->metatitle)) {
            
            if ($shop_name != '') {
                $title[] = $shop_name;
            }
            
            foreach ($config as $key => $val) {
                if ($val->user == 0) {
                    if ($val->active == 1) {
                        if ($key == 'seo-product') {
                            $title[] = $this->_product->title;
                        }
                        if ($key == 'seo-product_code') {
                            $title[] = $this->_product->product_code;
                        }
                        if ($key == 'seo-manufacturer' && isset($this->_product->manufacturer->id)) {;
                            $manufacturer_title = KSSystem::getTableByIds(array($this->_product->manufacturer->id), 'manufacturers', array('t.title'), false, false, true);
                            $title[] = $manufacturer_title->title;
                        }
                        if ($key == 'seo-country' && isset($this->_product->manufacturer->id)) {
                            if (!empty($this->_product->manufacturer->country)) {
                                $country_title = KSSystem::getTableByIds(array($this->_product->manufacturer->country->id), 'countries', array('t.title'), false, false, true);
                                $title[] = $country_title->title;
                            }
                        } elseif ($key == 'seo-parent-category') {
                            $categories = array();
                            $query = $this->_db->getQuery(true);
                            $query->select('category_id')->from('#__ksenmart_products_categories')->where('product_id=' . $this->_db->Quote((int)$this->_id))->where('is_default=1');
                            $this->_db->setQuery($query);
                            $default_category = $this->_db->loadResult();
                            $parent = $default_category;
                            
                            while ($parent != 0) {
                                $query = $this->_db->getQuery(true);
                                $query->select('title,parent_id')->from('#__ksenmart_categories')->where('id=' . $this->_db->Quote($parent));
                                $this->_db->setQuery($query);
                                $category = $this->_db->loadObject();
                                if ($category->title != '' && $parent != $default_category) $categories[] = $category->title;
                                $parent = $category->parent_id;
                            }
                            $categories = array_reverse($categories);
                            
                            foreach ($categories as $category) $title[] = $category;
                        } elseif ($key == 'seo-category') {
                            $query = $this->_db->getQuery(true);
                            $query->select('category_id')->from('#__ksenmart_products_categories')->where('product_id=' . $this->_db->Quote((int)$this->_id))->where('is_default=1');
                            $this->_db->setQuery($query);
                            $default_category = $this->_db->loadResult();
                            
                            $query = $this->_db->getQuery(true);
                            $query->select('title')->from('#__ksenmart_categories')->where('id=' . $this->_db->Quote($default_category));
                            $this->_db->setQuery($query);
                            $cat_title = $this->_db->loadResult();
                            if (!empty($cat_title)) $title[] = $cat_title;
                        }
                    }
                } else {
                    if (strpos($key, 'property_') !== false) {
                        $property_id = str_replace('property_', '', $key);
                        if (isset($this->_product->properties[$property_id]->values)) {
                            $values = array();
                            
                            foreach ($this->_product->properties[$property_id]->values as $value) {
                                if ($value->title != '') {
                                    $values[] = $value->title;
                                }
                            }
                            if (count($values) > 0) {
                                $title[] = $val->title . ' - ' . implode(', ', $values);
                            }
                        }
                    } else {
                        $title[] = $val->title;
                    }
                }
            }
        } else $title[] = $this->_product->metatitle;
        
        $this->onExecuteAfter('getProductTitle', array(&$path_separator, &$title));
        
        return implode($path_separator, $title);
    }
    
    public function setProductMetaData() {
        $this->onExecuteBefore('setProductMetaData');
        
        $document = JFactory::getDocument();
        $config = KSSystem::getSeoTitlesConfig('product', 'meta');
        $metatitle = null;
        $metadescription = null;
        $metakeywords = null;
        
        if (empty($this->_product->metatitle)) {
            $metatitle = $this->_product->title;
        } else {
            $metatitle = $this->_product->metatitle;
        }
        if (empty($this->_product->metadescription)) {
            if ($config->description->flag == 1) {
                if ($config->description->type == 'seo-type-mini-description') {
                    $metadescription = strip_tags($this->_product->introcontent);
                } elseif ($config->description->type == 'seo-type-description') {
                    $metadescription = strip_tags($this->_product->content);
                }
                $metadescription = mb_substr($metadescription, 0, $config->description->symbols);
            }
        } else {
            $metadescription = $this->_product->metadescription;
        }
        if (empty($this->_product->metakeywords)) {
            if ($config->keywords->flag == 1) {
                if ($config->keywords->type == 'seo-type-properties-and-values') {
                    $properties = array();
                    
                    foreach ($this->_product->properties as $property) {
                        $values = array();
                        if (!empty($property->values)) {
                            
                            foreach ($property->values as $value) {
                                if (!empty($value->title)) {
                                    $values[] = $value->title;
                                }
                            }
                            if (!empty($value->title)) {
                                $values[] = $value->title;
                            }
                        }
                        if (count($values) > 0) {
                            $properties[] = $property->title . ' - ' . implode(',', $values);
                        }
                    }
                    if (count($properties) > 0) {
                        $metakeywords = implode(';', $properties);
                    }
                } elseif ($config->keywords->type == 'seo-type-tag') {
                    $metakeywords = strip_tags($this->_product->tag);
                }
            }
        } else {
            $metakeywords = $this->_product->metakeywords;
        }
        
        if (!empty($metatitle)) {
            $document->setMetaData('title', $metatitle);
        }
        if (!empty($metadescription)) {
            $document->setMetaData('description', $metadescription);
        }
        if (!empty($metakeywords)) {
            $document->setMetaData('keywords', $metakeywords);
        }
        
        $this->onExecuteAfter('setProductMetaData', array(&$this));
    }
}
