<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

KSSystem::import('models.modelksadmin');
class KsenMartModelDiscounts extends JModelKSAdmin {

    function __construct() {
        parent::__construct();
    }

    function populateState() {
        $this->onExecuteBefore('populateState');

        $app = JFactory::getApplication();
        $params = JComponentHelper::getParams('com_ksenmart');
        if($layout = JRequest::getVar('layout', 'default')) {
            $this->context .= '.' . $layout;
        }

        $value = $app->getUserStateFromRequest($this->context . 'list.limit', 'limit', $params->get('admin_product_limit', 30), 'uint');
        $limit = $value;
        $this->setState('list.limit', $limit);

        $value = $app->getUserStateFromRequest($this->context . '.limitstart', 'limitstart', 0);
        $limitstart = ($limit != 0 ? (floor($value / $limit) * $limit) : 0);
        $this->setState('list.start', $limitstart);

        $order_dir = $app->getUserStateFromRequest($this->context . '.order_dir', 'order_dir', 'asc');
        $this->setState('order_dir', $order_dir);
        $order_type = $app->getUserStateFromRequest($this->context . '.order_type', 'order_type', 'ordering');
        $this->setState('order_type', $order_type);

        $types = $app->getUserStateFromRequest($this->context . '.types', 'types', array());
        $types = array_filter($types, 'KSFunctions::filterStrArray');
        $this->setState('types', $types);

        $discount_type = JRequest::getVar('type', null);
        $this->setState('discount_type', $discount_type);
        $this->setState('discount_params', null);
        $this->setState('discount_id', null);

        $this->onExecuteAfter('populateState');
    }

    function getListItems() {
        $this->onExecuteBefore('getListItems');

        $types = $this->getState('types');
        $order_dir = $this->getState('order_dir');
        $order_type = $this->getState('order_type');
        $query = $this->_db->getQuery(true);
        $query->select('SQL_CALC_FOUND_ROWS d.*,e.name as plugin_name')->from('#__ksenmart_discounts as d')->leftjoin('#__extensions as e on e.element=d.type and e.folder="kmdiscount"')->order($order_type . ' ' . $order_dir);
        if(count($types) > 0) $query->where('e.element in (\'' . implode('\',\'', $types) . '\')');
        $this->_db->setQuery($query, $this->getState('list.start'), $this->getState('list.limit'));
        $items = $this->_db->loadObjectList();
        $query = $this->_db->getQuery(true);
        $query->select('FOUND_ROWS()');
        $this->_db->setQuery($query);
        $this->total = $this->_db->loadResult();

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

        foreach($ids as $id) {
            $query = $this->_db->getQuery(true);
            $query->delete('#__ksenmart_discounts')->where('id=' . $id);
            $this->_db->setQuery($query);
            $this->_db->query();
        }

        $this->onExecuteAfter('deleteListItems', array(&$ids));
        return true;
    }

    function getDiscount() {
        $this->onExecuteBefore('getDiscount');

        $id = JRequest::getInt('id');
        $discount = KSSystem::loadDbItem($id, 'discounts');
        $discount = KSMedia::setItemMedia($discount, 'discount');

        if(empty($discount->from_date)) $discount->from_date = date('d.m.Y');
        if(empty($discount->to_date)) $discount->to_date = date('d.m.Y');
        if(empty($discount->categories)) $discount->categories = '{}';
        if(empty($discount->manufacturers)) $discount->manufacturers = '{}';
        if(empty($discount->regions)) $discount->regions = '{}';
        if(empty($discount->params)) $discount->params = '{}';
        if(empty($discount->user_actions)) $discount->user_actions = '{}';
        if(empty($discount->user_groups)) $discount->user_groups = '{}';
        if(empty($discount->info_methods)) $discount->info_methods = '{}';
        if(empty($discount->type)) $discount->type = $this->getState('discount_type');
        $discount->categories = json_decode($discount->categories, true);
        $discount->manufacturers = json_decode($discount->manufacturers, true);
        $discount->regions = json_decode($discount->regions, true);
        $discount->params = json_decode($discount->params, true);
        $discount->user_actions = json_decode($discount->user_actions, true);
        $discount->user_groups = json_decode($discount->user_groups, true);
        $discount->info_methods = json_decode($discount->info_methods, true);
        $discount->from_date = date('d.m.Y', strtotime($discount->from_date));
        $discount->to_date = date('d.m.Y', strtotime($discount->to_date));

        $this->setState('discount_type', $discount->type);
        $this->setState('discount_params', $discount->params);
        $this->setState('discount_id', $discount->id);

        $this->onExecuteAfter('getDiscount', array(&$discount));
        return $discount;
    }

    function getDiscountParamsForm() {
        $this->onExecuteBefore('getDiscountParamsForm');

        $type = $this->getState('discount_type', null);
        $params = $this->getState('discount_params', null);
        $discount_id = $this->getState('discount_id', null);
        $query = $this->_db->getQuery(true);
        $query->select('enabled')->from('#__extensions')->where('element=' . $this->_db->quote($type))->where('folder="kmdiscount"');
        $this->_db->setQuery($query);
        $enabled = $this->_db->loadResult();
        if(empty($enabled) || !$enabled) return false;
        $dispatcher = JDispatcher::getInstance();
        $results = $dispatcher->trigger('onDisplayParamsForm', array(
            $type,
            $params,
            $discount_id));

        if(isset($results[0]) && $results[0]) {
            $this->onExecuteAfter('getDiscountParamsForm', array(&$results[0]));
            return $results[0];
        }

        return false;
    }

    function SaveDiscount($data) {
        $this->onExecuteBefore('SaveDiscount', array(&$data));

        $data['categories'] = is_array($data['categories']) ? json_encode($data['categories']) : json_encode(array());
        $data['manufacturers'] = is_array($data['manufacturers']) ? json_encode($data['manufacturers']) : json_encode(array());
        $data['regions'] = isset($data['regions']) && is_array($data['regions']) ? $data['regions'] : array();
        $data['params'] = is_array($data['params']) ? json_encode($data['params']) : json_encode(array());
        $data['user_actions'] = isset($data['user_actions']) && is_array($data['user_actions']) ? json_encode($data['user_actions']) : json_encode(array());
        $data['user_groups'] = isset($data['user_groups']) && is_array($data['user_groups']) ? json_encode($data['user_groups']) : json_encode(array());
        $data['info_methods'] = isset($data['info_methods']) && is_array($data['info_methods']) ? json_encode($data['info_methods']) : json_encode(array());
        $data['from_date'] = date('Y-m-d', strtotime($data['from_date']));
        $data['to_date'] = date('Y-m-d', strtotime($data['to_date']));
        $data['sum'] = isset($data['sum']) ? $data['sum'] : 0;
        $data['enabled'] = isset($data['enabled']) ? $data['enabled'] : 0;
        $data['images'] = isset($data['images']) ? $data['images'] : array();
        foreach($data['regions'] as &$country) $country = array_filter($country, 'KSFunctions::filterArray');
        unset($country);
        $data['regions'] = json_encode($data['regions']);
        $table = $this->getTable('discounts');

        if(empty($data['id'])) {
            $query = $this->_db->getQuery(true);
            $query->update('#__ksenmart_discounts')->set('ordering=ordering+1');
            $this->_db->setQuery($query);
            $this->_db->query();
        }

        if(!$table->bindCheckStore($data)) {
            $this->setError($table->getError());
            return false;
        }
        $id = $table->id;
        KSMedia::saveItemMedia($id, $data, 'discount', 'discounts');

        $dispatcher = JDispatcher::getInstance();
        $dispatcher->trigger('onAfterSaveDiscount', array($id));

        $on_close = 'window.parent.DiscountsList.refreshList();';
        $return = array('id' => $id, 'on_close' => $on_close);

        $this->onExecuteAfter('SaveDiscount', array(&$return));
        return $return;
    }
}