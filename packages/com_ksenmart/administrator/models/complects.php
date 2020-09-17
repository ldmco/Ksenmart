<?php
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

KSSystem::import('models.modelksadmin');

class KsenMartModelComplects extends JModelKSAdmin {

	protected function populateState() {
		$this->onExecuteBefore('populateState');

		$app = JFactory::getApplication();

		$value = $app->getUserStateFromRequest($this->context . 'list.limit', 'limit', $this->params->get('admin_product_limit', 30), 'uint');
		$limit = $value;
		$this->setState('list.limit', $limit);

		$value      = $app->getUserStateFromRequest($this->context . '.limitstart', 'limitstart', 0);
		$limitstart = ($limit != 0 ? (floor($value / $limit) * $limit) : 0);
		$this->setState('list.start', $limitstart);

		$complects = $app->getUserStateFromRequest($this->context . '.complects', 'complects', array());
		\Joomla\Utilities\ArrayHelper::toInteger($complects);
		$complects = array_filter($complects, 'KSFunctions::filterArray');
		$this->setState('complects', $complects);

		$groups = $app->getUserStateFromRequest($this->context . '.groups', 'groups', array());
		\Joomla\Utilities\ArrayHelper::toInteger($groups);
		$groups = array_filter($groups, 'KSFunctions::filterArray');
		$this->setState('manufacturers', $groups);

		$order_dir = $app->getUserStateFromRequest($this->context . '.order_dir', 'order_dir', 'asc');
		$this->setState('order_dir', $order_dir);
		$order_type = $app->getUserStateFromRequest($this->context . '.order_type', 'order_type', 'ordering');
		$this->setState('order_type', $order_type);

		$searchword = $app->getUserStateFromRequest($this->context . '.searchword', 'searchword', null);
		$this->setState('searchword', $searchword);

		$excluded = $app->getUserStateFromRequest($this->context . '.excluded', 'excluded', array());
		\Joomla\Utilities\ArrayHelper::toInteger($excluded);
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

		$order_dir  = $this->getState('order_dir');
		$order_type = $this->getState('order_type');
		$searchword = $this->getState('searchword');
		$excluded   = $this->getState('excluded');
		$complects  = $this->getState('complects');
		$groups     = $this->getState('groups');
		if ($order_type != 'ordered') $order_type = 's.' . $order_type;
		$query = $this->_db->getQuery(true);
		$query->select('SQL_CALC_FOUND_ROWS s.*')->from('#__ksenmart_services as s')->order($order_type . ' ' . $order_dir);
		if (!empty($searchword)) $query->where('s.title like ' . $this->_db->quote('%' . $searchword . '%'));
		if (count($groups) > 0) $query->where('s.group in (' . implode(',', $groups) . ')');
		if (count($excluded) > 0) $query->where('s.id not in (' . implode(',', $excluded) . ')');
		if (count($complects) > 0) {
			$query->innerJoin('#__ksenmart_services_complects as sc on sc.service_id=s.id');
			$query->where('sc.complect_id in (' . implode(', ', $complects) . ')');
		}
		$query->group('s.id');
		$this->_db->setQuery($query, $this->getState('list.start'), $this->getState('list.limit'));
		$items = $this->_db->loadObjectList();
		$query = $this->_db->getQuery(true);
		$query->select('FOUND_ROWS()');
		$this->_db->setQuery($query);
		$this->total = $this->_db->loadResult();

		$this->onExecuteAfter('getListItems', array(&$items));

		return $items;
	}

	function deleteListItems($ids) {
		$this->onExecuteBefore('deleteListItems', array(&$ids));

		foreach ($ids as $id) {
			$query = $this->_db->getQuery(true);
			$query->delete('#__ksenmart_services_complects')->where('service_id=' . $id);
			$this->_db->setQuery($query);
			$this->_db->execute();

			$query = $this->_db->getQuery(true);
			$query->delete('#__ksenmart_services')->where('id=' . $id);
			$this->_db->setQuery($query);
			$this->_db->execute();
		}

		$this->onExecuteAfter('deleteListItems', array(&$ids));

		return true;
	}

	function getTotal() {
		$this->onExecuteBefore('getTotal');

		$total = $this->total;

		$this->onExecuteAfter('getTotal', array(&$total));

		return $total;
	}

	function getService() {
		$this->onExecuteBefore('getService', array());


		$id                 = JFactory::getApplication()->input->getInt('id', 0);
		$service            = KSSystem::loadDbItem($id, 'services');
		$service->complects = array();

		$query = $this->_db->getQuery(true);
		$query->select('complect_id')->from('#__ksenmart_services_complects')->where('service_id=' . $id);
		$this->_db->setQuery($query);
		$service->complects = $this->_db->loadColumn();

		$this->onExecuteAfter('getService', array(&$service));

		return $service;
	}

	function saveService($data) {
		$this->onExecuteBefore('saveService', array(&$data));

		$data['alias'] = KSFunctions::checkAlias($data['alias'], $data['id']);
		$data['alias'] = $data['alias'] == '' ? KSFunctions::GenAlias($data['title']) : $data['alias'];
		$table         = $this->getTable('services');

		if (empty($data['id'])) {
			$query = $this->_db->getQuery(true);
			$query->update('#__ksenmart_services')->set('ordering=ordering+1');
			$this->_db->setQuery($query);
			$this->_db->execute();
		}

		if (!$table->bindCheckStore($data)) {
			$this->setError($table->getError());

			return false;
		}
		$id = $table->id;

		\Joomla\Utilities\ArrayHelper::toInteger($data['complects']);
		$in = array();
		foreach ($data['complects'] as $complect_id) {
			$table = $this->getTable('ServiceComplects');
			$d     = array(
				'service_id'  => $id,
				'complect_id' => $complect_id,
			);
			if ($table->load($d)) {
				$d['id'] = $table->id;
			}
			if (!$table->bindCheckStore($d)) {
				$this->setError($table->getError());

				return false;
			}
			$in[] = $table->id;
		}
		$query = $this->_db->getQuery(true);
		$query->delete('#__ksenmart_services_complects')->where('service_id=' . $id);
		if (count($in)) {
			$query->where('id not in (' . implode(',', $in) . ')');
		}
		$this->_db->setQuery($query);
		$this->_db->execute();

		$on_close = 'window.parent.ServicesList.refreshList();';
		$return   = array('id' => $id, 'on_close' => $on_close);

		$this->onExecuteAfter('saveService', array(&$return));

		return $return;
	}

	function getComplect() {
		$this->onExecuteBefore('getComplect');


		$id                 = JFactory::getApplication()->input->getInt('id', 0);
		$complect           = KSSystem::loadDbItem($id, 'complects');
		$complect->services = array();

		$query = $this->_db->getQuery(true);
		$query->select('service_id')->from('#__ksenmart_services_complects')->where('complect_id=' . $id);
		$this->_db->setQuery($query);
		$complect->services = $this->_db->loadColumn();

		$this->onExecuteAfter('getComplect', array(&$complect));

		return $complect;
	}

	function saveComplect($data) {
		$this->onExecuteBefore('saveComplect', array(&$data));

		$data['alias'] = KSFunctions::checkAlias($data['alias'], $data['id']);
		$data['alias'] = $data['alias'] == '' ? KSFunctions::GenAlias($data['title']) : $data['alias'];
		$table         = $this->getTable('complects');

		if (empty($data['id'])) {
			$query = $this->_db->getQuery(true);
			$query->update('#__ksenmart_complects')->set('ordering=ordering+1');
			$this->_db->setQuery($query);
			$this->_db->execute();
		}

		if (!$table->bindCheckStore($data)) {
			$this->setError($table->getError());

			return false;
		}
		$id = $table->id;

		\Joomla\Utilities\ArrayHelper::toInteger($data['complects']);
		$in = array();
		foreach ($data['services'] as $service_id) {
			$table = $this->getTable('ServiceComplects');
			$d     = array(
				'complect_id' => $id,
				'service_id'  => $service_id,
			);
			if ($table->load($d)) {
				$d['id'] = $table->id;
			}
			if (!$table->bindCheckStore($d)) {
				$this->setError($table->getError());

				return false;
			}
			$in[] = $table->id;
		}
		$query = $this->_db->getQuery(true);
		$query->delete('#__ksenmart_services_complects')->where('complect_id=' . $id);
		if (count($in)) {
			$query->where('id not in (' . implode(',', $in) . ')');
		}
		$this->_db->setQuery($query);
		$this->_db->execute();

		$on_close = '
			if(typeof window.parent.ComplectsModule != "undefined") window.parent.ComplectsModule.refresh();
		';
		$return   = array('id' => $id, 'on_close' => $on_close);

		$this->onExecuteAfter('saveComplect', array(&$return));

		return $return;
	}

	function deleteComplect($id) {
		$this->onExecuteBefore('deleteComplect', array(&$id));

		$table = $this->getTable('complects');
		$table->delete($id);

		$query = $this->_db->getQuery(true);
		$query->delete('#__ksenmart_services_complects')->where('complect_id=' . $id);
		$this->_db->setQuery($query);
		$this->_db->execute();

		$query = $this->_db->getQuery(true);
		$query->delete('#__ksenmart_products_complects')->where('complect_id=' . $id);
		$this->_db->setQuery($query);
		$this->_db->execute();

		$this->onExecuteAfter('deleteComplect', array(&$id));

		return true;
	}

	function getServicesgroup() {
		$this->onExecuteBefore('getServicesgroup');


		$id              = JFactory::getApplication()->input->getInt('id', 0);
		$group           = KSSystem::loadDbItem($id, 'servicesgroups');
		$group->services = array();

		if ($id > 0) {
			$query = $this->_db->getQuery(true);
			$query->select('id')->from('#__ksenmart_services')->where('`group`=' . $id);
			$this->_db->setQuery($query);
			$group->services = $this->_db->loadColumn();
		}

		$this->onExecuteAfter('getServicesgroup', array(&$group));

		return $group;
	}

	function saveServicesgroup($data) {
		$this->onExecuteBefore('saveServicesgroup', array(&$data));

		$data['alias'] = KSFunctions::checkAlias($data['alias'], $data['id']);
		$data['alias'] = $data['alias'] == '' ? KSFunctions::GenAlias($data['title']) : $data['alias'];
		$table         = $this->getTable('servicesgroups');

		if (empty($data['id'])) {
			$query = $this->_db->getQuery(true);
			$query->update('#__ksenmart_services_groups')->set('ordering=ordering+1');
			$this->_db->setQuery($query);
			$this->_db->execute();
		}

		if (!$table->bindCheckStore($data)) {
			$this->setError($table->getError());

			return false;
		}
		$id = $table->id;

		\Joomla\Utilities\ArrayHelper::toInteger($data['complects']);
		foreach ($data['services'] as $service_id) {
			$table = $this->getTable('services');
			$d     = array(
				'id'    => $service_id,
				'group' => $id,
			);
			if (!$table->bindCheckStore($d)) {
				$this->setError($table->getError());

				return false;
			}
		}

		$on_close = '
			if(typeof window.parent.ServicesGroupsModule != "undefined") window.parent.ServicesGroupsModule.refresh();
		';
		$return   = array('id' => $id, 'on_close' => $on_close);

		$this->onExecuteAfter('saveServicesgroup', array(&$return));

		return $return;
	}

	function deleteServicesgroup($id) {
		$this->onExecuteBefore('deleteServicesgroup', array(&$id));

		$table = $this->getTable('servicesgroups');
		$table->delete($id);

		$query = $this->_db->getQuery(true);
		$query->update('#__ksenmart_services')->set('`group`=0')->where('`group`=' . $id);
		$this->_db->setQuery($query);
		$this->_db->execute();

		$this->onExecuteAfter('deleteServicesgroup', array(&$id));

		return true;
	}

	function getModifier() {
		$this->onExecuteBefore('getModifier');

		$id       = JFactory::getApplication()->input->getInt('id', 0);
		$modifier = KSSystem::loadDbItem($id, 'modifier');
		$modifier = KSMedia::setItemMedia($modifier, 'modifier');

		$this->onExecuteAfter('getModifier', array(&$modifier));

		return $modifier;
	}

	function saveModifier($data) {
		$this->onExecuteBefore('saveModifier', array(&$data));

		$table         = $this->getTable('modifier');

		if (empty($data['id'])) {
			$query = $this->_db->getQuery(true);
			$query->update('#__ksenmart_complects_modifier')->set('ordering=ordering+1');
			$this->_db->setQuery($query);
			$this->_db->execute();
		}

		if (!$table->bindCheckStore($data)) {
			$this->setError($table->getError());

			return false;
		}
		$id = $table->id;
		KSMedia::saveItemMedia($id, $data, 'modifier', 'modifiers');

		$on_close = '
			if(typeof window.parent.ModifiersModule != "undefined") window.parent.ModifiersModule.refresh();
		';
		$return   = array('id' => $id, 'on_close' => $on_close);

		$this->onExecuteAfter('saveModifier', array(&$return));

		return $return;
	}

	function deleteModifier($id) {
		$this->onExecuteBefore('deleteModifier', array(&$id));

		$table = $this->getTable('modifier');
		$table->delete($id);
		KSMedia::deleteItemMedia($id, 'category');

		$this->onExecuteAfter('deleteModifier', array(&$id));

		return true;
	}

}
