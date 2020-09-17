<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

KSSystem::import('models.modelksadmin');
class KsenMartModelManufacturers extends JModelKSAdmin {

	private $menu = array();
	private $tree = array();

    function populateState() {
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
		
		$countries = $app->getUserStateFromRequest($this->context . '.countries', 'countries', array());
        JArrayHelper::toInteger($countries);
        $countries = array_filter($countries, 'KSFunctions::filterArray');
        $this->setState('countries', $countries);

        $searchword = $app->getUserStateFromRequest($this->context . '.searchword', 'searchword', null);
        $this->setState('searchword', $searchword);

        $this->onExecuteAfter('populateState');
    }

    function getListItems() {
        $this->onExecuteBefore('getListItems');

		$order_dir = $this->getState('order_dir');
        $order_type = $this->getState('order_type');
        $searchword = $this->getState('searchword');
		$countries = $this->getState('countries');
		$order_type = 'm.' . $order_type;
        $query = $this->_db->getQuery(true);
        $query->select('SQL_CALC_FOUND_ROWS m.*')->from('#__ksenmart_manufacturers as m')->order($order_type . ' ' . $order_dir);
        if(!empty($searchword)) $query->where('m.title like ' . $this->_db->quote('%' . $searchword . '%'));
		if(count($countries) > 0) $query->where('m.country in (' . implode(',', $countries) . ')');
		$query->group('m.id');
		$query = KSMedia::setItemMainImageToQuery($query, 'manufacturer', 'm.');
        $this->_db->setQuery($query, $this->getState('list.start'), $this->getState('list.limit'));
        $items = $this->_db->loadObjectList();
        $query = $this->_db->getQuery(true);
        $query->select('FOUND_ROWS()');
        $this->_db->setQuery($query);
        $this->total = $this->_db->loadResult();
		foreach($items as &$item) {
            $item->folder = 'manufacturers';
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

		$table = $this->getTable('manufacturers');
        foreach($ids as $id) {
			$table->load($id);
			$table->delete($id);
			KSMedia::deleteItemMedia($id, 'manufacturer');
			
			$query = $this->_db->getQuery(true);
			$query->update('#__ksenmart_products')->set('manufacturer=0')->where('manufacturer=' . $id);
			$this->_db->setQuery($query);
			$this->_db->query();
        }

        $this->onExecuteAfter('deleteListItems', array(&$ids));
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

        $on_close = 'window.parent.ManufacturersList.refreshList();';
        $return = array('id' => $id, 'on_close' => $on_close);

        $this->onExecuteAfter('saveManufacturer', array(&$return));
        return $return;
    }
}