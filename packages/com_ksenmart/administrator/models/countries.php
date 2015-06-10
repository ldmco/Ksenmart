<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

KSSystem::import('models.modelksadmin');
class KsenMartModelCountries extends JModelKSAdmin{

	function __construct() {
		parent::__construct();
	}
	
	function populateState()
	{
	   $this->onExecuteBefore('populateState');
       
		$app = JFactory::getApplication();
		$params = JComponentHelper::getParams('com_ksenmart');
		if ($layout = JRequest::getVar('layout')) {
			$this->context .= '.'.$layout;
		}
		
		$value = $app->getUserStateFromRequest($this->context.'list.limit', 'limit', $params->get('admin_product_limit',30), 'uint');
		$limit = $value;
		$this->setState('list.limit', $limit);
		
		$value = $app->getUserStateFromRequest($this->context . '.limitstart', 'limitstart', 0);
		$limitstart = ($limit != 0 ? (floor($value / $limit) * $limit) : 0);
		$this->setState('list.start', $limitstart);	

		$order_dir=$app->getUserStateFromRequest($this->context . '.order_dir', 'order_dir', 'asc');
		$this->setState('order_dir',$order_dir);
		$order_type=$app->getUserStateFromRequest($this->context . '.order_type', 'order_type', 'ordering');
		$this->setState('order_type',$order_type);
		
		$countries=$app->getUserStateFromRequest($this->context . '.countries', 'countries', array());
		JArrayHelper::toInteger($countries);
		$countries=array_filter($countries,'KSFunctions::filterArray');
		$this->setState('countries',$countries);
        
        $this->onExecuteAfter('populateState');
	}	
	
	function getListItems()
	{
	    $this->onExecuteBefore('getListItems');
        
		$countries=$this->getState('countries');
		$order_dir=$this->getState('order_dir');
		$order_type=$this->getState('order_type');
		$query=$this->_db->getQuery(true);		
		$query->select('SQL_CALC_FOUND_ROWS *')->from('#__ksenmart_regions')->order($order_type.' '.$order_dir);
		if (count($countries)>0)
			$query->where('country_id in ('.implode(',',$countries).')');		
		$this->_db->setQuery($query,$this->getState('list.start'),$this->getState('list.limit'));
		$regions=$this->_db->loadObjectList();
		$query=$this->_db->getQuery(true);
		$query->select('FOUND_ROWS()');
		$this->_db->setQuery($query);
		$this->total=$this->_db->loadResult();
        
        $this->onExecuteAfter('getListItems', array(&$regions));			
		return $regions;
	}
	
	function getTotal()
	{
		$this->onExecuteBefore('getTotal');
		
		$total=$this->total;
		
		$this->onExecuteAfter('getTotal',array(&$total));
		return $total;
	}	
	
	function deleteListItems($ids)
	{
	    $this->onExecuteBefore('deleteListItems', array(&$ids));
       
		$table=$this->getTable('regions');
		foreach($ids as $id)
		{
			$table->delete($id);
			KSMedia::deleteItemMedia($id,'region');
		}
        
        $this->onExecuteAfter('deleteListItems', array(&$ids));
		return true;
	}	
	
	function getCountry()
	{
	    $this->onExecuteBefore('getCountry');
        
		$id=JRequest::getInt('id');
		$country=KSSystem::loadDbItem($id,'countries');
		$country=KSMedia::setItemMedia($country,'country');
        
        $this->onExecuteAfter('getCountry', array(&$country));
		return $country;
	}	
	
	function saveCountry($data)
	{
	    $this->onExecuteBefore('saveCountry', array(&$data));
        
		$data['alias']=KSFunctions::CheckAlias($data['alias'],$data['id']);
		$data['alias']=$data['alias']==''?KSFunctions::GenAlias($data['title']):$data['alias'];
		$table = $this->getTable('countries');
		if (!$table->bindCheckStore($data)) {
			$this->setError($table->getError());
			return false;
		}
		$id = $table->id;	
		KSMedia::saveItemMedia($id,$data,'country','countries');
	
		$on_close='window.parent.CountriesModule.refresh();';
		$return=array('id'=>$id,'on_close'=>$on_close);
		
        $this->onExecuteAfter('saveCountry', array(&$return));
		return $return;
	}	
	
	function deleteCountry($id)
	{
	    $this->onExecuteBefore('deleteCountry', array(&$id));
        
		$table=$this->getTable('countries');
		$table->delete($id);
		KSMedia::deleteItemMedia($id,'country');
		$query=$this->_db->getQuery(true);	
		$query->update('#__ksenmart_regions')->set('country_id=0')->where('country_id='.$id);
		$this->_db->setQuery($query);
		$this->_db->query();
        
        $this->onExecuteAfter('deleteCountry', array(&$id));	
		return true;
	}	
	
	function getRegion()
	{
	    $this->onExecuteBefore('getRegion');
       
		$id=JRequest::getInt('id');
		$region=KSSystem::loadDbItem($id,'regions');
		$region=KSMedia::setItemMedia($region,'region');
        
        $this->onExecuteAfter('getRegion', array(&$region));
		return $region;
	}
	
	function saveRegion($data)
	{
	    $this->onExecuteBefore('saveRegion', array(&$data));
       
		$data['alias']=KSFunctions::CheckAlias($data['alias'],$data['id']);
		$data['alias']=$data['alias']==''?KSFunctions::GenAlias($data['title']):$data['alias'];
		$data['country_id']=isset($data['country_id'])?$data['country_id']:0;
		$table = $this->getTable('regions');
		
		if (empty($data['id'])) {
			$query=$this->_db->getQuery(true);
			$query->update('#__ksenmart_regions')->set('ordering=ordering+1');
			$this->_db->setQuery($query);
			$this->_db->query();
		}		
		
		if (!$table->bindCheckStore($data)) {
			$this->setError($table->getError());
			return false;
		}
		$id = $table->id;	
		KSMedia::saveItemMedia($id,$data,'region','regions');
	
		$on_close='window.parent.RegionsList.refreshList();';
		$return=array('id'=>$id,'on_close'=>$on_close);
		
        $this->onExecuteAfter('saveRegion', array(&$return));
		return $return;
	}	
}
