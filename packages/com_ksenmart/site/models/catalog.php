<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

KSSystem::import('models.modelkslist');
class KsenMartModelcatalog extends JModelKSList {
    
    private $_ids            = array();
    private $_categories     = null;
    private $_manufacturers  = null;
    private $_properties     = null;
    private $_countries      = null;
    private $_title          = null;
    private $_new            = null;
    private $_promotion      = null;
    private $_hot            = null;
    private $_recommendation = null;
    private $_price_less     = null;
    private $_price_more     = null;
    
    /**
     * KsenMartModelcatalog::__construct()
     * 
     * @param mixed $config
     * @return
     */
    public function __construct($config = array()) {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array();
        }
        parent::__construct($config);
        
        $this->getDefaultStates();
        
        $this->setState('params', $this->_params);
    }
    
    /**
     * KsenMartModelcatalog::getDefaultStates()
     * 
     * @return
     */
    private function getDefaultStates(){
        $this->onExecuteBefore('getDefaultStates', array(&$this));

        $this->_categories     = $this->getState('com_ksenmart.categories');
        $this->_manufacturers  = $this->getState('com_ksenmart.manufacturers');
        $this->_properties     = $this->getState('com_ksenmart.properties');
        $this->_countries      = $this->getState('com_ksenmart.countries');
        $this->_title          = $this->getState('com_ksenmart.title');
        $this->_new            = $this->getState('com_ksenmart.new');
        $this->_promotion      = $this->getState('com_ksenmart.promotion');
        $this->_hot            = $this->getState('com_ksenmart.hot');
        $this->_recommendation = $this->getState('com_ksenmart.recommendation');
        $this->_price_less     = $this->getState('com_ksenmart.price_less');
        $this->_price_more     = $this->getState('com_ksenmart.price_more');
        
        $this->onExecuteAfter('getDefaultStates', array(&$this));
    }

    /**
     * KsenMartModelcatalog::populateState()
     * 
     * @param string $ordering
     * @param string $direction
     * @return
     */
    protected function populateState($ordering = 'ordering', $direction = 'ASC') {
        
        $this->onExecuteBefore('populateState', array(&$this));
        
        if(empty($this->_params)){
            $this->_params = JComponentHelper::getParams('com_ksenmart');
            $this->setState('params', $this->_params);
        }

        $categories = JRequest::getVar('categories', array());
        JArrayHelper::toInteger($categories);
        $categories = array_filter($categories, 'KSFunctions::filterArray');
        $this->setState('com_ksenmart.categories', $categories);

        $manufacturers = JRequest::getVar('manufacturers', array());
        JArrayHelper::toInteger($manufacturers);
        $manufacturers = array_filter($manufacturers, 'KSFunctions::filterArray');
        $this->setState('com_ksenmart.manufacturers', $manufacturers);

        $properties = JRequest::getVar('properties', array());
        JArrayHelper::toInteger($properties);
        $properties = array_filter($properties, 'KSFunctions::filterArray');
        $this->setState('com_ksenmart.properties', $properties);

        $countries = JRequest::getVar('countries', array());
        JArrayHelper::toInteger($countries);
        $countries = array_filter($countries, 'KSFunctions::filterArray');
        $this->setState('com_ksenmart.countries', $countries);

        $price_less = JRequest::getVar('price_less', '');
        $this->setState('com_ksenmart.price_less', $price_less);
        $price_more = JRequest::getVar('price_more', '');
        $this->setState('com_ksenmart.price_more', $price_more);
        $title = JRequest::getVar('title', '');
        $this->setState('com_ksenmart.title', $title);
        $order_type = JRequest::getVar('order_type', 'ordering');
        $new = JRequest::getVar('new', '');
        $this->setState('com_ksenmart.new', $new);
        $promotion = JRequest::getVar('promotion', '');
        $this->setState('com_ksenmart.promotion', $promotion);
        $hot = JRequest::getVar('hot', '');
        $this->setState('com_ksenmart.hot', $hot);
        $recommendation = JRequest::getVar('recommendation', '');
        $this->setState('com_ksenmart.recommendation', $recommendation);
        $this->setState('list.ordering', $order_type);
        $order_dir = JRequest::getVar('order_dir', 'asc');
        $this->setState('list.direction', $order_dir);
        $limit = JRequest::getVar('limit', $this->_params->get('site_product_limit', 20));
        $this->setState('list.limit', $limit);
        $limitstart = JRequest::getVar('limitstart', 0);
        $this->setState('list.start', $limitstart);
        
        $this->onExecuteAfter('populateState', array(&$this));
    }

    /**
     * KsenMartModelcatalog::getProductsIds()
     * 
     * @return
     */
    public function getProductsIds() {
        
        $this->onExecuteBefore('getProductsIds', array(&$this->_ids));
        
        $this->_ids = array();
        
        if ($this->_price_less >= 0 && !empty($this->_price_more)) {
            $this->_ids = $this->getIdsByMMPrices($this->_price_less, $this->_price_more);
        }
        if (count($this->_categories) > 0) {
            $this->_ids = $this->getIdsByCategories($this->_categories);
        }
        if (count($this->_properties) > 0) {
            $this->_ids = $this->getIdsByProperties($this->_properties);
        }
        if (count($this->_countries) > 0) {
            $this->_ids = $this->getIdsByCountries($this->_countries);
        }
        if (count($this->_manufacturers) > 0) {
            $this->_ids = $this->getIdsByManufacturers($this->_manufacturers);
        }   
        
        $this->onExecuteAfter('getProductsIds', array(&$this->_ids));
        return $this->_ids;
    }
    
    /**
     * KsenMartModelcatalog::getIdsByProperties()
     * 
     * @param mixed $properties
     * @return
     */
    private function getIdsByProperties($properties){
        
        $this->onExecuteBefore('getIdsByProperties', array(&$properties));

        if(!empty($properties)){
            $props      = $this->getIdsByPropertiesV($properties);
            $this->_ids = $this->getIdsByPropertiesPV($props);
            $this->_ids = $this->getProductsIdsBy($this->_ids);
            
            $this->onExecuteAfter('getIdsByProperties', array(&$this->_ids));
            
            return $this->_ids;
        }
        return array(0);
    }
    
    /**
     * KsenMartModelcatalog::getIdsByPropertiesV()
     * 
     * @param mixed $properties
     * @return
     */
    private function getIdsByPropertiesV($properties){
        $this->onExecuteBefore('getIdsByPropertiesV', array(&$properties));

        if(!empty($properties)){
            $properties = KSSystem::getTableByIds($properties, 'property_values', array('t.id', 't.property_id'), false); 
            foreach ($properties as $property) {
                if (!isset($props[$property->property_id])){
                    $props[$property->property_id] = array();
                }
                $props[$property->property_id][]  = $property->id;
            }
            $this->onExecuteAfter('getIdsByPropertiesV', array(&$props));
            return $props;
        }
        return array();
    }
    
    /**
     * KsenMartModelcatalog::getIdsByPropertiesPV()
     * 
     * @param mixed $properties
     * @return
     */
    private function getIdsByPropertiesPV($properties){
        $this->onExecuteBefore('getIdsByPropertiesPV', array(&$properties));
        
        if(!empty($properties)){
            $where_pv = array();
            end($properties);
            $last_value = key($properties);
 
            $query = $this->_db->getQuery(true); 
            foreach ($properties as $property_id => $property_values) {
                if (!empty($property_values)) {
                    $query->innerjoin('#__ksenmart_product_properties_values as kppv'.$property_id.' on kppv'.$property_id.'.product_id=p.id');
                    $where_pv[] = '(kppv'.$property_id.'.value_id IN (' . implode(',', $property_values) . '))';
                }
            }
            if (!empty($this->_ids)){
                $where_pv[] = "(p.id IN (" . implode(',', $this->_ids) . "))";
            }
            $query
                ->select('p.id')
                ->from('#__ksenmart_products as p')
                ->group('p.id')
                ->where($where_pv)
            ;
            $this->_db->setQuery($query);
            $this->_ids = $this->_db->loadColumn();
            $this->_ids = count($this->_ids) > 0 ? $this->_ids : array(0);
            
            $this->onExecuteAfter('getIdsByPropertiesPV', array(&$this->_ids));
            return $this->_ids;
        }
        return array(0);
    }
    
    /**
     * KsenMartModelcatalog::getProductsIdsBy()
     * 
     * @param mixed $where
     * @return
     */
    private function getProductsIdsBy(array $ids){
        $this->onExecuteBefore('getProductsIdsBy', array(&$ids));
        
        if(!empty($ids)){
            $products = KSSystem::getTableByIds($ids, 'products', array('t.id', 't.parent_id'));
            foreach ($products as $product) {
                $this->_ids[] = $product->id;
                if ($product->parent_id != 0){
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

    private function getProductsIdsByWhere(array $where){
        $this->onExecuteBefore('getProductsIdsByWhere', array(&$where));

        if(!empty($where)){
            $query = $this->_db->getQuery(true);
            $query
                ->select('
                    p.id,
                    p.parent_id
                ')
                ->from('#__ksenmart_products as p')
                ->where($where)
            ;
            
            $this->_db->setQuery($query);
            $products = $this->_db->loadObjectList();
    
            foreach ($products as $product) {
                $this->_ids[] = $product->id;
                if ($product->parent_id != 0){
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
     * KsenMartModelcatalog::getIdsByMMPrices()
     * 
     * @param mixed $price_less
     * @param mixed $price_more
     * @return
     */
    private function getIdsByMMPrices($price_less, $price_more){
        $this->onExecuteBefore('getIdsByMMPrices', array(&$price_less, &$price_more));
        
        if($price_less >= 0 && !empty($price_more)){
            $where          = array('p.published=1');
            $price_where_l  = array();
            $price_where_m  = array();
            
            $query          = $this->_db->getQuery(true);
            $query
                ->select('
                    c.id, 
                    c.rate
                ')
                ->from('#__ksenmart_currencies AS c')
            ;
            $this->_db->setQuery($query);
            $currencies  = $this->_db->loadObjectList('id');
            
            foreach ($currencies as $key => $value) {
                $cur_price_l = $price_less * $currencies[$key]->rate;
                $cur_price_m = $price_more * $currencies[$key]->rate;
                
                $price_where_l[] = '(p.price>='.$this->_db->escape($cur_price_l).' AND p.price_type='.$this->_db->escape($currencies[$key]->id).')';
                $price_where_m[] = '(p.price<='.$this->_db->escape($cur_price_m).' AND p.price_type='.$this->_db->escape($currencies[$key]->id).')';
            }
            if (count($price_where_l)) {
                $where[] = '(' . implode(' OR ', $price_where_l) . ')';
            }
            if (count($price_where_m)) {
                $where[] = '(' . implode(' OR ', $price_where_m) . ')';
            }
            $this->onExecuteAfter('getIdsByMMPrices', array(&$where));
            return $this->getProductsIdsByWhere($where);
        }
        return array(0);        
    }
    
    
    /**
     * KsenMartModelcatalog::getIdsByCategories()
     * 
     * @param mixed $categories
     * @return
     */
    private function getIdsByCategories($categories){
        $this->onExecuteBefore('getIdsByCategories', array(&$categories));
        
        if(!empty($categories)){
            $where = array();
            $this->_ids_l = count($this->_ids);
            if ($this->_params->get('show_products_from_subcategories', 1) == 1) {
                $cats = $categories;
                $categories = array();
                foreach ($cats as $cat) {
                    $c = $this->getChildCats($cat);
                    $categories = array_merge($categories, $c);
                }
            }
            $where[] = "(category_id IN (" . implode(',', $categories) . "))";
            if($this->_ids_l > 0){
                $where[] = "(product_id IN (" . implode(',', $this->_ids) . "))";
            }
            $query = $this->_db->getQuery(true);
            $query
                ->select('DISTINCT product_id')
                ->from('#__ksenmart_products_categories')
                ->where($where)
                ->group('product_id')
            ;
            $this->_db->setQuery($query, 0, $this->_ids_l);
            $this->_ids = $this->_db->loadColumn();
            $this->_ids = count($this->_ids) > 0 ? $this->_ids : array(0);  
            
            $this->onExecuteAfter('getIdsByCategories', array(&$this->_ids));        
            return $this->_ids;
        }
        return array(0);
    }
    
    /**
     * KsenMartModelcatalog::getIdsByCountries()
     * 
     * @param mixed $countries
     * @param mixed $manufacturers
     * @return
     */
    private function getIdsByCountries($countries){
        $this->onExecuteBefore('getIdsByCountries', array(&$countries));
        
        if(!empty($countries)){
            $where = array();
            if(count($this->_ids) > 0){
                $where[] = "(p.id IN (" . implode(',', $this->_ids) . "))";
            }

			$query = $this->_db->getQuery(true);
			$query->select('m.id')->from('#__ksenmart_manufacturers as m')->where('m.country in (' . implode(',', $countries) . ')');
			$this->_db->setQuery($query);
			$manufacturers = $this->_db->loadColumn();	
			if(count($manufacturers) > 0)
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
    
    private function getIdsByManufacturers($manufacturers){
        $this->onExecuteBefore('getIdsByManufacturers', array(&$manufacturers));
        
        if(!empty($manufacturers)){
            $where = array();
            if(count($this->_ids) > 0){
                $where[] = "(p.id IN (" . implode(',', $this->_ids) . "))";
            }
            if(count($manufacturers) > 0){
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
     * KsenMartModelcatalog::getListQuery()
     * 
     * @return
     */
    public function getListQuery() {
        $this->onExecuteBefore('getListQuery');
        
        if(empty($this->_ids)){
            $this->_ids = $this->getProductsIds();
        }
        
        $where   = $this->getFilterDefaultParams();
        $where[] = "(p.parent_id=0)";
        
        if ($this->_params->get('show_out_stock') != 1) {
            $where[] = "(p.in_stock>0)";
        }
        $query = $this->_db->getQuery(true);
        
        $query
            ->select('p.id')
            ->from('#__ksenmart_products AS p')
            ->leftjoin("#__ksenmart_files AS f ON p.id=f.owner_id AND f.owner_type=".$this->_db->Quote('product'))
            ->where($where)
            ->order('p.' . $this->getState('list.ordering') . ' ' . $this->getState('list.direction'))
            ->group('p.id')
        ;

        $this->onExecuteAfter('getListQuery', array(&$query));
        return $query;
    }

    /**
     * KsenMartModelcatalog::getFilterProperties()
     * 
     * @return
     */
    public function getFilterProperties() {
        $this->onExecuteBefore('getFilterProperties');
        
        $this->_ids = array();
        if ($this->_price_less >= 0 && !empty($this->_price_more)) {
            $ids = $this->getIdsByMMPrices($this->_price_less, $this->_price_more);
        }
        if (count($this->_categories) > 0) {
            $ids = $this->getIdsByCategories($this->_categories);
        }
        if (count($this->_countries) > 0) {
            $ids = $this->getIdsByCountries($this->_countries);
        }
        if (count($this->_manufacturers) > 0) {
            $ids = $this->getIdsByManufacturers($this->_manufacturers);
        }   
        $request_props = $this->getIdsByPropertiesV($this->_properties);
        
        $query = $this->_db->getQuery(true);
        $query->select('id')->from('#__ksenmart_properties')->where('published=1');
        $this->_db->setQuery($query);
        $db_props = $this->_db->loadColumn();

        $properties = array();      
        foreach($db_props as $property_id)
        {
            $this->_ids = $ids;
            $props = $request_props;
            unset($props[$property_id]);
            if (count($props))
                $this->_ids = $this->getIdsByPropertiesPV($props);
            
            $where = $this->getFilterDefaultParams();
            $where[] = 'ppv1.property_id = '.$property_id;
            $query = $this->_db->getQuery(true);
            $query
                ->select('ppv1.value_id')
                ->from('#__ksenmart_product_properties_values AS ppv1')
                ->leftjoin('#__ksenmart_products AS p on p.id=ppv1.product_id')
            ;
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
     * KsenMartModelcatalog::getFilterManufacturers()
     * 
     * @return
     */
    public function getFilterManufacturers() {
        $this->onExecuteBefore('getFilterManufacturers');
        
		$this->_ids = array();
        if ($this->_price_less >= 0 && !empty($this->_price_more)) {
            $this->_ids = $this->getIdsByMMPrices($this->_price_less, $this->_price_more);
        }
        if (count($this->_categories) > 0) {
            $this->_ids = $this->getIdsByCategories($this->_categories);
        }
        if (count($this->_properties) > 0) {
            $this->_ids = $this->getIdsByProperties($this->_properties);
        }
        if (count($this->_countries) > 0) {
            $this->_ids = $this->getIdsByCountries($this->_countries);
        }
        
        $where = $this->getFilterDefaultParams();

        $query = $this->_db->getQuery(true);
        $query
            ->select('p.manufacturer')
            ->from('#__ksenmart_products as p')
            ->group('p.manufacturer')
        ;
        if ($this->_params->get('show_out_stock') != 1) {
            $where[] = "(p.in_stock>0)";
        }
        $query->where($where);
        $this->_db->setQuery($query);
        $values = $this->_db->loadObjectList();
        
        $manufacturers = array();
        foreach ($values as $value){
            $manufacturers[] = $value->manufacturer;
        }
        
        $this->onExecuteAfter('getFilterManufacturers', array(&$manufacturers));
        return $manufacturers;
    }
    
    /**
     * KsenMartModelcatalog::getFilterDefaultParams()
     * 
     * @return
     */
    private function getFilterDefaultParams(){
        $this->onExecuteBefore('getFilterDefaultParams', array(&$this));
        
        $where = array('p.published=1');
        if (count($this->_ids) > 0) $where[] = "(p.id in (" . implode(',', $this->_ids) . "))";
        if (!empty($this->_title)) {
            $this->_title = $this->_db->quote('%'.$this->_title.'%');
            $where[] = '(p.title LIKE '.$this->_title.'  OR p.introcontent LIKE '.$this->_title.' OR p.content LIKE '.$this->_title.')';
        }
        if (!empty($this->_new)) {
            $where[] = "(p.new=1)";
        }
        if (!empty($this->_promotion)) {
            $where[] = "(p.promotion=1)";
        }
        if (!empty($this->_hot)) {
            $where[] = "(p.hot=1)";
        }
        if (!empty($this->_recommendation)) {
            $where[] = "(p.recommendation=1)";
        }
        
        $this->onExecuteAfter('getFilterDefaultParams', array(&$where));
        return $where;
    }

    /**
     * KsenMartModelcatalog::getFilterCountries()
     * 
     * @return
     */
    public function getFilterCountries() {
        $this->onExecuteBefore('getFilterCountries', array(&$this));
        
		$this->_ids = array();
        if ($this->_price_less >= 0 && !empty($this->_price_more)) {
            $this->_ids = $this->getIdsByMMPrices($this->_price_less, $this->_price_more);
        }
        if (count($this->_categories) > 0) {
            $this->_ids = $this->getIdsByCategories($this->_categories);
        }
        if (count($this->_properties) > 0) {
            $this->_ids = $this->getIdsByProperties($this->_properties);
        }
        if (count($this->_manufacturers) > 0) {
            $this->_ids = $this->getIdsByManufacturers($this->_manufacturers);
        }

        $where = $this->getFilterDefaultParams();
        
        $query = $this->_db->getQuery(true);
        $query
            ->select('m.country')
            ->from('#__ksenmart_manufacturers as m')
            ->leftjoin('#__ksenmart_products as p on p.manufacturer=m.id')
            ->group('m.country')
        ;
        if ($this->_params->get('show_out_stock') != 1) {
            $where[] = "(p.in_stock>0)";
        }
        $query->where($where);
        $this->_db->setQuery($query);
        $values = $this->_db->loadObjectList();
        
        $countries = array();
        foreach ($values as $value){
            $countries[] = $value->country;
        }
        
        $this->onExecuteAfter('getFilterCountries', array(&$countries));
        return $countries;
    }

    /**
     * KsenMartModelcatalog::getItems()
     * 
     * @return
     */
    public function getItems() {
        $this->onExecuteBefore('getItems');

        $items = parent::getItems();
        foreach ($items as &$item) {
            $item = KSMProducts::getProduct($item->id);
        }
        
        $this->onExecuteAfter('getItems', array(&$items));
        return $items;
    }

    /**
     * KsenMartModelcatalog::getCategory()
     * 
     * @return
     */
    public function getCategory() {
        $this->onExecuteBefore('getCategory');

        if(!empty($this->_categories)){
            $query = $this->_db->getQuery(true);
            $query
                ->select('
                    c.id,
                    c.title,
                    c.childs_title,
                    c.alias,
                    c.content,
                    c.introcontent,
                    c.hits,
                    c.parent_id,
                    c.ordering,
                    c.metatitle,
                    c.metadescription,
                    c.metakeywords,
                    f.filename,
                    f.folder,
                    f.params
                ')
                ->from('#__ksenmart_categories AS c')
                ->leftjoin('#__ksenmart_files AS f ON c.id=f.owner_id AND f.owner_type='.$this->_db->Quote('category'))
                ->where('c.published=1')
                ->where('c.id=' . $this->_categories[0])
            ;
            $this->_db->setQuery($query);
            $category = $this->_db->loadObject();
            if(!empty($category)){
                $category->image = KSMedia::resizeImage($category->filename, $category->folder, $this->_params->get('thumb_width'), $this->_params->get('thumb_height'));
            }
            
            $this->onExecuteAfter('getCategory', array(&$category));
            return $category;
        }
        return new stdClass;
    }

    /**
     * KsenMartModelcatalog::getCategoryTitle()
     * 
     * @return
     */
    public function getCategoryTitle() {
        $this->onExecuteBefore('getCategoryTitle');

        $shop_name      = $this->_params->get('shop_name', '');
        $path_separator = $this->_params->get('path_separator', ' ');
        $category       = $this->getCategory();
        $config         = KSSystem::getSeoTitlesConfig('category');
        $title          = array();

        if (empty($category->metatitle)){
            if (!empty($shop_name)){
                $title[] = $shop_name;
            }
            
            if($config){
                foreach ($config as $key => $val) {
                    if ($val->user == 0) {
                        if ($val->active == 1) {
                            if ($key=='seo-parent-category')
                            {
                                $categories=array();
                                $parent=$category->id;
                                while($parent!=0)
                                {
                                    $query = $this->_db->getQuery(true);
                                    $query->select('title,parent_id')
                                    ->from('#__ksenmart_categories')
                                    ->where('id='.$parent);
                                    $this->_db->setQuery($query);
                                    $db_category=$this->_db->loadObject();  
                                    if ($db_category->title!='' && $parent!=$category->id)
                                        $categories[]=$db_category->title;
                                    $parent=$db_category->parent_id;
                                }
                                $categories=array_reverse($categories);
                                foreach($categories as $category_title)
                                    $title[]=$category_title;
                            }                   
                            elseif ($key == 'seo-category') {
                                $title[] = $category->title;
                            }
                        }
                    } else{
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
     * KsenMartModelcatalog::getCountry()
     * 
     * @return
     */
    public function getCountry() {
        $this->onExecuteBefore('getCountry');

        if(!empty($this->_countries)){
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
                ->where('c.id=' . $this->_countries[0])
            ;
            $this->_db->setQuery($query);
            $country = $this->_db->loadObject();
            
            $this->onExecuteAfter('getCountry', array(&$country));
            return $country;
        }
        return false;
    }

    /**
     * KsenMartModelcatalog::getCountryTitle()
     * 
     * @return
     */
    public function getCountryTitle() {
        $this->onExecuteBefore('getCountryTitle');

        $shop_name      = $this->_params->get('shop_name', '');
        $path_separator = $this->_params->get('path_separator', ' ');
        $country        = $this->getCountry();
        $config         = KSSystem::getSeoTitlesConfig('country');
        $title          = array();

        if (empty($country->metatitle)){
            if ($shop_name != ''){
                $title[] = $shop_name;
            }

            if($config){
                foreach ($config as $key => $val) {
                    if ($val->user == 0) {
                        if ($val->active == 1) {
                            if ($key == 'seo-country') {
                                $title[] = $country->title;
                            }
                        }
                    } else {
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
     * KsenMartModelcatalog::getManufacturers()
     * 
     * @return
     */
    public function getManufacturers() {
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
            ->order('m.title')
        ;
        if (count($this->_countries) > 0) {
            $query->innerjoin("#__ksenmart_countries AS c ON m.country=c.id");
            $where[] = "(c.id in (" . implode(',', $this->_countries) . "))";
        }
        $query
            ->leftjoin("#__ksenmart_files AS f ON m.id=f.owner_id AND f.owner_type='manufacturer'")
            ->where(implode(' AND ', $where))
            ->order('m.ordering')
            ->group('m.id')
        ;
        $this->_db->setQuery($query);
        $manufacturers = $this->_db->loadObjectList();

        foreach ($manufacturers as &$manufacturer) {
            if (!empty($manufacturer->folder)){
                $manufacturer->img_link = JURI::root() . 'media/com_ksenmart/images/' . $manufacturer->folder . '/original/' . $manufacturer->filename;
            }else{
                $manufacturer->img_link = JURI::root() . 'media/com_ksenmart/images/manufacturers/no.jpg';
            }
            $manufacturer->small_img = KSMedia::resizeImage($manufacturer->filename, $manufacturer->folder, $this->_params->get('thumb_width'), $this->_params->get('thumb_height'));
            $manufacturer->link = JRoute::_('index.php?option=com_ksenmart&view=catalog&manufacturers[]=' . $manufacturer->id . '&Itemid=' . KSSystem::getShopItemid());
        }
        
        $this->onExecuteAfter('getManufacturers', array(&$manufacturers));
        return $manufacturers;
    }
    
    public function getManufacturersListGroupByCountry(){
        $this->onExecuteBefore('getManufacturersListGroupByCountry');

        $query   = $this->_db->getQuery(true);
        $query
            ->select('
                m.id,
                m.title,
                m.alias,
                m.country,
                c.title AS c_title
            ')
            ->from('#__ksenmart_manufacturers AS m')
            ->leftjoin('#__ksenmart_countries AS c ON c.id=m.country')
            ->where('m.country != 0')
            ->order('m.ordering')
        ;
        $query
            ->leftjoin("#__ksenmart_files AS f ON m.id=f.owner_id AND f.owner_type='manufacturer'")
            ->group('m.id')
        ;       
        $this->_db->setQuery($query);
        $manufacturers = $this->_db->loadObjectList();
        
        $brands_group = array();
        foreach($manufacturers as $manufacturer){
            if (!empty($manufacturer->folder)){
                $manufacturer->img_link = JURI::root() . 'media/com_ksenmart/images/' . $manufacturer->folder . '/original/' . $manufacturer->filename;
            }else{
                $manufacturer->img_link = JURI::root() . 'media/com_ksenmart/images/manufacturers/no.jpg';
            }       
            $brands_group[$manufacturer->c_title][] = $manufacturer;
        }
        
        $this->onExecuteAfter('getManufacturersListGroupByCountry', array(&$brands_group));
        return $brands_group;
    }
    
    public function getLetters($brands){
        
        $this->onExecuteBefore('getLetters', array(&$brands));
        
        $letters     = new stdClass;
        $letters->en = range('A', 'Z');
        $letters->ru = array('Рђ','Р‘','Р’','Р“','Р”','Р•','РЃ','Р–','Р—','Р','Р™','Рљ','Р›','Рњ','Рќ','Рћ','Рџ','Р ','РЎ','Рў','РЈ','Р¤','РҐ','Р¦','Р§','Р©','РЁ','Р¬','Р«','РЄ','Р­','Р®','РЇ');
        
        $letters_tmp = new stdClass;

        foreach($letters as $key => $lang){
            $i = 0;
            foreach($lang as $letter){
                $letters_tmp->{$key}[$i]         = new stdClass;
                $letters_tmp->{$key}[$i]->letter = $letter;
                $letters_tmp->{$key}[$i]->state  = true;
                
                if(!array_key_exists($letter, $brands)){
                    $letters_tmp->{$key}[$i]->state = false;
                }
                $i++;
            }
        }
        
        $this->onExecuteAfter('getLetters', array(&$letters_tmp));
        return $letters_tmp;
    }
    
    public function groupBrandsByLet($brands){
        $this->onExecuteBefore('groupBrandsByLet', array(&$brands));

        if(!empty($brands)){
            $group_brands = array();
            foreach($brands as $brand){
                $letter = mb_substr($brand->title, 0, 1);
                $group_brands[$letter][] = $brand;
            }
            
            $this->onExecuteAfter('groupBrandsByLet', array(&$group_brands));
            return $group_brands;
        }
        return new stdClass;
    }

    /**
     * KsenMartModelcatalog::getManufacturer()
     * 
     * @return
     */
    public function getManufacturer() {
        $this->onExecuteBefore('getManufacturer');

        if(!empty($this->_manufacturers)){
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
                ->leftjoin('#__ksenmart_files AS f ON m.id=f.owner_id AND f.owner_type='.$this->_db->quote('manufacturer'))
                ->where('m.published=1')
                 ->where('m.id=' . $this->_manufacturers[0])
            ;
            $this->_db->setQuery($query);
            $manufacturer = $this->_db->loadObject();
            if(!empty($manufacturer)){
                $manufacturer->image = KSMedia::resizeImage($manufacturer->filename, $manufacturer->folder, $this->_params->get('thumb_width'), $this->_params->get('thumb_height'));
            }
            
            $this->onExecuteAfter('getManufacturer', array(&$manufacturer));
            return $manufacturer;
        }
        return false;
    }

    /**
     * KsenMartModelcatalog::getManufacturerTitle()
     * 
     * @return
     */
    public function getManufacturerTitle() {
        $this->onExecuteBefore('getManufacturerTitle');

        $config         = KSSystem::getSeoTitlesConfig('manufacturer');
        $shop_name      = $this->_params->get('shop_name', '');
        $path_separator = $this->_params->get('path_separator', ' ');
        $manufacturer   = $this->getManufacturer();
        $title          = array();
        
        if (empty($manufacturer->metatitle)){
            if ($shop_name != ''){
                $title[] = $shop_name;
            }
            if($config){
                foreach ($config as $key => $val) {
                    if ($val->user == 0) {
                        if ($val->active == 1) {
                            if ($key == 'seo-manufacturer') {
                                $title[] = $manufacturer->title;
                            }
                            if ($key == 'seo-country') {
                                $query = $this->_db->getQuery(true);
                                $query
                                    ->select('c.title')
                                    ->from('#__ksenmart_manufacturers as m')
                                    ->leftjoin('#__ksenmart_countries as c on m.country=c.id')
                                    ->where('m.id=' . $manufacturer->id)
                                ;
                                $this->_db->setQuery($query);
                                $country_title = $this->_db->loadResult();
                                if (!empty($country_title)){
                                    $title[] = $country_title;
                                }
                            }
                        }
                    } else{
                        $title[] = $val->title;
                    }
                }
            }
        }
        else{
            $title[] = $manufacturer->metatitle;
        }
        
        $this->onExecuteAfter('getManufacturerTitle', array(&$path_separator, &$title));
        return implode($path_separator, $title);
    }

    /**
     * KsenMartModelcatalog::getChildCats()
     * 
     * @param mixed $catid
     * @return
     */
    public function getChildCats($catid) {
        $this->onExecuteBefore('getChildCats', array(&$catid));

        $return     = array();
        $return1    = array();
        $return[]   = $catid;
        
        $query      = $this->_db->getQuery(true);        
        $query
            ->select('
                c.id,
                c.title,
                c.childs_title,
                c.alias,
                c.content,
                c.introcontent,
                c.metatitle,
                c.metadescription,
                c.metakeywords                                                                
            ')
            ->from('#__ksenmart_categories AS c')
            ->where('c.parent_id='.$this->_db->escape($catid))
            ->order('c.ordering')                        
        ;
        $this->_db->setQuery($query);
        $cats = $this->_db->loadObjectList();
        if (count($cats) > 0) {
            foreach ($cats as $cat) {
                $return1 = $this->getChildCats($cat->id);
                if (count($return1) > 0) {
                    foreach ($return1 as $r1){
                        $return[] = $r1;
                    }
                }
                continue;
            }
        }
        $this->onExecuteAfter('getChildCats', array(&$return));
        return $return;
    }

    /**
     * KsenMartModelcatalog::getCatalogPath()
     * 
     * @return
     */
    public function getCatalogPath() {
        $this->onExecuteBefore('getCatalogPath');

        $path = array();

        if (!empty($this->_categories)) {
            $catid = $this->_categories[0];
            while ((int)$catid != 0) {
                $query = $this->_db->getQuery(true);
                $query
                    ->select('
                        c.id,
                        c.parent_id,
                        c.title,
                        c.alias
                    ')
                    ->from('#__ksenmart_categories AS c')
                    ->where('id='.$this->_db->escape($catid))
                ;
                
                $this->_db->setQuery($query);
                $cat        = $this->_db->loadObject();
                $cat->link  = JRoute::_('index.php?option=com_ksenmart&view=catalog&categories[]=' . $cat->id);
                $path[]     = array(
                    'title' => $cat->title, 
                    'link'  => $cat->link
                );
                $catid      = $cat->parent_id;
            }
            $path = array_reverse($path);
        } elseif (!empty($this->_manufacturers)) {
            $query = $this->_db->getQuery(true);
            $query
                ->select('c.title')
                ->from('#__ksenmart_manufacturers AS c')
                ->where('id='.$this->_db->escape($this->_manufacturers[0]))
            ;
            $this->_db->setQuery($query);
            $title  = $this->_db->loadResult();
            $path[] = array(
                'title' => $title, 
                'link'  => ''
            );
        } elseif ($this->_countries) {
            $query = $this->_db->getQuery(true);
            $query
                ->select('c.title')
                ->from('#__ksenmart_countries AS c')
                ->where('c.id='.$this->_db->escape($this->_countries[0]))
            ;
            $this->_db->setQuery($query);
            $title  = $this->_db->loadResult();
            $path[] = array(
                'title' => $title, 
                'link'  => ''
            );
        } else {
            $path[] = array(
                'title' => JText::_('KSM_CATALOG_TITLE'), 
                'link'  => ''
            );
        }
        
        $this->onExecuteAfter('getCatalogPath', array(&$path));
        return $path;
    }


    /**
     * KsenMartModelcatalog::getCatalogTitle()
     * 
     * @return
     */
    public function getCatalogTitle() {
        $this->onExecuteBefore('getCatalogTitle');

        $shop_name      = $this->_params->get('shop_name', '');
        $path_separator = $this->_params->get('path_separator', ' ');
        $title[]        = JText::_('KSM_CATALOG_TITLE');
        
        if (!empty($shop_name)){
            $title[] = $shop_name;
        }
        
        if(!empty($this->_categories)){
            $categories = KSSystem::getTableByIds($this->_categories, 'categories', array('t.id', 't.title'));
            foreach ($categories as $category) {
                if (!empty($category->title)){
                    $title[] = $category->title;
                }
            }
        }
        
        if(!empty($this->_countries)){
            $countries = KSSystem::getTableByIds($this->_countries, 'countries', array('t.id', 't.title'));
            foreach ($countries as $country) {
                if (!empty($country->title)){
                    $title[] = $country->title;
                }
            }
        }
    
        if(!empty($this->_manufacturers)){
            $manufactureries = KSSystem::getTableByIds($this->_manufacturers, 'manufacturers', array('t.id', 't.title'));
            foreach ($manufactureries as $manufacturer) {
                if (!empty($manufacturer->title)){
                    $title[] = $manufacturer->title;
                }
            }
        }

        if(!empty($this->_properties)){
            $properties = KSSystem::getTableByIds($this->_properties, 'property_values', array('t.id', 't.title', 't.property_id'), false);
            if(!empty($properties)){
                $props  = array();
                foreach ($properties as $property) {
                    if (!isset($props[$property->property_id])){
                        $props[$property->property_id] = array();
                    }
                    if (!empty($property->title)){
                        $props[$property->property_id][] = $property->title;
                    }
                }
                
                $property = KSSystem::getTableByIds($props, 'properties', array('t.id', 't.title'), true, true);
                foreach ($props as $key => $property_values) {
                    if (!empty($property->title)){
                        $title[] = $property->title . '=' . implode('+', $property_values);
                    }
                }
            }
        }
        
        $this->onExecuteAfter('getCatalogTitle', array(&$path_separator, &$title));
        return implode($path_separator, $title);
    }

    /**
     * KsenMartModelcatalog::setCategoryMetaData()
     * 
     * @return
     */
    public function setCategoryMetaData() {
        $this->onExecuteBefore('setCategoryMetaData');

        $document           = JFactory::getDocument();
        $metatitle          = '';
        $metadescription    = '';
        $metakeywords       = '';
        $category           = $this->getCategory();
        $config             = KSSystem::getSeoTitlesConfig('category', 'meta');
        
        if (empty($category->metatitle)){
            $metatitle = $category->title;
        }else{
            $metatitle = $category->metatitle;
        }
        if(empty($category->metadescription)) {
            if ($config->description->flag == 1) {
                if ($config->description->type == 'seo-type-mini-description'){
                    $metadescription = strip_tags($category->introcontent);
                }elseif($config->description->type == 'seo-type-description'){
                    $metadescription = strip_tags($category->content);
                }
                $metadescription = mb_substr($metadescription, 0, $config->description->symbols);
            }
        } else {
            $metadescription = $category->metadescription;
        }
        if (empty($category->metakeywords)) {
            if ($config->keywords->flag == 1) {
                if ($config->keywords->type == 'seo-type-properties') {
                    $query = $this->_db->getQuery(true);
                    $query
                        ->select('p.title')
                        ->from('#__ksenmart_product_categories_properties as pcp')
                        ->leftjoin('#__ksenmart_properties as p on p.id=pcp.property_id')
                        ->where('pcp.category_id=' . $this->_db->q($category->id))
                    ;
                    $this->_db->setQuery($query);
                    $properties = $this->_db->loadObjectList();
                    $titles     = array();
                    foreach ($properties as $property){
                        $titles[] = $property->title;
                    }
                    $metakeywords = implode(',', $titles);
                }elseif($config->keywords->type == 'seo-type-title'){
                    $metakeywords = strip_tags($category->title);
                }
            }
        } else{
            $metakeywords = $category->metakeywords;
        }
        if (!empty($metatitle)) {
            //$document->setMetaData('title', $metatitle);
        }
        if (!empty($metadescription)){
            $document->setMetaData('description', $metadescription);
        }
        if (!empty($metakeywords)){
            $document->setMetaData('keywords', $metakeywords);
        }

        $this->onExecuteAfter('setCategoryMetaData', array(&$this));
        return true;
    }

    /**
     * KsenMartModelcatalog::setManufacturerMetaData()
     * 
     * @return
     */
    public function setManufacturerMetaData() {
        $this->onExecuteBefore('setManufacturerMetaData');

        $document           = JFactory::getDocument();
        $metatitle          = '';
        $metadescription    = '';
        $metakeywords       = '';
        $manufacturer       = $this->getManufacturer();
        $config             = KSSystem::getSeoTitlesConfig('manufacturer', 'meta');

        if (empty($manufacturer->metatitle)){
            $metatitle = $manufacturer->title;
        }else{
            $metatitle = $manufacturer->metatitle;
        }
        if (empty($manufacturer->metadescription)) {
            if ($config->description->flag == 1) {
                if ($config->description->type == 'seo-type-mini-description') {
                    $metadescription = strip_tags($manufacturer->introcontent);
                }elseif ($config->description->type == 'seo-type-description'){
                    $metadescription = strip_tags($manufacturer->content);
                }
                $metadescription = mb_substr($metadescription, 0, $config->description->symbols);
            }
        } else {
            $metadescription = $manufacturer->metadescription;
        }
        if (empty($manufacturer->metakeywords)) {
            if ($config->keywords->flag == 1) {
                if ($config->keywords->type == 'seo-type-country') {
                    $countries = KSSystem::getTableByIds(array($manufacturer->country), 'countries', array('t.title'));
                    if (!empty($countries[0]->title)){
                        $metakeywords = $countries[0]->title;
                    }
                } elseif ($config->keywords->type == 'seo-type-title'){
                    $metakeywords = strip_tags($manufacturer->title);
                }
            }
        } else {
            $metakeywords = $manufacturer->metakeywords;
        }
        if (!empty($metatitle)) {
            $document->setMetaData('title', $metatitle);
        }
        if (!empty($metadescription)){
            $document->setMetaData('description', $metadescription);
        }
        if (!empty($metakeywords)){
            $document->setMetaData('keywords', $metakeywords);
        }

        $this->onExecuteAfter('setManufacturerMetaData', array(&$this));
        return true;
    }

    /**
     * KsenMartModelcatalog::setCountryMetaData()
     * 
     * @return
     */
    public function setCountryMetaData() {
        $this->onExecuteBefore('setCountryMetaData');

        $document           = JFactory::getDocument();
        $metatitle          = '';
        $metadescription    = '';
        $metakeywords       = '';
        $country            = $this->getCountry();
        $config             = KSSystem::getSeoTitlesConfig('country', 'meta');
        
        if (empty($country->metatitle)){
            $metatitle = $country->title;
        }else {
            $metatitle = $country->metatitle;
        }
        if (empty($country->metadescription)) {
            if ($config->description->flag == 1) {
                if ($config->description->type == 'seo-type-mini-description'){
                    $metadescription = strip_tags($country->introcontent);
                }elseif ($config->description->type == 'seo-type-description'){
                    $metadescription = strip_tags($country->content);
                }
                $metadescription = mb_substr($metadescription, 0, $config->description->symbols);
            }
        } else {
            $metadescription = $country->metadescription;
        }
        if ($country->metakeywords == '') {
            if ($config->keywords->flag == 1) {
                if ($config->keywords->type == 'seo-type-title'){
                    $metakeywords = strip_tags($country->title);
                }
            }
        } else {
            $metakeywords = $country->metakeywords;
        }
        if (!empty($metatitle)) {
            $document->setMetaData('title', $metatitle);
        }
        if (!empty($metadescription)){
            $document->setMetaData('description', $metadescription);
        }
        if (!empty($metakeywords)){
            $document->setMetaData('keywords', $metakeywords);
        }
        
        $this->onExecuteAfter('setCountryMetaData', array(&$this));
        return true;
    }

    /**
     * KsenMartModelcatalog::getSortLinks()
     * 
     * @return
     */
    public function getSortLinks() {
        $this->onExecuteBefore('getSortLinks');

        $order_type = $this->getState('list.ordering');
        $order_dir = $this->getState('list.direction');
        $params_get = '';
        if (!empty($this->_categories)){
            foreach ($this->_categories as $category){
                $params_get .= '&categories[]=' . $category;
            }
        }
        if (!empty($this->_manufacturers)){
            foreach ($this->_manufacturers as $manufacturer){
                $params_get .= '&manufacturers[]=' . $manufacturer;
            }
        }
        if (!empty($this->_properties)){
            foreach ($this->_properties as $property){
                $params_get .= '&properties[]=' . $property;
            }
        }
        if (!empty($this->_countries)){
            foreach ($this->_countries as $country){
                $params_get .= '&countries[]=' . $country;
            }
        }
        if (!empty($this->_title)){
            $params_get .= '&title=' . $this->_title;
        }
        if (!empty($this->_price_less)){
            $params_get .= '&price_less=' . $this->_price_less;
        }
        if (!empty($this->_price_more)){
            $params_get .= '&price_more=' . $this->_price_more;
        }
        if (!empty($this->_new)){
            $params_get .= '&new=1';
        }
        if (!empty($this->_promotion)){
            $params_get .= '&promotion=1';
        }
        if (!empty($this->_hot)){
            $params_get .= '&hot=1';
        }
        if (!empty($this->_recommendation)){
            $params_get .= '&recommendation=1';
        }
        $sort = array(
            array(
                'order_type' => 'price', 
                'name' => JText::_('KSM_CATALOG_SORT_BY_PRICE_TEXT')
            ), 
            array(
                'order_type' => 'hits', 
                'name' => JText::_('KSM_CATALOG_SORT_BY_HITS_TEXT')
            )
        );
        $sort_links = array();
        foreach ($sort as $s) {
            $sort_links[$s['order_type']]['asc_link'] = '<a type="' . $s['order_type'] . '" dir="asc" href="' . JRoute::_('index.php?option=com_ksenmart&view=catalog&order_type=' . $s['order_type'] . '&order_dir=asc' . $params_get) . '"><img src="' . JURI::base() . 'components/com_ksenmart/images/bottomb.png"></a>';
            $sort_links[$s['order_type']]['desc_link'] = '<a type="' . $s['order_type'] . '" dir="desc" href="' . JRoute::_('index.php?option=com_ksenmart&view=catalog&order_type=' . $s['order_type'] . '&order_dir=desc' . $params_get) . '"><img src="' . JURI::base() . 'components/com_ksenmart/images/topb.png"></a>';
            if ($s['order_type'] == $order_type) {
                $class = "active";
                if ($order_dir == 'asc') {
                    $sort_links[$s['order_type']]['asc_link'] = '<a type="' . $s['order_type'] . '" dir="asc" class="' . $class . '" href="' . JRoute::_('index.php?option=com_ksenmart&view=catalog&order_type=' . $s['order_type'] . '&order_dir=asc' . $params_get) . '"><img src="' . JURI::base() . 'components/com_ksenmart/images/bottomba.png"></a>';
                    $class .= " down";
                    $dir = 'desc';
                } else {
                    $sort_links[$s['order_type']]['desc_link'] = '<a type="' . $s['order_type'] . '" dir="desc" class="' . $class . '" href="' . JRoute::_('index.php?option=com_ksenmart&view=catalog&order_type=' . $s['order_type'] . '&order_dir=desc' . $params_get) . '"><img src="' . JURI::base() . 'components/com_ksenmart/images/topba.png"></a>';
                    $dir = 'asc';
                    $class .= " up";
                }
            } else {
                $dir = 'asc';
                $class = "";
            }
            $sort_links[$s['order_type']]['link'] = '<a type="' . $s['order_type'] . '" dir="' . $dir . '" class="' . $class . '" href="' . JRoute::_('index.php?option=com_ksenmart&view=catalog&order_type=' . $s['order_type'] . '&order_dir=' . $dir . $params_get) . '">' . $s['name'] . '</a>';
        }
        
        $this->onExecuteAfter('getSortLinks', array(&$sort_links));
        return $sort_links;
    }

    /**
     * KsenMartModelcatalog::sortOnPriceAsc()
     * 
     * @param mixed $a
     * @param mixed $b
     * @return
     */
    function sortOnPriceAsc($a, $b) {
        $this->onExecuteBefore('sortOnPriceAsc', array(&$a, &$b));

        if ($a->val_price_wou == $b->val_price_wou) {
            return 0;
        }
        
        $result = ($a->val_price_wou < $b->val_price_wou) ? -1 : 1;
        $this->onExecuteAfter('sortOnPriceAsc', array(&$result));
        return $result;
    }

    /**
     * KsenMartModelcatalog::sortOnPriceDesc()
     * 
     * @param mixed $a
     * @param mixed $b
     * @return
     */
    function sortOnPriceDesc($a, $b) {
        $this->onExecuteBefore('sortOnPriceDesc', array(&$a, &$b));

        if ($a->val_price_wou == $b->val_price_wou) {
            return 0;
        }
        
        $result = ($a->val_price_wou > $b->val_price_wou) ? -1 : 1;
        $this->onExecuteAfter('sortOnPriceDesc', array(&$result));
        return $result;
    }

    /**
     * KsenMartModelcatalog::getStart()
     * 
     * @return
     */
    public function getStart() {
        return $this->getState('list.start');
    }

    /**
     * KsenMartModelcatalog::setLayoutCatalog()
     * 
     * @param mixed $layout
     * @return
     */
    public function setLayoutCatalog($layout) {
        $this->onExecuteBefore('setLayoutCatalog', array(&$layout));

        if (!empty($layout)) {
            $user = KSUsers::getUser();
            $user_update = new stdClass();
            $user->settings->catalog_layout = $layout;

            $user_update->id = $user->id;
            $user_update->settings = json_encode($user->settings);

            try {
                $result = $this->_db->updateObject('#__ksen_users', $user_update, 'id');
                $this->onExecuteAfter('setLayoutCatalog', array(&$result));
                return true;
            }
            catch (exception $e) {}
        }
        return false;
    }
}