<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

require_once JPATH_ROOT . '/plugins/system/ksencore/core/helpers/common/functions.php';

function KsenMartBuildRoute(&$query) {
    $app = JFactory::getApplication();
    $menu = $app->getMenu();
    $db = JFactory::getDBO();
    $segments = array();
    
    if (empty($query['Itemid'])) unset($query['Itemid']);
    
    if (!isset($query['view'])) $query['view'] = 'catalog';
    
    if (isset($query['task']) && $query['task'] == 'catalog.filter_products') {
        unset($query['task']);
    }
    
    switch ($query['view']) {
        case 'cart':
            $segments[] = 'cart';
        break;
        case 'profile':
            $segments[] = 'profile';
            if (isset($query['layout']) && !empty($query['layout'])) {
                $segments[] = $query['layout'];
                unset($query['layout']);
            }
        break;
        case 'comments':
            $segments[] = 'reviews';
            if (isset($query['id']) && !empty($query['id']) && $query['id'] != 0) {
                $segments[] = $query['id'];
                unset($query['id']);
            }
        break;
        case 'search':
            $segments[] = 'searching';
            if (isset($query['value']) && !empty($query['value'])) {
                $segments[] = $query['value'];
                unset($query['value']);
            }
        case 'product':
            if (isset($query['id'])) {
                if (!empty($query['id']) && $query['id'] != 0) {
                    $sql = $db->getQuery(true);
                    $sql->select('config')->from('#__ksen_seo_config')->where('type="url"')->where('part="product"')->where('extension=' . $db->quote('com_ksenmart'));
                    $db->setQuery($sql);
                    $config = json_decode($db->loadResult());
                    
                    foreach ($config as $key => $val) {
                        if ($val->user == 0) {
                            if ($val->active == 1) {
                                if ($key == 'seo-country') {
                                    $sql = $db->getQuery(true);
                                    $sql->select('c.alias,c.id')->from('#__ksenmart_products as p')->leftjoin('#__ksenmart_manufacturers as m on m.id=p.manufacturer')->leftjoin('#__ksenmart_countries as c on m.country=c.id')->where('p.id=' . (int)$query['id']);
                                    $db->setQuery($sql);
                                    $country = $db->loadObject();
                                    if (!empty($country) && $country->alias != '') $segments[] = $country->alias;
                                } elseif ($key == 'seo-manufacturer') {
                                    $sql = $db->getQuery(true);
                                    $sql->select('m.alias,m.id')->from('#__ksenmart_products as p')->leftjoin('#__ksenmart_manufacturers as m on m.id=p.manufacturer')->where('p.id=' . (int)$query['id']);
                                    $db->setQuery($sql);
                                    $manufacturer = $db->loadObject();
                                    if (!empty($manufacturer) && $manufacturer->alias != '') $segments[] = $manufacturer->alias;
                                } elseif ($key == 'seo-parent-category') {
                                    $categories = array();
                                    $sql = $db->getQuery(true);
                                    $sql->select('category_id')->from('#__ksenmart_products_categories')->where('product_id=' . (int)$query['id'])->where('is_default=1');
                                    $db->setQuery($sql);
                                    $default_category = $db->loadResult();
                                    $parent = $default_category;
                                    
                                    while ($parent != 0) {
                                        $sql = $db->getQuery(true);
                                        $sql->select('alias,parent_id')->from('#__ksenmart_categories')->where('id=' . $parent);
                                        $db->setQuery($sql);
                                        $category = $db->loadObject();
                                        if ($category->alias != '' && $parent != $default_category) $categories[] = $category->alias;
                                        $parent = $category->parent_id;
                                    }
                                    $categories = array_reverse($categories);
                                    
                                    foreach ($categories as $category) $segments[] = $category;
                                } elseif ($key == 'seo-category') {
                                    $sql = $db->getQuery(true);
                                    $sql->select('category_id')->from('#__ksenmart_products_categories')->where('product_id=' . (int)$query['id'])->where('is_default=1');
                                    $db->setQuery($sql);
                                    $default_category = $db->loadResult();
                                    
                                    $sql = $db->getQuery(true);
                                    $sql->select('alias')->from('#__ksenmart_categories')->where('id=' . $default_category);
                                    $db->setQuery($sql);
                                    $alias = $db->loadResult();
                                    if (!empty($alias)) $segments[] = $alias;
                                } elseif ($key == 'seo-parent-product') {
                                    $sql = $db->getQuery(true);
                                    $sql->select('pp.alias')->from('#__ksenmart_products as p')->leftjoin('#__ksenmart_products as pp on p.parent_id=pp.id')->where('p.id=' . (int)$query['id']);
                                    $db->setQuery($sql);
                                    $alias = $db->loadResult();
                                    if (!empty($alias)) {
                                        if ($alias != '') $segments[] = $alias;
                                    }
                                } elseif ($key == 'seo-product') {
                                    $sql = $db->getQuery(true);
                                    $sql->select('alias')->from('#__ksenmart_products')->where('id=' . (int)$query['id']);
                                    $db->setQuery($sql);
                                    $alias = $db->loadResult();
                                    if (!empty($alias)) {
                                        if ($alias != '') $segments[] = $alias;
                                        else $segments[] = 'product-' . (int)$query['id'];
                                    }
                                }
                            }
                        } else $segments[] = $val->title;
                    }
                }
                unset($query['id']);
            }
            
            break;
        case 'catalog':
            if (isset($query['categories'])) $query['categories'] = array_filter($query['categories'], 'KSFunctions::filterStrArray');
            if (isset($query['countries'])) $query['countries'] = array_filter($query['countries'], 'KSFunctions::filterStrArray');
            if (isset($query['manufacturers'])) $query['manufacturers'] = array_filter($query['manufacturers'], 'KSFunctions::filterStrArray');
            if (isset($query['properties'])) $query['properties'] = array_filter($query['properties'], 'KSFunctions::filterStrArray');
            
            $search = false;
            $search_add = 0;
            if (isset($query['categories']) && !empty($query['categories']) && is_array($query['categories']) && count($query['categories']) >= 1) $search_add++;
            if (isset($query['manufacturers']) && !empty($query['manufacturers']) && is_array($query['manufacturers']) && count($query['manufacturers']) >= 1) $search_add++;
            if (isset($query['countries']) && !empty($query['countries']) && is_array($query['countries']) && count($query['countries']) >= 1) $search_add++;
            if ($search_add > 1) $search = true;
            
            if (isset($query['categories']) && !empty($query['categories']) && is_array($query['categories']) && count($query['categories']) > 1) $search = true;
            if (isset($query['manufacturers']) && !empty($query['manufacturers']) && is_array($query['manufacturers']) && count($query['manufacturers']) > 1) $search = true;
            if (isset($query['countries']) && !empty($query['countries']) && is_array($query['countries']) && count($query['countries']) > 1) $search = true;
            if (isset($query['properties']) && !empty($query['properties']) && is_array($query['properties']) && count($query['properties']) > 0) $search = true;
            
            if (isset($query['price_less'])) $search = true;
            if (isset($query['price_more'])) $search = true;
            
            if ($search) $segments[] = 'search';
            
            if (isset($query['categories'])) {
                $categories = array();
                if (!empty($query['categories']) && is_array($query['categories'])) {
                    if (count($query['categories']) == 1) {
                        $sql = $db->getQuery(true);
                        $sql->select('config')->from('#__ksen_seo_config')->where('type="url"')->where('part="category"')->where('extension=' . $db->quote('com_ksenmart'));
                        $db->setQuery($sql);
                        $config = json_decode($db->loadResult());
                        
                        foreach ($config as $key => $val) {
                            if ($val->user == 0) {
                                if ($val->active == 1) {
                                    if ($key == 'seo-parent-category') {
                                        $categories = array();
                                        $parent = $query['categories'][0];
                                        
                                        while ($parent != 0) {
                                            $sql = $db->getQuery(true);
                                            $sql->select('alias,parent_id')->from('#__ksenmart_categories')->where('id=' . $parent);
                                            $db->setQuery($sql);
                                            $category = $db->loadObject();
                                            if ($category->alias != '' && $parent != $query['categories'][0]) $categories[] = $category->alias;
                                            $parent = $category->parent_id;
                                        }
                                        $categories = array_reverse($categories);
                                        
                                        foreach ($categories as $category) $segments[] = $category;
                                    } elseif ($key == 'seo-category') {
                                        $category_id = $query['categories'][0];
                                        $sql = $db->getQuery(true);
                                        $sql->select('alias')->from('#__ksenmart_categories')->where('id=' . $category_id);
                                        $db->setQuery($sql);
                                        $alias = $db->loadResult();
                                        if (!empty($alias)) $segments[] = $alias;
                                    }
                                }
                            } else $segments[] = $val->title;
                        }
                    } else {
                        
                        foreach ($query['categories'] as $category) {
                            $sql = $db->getQuery(true);
                            $sql->select('alias')->from('#__ksenmart_categories')->where('id=' . $category);
                            $db->setQuery($sql);
                            $alias = $db->loadResult();
                            if (!empty($alias)) $categories[] = $alias;
                            else $categories[] = 'category-' . $category;
                        }
                        $categories = implode(';', $categories);
                        $segments[] = $categories;
                    }
                }
            }
            if (isset($query['manufacturers'])) {
                if ((empty($query['categories']) || !is_array($query['categories']) || count($query['categories']) != 1) && count($query['manufacturers']) == 1) {
                    $sql = $db->getQuery(true);
                    $sql->select('config')->from('#__ksen_seo_config')->where('type="url"')->where('part="manufacturer"')->where('extension=' . $db->quote('com_ksenmart'));
                    $db->setQuery($sql);
                    $config = json_decode($db->loadResult());
                    
                    foreach ($config as $key => $val) {
                        if ($val->user == 0) {
                            if ($val->active == 1) {
                                if ($key == 'seo-country') {
                                    $sql = $db->getQuery(true);
                                    $sql->select('c.alias,c.id')->from('#__ksenmart_manufacturers as m')->leftjoin('#__ksenmart_countries as c on m.country=c.id')->where('m.id=' . $query['manufacturers'][0]);
                                    $db->setQuery($sql);
                                    $country = $db->loadObject();
                                    if (!empty($country) && $country->alias != '') $segments[] = $country->alias;
                                    else $segments[] = 'country-' . $country->id;
                                }
                                if ($key == 'seo-manufacturer') {
                                    $sql = $db->getQuery(true);
                                    $sql->select('alias,id')->from('#__ksenmart_manufacturers')->where('id=' . $query['manufacturers'][0]);
                                    $db->setQuery($sql);
                                    $manufacturer = $db->loadObject();
                                    if (!empty($manufacturer) && $manufacturer->alias != '') $segments[] = $manufacturer->alias;
                                    else $segments[] = 'manufacturer-' . $manufacturer->id;
                                }
                            }
                        } else $segments[] = $val->title;
                    }
                } else {
                    $manufacturers = array();
                    if (!empty($query['manufacturers']) && is_array($query['manufacturers'])) {
                        
                        foreach ($query['manufacturers'] as $manufacturer) {
                            $sql = $db->getQuery(true);
                            $sql->select('alias')->from('#__ksenmart_manufacturers')->where('id=' . $manufacturer);
                            $db->setQuery($sql);
                            $alias = $db->loadResult();
                            if (!empty($alias)) $manufacturers[] = $alias;
                            else $manufacturers[] = 'manufacturer-' . $manufacturer;
                        }
                        $manufacturers = implode(';', $manufacturers);
                        $segments[] = $manufacturers;
                    }
                }
            }
            if (isset($query['countries'])) {
                if ((empty($query['categories']) || !is_array($query['categories']) || count($query['categories']) != 1) && (empty($query['manufacturers']) || !is_array($query['manufacturers']) || count($query['manufacturers']) != 1) && count($query['countries']) == 1) {
                    $sql = $db->getQuery(true);
                    $sql->select('config')->from('#__ksen_seo_config')->where('type="url"')->where('part="country"')->where('extension=' . $db->quote('com_ksenmart'));
                    $db->setQuery($sql);
                    $config = json_decode($db->loadResult());
                    
                    foreach ($config as $key => $val) {
                        if ($val->user == 0) {
                            if ($val->active == 1) {
                                if ($key == 'seo-country') {
                                    $sql = $db->getQuery(true);
                                    $sql->select('alias,id')->from('#__ksenmart_countries')->where('id=' . $query['countries'][0]);
                                    $db->setQuery($sql);
                                    $country = $db->loadObject();
                                    if (!empty($country) && $country->alias != '') $segments[] = $country->alias;
                                    else $segments[] = 'country-' . $country->id;
                                }
                            }
                        } else $segments[] = $val->title;
                    }
                } else {
                    $countries = array();
                    if (!empty($query['countries']) && is_array($query['countries'])) {
                        
                        foreach ($query['countries'] as $country) {
                            $sql = $db->getQuery(true);
                            $sql->select('alias')->from('#__ksenmart_countries')->where('id=' . $country);
                            $db->setQuery($sql);
                            $alias = $db->loadResult();
                            if (!empty($alias)) $countries[] = $alias;
                            else $countries[] = 'country-' . $country;
                        }
                        $countries = implode(';', $countries);
                        $segments[] = $countries;
                    }
                }
                unset($query['countries']);
            }
            if (isset($query['properties'])) {
                $properties = array();
                if (!empty($query['properties']) && is_array($query['properties'])) {
                    
                    foreach ($query['properties'] as $property) {
                        $sql = $db->getQuery(true);
                        $sql->select('alias,property_id')->from('#__ksenmart_property_values')->where('id=' . $property);
                        $db->setQuery($sql);
                        $property_value = $db->loadObject();
                        if (!isset($properties[$property_value->property_id])) $properties[$property_value->property_id] = array();
                        if (!empty($property_value->alias)) $properties[$property_value->property_id][] = $property_value->alias;
                        else $properties[$property_value->property_id][] = 'propertyvalue-' . $property;
                    }
                    
                    foreach ($properties as $key => $property_values) {
                        $sql = $db->getQuery(true);
                        $sql->select('alias')->from('#__ksenmart_properties')->where('id=' . $key);
                        $db->setQuery($sql);
                        $alias = $db->loadResult();
                        if (!empty($alias)) $segments[] = $alias . '=' . implode(';', $property_values);
                        else $segments[] = 'property-' . $key . '=' . implode(';', $property_values);
                    }
                }
                unset($query['properties']);
            }
            if (isset($query['price_less'])) {
                $segments[] = 'price_less=' . $query['price_less'];
                unset($query['price_less']);
            }
            if (isset($query['price_more'])) {
                $segments[] = 'price_more=' . $query['price_more'];
                unset($query['price_more']);
            }
            if (isset($query['order_type'])) {
                $segments[] = 'order_type=' . $query['order_type'];
                unset($query['order_type']);
            }
            if (isset($query['order_dir'])) {
                $segments[] = 'order_dir=' . $query['order_dir'];
                unset($query['order_dir']);
            }
            if (isset($query['layout']) && $query['layout'] == 'manufacturers') {
                $segments[] = $query['layout'];
                unset($query['layout']);
            }
            
            break;
        }
        unset($query['view']);
        if (isset($query['categories'])) unset($query['categories']);
        if (isset($query['manufacturers'])) unset($query['manufacturers']);
        
        return $segments;
    }
    
    function KsenMartParseRoute($segments) {
        $db = JFactory::getDBO();
        $vars = array();
        $categories = array();
        $properties = array();
        $manufacturers = array();
        $countries = array();
        
        $vars['view'] = 'catalog';
        
        foreach ($segments as $segment) {
            
            switch ($segment) {
                case 'cart':
                    $vars['view'] = 'cart';
                break;
                case 'profile':
                    $vars['view'] = 'profile';
                    if (isset($segments[1])) $vars['layout'] = $segments[1];
                    
                    break;
                case 'reviews':
                    $vars['view'] = 'comments';
                    if (isset($segments[1])) $vars['id'] = $segments[1];
                    
                    break;
                case 'searching':
                    $vars['view'] = 'search';
                    if (isset($segments[1])) $vars['value'] = $segments[1];
                    
                    break;
                default:
                    if (strpos($segment, '=') === false) {
                        $segment = explode(';', $segment);
                        
                        foreach ($segment as $alias) {
                            if ($alias == 'manufacturers') {
                                $vars['view'] = 'catalog';
                                $vars['layout'] = 'manufacturers';
                                
                                continue;
                            }
                            $id = null;
                            $sql = $db->getQuery(true);
                            $sql->select('id')->from('#__ksenmart_categories')->where('alias=' . $db->Quote($alias));
                            $db->setQuery($sql);
                            $id = $db->loadResult();
                            if (!empty($id)) {
                                $categories[] = $id;
                                $vars['view'] = 'catalog';
                                
                                continue;
                            }
                            $sql = $db->getQuery(true);
                            $sql->select('id')->from('#__ksenmart_manufacturers')->where('alias=' . $db->Quote($alias));
                            $db->setQuery($sql);
                            $id = $db->loadResult();
                            if (!empty($id)) {
                                $manufacturers[] = $id;
                                $vars['view'] = 'catalog';
                                
                                continue;
                            }
                            $sql = $db->getQuery(true);
                            $sql->select('id')->from('#__ksenmart_countries')->where('alias=' . $db->Quote($alias));
                            $db->setQuery($sql);
                            $id = $db->loadResult();
                            if (!empty($id)) {
                                $countries[] = $id;
                                $vars['view'] = 'catalog';
                                
                                continue;
                            }
                            $sql = $db->getQuery(true);
                            $sql->select('id')->from('#__ksenmart_products')->where('alias=' . $db->Quote($alias));
                            $db->setQuery($sql);
                            $id = $db->loadResult();
                            if (!empty($id)) {
                                $vars['view'] = 'product';
                                $vars['id'] = $id;
                                
                                continue;
                            }
                        }
                    } else {
                        $segment = explode('=', $segment);
                        
                        switch ($segment[0]) {
                            case 'price_less':
                                $vars['price_less'] = $segment[1];
                            break;
                            case 'price_more':
                                $vars['price_more'] = $segment[1];
                            break;
                            case 'order_type':
                                $vars['order_type'] = $segment[1];
                            break;
                            case 'order_dir':
                                $vars['order_dir'] = $segment[1];
                            break;
                            default:
                                $segment = explode(';', $segment[1]);
                                
                                foreach ($segment as $alias) {
                                    $sql = $db->getQuery(true);
                                    $sql->select('id')->from('#__ksenmart_property_values')->where('alias=' . $db->Quote($alias));
                                    $db->setQuery($sql);
                                    $id = $db->loadResult();
                                    if (!empty($id)) {
                                        $properties[] = $id;
                                        $vars['view'] = 'catalog';
                                    }
                                }
                        }
                    }
                }
            }
            if (count($categories) > 0) {
                $count = count($categories);
                
                for ($k = 1;$k < $count;$k++) {
                    if (isset($categories[1])) {
                        $sql = $db->getQuery(true);
                        $sql->select('parent_id')->from('#__ksenmart_categories')->where('id=' . $categories[1]);
                        $db->setQuery($sql);
                        $parent = $db->loadResult();
                        if ($categories[0] == $parent) array_shift($categories);
                    }
                }
                $vars['categories'] = $categories;
            }
            if (count($properties) > 0) $vars['properties'] = $properties;
            if (count($manufacturers) > 0) $vars['manufacturers'] = $manufacturers;
            if (count($countries) > 0) $vars['countries'] = $countries;
            
            $check_vars = $vars;
            $check_segments = KsenMartBuildRoute($check_vars);
            if (implode('/', $segments) != implode('/', $check_segments)) {
                JError::raiseError(404, 'Page not found');
            }
            
            return $vars;
        }
        