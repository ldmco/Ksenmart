<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

KSSystem::import('models.modelksadmin');
class KsenMartModelCatalog extends JModelKSAdmin {

    protected function populateState() {
        $this->onExecuteBefore('populateState');

        $app = JFactory::getApplication();

        $value = $app->getUserStateFromRequest($this->context . 'list.limit', 'limit', $this->params->get('admin_product_limit', 30), 'uint');
        $limit = $value;
        $this->setState('list.limit', $limit);

        $value = $app->getUserStateFromRequest($this->context . '.limitstart', 'limitstart', 0);
        $limitstart = ($limit != 0 ? (floor($value / $limit) * $limit) : 0);
        $this->setState('list.start', $limitstart);

        $order_dir = $app->getUserStateFromRequest($this->context . '.order_dir', 'order_dir', 'asc');
        $this->setState('order_dir', $order_dir);
        $order_type = $app->getUserStateFromRequest($this->context . '.order_type', 'order_type', 'ordering');
        $this->setState('order_type', $order_type);

        $categories = $app->getUserStateFromRequest($this->context . '.categories', 'categories', array());
        JArrayHelper::toInteger($categories);
        $categories = array_filter($categories, 'KSFunctions::filterArray');
        $this->setState('categories', $categories);
        $manufacturers = $app->getUserStateFromRequest($this->context . '.manufacturers', 'manufacturers', array());
        JArrayHelper::toInteger($manufacturers);
        $manufacturers = array_filter($manufacturers, 'KSFunctions::filterArray');
        $this->setState('manufacturers', $manufacturers);

        $searchword = $app->getUserStateFromRequest($this->context . '.searchword', 'searchword', null);
        $this->setState('searchword', $searchword);

        $excluded = $app->getUserStateFromRequest($this->context . '.excluded', 'excluded', array());
        JArrayHelper::toInteger($excluded);
        $excluded = array_filter($excluded, 'KSFunctions::filterArray');
        $this->setState('excluded', $excluded);
        $items_tpl = $app->getUserStateFromRequest($this->context . '.items_tpl', 'items_tpl', null);
        $this->setState('items_tpl', $items_tpl);
        $items_to = $app->getUserStateFromRequest($this->context . '.items_to', 'items_to', null);
        $this->setState('items_to', $items_to);

        $this->onExecuteAfter('populateState');
    }

    function getListItems() {
        $this->onExecuteBefore('getListItems');

        $order_dir = $this->getState('order_dir');
        $order_type = $this->getState('order_type');
        $searchword = $this->getState('searchword');
        $categories = $this->getState('categories');
        $manufacturers = $this->getState('manufacturers');
        $excluded = $this->getState('excluded');
        if($order_type != 'ordered') $order_type = 'p.' . $order_type;
        $query = $this->_db->getQuery(true);
        $query->select('SQL_CALC_FOUND_ROWS p.*')->from('#__ksenmart_products as p')->where('p.parent_id=0')->order($order_type . ' ' . $order_dir);
        if(!empty($searchword)) $query->where('p.title like ' . $this->_db->quote('%' . $searchword . '%') . ' or p.product_code like ' . $this->_db->quote('%' . $searchword . '%'));
        if(count($manufacturers) > 0) $query->where('p.manufacturer in (' . implode(',', $manufacturers) . ')');
        if(count($excluded) > 0) $query->where('p.id not in (' . implode(',', $excluded) . ')');
        if(count($categories) > 0) {
            $query->innerjoin('#__ksenmart_products_categories as pc on pc.product_id=p.id');
            $query->where('pc.category_id in (' . implode(', ', $categories) . ')');
        }
        $query->group('p.id');
        $query = KSMedia::setItemMainImageToQuery($query);
        $this->_db->setQuery($query, $this->getState('list.start'), $this->getState('list.limit'));
        $items = $this->_db->loadObjectList();
        $query = $this->_db->getQuery(true);
        $query->select('FOUND_ROWS()');
        $this->_db->setQuery($query);
        $this->total = $this->_db->loadResult();
        foreach($items as &$item) {
            $item->folder = 'products';
            $item->small_img = KSMedia::resizeImage($item->filename, $item->folder, $this->params->get('admin_product_thumb_image_width', 36), $this->params->get('admin_product_thumb_image_heigth', 36), json_decode($item->params, true));
            $item->medium_img = KSMedia::resizeImage($item->filename, $item->folder, $this->params->get('admin_product_medium_image_width', 120), $this->params->get('admin_product_medium_image_heigth', 120), json_decode($item->params, true));
        }

        $this->onExecuteAfter('getListItems', array(&$items));
        return $items;
    }

    function getTotal() {
        $this->onExecuteBefore('getTotal');

        $total = $this->total;

        $this->onExecuteAfter('getTotal', array(&$total));
        return $total;
    }

    function deleteListItems($ids) {
        $this->onExecuteBefore('deleteListItems', array(&$ids));
		
		$tags = new JHelperTags;
		$table = $this->getTable('products');
		$tags->typeAlias = 'com_ksenmart.product';
		
        foreach($ids as $id) {
			$table->load($id);
			$tags->deleteTagData($table, $id);	
            $query = $this->_db->getQuery(true);
            $query->delete('#__ksenmart_product_properties_values')->where('product_id=' . $id);
            $this->_db->setQuery($query);
            $this->_db->query();
            $query = $this->_db->getQuery(true);
            $query->delete('#__ksenmart_products_categories')->where('product_id=' . $id);
            $this->_db->setQuery($query);
            $this->_db->query();
            $query = $this->_db->getQuery(true);
            $query->delete('#__ksenmart_products_child_groups')->where('product_id=' . $id);
            $this->_db->setQuery($query);
            $this->_db->query();
            $query = $this->_db->getQuery(true);
            $query->delete('#__ksenmart_products_relations')->where('product_id=' . $id);
            $this->_db->setQuery($query);
            $this->_db->query();
            $query = $this->_db->getQuery(true);
            $query->delete('#__ksenmart_products')->where('id=' . $id);
            $this->_db->setQuery($query);
            $this->_db->query();
            KSMedia::deleteItemMedia($id, 'product');
            $query = $this->_db->getQuery(true);
            $query->select('id')->from('#__ksenmart_products')->where('parent_id=' . $id);
            $this->_db->setQuery($query);
            $childs = $this->_db->loadColumn();
            if(count($childs) > 0) $this->deleteListItems($childs);
        }

        $this->onExecuteAfter('deleteListItems', array(&$ids));
        return true;
    }

    function copyListItems($products, $parent_id = 0, $childs_group = 0) {
        $this->onExecuteBefore('copyListItems', array(
            &$products,
            &$parent_id,
            &$childs_group));

        foreach($products as $product) {
            $table = $this->getTable('products');
            $table->load($product);
            $table->id = null;
            $table->date_added = JFactory::getDate()->toSql();
			$same_title = false;
			$i = 1;
			$title = $table->title;
			while(!$same_title){
				$title = $table->title.' ('.$i.')';
				$query = $this->_db->getQuery(true);
				$query->select('count(id)')->from('#__ksenmart_products')->where('title='.$this->_db->quote($title));
				$this->_db->setQuery($query);
				$same_title = !$this->_db->loadResult();	
				$i++;
			}
			$table->title = $title;
            $table->alias = KSFunctions::GenAlias($table->title);
            if($parent_id != 0) $table->parent_id = $parent_id;
            if($childs_group != 0) $table->childs_group = $childs_group;
            if($table->check()) {
                if(!$table->store()) {
                    return false;
                }
            }
            $product_id = $table->id;

            $query = $this->_db->getQuery(true);
            $query->select('*')->from('#__ksenmart_files')->where('owner_id=' . $product)->where('owner_type="product"');
            $this->_db->setQuery($query);
            $images = $this->_db->loadObjectList();
            foreach($images as $image) {
                $ptable = $this->getTable('Files');
                $ptable->load($image->id);
                $old_filename = $filename = $ptable->filename;
                $filename = $ptable->filename;
                $filename = explode('.', $filename);
                $filename = microtime(true) . '.' . $filename[count($filename) - 1];
                $ptable->filename = $filename;
                $ptable->owner_id = $product_id;
                $ptable->id = null;
                if($ptable->check()) {
                    if($ptable->store()) {
                        copy(JPATH_ROOT . DS . 'media' . DS . 'com_ksenmart' . DS . 'images' . DS . $ptable->folder . DS . 'original' . DS . $old_filename, JPATH_ROOT . DS . 'media' . DS . 'com_ksenmart' . DS . 'images' . DS . $ptable->folder . DS . 'original' . DS . $filename);
                    } else  return false;
                }
            }
            $query = $this->_db->getQuery(true);
            $query->select('*')->from('#__ksenmart_products_categories')->where('product_id=' . $product);
            $this->_db->setQuery($query);
            $categories = $this->_db->loadObjectList();
            foreach($categories as $category) {
                $ctable = $this->getTable('ProductCategories');
                $ctable->load($category->id);
                $ctable->product_id = $product_id;
                $ctable->id = null;
                if($ctable->check()) {
                    if(!$ctable->store()) {
                        return false;
                    }
                }

            }
            $query = $this->_db->getQuery(true);
            $query->select('*')->from('#__ksenmart_product_properties_values')->where('product_id=' . $product);
            $this->_db->setQuery($query);
            $properties = $this->_db->loadObjectList();
            foreach($properties as $property) {
                $ppvtable = $this->getTable('ProductPropertiesValues');
                $ppvtable->load($property->id);
                $ppvtable->product_id = $product_id;
                $ppvtable->id = null;
                if($ppvtable->check()) {
                    if(!$ppvtable->store()) {
                        return false;
                    }
                }
            }
            $query = $this->_db->getQuery(true);
            $query->select('*')->from('#__ksenmart_products_relations')->where('product_id=' . $product);
            $this->_db->setQuery($query);
            $relativies = $this->_db->loadObjectList();
            foreach($relativies as $relative) {
                $prtable = $this->getTable('ProductRelations');
                $prtable->load($relative->id);
                $prtable->product_id = $product_id;
                $prtable->id = null;
                if($prtable->check()) {
                    if(!$prtable->store()) {
                        return false;
                    }
                }
            }
            $query = $this->_db->getQuery(true);
            $query->select('*')->from('#__ksenmart_products_child_groups')->where('product_id=' . $product);
            $this->_db->setQuery($query);
            $childs_groups = $this->_db->loadObjectList();
            foreach($childs_groups as $childs_group) {
                $pcgtable = $this->getTable('ProductsChildGroups');
                $pcgtable->load($childs_group->id);
                $pcgtable->product_id = $product_id;
                $pcgtable->id = null;
                if($pcgtable->check()) {
                    if(!$pcgtable->store()) {
                        return false;
                    }
                }
                $query = $this->_db->getQuery(true);
                $query->select('*')->from('#__ksenmart_products')->where('parent_id=' . $product)->where('childs_group=' . $childs_group->id);
                $this->_db->setQuery($query);
                $childs = $this->_db->loadObjectList();
                foreach($childs as $child) {
                    $this->copyListItems(array($child->id), $product_id, $pcgtable->id);
                }
            }
            $query = $this->_db->getQuery(true);
            $query->select('*')->from('#__ksenmart_products')->where('parent_id=' . $product)->where('childs_group=0');
            $this->_db->setQuery($query);
            $childs = $this->_db->loadObjectList();
            foreach($childs as $child) {
                $this->copyListItems(array($child->id), $product_id);
            }
        }

        $this->onExecuteAfter('copyListItems', array(
            &$products,
            &$parent_id,
            &$childs_group));
        return true;
    }

    function getProducts($ids) {
        $this->onExecuteBefore('getProducts', array(&$ids));

        $query = $this->_db->getQuery(true);
        $query->select('p.*')->from('#__ksenmart_products as p')->where('p.id in (' . implode(',', $ids) . ')');
        $query = KSMedia::setItemMainImageToQuery($query);
        $this->_db->setQuery($query);
        $items = $this->_db->loadObjectList();

        foreach($items as &$item) {
            $item->small_img = KSMedia::resizeImage($item->filename, $item->folder, $this->params->get('admin_product_thumb_image_width', 36), $this->params->get('admin_product_thumb_image_heigth', 36), json_decode($item->params, true));
            $item->medium_img = KSMedia::resizeImage($item->filename, $item->folder, $this->params->get('admin_product_medium_image_width', 120), $this->params->get('admin_product_medium_image_heigth', 120), json_decode($item->params, true));
            $item->product_packaging = round($item->product_packaging)==$item->product_packaging ? round($item->product_packaging) : $item->product_packaging ;
            KSMProducts::productPricesTransform($item);
        }

        $this->onExecuteAfter('getProducts', array(&$items));
        return $items;
    }

    function getSet($categories = array()) {
        $this->onExecuteBefore('getSet', array(&$categories));

        $id = JRequest::getInt('id');
        $ids = JRequest::getVar('ids', array());
        $set = KSSystem::loadDbItem($id, 'products');
        $set = KSMedia::setItemMedia($set, 'product');

        if(count($categories)) {
            $query = $this->_db->getQuery(true);
            $query->select('category_id')->from('#__ksenmart_products_categories')->where('product_id=' . $id)->where('is_default=1');
            $this->_db->setQuery($query);
            $is_default = $this->_db->loadResult();
            foreach($categories as $category_id) {
                $category = new stdClass();
                $category->category_id = $category_id;
                $category->is_default = $category->category_id == $is_default ? 1 : 0;
                $set->categories[$category_id] = $category;
            }
        } else {
            $query = $this->_db->getQuery(true);
            $query->select('category_id, is_default')->from('#__ksenmart_products_categories')->where('product_id=' . $id);
            $this->_db->setQuery($query);
            $set->categories = $this->_db->loadObjectList('category_id');
        }

        if(count($ids)) {
            $query = $this->_db->getQuery(true);
            $query->select('p.*')->from('#__ksenmart_products as p')->where('p.id in (' . implode(',', $ids) . ')');
            $query = KSMedia::setItemMainImageToQuery($query);
            $this->_db->setQuery($query);
            $set->relative = $this->_db->loadObjectList('id');
        } else {
            $query = $this->_db->getQuery(true);
            $query->select('p.*')->from('#__ksenmart_products as p')->innerjoin('#__ksenmart_products_relations as pr on pr.relative_id=p.id')->where('pr.relation_type=' . $this->_db->quote('set'))->where('pr.product_id=' . $id);
            $query = KSMedia::setItemMainImageToQuery($query);
            $this->_db->setQuery($query);
            $set->relative = $this->_db->loadObjectList('id');
        }

        $set->old_price = 0;
        foreach($set->relative as &$prd) {
            $prd->small_img = KSMedia::resizeImage($prd->filename, $prd->folder, 36, 36, json_decode($prd->params, true));
            KSMProducts::productPricesTransform($prd);
            $set->old_price += $prd->price;
        }

        $this->onExecuteAfter('getSet', array(&$set));
        return $set;
    }

    function saveSet($data) {
        $this->onExecuteBefore('saveSet', array(&$data));

        $data['alias'] = KSFunctions::CheckAlias($data['alias'], $data['id']);
        $data['alias'] = $data['alias'] == '' ? KSFunctions::GenAlias($data['title']) : $data['alias'];
        $data['new'] = isset($data['new']) ? $data['new'] : 0;
        $data['promotion'] = isset($data['promotion']) ? $data['promotion'] : 0;
        $data['hot'] = isset($data['hot']) ? $data['hot'] : 0;
        $data['recommendation'] = isset($data['recommendation']) ? $data['recommendation'] : 0;
        $table = $this->getTable('products');

        if(empty($data['id'])) {
            $query = $this->_db->getQuery(true);
            $query->update('#__ksenmart_products')->set('ordering=ordering+1');
            $this->_db->setQuery($query);
            $this->_db->query();
            $data['date_added'] = JFactory::getDate()->toSql();
        }

        if(!$table->bindCheckStore($data)) {
            $this->setError($table->getError());
            return false;
        }
        $id = $table->id;
        KSMedia::saveItemMedia($id, $data, 'product', 'products');

        JArrayHelper::toInteger($data['categories']);
        $default_category = 0;
        if(isset($data['categories']['default'])) {
            $default_category = $data['categories']['default'];
            unset($data['categories']['default']);
        }
        $in = array();
        foreach($data['categories'] as $category_id) {
            $table = $this->getTable('ProductCategories');
            $d = array(
                'product_id' => $id,
                'category_id' => $category_id,
                );
            if($table->load($d)) {
                $d['id'] = $table->id;
            }
            $d['is_default'] = ($category_id == $default_category) ? 1 : 0;
            if(!$table->bindCheckStore($d)) {
                $this->setError($table->getError());
                return false;
            }
            $in[] = $table->id;
        }
        $query = $this->_db->getQuery(true);
        $query->delete('#__ksenmart_products_categories')->where('product_id=' . $id);
        if(count($in)) {
            $query->where('id not in (' . implode(',', $in) . ')');
        }
        $this->_db->setQuery($query);
        $this->_db->query();

        $query = $this->_db->getQuery(true);
        $query->delete('#__ksenmart_products_relations')->where('product_id=' . $id)->where('relation_type=' . $this->_db->quote('set'));
        $this->_db->setQuery($query);
        $this->_db->query();
        foreach($data['relative'] as $k => $v) {
            $v['product_id'] = $id;
            $v['relation_type'] = 'set';
            $table = $this->getTable('ProductRelations');
            if(!$table->bindCheckStore($v)) {
                $this->setError($table->getError());
                return false;
            }
        }
        $on_close = 'window.parent.ProductsList.refreshList();                                       ';
        $return = array('id' => $id, 'on_close' => $on_close);

        $this->onExecuteAfter('saveSet', array(&$return));
        return $return;
    }

    function getProduct($categories = array()) {
        $this->onExecuteBefore('getProduct', array(&$categories));

        $id = JRequest::getInt('id');
        $product = KSSystem::loadDbItem($id, 'products');
        $product = KSMedia::setItemMedia($product, 'product');
        $product->categories = array();
        $product->properties = array();
        $product->childs = array();

        if(count($categories)) {
            $query = $this->_db->getQuery(true);
            $query->select('category_id')->from('#__ksenmart_products_categories')->where('product_id=' . $id)->where('is_default=1');
            $this->_db->setQuery($query);
            $is_default = $this->_db->loadResult();
            foreach($categories as $category_id) {
                $category = new stdClass();
                $category->category_id = $category_id;
                $category->is_default = $category->category_id == $is_default ? 1 : 0;
                $product->categories[$category_id] = $category;
            }
        } else {
            $query = $this->_db->getQuery(true);
            $query->select('category_id, is_default')->from('#__ksenmart_products_categories')->where('product_id=' . $id);
            $this->_db->setQuery($query);
            $product->categories = $this->_db->loadObjectList('category_id');
        }

        if(count($product->categories)) {
            $query = $this->_db->getQuery(true);
            $query->select('p.*')->from('#__ksenmart_properties as p')->innerjoin('#__ksenmart_product_categories_properties as cp on cp.property_id=p.id')->where('cp.category_id in (' . implode(',', array_keys($product->categories)) . ')')->where('p.published=1');
            $this->_db->setQuery($query);
            $product->properties = $this->_db->loadObjectList('id');
            foreach($product->properties as &$p) $p->values = array();

            if(!empty($product->properties)) {
                $in = array_keys($product->properties);
                $query = $this->_db->getQuery(true);
                $query->select('*')->from('#__ksenmart_product_properties_values')->where('product_id=' . $id)->where('property_id in (' . implode(',', $in) . ')');
                $this->_db->setQuery($query);
                $values = $this->_db->loadObjectList();
                foreach($values as $v) {
                    if(isset($product->properties[$v->property_id])) {
                        $product->properties[$v->property_id]->values[$v->id] = $v;
                    }
                }
            }
        }

        if($product->is_parent == 1) {
            $empty_group = new stdClass();
            $empty_group->id = 0;
            $empty_group->title = JText::_('KSM_CATALOG_PRODUCT_CHILDS_EMPTY_GROUP');
            $empty_group->product_id = $id;
            $empty_group->products = array();
            $empty_group->ordering = 0;

            $query = $this->_db->getQuery(true);
            $query->select('*')->from('#__ksenmart_products_child_groups')->where('product_id=' . $id)->order('ordering');
            $this->_db->setQuery($query);
            $product->childs = $this->_db->loadObjectList('id');
            array_unshift($product->childs, $empty_group);
            foreach($product->childs as &$child) {
                $query = $this->_db->getQuery(true);
                $query->select('p.*')->from('#__ksenmart_products as p')->where('p.parent_id=' . $id)->where('p.childs_group =' . $child->id)->order('p.ordering');
                $query = KSMedia::setItemMainImageToQuery($query);
                $this->_db->setQuery($query);
                $child->products = $this->_db->loadObjectList('id');
                foreach($child->products as &$prd) $prd->small_img = KSMedia::resizeImage($prd->filename, $prd->folder, $this->params->get('admin_product_thumb_image_width', 36), $this->params->get('admin_product_thumb_image_heigth', 36), json_decode($prd->params, true));
            }
        }

        $query = $this->_db->getQuery(true);
        $query->select('p.*')->from('#__ksenmart_products as p')->innerjoin('#__ksenmart_products_relations as pr on pr.relative_id=p.id')->where('pr.relation_type=' . $this->_db->quote('relation'))->where('pr.product_id=' . $id);
        $query = KSMedia::setItemMainImageToQuery($query);
        $this->_db->setQuery($query);
        $product->relative = $this->_db->loadObjectList('id');
        foreach($product->relative as &$prd) $prd->small_img = KSMedia::resizeImage($prd->filename, $prd->folder, $this->params->get('admin_product_thumb_image_width', 36), $this->params->get('admin_product_thumb_image_heigth', 36), json_decode($prd->params, true));

        $tagHelper = new JHelperTags;
        $product->tags = $tagHelper->getTagIds(array($product->id), 'com_ksenmart.product');

        $this->onExecuteAfter('getProduct', array(&$product));
        return $product;
    }

    function saveProduct($data) {
        $this->onExecuteBefore('saveProduct', array(&$data));

        $data['alias'] = KSFunctions::CheckAlias($data['alias'], $data['id']);
        $data['alias'] = $data['alias'] == '' ? KSFunctions::GenAlias($data['title']) : $data['alias'];
        $data['new'] = isset($data['new']) ? $data['new'] : 0;
        $data['promotion'] = isset($data['promotion']) ? $data['promotion'] : 0;
        $data['hot'] = isset($data['hot']) ? $data['hot'] : 0;
        $data['recommendation'] = isset($data['recommendation']) ? $data['recommendation'] : 0;
        $table = $this->getTable('products');

        if(empty($data['id'])) {
            $query = $this->_db->getQuery(true);
            $query->update('#__ksenmart_products')->set('ordering=ordering+1');
            $this->_db->setQuery($query);
            $this->_db->query();
            $data['date_added'] = JFactory::getDate()->toSql();
        }

        if(!$table->bindCheckStore($data)) {
            $this->setError($table->getError());
            return false;
        }
        $id = $table->id;
        KSMedia::saveItemMedia($id, $data, 'product', 'products');

        $tagsObserver = $table->getObserverOfClass('JTableObserverTags');
        $result = $tagsObserver->setNewTags($data['tags'], true);

        JArrayHelper::toInteger($data['categories']);
        $default_category = 0;
        if(isset($data['categories']['default'])) {
            $default_category = $data['categories']['default'];
            unset($data['categories']['default']);
        }
        $in = array();
        foreach($data['categories'] as $category_id) {
            $table = $this->getTable('ProductCategories');
            $d = array(
                'product_id' => $id,
                'category_id' => $category_id,
                );
            if($table->load($d)) {
                $d['id'] = $table->id;
            }
            $d['is_default'] = ($category_id == $default_category) ? 1 : 0;
            if(!$table->bindCheckStore($d)) {
                $this->setError($table->getError());
                return false;
            }
            $in[] = $table->id;
        }
        $query = $this->_db->getQuery(true);
        $query->delete('#__ksenmart_products_categories')->where('product_id=' . $id);
        if(count($in)) {
            $query->where('id not in (' . implode(',', $in) . ')');
        }
        $this->_db->setQuery($query);
        $this->_db->query();

        $values = array();
        foreach($data['properties'] as $property_id => $property) {
            $property_id = (int)$property_id;
            $query = $this->_db->getQuery(true);
            $query->select('type')->from('#__ksenmart_properties')->where('id=' . $property_id);
            $this->_db->setQuery($query);
            $type = $this->_db->loadResult();
            if(empty($type)) {
                $this->setError(JText::_('KSM_CATALOG_PRODUCT_INVALID_PROPERTY_DATA'));
                return false;
            }
            switch($type) {
                case 'text':
					if(!empty($property['text'])){
						$property['product_id'] = $id;
						$property['property_id'] = $property_id;
						$text = $this->_db->quote($property['text']);
						$query = $this->_db->getQuery(true);
						$query->select('*')->from('#__ksenmart_property_values')->where('title=' . $text);
						$this->_db->setQuery($query);
						$value_row = $this->_db->loadObject();
						if(empty($value_row)) {
							$p_alias = KSFunctions::GenAlias($text);							
							$query = $this->_db->getQuery(true);
							$query->insert('#__ksenmart_property_values')->columns('property_id,title,alias')->values($property_id . ',' . $text . ',' . $this->_db->quote($p_alias));
							$this->_db->setQuery($query);
							$this->_db->query();
							$property['value_id'] = $this->_db->insertid();
						} else {
							$property['value_id'] = $value_row->id;
						}
						$values[] = $property;
					}
                    break;
                case 'select':
                    foreach($property as $tmpkey => $tmpvalue) {
                        if(array_key_exists('id', $tmpvalue) && $tmpvalue['id'] == $tmpkey) {
                            unset($tmpvalue['id']);
                            $tmpvalue['product_id'] = $id;
                            $tmpvalue['property_id'] = $property_id;
                            $tmpvalue['value_id'] = $tmpkey;
                            $values[] = $tmpvalue;
                        }
                    }
                    break;
            }
        }
        $in = array();
        foreach($values as $value) {
            $table = $this->getTable('ProductPropertiesValues');
            $d = array('product_id' => $value['product_id'], 'property_id' => $value['property_id']);
            if(array_key_exists('value_id', $value)) {
                $d['value_id'] = $value['value_id'];
            }
            if($table->load($d)) {
                $value['id'] = $table->id;
            }
            if(!$table->bindCheckStore($value)) {
                $this->setError($table->getError());
                return false;
            }
            $in[] = $table->id;
        }
        $query = $this->_db->getQuery(true);
        $query->delete('#__ksenmart_product_properties_values')->where('product_id=' . $id);
        if(count($in)) {
            $query->where('id not in (' . implode(',', $in) . ') ');
        }
        $this->_db->setQuery($query);
        $this->_db->query();

        $query = $this->_db->getQuery(true);
        $query->delete('#__ksenmart_products_relations')->where('product_id=' . $id)->where('relation_type=' . $this->_db->quote('relation'));
        $this->_db->setQuery($query);
        $this->_db->query();
        foreach($data['relative'] as $k => $v) {
            $v['product_id'] = $id;
            $v['relation_type'] = 'relation';
            $table = $this->getTable('ProductRelations');
            if(!$table->bindCheckStore($v)) {
                $this->setError($table->getError());
                return false;
            }
        }

        foreach($data['childs'] as $k => $v) {
            $v['published'] = isset($v['published']) ? $v['published'] : 0;
            $table = $this->getTable('products');
            if(!$table->bindCheckStore($v)) {
                $this->setError($table->getError());
                return false;
            }
        }
        $child_groups = JRequest::getVar('child_groups', array());
        foreach($child_groups as $k => $v) {
            if($k != 0) {
                $table = $this->getTable('ProductsChildGroups');
                if(!$table->bindCheckStore($v)) {
                    $this->setError($table->getError());
                    return false;
                }
            }
        }

        $on_close = 'window.parent.ProductsList.refreshList();';
        $return = array('id' => $id, 'on_close' => $on_close);

        $this->onExecuteAfter('saveProduct', array(&$return));
        return $return;
    }

    function getChild($categories = array()) {
        $this->onExecuteBefore('getChild', array(&$categories));

        $id = JRequest::getInt('id');
        $product = KSSystem::loadDbItem($id, 'products');
        $product = KSMedia::setItemMedia($product, 'product');
        if($id == 0) $product->parent_id = JRequest::getInt('parent_id');
        $product->categories = array();
        $product->properties = array();
        $product->childs = array();

        if(count($categories)) {
            $query = $this->_db->getQuery(true);
            $query->select('category_id')->from('#__ksenmart_products_categories')->where('product_id=' . $id)->where('is_default=1');
            $this->_db->setQuery($query);
            $is_default = $this->_db->loadResult();
            foreach($categories as $category_id) {
                $category = new stdClass();
                $category->category_id = $category_id;
                $category->is_default = $category->category_id == $is_default ? 1 : 0;
                $product->categories[$category_id] = $category;
            }
        } else {
            $query = $this->_db->getQuery(true);
            $query->select('category_id, is_default')->from('#__ksenmart_products_categories');
            if($id > 0) $query->where('product_id=' . $id);
            else  $query->where('product_id=' . $product->parent_id);
            $this->_db->setQuery($query);
            $product->categories = $this->_db->loadObjectList('category_id');
        }

        if($id == 0) {
            $query = $this->_db->getQuery(true);
            $query->select('manufacturer')->from('#__ksenmart_products')->where('id=' . $product->parent_id);
            $this->_db->setQuery($query);
            $product->manufacturer = $this->_db->loadResult();
        }

        if(count($product->categories)) {
            $query = $this->_db->getQuery(true);
            $query->select('p.*')->from('#__ksenmart_properties as p')->innerjoin('#__ksenmart_product_categories_properties as cp on cp.property_id=p.id')->where('cp.category_id in (' . implode(',', array_keys($product->categories)) . ')');
            $this->_db->setQuery($query);
            $product->properties = $this->_db->loadObjectList('id');
            foreach($product->properties as &$p) $p->values = array();

            if(!empty($product->properties)) {
                $in = array_keys($product->properties);
                $query = $this->_db->getQuery(true);
                $query->select('*')->from('#__ksenmart_product_properties_values')->where('product_id=' . $id)->where('property_id in (' . implode(',', $in) . ')');
                $this->_db->setQuery($query);
                $values = $this->_db->loadObjectList();
                foreach($values as $v) {
                    if(isset($product->properties[$v->property_id])) {
                        $product->properties[$v->property_id]->values[$v->id] = $v;
                    }
                }
            }
        }

        $query = $this->_db->getQuery(true);
        $query->select('p.*')->from('#__ksenmart_products as p')->innerjoin('#__ksenmart_products_relations as pr on pr.relative_id=p.id')->where('pr.relation_type=' . $this->_db->quote('relation'))->where('pr.product_id=' . $id);
        $query = KSMedia::setItemMainImageToQuery($query);
        $this->_db->setQuery($query);
        $product->relative = $this->_db->loadObjectList('id');
        foreach($product->relative as &$prd) $prd->small_img = KSMedia::resizeImage($prd->filename, $prd->folder, $this->params->get('admin_product_thumb_image_width', 36), $this->params->get('admin_product_thumb_image_heigth', 36), json_decode($prd->params, true));

        $this->onExecuteAfter('getChild', array(&$product));
        return $product;
    }

    function saveChild($data) {
        $this->onExecuteBefore('saveChild', array(&$data));

        $data['alias'] = KSFunctions::CheckAlias($data['alias'], $data['id']);
        $data['alias'] = $data['alias'] == '' ? KSFunctions::GenAlias($data['title']) : $data['alias'];
        $data['new'] = isset($data['new']) ? $data['new'] : 0;
        $data['promotion'] = isset($data['promotion']) ? $data['promotion'] : 0;
        $data['hot'] = isset($data['hot']) ? $data['hot'] : 0;
        $data['recommendation'] = isset($data['recommendation']) ? $data['recommendation'] : 0;
        $table = $this->getTable('products');

        if(empty($data['id'])) {
            $query = $this->_db->getQuery(true);
            $query->update('#__ksenmart_products')->set('ordering=ordering+1');
            $this->_db->setQuery($query);
            $this->_db->query();
            $data['date_added'] = JFactory::getDate()->toSql();
        }

        if(!$table->bindCheckStore($data)) {
            $this->setError($table->getError());
            return false;
        }
        $id = $table->id;
        KSMedia::saveItemMedia($id, $data, 'product', 'products');

        JArrayHelper::toInteger($data['categories']);
        $default_category = 0;
        if(isset($data['categories']['default'])) {
            $default_category = $data['categories']['default'];
            unset($data['categories']['default']);
        }
        $in = array();
        foreach($data['categories'] as $category_id) {
            $table = $this->getTable('ProductCategories');
            $d = array(
                'product_id' => $id,
                'category_id' => $category_id,
                );
            if($table->load($d)) {
                $d['id'] = $table->id;
            }
            $d['is_default'] = ($category_id == $default_category) ? 1 : 0;
            if(!$table->bindCheckStore($d)) {
                $this->setError($table->getError());
                return false;
            }
            $in[] = $table->id;
        }
        $query = $this->_db->getQuery(true);
        $query->delete('#__ksenmart_products_categories')->where('product_id=' . $id);
        if(count($in)) {
            $query->where('id not in (' . implode(',', $in) . ')');
        }
        $this->_db->setQuery($query);
        $this->_db->query();

        $values = array();
        foreach($data['properties'] as $property_id => $property) {
            $property_id = (int)$property_id;
            $query = $this->_db->getQuery(true);
            $query->select('type')->from('#__ksenmart_properties')->where('id=' . $property_id);
            $this->_db->setQuery($query);
            $type = $this->_db->loadResult();
            if(empty($type)) {
                $this->setError(JText::_('KSM_CATALOG_PRODUCT_INVALID_PROPERTY_DATA'));
                return false;
            }
            switch($type) {
                case 'checkbox':
                case 'text':
                    $property['product_id'] = $id;
                    $property['property_id'] = $property_id;
                    $text = $this->_db->quote($property['text']);
                    $query = $this->_db->getQuery(true);
                    $query->select('*')->from('#__ksenmart_property_values')->where('title=' . $text);
                    $this->_db->setQuery($query);
                    $value_row = $this->_db->loadObject();
                    if(empty($value_row)) {
						$p_alias = KSFunctions::GenAlias($text);							
                        $query = $this->_db->getQuery(true);
                        $query->insert('#__ksenmart_property_values')->columns('property_id,title,alias')->values($property_id . ',' . $this->_db->quote($text) . ',' . $this->_db->quote($p_alias));
                        $this->_db->setQuery($query);
                        $this->_db->query();
                        $property['value_id'] = $this->_db->insertid();
                    } else {
                        $property['value_id'] = $value_row->id;
                    }
                    $values[] = $property;
                    break;
                case 'select':
                case 'radio':
                    foreach($property as $tmpkey => $tmpvalue) {
                        if(array_key_exists('id', $tmpvalue) && $tmpvalue['id'] == $tmpkey) {
                            unset($tmpvalue['id']);
                            $tmpvalue['product_id'] = $id;
                            $tmpvalue['property_id'] = $property_id;
                            $tmpvalue['value_id'] = $tmpkey;
                            $values[] = $tmpvalue;
                        }
                    }
                    break;
            }
        }
        $in = array();
        foreach($values as $value) {
            $table = $this->getTable('ProductPropertiesValues');
            $d = array('product_id' => $value['product_id'], 'property_id' => $value['property_id']);
            if(array_key_exists('value_id', $value)) {
                $d['value_id'] = $value['value_id'];
            }
            if($table->load($d)) {
                $value['id'] = $table->id;
            }
            if(!$table->bindCheckStore($value)) {
                $this->setError($table->getError());
                return false;
            }
            $in[] = $table->id;
        }
        $query = $this->_db->getQuery(true);
        $query->delete('#__ksenmart_product_properties_values')->where('product_id=' . $id);
        if(count($in)) {
            $query->where('id not in (' . implode(',', $in) . ') ');
        }
        $this->_db->setQuery($query);
        $this->_db->query();

        $query = $this->_db->getQuery(true);
        $query->delete('#__ksenmart_products_relations')->where('product_id=' . $id)->where('relation_type=' . $this->_db->quote('relation'));
        $this->_db->setQuery($query);
        $this->_db->query();
        foreach($data['relative'] as $k => $v) {
            $v['product_id'] = $id;
            $v['relation_type'] = 'relation';
            $table = $this->getTable('ProductRelations');
            if(!$table->bindCheckStore($v)) {
                $this->setError($table->getError());
                return false;
            }
        }

        $parent_id = JRequest::getInt('parent_id');
        if($parent_id == 0) $on_close = 'window.parent.ProductsList.refreshList();';
        else  $on_close = 'parent.window.location.reload();';
        $return = array('id' => $id, 'on_close' => $on_close);

        $this->onExecuteAfter('saveChild', array(&$return));
        return $return;
    }

    function getChildGroup() {
        $this->onExecuteBefore('getChildGroup');

        $id = JRequest::getInt('id');
        $product_id = JRequest::getInt('product_id');
        $childgroup = KSSystem::loadDbItem($id, 'productschildgroups');
        $childgroup->product_id = $product_id;

        $this->onExecuteAfter('getChildGroup', array(&$childgroup));
        return $childgroup;
    }

    function saveChildGroup($data) {
        $this->onExecuteBefore('saveChildGroup', array(&$data));

        $table = $this->getTable('productschildgroups');
        if(!$table->bindCheckStore($data)) {
            $this->setError($table->getError());
            return false;
        }
        $id = $table->id;

        $on_close = 'parent.window.location.reload();';
        $return = array('id' => $id, 'on_close' => $on_close);

        $this->onExecuteBefore('saveChildGroup', array(&$return));
        return $return;
    }

    function deleteChildGroup($group_id) {
        $this->onExecuteBefore('deleteChildGroup', array(&$group_id));

        $query = $this->_db->getQuery(true);
        $query->delete('#__ksenmart_products_child_groups');
        $query->where('id=' . $group_id);
        $this->_db->setQuery($query);
        $this->_db->query();
        $query = $this->_db->getQuery(true);
        $query->update('#__ksenmart_products');
        $query->set('childs_group=0');
        $query->where('childs_group=' . $group_id);
        $this->_db->setQuery($query);
        $this->_db->query();

        $this->onExecuteAfter('deleteChildGroup', array(&$group_id));
    }

    function getCategory() {
        $this->onExecuteBefore('getCategory');

        $id = JRequest::getInt('id');
        $category = KSSystem::loadDbItem($id, 'categories');
        $category = KSMedia::setItemMedia($category, 'category');
        $query = $this->_db->getQuery(true);
        $query->select('property_id')->from('#__ksenmart_product_categories_properties')->where('category_id=' . $id);
        $this->_db->setQuery($query);
        $category->properties = $this->_db->loadColumn();

        $this->onExecuteAfter('getCategory', array(&$category));
        return $category;
    }

    public function saveCategory($data) {
        $this->onExecuteBefore('saveCategory', array(&$data));

        $data['parent_id']  = isset($data['parent_id']) ? $data['parent_id'] : 0;
        $data['published']  = isset($data['published']) ? 1 : 0;
        $data['properties'] = isset($data['properties']) ? $data['properties'] : array();
        
        $data['alias'] = KSFunctions::CheckAlias($data['alias'], $data['id']);
        if($data['alias'] == '') {
            $data['alias'] = KSFunctions::GenAlias($data['title']);
        }
        
        $table = $this->getTable('categories');
        if(!$table->bindCheckStore($data)) {
            $this->setError($table->getError());
            return false;
        }
        $id = $table->id;
        KSMedia::saveItemMedia($id, $data, 'category', 'categories');

        $in = array();
        foreach($data['properties'] as $property) {
            $table = $this->getTable('ProductCategoriesProperties');
            $v = array('property_id' => $property, 'category_id' => $id);
            if(!$table->bindCheckStore($v)) {
                $this->setError($table->getError());
                return false;
            }
            $in[] = $table->id;
        }
        $query = $this->_db->getQuery(true);
        $query->delete('#__ksenmart_product_categories_properties')->where('category_id=' . $id);
        if(count($in) > 0) $query->where('id not in (' . implode(',', $in) . ')');
        $this->_db->setQuery($query);
        $this->_db->query();

        $on_close = 'window.parent.CategoriesModule.refresh();';
        $return = array('id' => $id, 'on_close' => $on_close);

        $this->onExecuteAfter('saveCategory', array(&$return));
        return $return;
    }

    public function deleteCategory($id) {
        $this->onExecuteBefore('deleteCategory', array(&$id));

        $table = $this->getTable('categories');
        $table->load($id);
		$parent_id = $table->parent_id;
        $table->delete($id);
        KSMedia::deleteItemMedia($id, 'category');
		
        $query = $this->_db->getQuery(true);
        $query->update('#__ksenmart_categories')->set('parent_id = '.$parent_id)->where('parent_id = '.$id);
        $this->_db->setQuery($query);
        $this->_db->query();		

        $this->onExecuteAfter('deleteCategory', array(&$id));
        return true;
    }

    function getManufacturer() {
        $this->onExecuteBefore('getManufacturer');

        $id = JRequest::getInt('id');
        $manufacturer = KSSystem::loadDbItem($id, 'manufacturers');
        $manufacturer = KSMedia::setItemMedia($manufacturer, 'manufacturer');

        $this->onExecuteAfter('getManufacturer', array(&$manufacturer));
        return $manufacturer;
    }

    function saveManufacturer($data) {
        $this->onExecuteBefore('saveManufacturer', array(&$data));

        $data['alias'] = KSFunctions::CheckAlias($data['alias'], $data['id']);
        if($data['alias'] == '') $data['alias'] = KSFunctions::GenAlias($data['title']);
        $data['country'] = isset($data['country']) ? $data['country'] : 0;
        $table = $this->getTable('manufacturers');
        if(!$table->bindCheckStore($data)) {
            $this->setError($table->getError());
            return false;
        }
        $id = $table->id;
        KSMedia::saveItemMedia($id, $data, 'manufacturer', 'manufacturers');

        $on_close = 'window.parent.ManufacturersModule.refresh();';
        $return = array('id' => $id, 'on_close' => $on_close);

        $this->onExecuteAfter('saveManufacturer', array(&$return));
        return $return;
    }

    function deleteManufacturer($id) {
        $this->onExecuteBefore('deleteManufacturer', array(&$id));

        $table = $this->getTable('manufacturers');
        $table->delete($id);
        KSMedia::deleteItemMedia($id, 'category');
        $query = $this->_db->getQuery(true);
        $query->update('#__ksenmart_products')->set('manufacturer=0')->where('manufacturer=' . $id);
        $this->_db->setQuery($query);
        $this->_db->query();

        $this->onExecuteAfter('deleteManufacturer', array(&$id));
        return true;
    }

}
