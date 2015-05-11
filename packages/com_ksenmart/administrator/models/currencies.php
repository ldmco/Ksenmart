<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

KSSystem::import('models.modelksadmin');
class KsenMartModelCurrencies extends JModelKSAdmin {

    function __construct() {
        parent::__construct();
    }

    function populateState() {
        $this->onExecuteBefore('populateState');
        
        $app = JFactory::getApplication();
        $params = JComponentHelper::getParams('com_ksenmart');
        if($layout = JRequest::getVar('layout')) {
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
        $order_type = $app->getUserStateFromRequest($this->context . '.order_type', 'order_type', 'id');
        $this->setState('order_type', $order_type);
        
        $this->onExecuteAfter('populateState');
    }

    function getListItems() {
        
        $this->onExecuteBefore('getListItems');
        
        $order_dir = $this->getState('order_dir');
        $order_type = $this->getState('order_type');
        $query = $this->_db->getQuery(true);
        $query->select('SQL_CALC_FOUND_ROWS *')->from('#__ksenmart_currencies')->order($order_type . ' ' . $order_dir);
        $this->_db->setQuery($query, $this->getState('list.start'), $this->getState('list.limit'));
        $currencies = $this->_db->loadObjectList();
        $query = $this->_db->getQuery(true);
        $query->select('FOUND_ROWS()');
        $this->_db->setQuery($query);
        $this->total = $this->_db->loadResult();
        
        $this->onExecuteAfter('getListItems', array(&$currencies));
        return $currencies;
    }

    function getTotal()
	{
		$this->onExecuteBefore('getTotal');
		
		$total=$this->total;
		
		$this->onExecuteAfter('getTotal',array(&$total));
		return $total;
	}

    function deleteListItems($ids) {
        
        $this->onExecuteBefore('deleteListItems', array(&$ids));
        
        $table = $this->getTable('currencies');
        foreach($ids as $id) $table->delete($id);
        $this->setDefaultCurrency();
        
        $this->onExecuteAfter('deleteListItems');
        return true;
    }

    function getCurrency() {
        
        $this->onExecuteBefore('getCurrency');
        
        $id = JRequest::getInt('id');
        $currency = KSSystem::loadDbItem($id, 'currencies');
        
        $this->onExecuteAfter('getCurrency', array(&$currency));
        return $currency;
    }

    function SaveCurrency($data) {
        
        $this->onExecuteBefore('SaveCurrency', array(&$data));
        
        $data['default'] = isset($data['default']) ? $data['default'] : 0;
        $table = $this->getTable('currencies');

        if(!$table->bindCheckStore($data)) {
            $this->setError($table->getError());
            return false;
        }
        $id = $table->id;
        if($data['default'] == 1) $this->setDefaultCurrency($id);

        $on_close = 'window.parent.CurrenciesRatesModule.refresh();window.parent.CurrenciesList.refreshList();';
        $return = array('id' => $id, 'on_close' => $on_close);
        
        $this->onExecuteAfter('SaveCurrency', array(&$return));
        return $return;
    }

    function setDefaultCurrency($id = null) {
        
        $this->onExecuteBefore('setDefaultCurrency', array(&$id));
        
        if(empty($id)) {
            $query = $this->_db->getQuery(true);
            $query->select('id')->from('#__ksenmart_currencies')->where('`default`=1');
            $this->_db->setQuery($query);
            $id = $this->_db->loadResult();
            if(empty($id)) {
                $query = $this->_db->getQuery(true);
                $query->select('id')->from('#__ksenmart_currencies');
                $this->_db->setQuery($query, 0, 1);
                $id = $this->_db->loadResult();
            }
        }
        $query = $this->_db->getQuery(true);
        $query->select('*')->from('#__ksenmart_currencies')->where('id=' . $id);
        $this->_db->setQuery($query);
        $default = $this->_db->loadObject();
        $query = $this->_db->getQuery(true);
        $query->select('*')->from('#__ksenmart_currencies');
        $this->_db->setQuery($query);
        $currencies = $this->_db->loadObjectList();
        foreach($currencies as $currency) {
            $rate = round($currency->rate / $default->rate, 6);
            $query = $this->_db->getQuery(true);
            $query->update('#__ksenmart_currencies')->set('`default`=0')->set('rate=' . $rate)->where('id=' . $currency->id);
            $this->_db->setQuery($query);
            $this->_db->query();
        }
        $query = $this->_db->getQuery(true);
        $query->update('#__ksenmart_currencies')->set('`default`=1')->set('rate=1')->where('id=' . $id);
        $this->_db->setQuery($query);
        $this->_db->query();
        
        $this->onExecuteAfter('setDefaultCurrency');
    }
}