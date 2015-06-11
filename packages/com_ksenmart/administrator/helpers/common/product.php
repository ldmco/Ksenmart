<?php 

/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

KSSystem::import('helpers.corehelper');
class KSMProducts extends KSCoreHelper {
    
    private $ext_name_com = 'com_ksenmart';
    private $ext_prefix   = 'KSM';
    private $helper_name  = 'Products';
    
    private static function setProductMainImageToQuery($query) {
        $db = JFactory::getDBO();
        $query
            ->select($db->qn(array(
                'f.filename',
                'f.folder',
                'f.params',
            )))
            ->leftjoin($db->qn('#__ksenmart_files', 'f') . ' ON ' . 
                $db->qn('owner_id') . '=' . $db->qn('p.id') . ' AND ' . 
                $db->qn('f.owner_type') . '=' . $db->q('product') . ' AND ' . 
                $db->qn('f.media_type') . '=' . $db->q('image'))
        ;
        return $query;
    }
    
    private static $_property_values       = array();
    private static $_images                = array();
    private static $_images_products_files = array();
    
    public static function getProduct($id) {

        self::onExecuteBefore(array(&$id));

        global $ext_name;
        $old_ext_name = $ext_name;
        $ext_name     = 'ksenmart';
		
        $db     = JFactory::getDBO();
        $params = JComponentHelper::getParams('com_ksenmart');
        $query  = $db->getQuery(true);
        $query  = KSMProducts::setProductMainImageToQuery($query);
        $query
            ->select('`p`.*')
            ->select($db->qn(array(
                'm.title',
                'u.form1',
            ), array(
                'manufacturer_title',
                'unit',
            )))
            ->from($db->qn('#__ksenmart_products', 'p'))
            ->leftjoin($db->qn('#__ksenmart_manufacturers', 'm') . ' ON ' . $db->qn('p.manufacturer') . '=' . $db->qn('m.id'))
            ->leftjoin($db->qn('#__ksenmart_product_units', 'u') . ' ON ' . $db->qn('p.product_unit') . '=' . $db->qn('u.id'))
            ->where($db->qn('p.id') . '=' . $db->q($id))
            ->group($db->qn('p.id'))
        ;
        $db->setQuery($query);
        $row = $db->loadObject();
        
        if ($row && !empty($row)) {
            $row->properties = KSMProducts::getProperties($id);
            
            if (empty($row->folder)) {
                $row->folder = 'products';
            }
            if (empty($row->filename)) {
                $row->filename = 'no.jpg';
            }
            
            if ($row->product_packaging == 0) {
                $row->product_packaging = 1;
            }
            $row->product_packaging = rtrim(rtrim($row->product_packaging, '0'), '.');

            if($row->parent_id > 0) {
                $row->parent = self::getProduct($row->parent_id);
            }
            
            self::productPricesTransform($row);
            $row->link           = JRoute::_('index.php?option=com_ksenmart&view=product&id=' . $row->id . ':' . $row->alias . '&Itemid=' . KSSystem::getShopItemid());
            $row->mini_small_img = KSMedia::resizeImage($row->filename, $row->folder, $params->get('mini_thumb_width'), $params->get('mini_thumb_height'), json_decode($row->params, true));
            $row->small_img      = KSMedia::resizeImage($row->filename, $row->folder, $params->get('thumb_width'), $params->get('thumb_height'), json_decode($row->params, true));
            $row->img            = KSMedia::resizeImage($row->filename, $row->folder, $params->get('middle_width'), $params->get('middle_height'), json_decode($row->params, true));
            $row->img_link       = KSMedia::resizeImage($row->filename, $row->folder, $params->get('full_width', 900), $params->get('full_height', 900));
            $row->rate           = KSMProducts::getProductRate($row->id);
            $row->add_link_cart  = KSFunctions::getAddToCartLink($row->price, 2);

            $row->tags = new JHelperTags;
            $row->tags->getItemTags('com_ksenmart.product', $row->id);
        }
        $ext_name = $old_ext_name;
        
        self::onExecuteAfter(array(&$row));
        return $row;
    }

    public static function productPricesTransform(&$product){
        $product->price = KSMPrice::getPriceInCurrentCurrency($product->price, $product->price_type);
        $product->val_price = KSMPrice::showPriceWithTransform($product->price);
        $product->old_price = KSMPrice::getPriceInCurrentCurrency($product->old_price, $product->price_type);
        $product->val_old_price = KSMPrice::showPriceWithTransform($product->old_price);
        $product->val_diff_price_wou = $product->old_price - $product->price;
        $product->val_diff_price     = KSMPrice::showPriceWithTransform($product->val_diff_price_wou);

        return $product;
    }
    
    public static function getLinks($pid) {
        if ($pid > 0) {
            $db = JFactory::getDBO();
			
            $query = $db->getQuery(true);
            $query
                ->select('parent_id')
                ->from('#__ksenmart_products')
                ->where('id=' . $db->q($pid))
            ;
            $db->setQuery($query);
            $parent_id = $db->loadResult();		

			$query = $db->getQuery(true);
			$query
				->select('id')
				->from('#__ksenmart_products')
				->where('parent_id=' . $db->q($parent_id))
			;
			$db->setQuery($query);
			$ids = $db->loadColumn();						

            $cid = self::getProductCategory($pid);

            $query = $db->getQuery(true);
            $query
                ->select('product_id')
                ->from('#__ksenmart_products_categories')
                ->where('product_id<' . $db->q($pid))
                ->where('product_id in ('.implode(',', $ids).')')
                ->order('product_id DESC')
            ;
			if (!empty($cid))
				$query->where('category_id=' . $db->q($cid));

            $db->setQuery($query, 0, 1);
            $prev_id = $db->loadResult();

            if (empty($prev_id)) {
                $query = $db->getQuery(true);
                $query
                    ->select('MAX(product_id)')
                    ->from('#__ksenmart_products_categories')
					->where('product_id in ('.implode(',', $ids).')')
                ;
				if (!empty($cid))
					$query->where('category_id=' . $db->q($cid));
 			    $db->setQuery($query, 0, 1);
                $prev_id = $db->loadResult();
            }
			if (empty($prev_id))
				$prev_id = $pid;

            $query = $db->getQuery(true);
            $query
                ->select('product_id')
                ->from('#__ksenmart_products_categories')
                ->where('product_id>' . $db->q($pid))
				->where('product_id in ('.implode(',', $ids).')')
                ->order('product_id ASC')
            ;
			if (!empty($cid))
			   $query->where('category_id=' . $db->q($cid));
			$db->setQuery($query, 0, 1);
            $next_id = $db->loadResult();

            if (empty($next_id)) {
                $query = $db->getQuery(true);
                $query
                    ->select('MIN(product_id)')
                    ->from('#__ksenmart_products_categories')
					->where('product_id in ('.implode(',', $ids).')')
                ;
				if (!empty($cid))
					$query->where('category_id=' . $db->q($cid));				
                $db->setQuery($query, 0, 1);
                $next_id = $db->loadResult();
            }
			if (empty($next_id))
				$next_id = $pid;
				
            $prev_link = self::generateProductLink($prev_id);
            $next_link = self::generateProductLink($next_id);
            
            return array($prev_link, $next_link);
        }
        return false;
    }
    
    public static function generateProductLink($pid, $alias = '') {
        if (!empty($pid) && $pid > 0) {
            return JRoute::_('index.php?option=com_ksenmart&view=product&id=' . $pid . ':' . $alias . '&Itemid=' . KSSystem::getShopItemid());
        }
        return null;
    }
    
    public static function getProductPrices($pid) {
        if (!empty($pid) && $pid > 0) {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);
            
            $query->select('
                    p.id,
                    p.price,
                    p.old_price,
                    p.price_type
                ')->from('#__ksenmart_products AS p')->where('p.id=' . $db->escape($pid));
            
            $db->setQuery($query);
            return $db->loadObject();
        }
        return false;
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
        $query->where('p.published=1')->group('ppv.property_id');
        
        if ($prid) {
            $query->where('ppv.property_id=' . $prid);
        }
        
        $query->order('p.ordering');
        $db->setQuery($query);
        $properties = $db->loadObjectList();
        $properties = KSMProducts::getPropertiesChild($pid, $properties, $val_id);
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
    
    public static function getProductRate($id) {
        $db = JFactory::getDBO();
        $rate = new stdClass();
        $rate->rate = 0;
        $rate->count = 0;
        $query = $db->getQuery(true);
        $query->select('c.rate')->from('#__ksenmart_comments AS c')->where('c.product_id=' . $db->escape($id));
        $db->setQuery($query);
        $comments = $db->loadObjectList();
        $rate->count = count($comments);
        if (!empty($comments)) {
            foreach ($comments as $comment) {
                $rate->rate+= $comment->rate;
            }
            $rate->rate = $rate->rate / $rate->count;
        }
        return $rate;
    }
    
    public static function getProductManufacturer($id) {
        $params = JComponentHelper::getParams('com_ksenmart');
        $db     = JFactory::getDbo();
        $query  = $db->getQuery(true);

        $query
            ->select($db->qn(array(
                'm.id',
                'm.title',
                'm.alias',
                'm.content',
                'm.introcontent',
                'm.country',
                'm.ordering',
                'm.metatitle',
                'm.metadescription',
                'm.metakeywords',
                'f.filename',
                'f.folder',
                'f.params',
            )))
            ->from($db->qn('#__ksenmart_manufacturers', 'm'))
            ->leftjoin($db->qn('#__ksenmart_files', 'f') . ' ON ' . $db->qn('m.id') . '=' . $db->qn('f.owner_id') . 'AND' . $db->qn('f.owner_type') . '=' .  $db->q('manufacturer'))
            ->where($db->qn('m.id') . '=' . $db->q($id))
            ->where($db->qn('m.published') . '=' . $db->q('1'))
        ;

        $db->setQuery($query);
        $manufacturer = $db->loadObject();
        
        if (count($manufacturer) > 0) {
            
            $manufacturer->img = KSMedia::resizeImage($manufacturer->filename, $manufacturer->folder, $params->get('manufacturer_width', 240), $params->get('manufacturer_height', 120), json_decode($manufacturer->params, true));

            unset($manufacturer->filename);
            unset($manufacturer->folder);
            unset($manufacturer->params);
            
            $query = $db->getQuery(true);
            $query
                ->select('*')
                ->from($db->qn('#__ksenmart_countries', 'c'))
                ->where($db->qn('id') . '=' . $db->q($manufacturer->country));
            ;
            $db->setQuery($query);
            $manufacturer->country = $db->loadObject();
        }
        return $manufacturer;
    }
    
    public static function incProductHit($id) {
        $db = JFactory::getDBO();
        $query = "update #__ksenmart_products set hits=hits+1 where id='$id'";
        $db->setQuery($query);
        $db->query();
    }
    
    public static function getPriceWithProperties($product_id, $properties = array(), $price = null) {
        $db = JFactory::getDBO();
        if (empty($price)) {
            $product = KSSystem::loadDbItem($product_id, 'products');
            $price = KSMPrice::getPriceInCurrentCurrency($product->price, $product->price_type);
        }
        foreach ($properties as $property_id => $values) {
            $query = $db->getQuery(true);
            $query->select('edit_price')->from('#__ksenmart_properties');
            $query->where('id=' . $property_id);
            $db->setQuery($query);
            $edit_price = $db->loadResult();
            if ($edit_price == 1) {
                foreach ($values as $value_id) {
                    $query = $db->getQuery(true);
                    $query->select('price')->from('#__ksenmart_product_properties_values');
                    $query->where('property_id=' . $property_id)->where('value_id=' . $value_id)->where('product_id=' . $product_id);
                    $db->setQuery($query);
                    $under_price = $db->loadResult();
                    if ($under_price && !empty($under_price)) {
                        $under_price_act = substr($under_price, 0, 1);
                        switch ($under_price_act) {
                            case '+':
                                $price+= substr($under_price, 1, strlen($under_price) - 1);
                            break;
                            case '-':
                                $price-= substr($under_price, 1, strlen($under_price) - 1);
                            break;
                            case '/':
                                $price = $price / substr($under_price, 1, strlen($under_price) - 1);
                            break;
                            case '*':
                                $price = $price * substr($under_price, 1, strlen($under_price) - 1);
                            break;
                            default:
                                $price+= $under_price;
                        }
                    }
                }
            }
        }
        return $price;
    }
    
    public static function getSetRelated($pid, $info_generate = false) {
        $rows = new stdClass;
        if (!empty($pid) && $pid > 0) {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->select('
                    pr.id,
                    pr.product_id,
                    pr.relative_id,
                    pr.relation_type
                ')->from('#__ksenmart_products_relations AS pr')->where('pr.relation_type="set"')->where('pr.product_id=' . $db->escape($pid));
            $db->setQuery($query);
            $rows = $db->loadObjectList();
            if (!empty($rows)) {
                if ($info_generate) {
                    foreach ($rows as & $row) {
                        $row = KSMProducts::getProduct($row->relative_id);
                    }
                }
            }
        }
        return $rows;
    }
    
    public static function getRelated($pid) {
        $rows = new stdClass;
        if (!empty($pid) && $pid > 0) {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->select('
                    pr.id,
                    pr.product_id,
                    pr.relative_id,
                    pr.relation_type
                ')->from('#__ksenmart_products_relations AS pr')->where('pr.relation_type="relation"')->where('pr.product_id=' . $db->escape($pid));
            
            $db->setQuery($query);
            $rows = $db->loadObjectList();
            if (!empty($rows)) {
                foreach ($rows as & $row) {
                    $row = KSMProducts::getProduct($row->relative_id);
                }
            }
        }
        return $rows;
    }
    
    public static function getSetRelatedIds($pid) {
        $rows = array();
        if (!empty($pid) && $pid > 0) {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->select('
                    pr.id
                ')->from('#__ksenmart_products_relations AS pr')->where('pr.relation_type="set"')->where('pr.product_id=' . $db->escape($pid));
            $db->setQuery($query);
            $rows = $db->loadColumn();
        }
        return $rows;
    }
	
    public static function getDefaultCategory($product_id) {
		$db = JFactory::getDBO();
        $sql = $db->getQuery(true);
        $sql->select('category_id')->from('#__ksenmart_products_categories AS pc')->where('pc.product_id=' . $db->escape($product_id))->where('pc.is_default=1');
        $db->setQuery($sql);
        $category = $db->loadResult();
        
        return $category;
    }
    
    public static function getProductCategories($product_id) {
		$db = JFactory::getDBO();
        $sql = $db->getQuery(true);
        $sql->select('pc.category_id')->from('#__ksenmart_products_categories AS pc')->where('pc.product_id=' . $db->escape($product_id));
        $db->setQuery($sql);
        $categories = $db->loadObjectList();
        
        return $categories;
    }	
	
    public static function getProductCategory($product_id) {
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
                $category = KSSystem::getTableByIds(array($parent), 'categories', array('t.id', 't.parent_id'), true, false, true);
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
