<?php
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

require_once JPATH_ROOT.'/plugins/system/ksencore/core/helpers/common/functions.php';

class KsenmartRouter extends JComponentRouterBase
{

    private $_config = array();
    private $_aliases = array();

    function build(&$query)
    {
        if (JFactory::getApplication()->isSite()) {
            if (empty($query['Itemid'])) {
                $menuItem      = $this->menu->getActive();
                $menuItemGiven = false;
            } else {
                $menuItem = $this->menu->getActive();
                //if ($menuItem->component != 'com_ksenmart')
                //{
                $menuItem = $this->menu->getItem($query['Itemid']);
                //}
                /*else
                {
                    $query['Itemid'] = $menuItem->id;
                }*/

                $menuItemGiven = true;
            }

            if ($menuItemGiven && isset($menuItem) && $menuItem->component != 'com_ksenmart') {
                unset($query['Itemid']);
            }

            if (($menuItem instanceof stdClass)
                && isset($query['view'])
                && $menuItem->query['view'] == $query['view']
                && ((isset($query['id']) && $menuItem->query['id'] == (int) $query['id'])
                    || (isset($query['categories']) && isset($menuItem->query['categories']) && $menuItem->query['categories'] == $query['categories']))
            ) {
                unset($query['view']);

                if (isset($query['layout'])) {
                    unset($query['layout']);
                }

                if (isset($query['categories'])) {
                    unset($query['categories']);
                }

                unset($query['id']);
            }
        }

        $db = JFactory::getDbo();
        if (empty($this->_config)) {
            $configs = KSSystem::getSeoConfig('com_ksenmart');

            foreach ($configs as $config) {
                if ($config->type != 'url') {
                    continue;
                }
                $this->_config['url'][$config->part] = json_decode($config->config);
            }
        }
        $segments = array();

        if (empty($query['Itemid'])) {
            unset($query['Itemid']);
        }
        if (!isset($query['view'])) {
            $query['view'] = 'catalog';
        }
        if (isset($query['task']) && $query['task'] == 'catalog.filter_products') {
            unset($query['task']);
        }

        switch ($query['view']) {
            case 'cart':
                $segments[] = 'cart';
                break;
            case 'comments':
                if (!isset($query['Itemid'])) {
                    $segments[] = 'comments';
                    $segments[] = $query['layout'];
                }
                unset($query['layout']);
                if (isset($query['id']) && !empty($query['id']) && $query['id'] != 0) {
                    $segments[] = $query['id'];
                    unset($query['id']);
                }
                break;
            case 'product':
                if (!isset($query['id']) || empty($query['id']) || $query['id'] == 0) {
                    unset($query['id']);
                    unset($query['parent_id']);
                    break;
                }
                $config = $this->_config['url']['product'];

                foreach ($config as $key => $val) {
                    if ($val->user != 0) {
                        $segments[] = $val->title;
                        continue;
                    }
                    if ($val->active != 1) {
                        continue;
                    }
                    switch ($key) {
                        case 'seo-country':
                            $sql = $db->getQuery(true);
                            $sql->select('c.alias,c.id')->from('#__ksenmart_products as p')->leftJoin('#__ksenmart_manufacturers as m on m.id=p.manufacturer')->leftJoin('#__ksenmart_countries as c on m.country=c.id')->where('p.id='.(int) $query['id']);
                            $db->setQuery($sql);
                            $country = $db->loadObject();
                            $this->setAlias('countries', $country, $segments);
                            break;
                        case 'seo-manufacturer':
                            $sql = $db->getQuery(true);
                            $sql->select('m.alias,m.id')->from('#__ksenmart_products as p')->leftJoin('#__ksenmart_manufacturers as m on m.id=p.manufacturer')->where('p.id='.$db->q((int) $query['id']));
                            $db->setQuery($sql);
                            $manufacturer = $db->loadObject();
                            $this->setAlias('manufacturers', $manufacturer, $segments);
                            break;
                        case 'seo-parent-category':
                            $categories = array();
                            $sql        = $db->getQuery(true);
                            $sql->select('category_id')->from('#__ksenmart_products_categories')->where('product_id='.(int) $query['id'])->where('is_default=1');
                            $db->setQuery($sql);
                            $default_category = $db->loadResult();
                            $parent           = $default_category;

                            while ($parent != 0) {
                                $category = $this->getAlias('categories', $parent, 'alias, parent_id');

                                if ($category->alias != '' && $parent != $default_category) {
                                    $categories[] = $category->alias;
                                }
                                $parent = $category->parent_id;
                            }
                            $categories = array_reverse($categories);

                            foreach ($categories as $category) {
                                $segments[] = $category;
                            }
                            break;
                        case 'seo-category':
                            $sql = $db->getQuery(true);
                            $sql->select('category_id')->from('#__ksenmart_products_categories')->where('product_id='.$db->q((int) $query['id']))->where('is_default=1');
                            $db->setQuery($sql);
                            $default_category = $db->loadResult();

                            $alias = $this->getAlias('categories', $default_category);
                            if (!empty($alias)) {
                                $segments[] = $alias;
                            }
                            break;
                        case 'seo-parent-product':
                            if (empty($query['parent_id'])) {
                                $sql = $db->getQuery(true);
                                $sql->select('pp.alias')->from('#__ksenmart_products as p')->leftJoin('#__ksenmart_products as pp on p.parent_id=pp.id')->where('p.id='.(int) $query['id']);
                                $db->setQuery($sql);
                                $alias = $db->loadResult();
                            } elseif ($query['parent_id'] > 0) {
                                $alias = $this->getAlias('products', $query['parent_id']);
                            }
                            if (!empty($alias)) {
                                $segments[] = $alias;
                            }
                            break;
                        case 'seo-product':
                            $alias      = $this->getAlias('products', $query['id']);
                            $segments[] = empty($alias) ? 'product-'.(int) $query['id'] : $alias;

                            break;
                    }
                }
                unset($query['id']);
                unset($query['parent_id']);

                break;
            case 'catalog':
                if (isset($query['layout']) && $query['layout'] == 'catalog') {
                    unset($query['layout']);
                }
                if (isset($query['categories'])) {
                    $query['categories'] = array_filter($query['categories'], 'KSFunctions::filterStrArray');
                }
                if (isset($query['countries'])) {
                    $query['countries'] = array_filter($query['countries'], 'KSFunctions::filterStrArray');
                }
                if (isset($query['manufacturers'])) {
                    $query['manufacturers'] = array_filter($query['manufacturers'], 'KSFunctions::filterStrArray');
                }
                if (isset($query['properties'])) {
                    $query['properties'] = \Joomla\Utilities\ArrayHelper::toInteger($query['properties']);
                }
                $search     = false;
                $search_add = 0;
                if (isset($query['categories']) && !empty($query['categories']) && is_array($query['categories']) && count($query['categories']) >= 1) {
                    $search_add++;
                }
                if (isset($query['manufacturers']) && !empty($query['manufacturers']) && is_array($query['manufacturers']) && count($query['manufacturers']) >= 1) {
                    $search_add++;
                }
                if (isset($query['countries']) && !empty($query['countries']) && is_array($query['countries']) && count($query['countries']) >= 1) {
                    $search_add++;
                }
                if ($search_add > 1) {
                    $search = true;
                }

                if (isset($query['categories']) && !empty($query['categories']) && is_array($query['categories']) && count($query['categories']) > 1) {
                    $search = true;
                }
                if (isset($query['manufacturers']) && !empty($query['manufacturers']) && is_array($query['manufacturers']) && count($query['manufacturers']) > 1) {
                    $search = true;
                }
                if (isset($query['countries']) && !empty($query['countries']) && is_array($query['countries']) && count($query['countries']) > 1) {
                    $search = true;
                }
                if (isset($query['properties']) && !empty($query['properties']) && is_array($query['properties']) && count($query['properties']) > 0) {
                    $search = true;
                }

                if (isset($query['price_less'])) {
                    $search = true;
                }
                if (isset($query['price_more'])) {
                    $search = true;
                }

                if ($search) {
                    $segments[] = 'search';
                }

                if (isset($query['plugin'])) {
                    $segments[] = 'plugin';
                    $segments[] = $query['plugin'];
                    if (isset($query['pluginvars'])) {
                        foreach ($query['pluginvars'] as $var) {
                            $segments[] = $var;
                        }
                    }
                }
                if (isset($query['categories'])) {
                    $categories = array();
                    if (!empty($query['categories']) && is_array($query['categories'])) {
                        if (count($query['categories']) == 1) {
                            $config = $this->_config['url']['category'];

                            foreach ($config as $key => $val) {
                                if ($val->user != 0) {
                                    $segments[] = $val->title;
                                    continue;
                                }
                                if ($val->active != 1) {
                                    continue;
                                }
                                if ($key == 'seo-parent-category') {
                                    $categories = [];
                                    $parent     = $query['categories'][0];
                                    $slug       = explode(':', $parent);
                                    $parent     = $slug[0];

                                    while ($parent != 0) {
                                        $sql = $db->getQuery(true);
                                        $sql->select('alias,parent_id')->from('#__ksenmart_categories')->where('id='.$parent);
                                        $db->setQuery($sql);
                                        $category = $db->loadObject();
                                        if ($category->alias != '' && $parent != (int) $query['categories'][0]) {
                                            $categories[]                          = $category->alias;
                                            $this->_aliases['categories'][$parent] = $category->alias;
                                        }
                                        $parent = $category->parent_id;
                                    }
                                    $categories = array_reverse($categories);

                                    foreach ($categories as $category) {
                                        $segments[] = $category;
                                    }
                                } elseif ($key == 'seo-category') {
                                    $category_id = $query['categories'][0];
                                    $slug        = explode(':', $category_id);
                                    if (!isset($menuItem->query['categories']) || $menuItem->query['categories'][0] != $slug[0]) {
                                        if (!empty($slug[1])) {
                                            $alias = trim($slug[1]);
                                        }
                                        if (empty($alias)) {
                                            $alias = $this->getAlias('categories', $category_id);
                                        }
                                        if (!empty($alias)) {
                                            $segments[] = $alias;
                                        }
                                    }
                                }
                            }
                        } else {
                            foreach ($query['categories'] as $category) {
                                $alias        = $this->getAlias('categories', $category);
                                $categories[] = empty($alias) ? 'category-'.$category : $alias;
                            }
                            $categories = implode(';', $categories);
                            $segments[] = $categories;
                        }
                    }
                }
                if (isset($query['manufacturers'])) {
                    if ((empty($query['categories']) || !is_array($query['categories']) || count($query['categories']) != 1) && count($query['manufacturers']) == 1) {
                        $config = $this->_config['url']['manufacturer'];

                        foreach ($config as $key => $val) {
                            if ($val->user != 0) {
                                $segments[] = $val->title;
                                continue;
                            }
                            if ($val->active != 1) {
                                continue;
                            }
                            if ($key == 'seo-country') {
                                $sql = $db->getQuery(true);
                                $sql->select('c.alias,c.id')->from('#__ksenmart_manufacturers as m')->leftJoin('#__ksenmart_countries as c on m.country=c.id')->where('m.id='.$query['manufacturers'][0]);
                                $db->setQuery($sql);
                                $country = $db->loadObject();
                                if (!empty($country) && $country->alias != '') {
                                    $segments[] = $country->alias;
                                } else {
                                    $segments[] = 'country-'.$country->id;
                                }
                            }
                            if ($key == 'seo-manufacturer') {
                                $sql = $db->getQuery(true);
                                $sql->select('alias,id')->from('#__ksenmart_manufacturers')->where('id='.$query['manufacturers'][0]);
                                $db->setQuery($sql);
                                $manufacturer = $db->loadObject();
                                if (!empty($manufacturer) && $manufacturer->alias != '') {
                                    $segments[] = $manufacturer->alias;
                                } else {
                                    $segments[] = 'manufacturer-'.$manufacturer->id;
                                }
                            }
                        }
                    } else {
                        $manufacturers = array();
                        if (!empty($query['manufacturers']) && is_array($query['manufacturers'])) {

                            foreach ($query['manufacturers'] as $manufacturer) {
                                $sql = $db->getQuery(true);
                                $sql->select('alias')->from('#__ksenmart_manufacturers')->where('id='.$manufacturer);
                                $db->setQuery($sql);
                                $alias = $db->loadResult();
                                if (!empty($alias)) {
                                    $manufacturers[] = $alias;
                                } else {
                                    $manufacturers[] = 'manufacturer-'.$manufacturer;
                                }
                            }
                            $manufacturers = implode(';', $manufacturers);
                            $segments[]    = $manufacturers;
                        }
                    }
                }
                if (isset($query['countries'])) {
                    if ((empty($query['categories']) || !is_array($query['categories']) || count($query['categories']) != 1) && (empty($query['manufacturers']) || !is_array($query['manufacturers']) || count($query['manufacturers']) != 1) && count($query['countries']) == 1) {
                        $config = $this->_config['url']['country'];

                        foreach ($config as $key => $val) {
                            if ($val->user != 0) {
                                $segments[] = $val->title;
                                continue;
                            }
                            if ($val->active != 1) {
                                continue;
                            }
                            if ($key == 'seo-country') {
                                $sql = $db->getQuery(true);
                                $sql->select('alias,id')->from('#__ksenmart_countries')->where('id='.$query['countries'][0]);
                                $db->setQuery($sql);
                                $country = $db->loadObject();
                                if (!empty($country) && $country->alias != '') {
                                    $segments[] = $country->alias;
                                } else {
                                    $segments[] = 'country-'.$country->id;
                                }
                            }
                        }
                    } else {
                        $countries = array();
                        if (!empty($query['countries']) && is_array($query['countries'])) {

                            foreach ($query['countries'] as $country) {
                                $sql = $db->getQuery(true);
                                $sql->select('alias')->from('#__ksenmart_countries')->where('id='.$country);
                                $db->setQuery($sql);
                                $alias = $db->loadResult();
                                if (!empty($alias)) {
                                    $countries[] = $alias;
                                } else {
                                    $countries[] = 'country-'.$country;
                                }
                            }
                            $countries  = implode(';', $countries);
                            $segments[] = $countries;
                        }
                    }
                    unset($query['countries']);
                }
                if (isset($query['range_properties'])) {
                    $properties = array();
                    if (!empty($query['range_properties']) && is_array($query['range_properties'])) {
                        foreach ($query['range_properties'] as $key => $property) {
                            if (!empty($property['min'])) {
                                $sql = $db->getQuery(true);
                                $sql
                                    ->select('id')
                                    ->from('#__ksenmart_property_values')
                                    ->where('property_id='.(int) $key)
                                    ->where('title <= '.(int) $property['min'])
                                    ->order('title DESC');
                                $db->setQuery($sql);
                                $result = $db->loadResult();
                                if (empty($result)) {
                                    $sql = $db->getQuery(true);
                                    $sql
                                        ->select('id')
                                        ->from('#__ksenmart_property_values')
                                        ->where('property_id='.(int) $key)
                                        ->order('title ASC');
                                    $db->setQuery($sql);
                                    $result = $db->loadResult();
                                }
                                $properties[] = $result;
                            }
                            if (!empty($property['max'])) {
                                $sql = $db->getQuery(true);
                                $sql
                                    ->select('id')
                                    ->from('#__ksenmart_property_values')
                                    ->where('property_id='.(int) $key)
                                    ->where('title >= '.$property['max'])
                                    ->order('title ASC');
                                $db->setQuery($sql);
                                $result = $db->loadResult();
                                if (empty($result)) {
                                    $sql = $db->getQuery(true);
                                    $sql
                                        ->select('id')
                                        ->from('#__ksenmart_property_values')
                                        ->where('property_id='.(int) $key)
                                        ->order('title DESC');
                                    $db->setQuery($sql);
                                    $result = $db->loadResult();
                                }
                                $properties[] = $result;
                            }
                        }
                        if (empty($query['properties'])) {
                            $query['properties'] = $properties;
                        } else {
                            $query['properties'] = array_merge($query['properties'], $properties);
                            $query['properties'] = array_unique($query['properties']);
                        }
                    }
                    unset($query['range_properties']);
                }
                if (isset($query['properties'])) {
                    $properties = array();
                    if (!empty($query['properties']) && is_array($query['properties'])) {
                        if (!empty($this->_aliases['values'][implode(',', $query['properties'])])) {
                            $property_values = $this->_aliases['values'][implode(',', $query['properties'])];
                        } else {
                            $sql = $db->getQuery(true);
                            $sql->select('id,alias,property_id')->from('#__ksenmart_property_values')->where('id IN ('.implode(',',
                                    $query['properties']).')');
                            $db->setQuery($sql);
                            $property_values                                              = $db->loadObjectList('id');
                            $this->_aliases['values'][implode(',', $query['properties'])] = $property_values;
                        }
                        foreach ($query['properties'] as $property) {
                            if (!isset($property_values[$property])) {
                                continue;
                            }
                            if (!isset($properties[$property_values[$property]->property_id])) {
                                $properties[$property_values[$property]->property_id] = array();
                            }
                            if (!empty($property_values[$property]->alias)) {
                                $properties[$property_values[$property]->property_id][] = $property_values[$property]->alias;
                            } else {
                                $properties[$property_values[$property]->property_id][] = 'propertyvalue-'.$property;
                            }
                        }

                        foreach ($properties as $key => $property_values) {
                            if (!empty($this->_aliases['properties'][$key])) {
                                $property = $this->_aliases['properties'][$key];
                            } else {
                                $sql = $db->getQuery(true);
                                $sql->select($db->qn(array(
                                    'alias', 'range'
                                )))->from('#__ksenmart_properties')->where('id='.$key);
                                $db->setQuery($sql);
                                $property = $db->loadObject();
                            }
                            if (!empty($property)) {
                                if ($property->range) {
                                    $segments[] = $property->alias.'='.implode('|', $property_values);
                                } else {
                                    $segments[] = $property->alias.'='.implode(';', $property_values);
                                }
                                $this->_aliases['properties'][$key] = $property;
                            } else {
                                $segments[] = 'property-'.$key.'='.implode(';', $property_values);
                            }
                        }
                    }
                    unset($query['properties']);
                }
                if (isset($query['price_less'])) {
                    $segments[] = 'price_less='.$query['price_less'];
                    unset($query['price_less']);
                }
                if (isset($query['price_more'])) {
                    $segments[] = 'price_more='.$query['price_more'];
                    unset($query['price_more']);
                }
                if (isset($query['order_type'])) {
                    $segments[] = 'order_type='.$query['order_type'];
                    unset($query['order_type']);
                }
                if (isset($query['order_dir'])) {
                    $segments[] = 'order_dir='.$query['order_dir'];
                    unset($query['order_dir']);
                }
                if (isset($query['layout']) && $query['layout'] == 'manufacturers') {
                    $segments[] = $query['layout'];
                    unset($query['layout']);
                }
                if (isset($query['title']) && empty($query['title'])) {
                    unset($query['title']);
                }

                break;
        }
        unset($query['view']);
        if (isset($query['categories'])) {
            unset($query['categories']);
        }
        if (isset($query['manufacturers'])) {
            unset($query['manufacturers']);
        }

        return $segments;
    }

    function parse(&$segments)
    {
        $db               = JFactory::getDbo();
        $vars             = array();
        $categories       = array();
        $properties       = array();
        $range_properties = array();
        $manufacturers    = array();
        $countries        = array();

        $item = $this->menu->getActive();
        if (!isset($item) || $item->id == KSSystem::getShopItemid()) {
            $vars['view'] = 'catalog';
            $segment      = $segments[0];
        } else {
            $vars['Itemid'] = $item->id;
            $segment        = $item->query['view'];
            if (isset($item->query['layout'])) {
                $vars['layout'] = $item->query['layout'];
            }
        }

        switch ($segment) {
            case 'cart':
                $vars['view'] = 'cart';
                break;
            case 'plugin':
                $vars['view'] = 'catalog';
                if (isset($segments[1])) {
                    $vars['plugin'] = $segments[1];
                }
                if (count($segments) > 2) {
                    $c_segments         = 0;
                    $vars['pluginvars'] = array();
                    foreach ($segments as $segment) {
                        if ($c_segments > 1) {
                            $vars['pluginvars'][] = $segment;
                        }
                        $c_segments++;
                    }
                }
                break;
            case 'comments':
                $vars['view'] = 'comments';
                if (!isset($item)) {
                    $vars['layout'] = $segments[1];
                    if (isset($segments[2])) {
                        $vars['id']     = $segments[2];
                        $vars['layout'] = $vars['layout'] == 'reviews' ? 'review' : 'comment';
                    }
                } else {
                    if (isset($segments[0])) {
                        $vars['id']     = $segments[0];
                        $vars['layout'] = $vars['layout'] == 'reviews' ? 'review' : 'comment';
                    }
                }

                break;
            default:
                foreach ($segments as $segment) {
                    if (strpos($segment, '=') === false) {
                        $segment = explode(';', $segment);

                        foreach ($segment as $alias) {
                            if ($alias == 'manufacturers') {
                                $vars['view']   = 'catalog';
                                $vars['layout'] = 'manufacturers';

                                continue;
                            }
                            $id  = null;
                            $sql = $db->getQuery(true);
                            $sql->select('id')->from('#__ksenmart_categories')->where('alias='.$db->quote($alias));
                            $db->setQuery($sql);
                            $id = $db->loadResult();
                            if (!empty($id)) {
                                $categories[]  = $id;
                                $category_slug = $id.':'.$alias;
                                $vars['view']  = 'catalog';

                                continue;
                            }
                            $sql = $db->getQuery(true);
                            $sql->select('id')->from('#__ksenmart_manufacturers')->where('alias='.$db->quote($alias));
                            $db->setQuery($sql);
                            $id = $db->loadResult();
                            if (!empty($id)) {
                                $manufacturers[] = $id;
                                $vars['view']    = 'catalog';

                                continue;
                            }
                            $sql = $db->getQuery(true);
                            $sql->select('id')->from('#__ksenmart_countries')->where('alias='.$db->quote($alias));
                            $db->setQuery($sql);
                            $id = $db->loadResult();
                            if (!empty($id)) {
                                $countries[]  = $id;
                                $vars['view'] = 'catalog';

                                continue;
                            }
                            $sql = $db->getQuery(true);
                            $sql->select('id, parent_id')->from('#__ksenmart_products')->where('alias='.$db->quote($alias));
                            $db->setQuery($sql);
                            $product = $db->loadObject();
                            if (!empty($product)) {
                                $vars['view']      = 'product';
                                $vars['id']        = $product->id.':'.$alias;
                                $vars['parent_id'] = $product->parent_id;

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
                                $values = explode('|', $segment[1]);
                                if (count($values) > 1) {
                                    $check_properties = array();
                                    $sql              = $db->getQuery(true);
                                    $sql->select('id')->from('#__ksenmart_properties')->where('alias='.$db->quote($segment[0]));
                                    $db->setQuery($sql);
                                    $property_id                    = $db->loadResult();
                                    $range_properties[$property_id] = array();
                                    foreach ($values as $alias) {
                                        $sql = $db->getQuery(true);
                                        $sql->select('id, title')->from('#__ksenmart_property_values')->where('alias='.$db->quote($alias));
                                        $db->setQuery($sql);
                                        $value = $db->loadObject();
                                        if (!empty($value)) {
                                            $range_properties[$property_id][] = $value->title;
                                            $check_properties[]               = $value->id;
                                            $vars['view']                     = 'catalog';
                                        }
                                    }
                                } else {
                                    $segment = explode(';', $segment[1]);

                                    foreach ($segment as $alias) {
                                        $sql = $db->getQuery(true);
                                        $sql->select('id')->from('#__ksenmart_property_values')->where('alias='.$db->quote($alias));
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
        }
        if (count($categories) > 0) {
            $count = count($categories);

            for ($k = 1; $k < $count; $k++) {
                if (isset($categories[1])) {
                    $sql = $db->getQuery(true);
                    $sql->select('parent_id')->from('#__ksenmart_categories')->where('id='.$categories[1]);
                    $db->setQuery($sql);
                    $parent = $db->loadResult();
                    if ($categories[0] == $parent) {
                        array_shift($categories);
                    }
                }
            }
            $vars['categories'] = $categories;
        }
        if (count($properties) > 0 || count($range_properties) > 0) {
            $vars['properties'] = $properties;
        }
        if (count($range_properties) > 0) {
            $vars['range_properties'] = $range_properties;
        }
        if (count($manufacturers) > 0) {
            $vars['manufacturers'] = $manufacturers;
        }
        if (count($countries) > 0) {
            $vars['countries'] = $countries;
        }

        $check_vars = $vars;
        if (isset($check_properties)) {
            $check_vars['properties'] = array_merge($check_vars['properties'], $check_properties);
        }
        if (isset($category_slug)) {
            $check_vars['categories'][0] = $category_slug;
        }
        $check_segments = KsenmartBuildRoute($check_vars);
        if (implode('/', $segments) != implode('/', $check_segments)) {
            JError::raiseError(404, 'Page not found');
        }

        return $vars;
    }

    private function getAlias($type, $id, $columns = 'alias')
    {
        $slug = explode(':', $id);

        if (!empty($slug[1])) {
            $this->_aliases[$type][$columns][$slug[0]] = trim($slug[1]);

            return $this->_aliases[$type][$columns][$slug[0]];
        }

        if (!empty($this->_aliases[$type][$columns][$id])) {
            return $this->_aliases[$type][$columns][$id];
        }

        $parts = explode(',', $columns);

        $db  = JFactory::getDbo();
        $sql = $db->getQuery(true);
        $sql->select($columns)->from('#__ksenmart_'.$type)->where('id='.$db->q($id));
        $db->setQuery($sql);
        if (count($parts) > 1) {
            $alias = $db->loadObject();
        } else {
            $alias = $db->loadResult();
        }

        if (!empty($alias)) {
            $this->_aliases[$type][$columns][$id] = $alias;

            return $alias;
        }

        return null;
    }

    private function setAlias($type, $value, &$segments)
    {
        if (!empty($value) && $value->alias != '') {
            $segments[]                        = $value->alias;
            $this->_aliases[$type][$value->id] = $value->alias;
        }
    }
}

function KsenmartBuildRoute(&$query)
{
    $router = new KsenmartRouter;

    return $router->build($query);
}

function KsenmartParseRoute($segments)
{
    $router = new KsenmartRouter;

    return $router->parse($segments);
}
        