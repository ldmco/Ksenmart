<?php defined('_JEXEC') or die;

class modKsenmartSearchHelper {
    
    public $price_min = null;
    public $price_max = null;
    public $manufacturers = array();
    public $countries = array();
    public $properties = array();
    public $categories = array();
    
    public function init($mod_params) {
        $params = JComponentHelper::getParams('com_ksenmart');
        $app = JFactory::getApplication();
        $db = JFactory::getDBO();
        $jinput = $app->input;
        $option = $jinput->get('option', null);
        $view = $jinput->get('view', null);
        $categories = $jinput->get('categories', array() , 'array');
        if (!count($categories) && $option == 'com_ksenmart' && $view == 'product') {
            $product_id = $jinput->get('id', 0, 'int');
            $default_category = $this->getProductCategory($product_id);
            if (!empty($default_category)) $categories[] = $default_category;
        }
        $this->categories = $categories;
        $session_manufacturers = $jinput->get('manufacturers', array() , 'array');
        $session_countries = $jinput->get('countries', array() , 'array');
        $session_properties = $jinput->get('properties', array() , 'array');
        
        $cats = array();
        $manufacturers = array();
        $ids = array();
        foreach ($categories as $cat) {
            $tmp = $this->getChildCats($cat);
            $cats = array_merge($cats, $tmp);
        }
        $where = array(
            'p.published=1'
        );
        if ($params->get('show_out_stock') != 1) $where[] = "(p.in_stock>0)";
        $sql = $db->getQuery(true);
        $sql->select('min(p.price/c.rate)')->from('#__ksenmart_products as p');
        if (count($cats) > 0) {
            $sql->join("INNER", "#__ksenmart_products_categories as pc on p.id=pc.product_id");
            $where[] = "(pc.category_id in (" . implode(',', $cats) . "))";
        }
        $sql->join("LEFT", "#__ksenmart_currencies as c on p.price_type=c.id");
        $sql->where(implode(' and ', $where));
        $db->setQuery($sql);
        $this->price_min = $db->loadResult();
        
        $where = array(
            1
        );
        if ($params->get('show_out_stock') != 1) $where[] = "(p.published=1)";
        $sql = $db->getQuery(true);
        $sql->select('max(p.price/c.rate)')->from('#__ksenmart_products as p');
        if (count($cats) > 0) {
            $sql->join("INNER", "#__ksenmart_products_categories as pc on p.id=pc.product_id");
            $where[] = "(pc.category_id in (" . implode(',', $cats) . "))";
        }
        $sql->join("LEFT", "#__ksenmart_currencies as c on p.price_type=c.id");
        $sql->where(implode(' and ', $where));
        $db->setQuery($sql);
        $this->price_max = $db->loadResult();
        
        $where = array();
        if ($params->get('show_out_stock') != 1) $where[] = "(p.published=1)";
        $where[] = "(m.published=1)";
        $sql = $db->getQuery(true);
        $sql->select('m.*')->from('#__ksenmart_manufacturers as m');
        $sql->join("INNER", "#__ksenmart_products as p on p.manufacturer=m.id");
        if (count($cats) > 0) {
            $sql->join("INNER", "#__ksenmart_products_categories as pc on p.id=pc.product_id");
            $where[] = "(pc.category_id in (" . implode(',', $cats) . "))";
        }
        $sql->where(implode(' and ', $where));
        $sql->group('m.id');
        $db->setQuery($sql);
        $this->manufacturers = $db->loadObjectList();
        foreach ($this->manufacturers as & $manufacturer) {
            $manufacturer->selected = false;
            if (in_array($manufacturer->id, $session_manufacturers)) $manufacturer->selected = true;
        }
        
        $where = array();
        if ($params->get('show_out_stock') != 1) $where[] = "(p.published=1)";
        $where[] = "(m.published=1)";
        $sql = $db->getQuery(true);
        $sql->select('c.*')->from('#__ksenmart_countries as c');
        $sql->join("INNER", "#__ksenmart_manufacturers as m on m.country=c.id");
        $sql->join("INNER", "#__ksenmart_products as p on p.manufacturer=m.id");
        if (count($cats) > 0) {
            $sql->join("INNER", "#__ksenmart_products_categories as pc on p.id=pc.product_id");
            $where[] = "(pc.category_id in (" . implode(',', $cats) . "))";
        }
        $sql->where(implode(' and ', $where));
        $sql->group('c.id');
        $db->setQuery($sql);
        $this->countries = $db->loadObjectList();
        foreach ($this->countries as & $country) {
            $country->selected = false;
            if (in_array($country->id, $session_countries)) $country->selected = true;
        }
        
        $properties = $mod_params->toArray();
        $properties = $properties['properties'];
        $this->properties = self::getProperties();
        foreach ($this->properties as $key => & $property) {
            if (!empty($properties)) {
                if (!isset($properties[$property->property_id]) || $properties[$property->property_id]['view'] == 'none') {
                    unset($this->properties[$key]);
                    continue;
                } else {
                    $this->properties[$key]->view = $properties[$property->property_id]['view'];
                    $this->properties[$key]->display = $properties[$property->property_id]['display'];
                }
            }
            if (!empty($property->values)) {
                foreach ($property->values as & $value) {
                    $value->selected = false;
                    if (in_array($value->id, $session_properties)) {
                        $value->selected = true;
                    }
                }
            }
        }
    }
    
    private function getChildCats($catid) {
        $db = JFactory::getDBO();
        $return = array();
        $return1 = array();
        $return[] = $catid;
        $sql = $db->getQuery(true);
        $sql->select('id')->from('#__ksenmart_categories')->where('parent_id=' . $catid);
        $db->setQuery($sql);
        $cats = $db->loadObjectList();
        if (count($cats) > 0) {
            foreach ($cats as $cat) {
                $return1 = $this->getChildCats($cat->id);
                if (count($return1) > 0) {
                    foreach ($return1 as $r1) $return[] = $r1;
                }
            }
        }
        return $return;
    }
    
    public static function getProperties($pid = 0, $prid = 0, $val_id = 0, $by = 'ppv.product_id', $by_sort = 0) {
        
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('
                ppv.id,
                ppv.property_id,
                ppv.value_id,
                ppv.text,
                p.edit_price,
                p.title,
                p.type,
                p.view,
                p.default,
                p.prefix,
                p.suffix
            ')->from('#__ksenmart_properties AS p')->leftjoin('#__ksenmart_product_properties_values AS ppv ON p.id=ppv.property_id');
        if ($pid) {
            $query->where('ppv.product_id=' . $pid);
        }
        
        if ($by_sort) {
            switch ($by) {
                case 'ppv.id':
                    $query->where('ppv.id=' . $by_sort);
                break;
                default:
                    $query->where('ppv.product_id=' . $pid);
                break;
            }
        }
        $query->group('ppv.property_id');
        
        if ($prid) {
            $query->where('ppv.property_id=' . $prid);
        }
        
        $query->order('p.ordering');
        $db->setQuery($query);
        $properties = $db->loadObjectList();
        $properties = self::getPropertiesChild($pid, $properties, $val_id);
        return $properties;
    }
    
    public static function getPropertiesChild($pid, $properties, $val_id) {
        if (!empty($properties)) {
            $where = array();
            $properties_l = count($properties) - 1;
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);
            
            $query->select('
                    pv.id,
                    pv.title,
                    pv.image,
                    ppv.property_id,
                    ppv.price,
                    ppv.text
                ')->from('#__ksenmart_property_values AS pv')->leftjoin('#__ksenmart_product_properties_values AS ppv ON ppv.value_id=pv.id');
            
            $query->order('pv.ordering');
            $query->group('pv.id');
            
            if ($pid) {
                $query->where('ppv.product_id=' . $pid);
            }
            
            if ($val_id) {
                $query->where('pv.id=' . $val_id);
                //$query->where('ppv.value_id=' . $val_id);
                
                
            }
            
            $db->setQuery($query);
            $values = $db->loadObjectList();
            
            $values_l = count($values);
            
            for ($i = 0;$i < $values_l;$i++) {
                for ($j = 0;$j <= $properties_l;$j++) {
                    if ($values[$i]->property_id == $properties[$j]->property_id) {
                        $properties[$j]->values[$values[$i]->id] = $values[$i];
                    }
                    continue;
                }
            }
            return $properties;
        }
        return $properties;
    }
    
    public function getDefaultCategory($product_id) {
        $db = JFactory::getDBO();
        $sql = $db->getQuery(true);
        $sql->select('category_id')->from('#__ksenmart_products_categories AS pc')->where('pc.product_id=' . $db->escape($product_id))->where('pc.is_default=1');
        $db->setQuery($sql);
        $category = $db->loadResult();
        
        return $category;
    }
    
    public function getProductCategories($product_id) {
        $db = JFactory::getDBO();
        $sql = $db->getQuery(true);
        $sql->select('pc.category_id')->from('#__ksenmart_products_categories AS pc')->where('pc.product_id=' . $db->escape($product_id));
        $db->setQuery($sql);
        $categories = $db->loadObjectList();
        
        return $categories;
    }
    
    public function getProductCategory($product_id) {
        $final_categories = array();
        $parent_ids = array();
        $default_category = $this->getDefaultCategory($product_id);
        $product_categories = $this->getProductCategories($product_id);
        
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