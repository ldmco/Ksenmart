<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

if (!class_exists('KMPlugin')) {
    require (JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_ksenmart' . DS . 'classes' . DS . 'kmplugin.php');
}

class plgKMPluginsDoBeforeCache extends KMPlugin {
    
    function __construct(&$subject, $config) {
        parent::__construct($subject, $config);
    }
    
    function onBeforeStartComponent() {
        $config = JFactory::getConfig();
        if (!$config->get('config.caching', 0)) 
        return false;
        $db = JFactory::getDBO();
        $app = JFactory::getApplication();
        $path = $app->getPathway();
        $jinput = $app->input;
        $view = $jinput->get('view', 'catalog', 'string');
        $layout = $jinput->get('layout', 'default', 'string');
        
        
        switch ($view) {
            case 'catalog':
                $catalog_path = array();
                $categories = JRequest::getVar('categories', array());
                JArrayHelper::toInteger($categories);
                $manufacturers = JRequest::getVar('manufacturers', array());
                JArrayHelper::toInteger($manufacturers);
                $countries = JRequest::getVar('countries', array());
                JArrayHelper::toInteger($countries);
                if (count($categories) == 1) {
                    $catid = $categories[0];
                    
                    while ((int)$catid != 0) {
                        $query = "select id,parent,title,alias from #__ksenmart_categories where id='$catid'";
                        $db->setQuery($query);
                        $cat = $db->loadObject();
                        $cat->link = JRoute::_('index.php?option=com_ksenmart&view=catalog&categories[]=' . $cat->id);
                        $catalog_path[] = array(
                            'title' => $cat->title,
                            'link' => $cat->link
                        );
                        $catid = $cat->parent;
                    }
                    $catalog_path = array_reverse($catalog_path);
                } elseif (count($manufacturers) == 1) {
                    $query = "select title from #__ksenmart_manufacturers where id='$manufacturers[0]'";
                    $db->setQuery($query);
                    $title = $db->loadResult();
                    $catalog_path[] = array(
                        'title' => $title,
                        'link' => ''
                    );
                } elseif (count($countries) == 1) {
                    $query = "select title from #__ksenmart_countries where id='$countries[0]'";
                    $db->setQuery($query);
                    $title = $db->loadResult();
                    $catalog_path[] = array(
                        'title' => $title,
                        'link' => ''
                    );
                } else $catalog_path[] = array(
                    'title' => JText::_('KSM_CATALOG_TITLE') ,
                    'link' => ''
                );
                $k = 0;
                
                foreach ($catalog_path as $c_path) {
                    $k++;
                    if ($k == count($catalog_path)) $path->addItem($c_path['title']);
                    else $path->addItem($c_path['title'], $c_path['link']);
                }
                
                break;
            case 'comments':
                $id = $jinput->get('id', 0, 'int');
                if ($id == 0) {
                    $path->addItem(JText::_('KSM_REVIEWS_LIST_PATH_TITLE'));
                } else {
                    $path->addItem(JText::_('KSM_REVIEWS_LIST_PATH_TITLE') , 'index.php?option=com_ksenmart&view=comments&Itemid=' . KSSystem::getShopItemid());
                    $path->addItem(JText::_('KSM_REVIEW_ITEM_PATH_TITLE'));
                }
                
                break;
            case 'order':
                $path->addItem('Оформление заказа');
                
                break;
            case 'product':
                $id = $jinput->get('id', 0, 'int');
                KSMProducts::incProductHit($id);
                $cat_path = array();
                $final_categories = array();
                
                $sql = $db->getQuery(true);
                $sql->select('category_id')->from('#__ksenmart_products_categories')->where('product_id=' . $id)->where('is_default=1');
                $db->setQuery($sql);
                $default_category = $db->loadResult();
                
                $sql = $db->getQuery(true);
                $sql->select('category_id')->from('#__ksenmart_products_categories')->where('product_id=' . $id);
                $db->setQuery($sql);
                
                $product_categories = $db->loadObjectList();
                
                foreach ($product_categories as $product_category) {
                    if (!empty($default_category)) $id_default_way = false;
                    else $id_default_way = true;
                    $categories = array();
                    $parent = $product_category->category_id;
                    
                    while ($parent != 0) {
                        if ($parent == $default_category) $id_default_way = true;
                        $sql = $db->getQuery(true);
                        $sql->select('id,parent')->from('#__ksenmart_categories')->where('id=' . $parent);
                        $db->setQuery($sql);
                        $category = $db->loadObject();
                        $categories[] = $category->id;
                        $parent = $category->parent;
                    }
                    if ($id_default_way && count($categories) > count($final_categories)) $final_categories = $categories;
                }
                $final_categories = array_reverse($final_categories);
                
                foreach ($final_categories as $final_category) {
                    $sql = $db->getQuery(true);
                    $sql->select('title,id')->from('#__ksenmart_categories')->where('id=' . $final_category);
                    $db->setQuery($sql);
                    $category = $db->loadObject();
                    $category->link = JRoute::_('index.php?option=com_ksenmart&view=catalog&categories[]=' . $final_category . '&Itemid=' . KSSystem::getShopItemid());
                    $cat_path[] = $category;
                }
                
                foreach ($cat_path as $cat) {
                    $path->addItem($cat->title, $cat->link);
                }
                $query = "select title from #__ksenmart_products where id='$id'";
                $db->setQuery($query);
                $title = $db->loadResult();
                $path->addItem($title);
                
                break;
            }
            
            return true;
        }
    }