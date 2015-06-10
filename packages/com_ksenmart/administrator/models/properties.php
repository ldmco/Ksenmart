<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

KSSystem::import('models.modelksadmin');
class KsenMartModelProperties extends JModelKSAdmin {

	function __construct() {
		parent::__construct();
	}
	
	function populateState()
	{
	    $this->onExecuteBefore('populateState');
        
		$app = JFactory::getApplication();
		$params = JComponentHelper::getParams('com_ksenmart');
		if ($layout = JRequest::getVar('layout','default')) {
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
		
		$categories=$app->getUserStateFromRequest($this->context . '.categories', 'categories', array());
		JArrayHelper::toInteger($categories);
		$categories=array_filter($categories,'KSFunctions::filterArray');
		$this->setState('categories',$categories);
        
        $this->onExecuteAfter('populateState');
	}
	
	function getListItems()
	{
	    $this->onExecuteBefore('getListItems');
        
		$categories=$this->getState('categories');
		$order_dir=$this->getState('order_dir');
		$order_type=$this->getState('order_type');
		$query=$this->_db->getQuery(true);		
		$query->select('SQL_CALC_FOUND_ROWS p.*')->from('#__ksenmart_properties as p')->order('p.'.$order_type.' '.$order_dir);
		if (count($categories)>0)
		{
			$query->innerjoin('#__ksenmart_product_categories_properties as pcp on pcp.property_id=p.id');
			$query->where('pcp.category_id in ('.implode(', ', $categories).')');
		}	
		$query->group('p.id');
		$this->_db->setQuery($query,$this->getState('list.start'),$this->getState('list.limit'));
		$properties=$this->_db->loadObjectList();
		$query=$this->_db->getQuery(true);
		$query->select('FOUND_ROWS()');
		$this->_db->setQuery($query);
		$this->total=$this->_db->loadResult();
        
        $this->onExecuteAfter('getListItems',array(&$properties));		
		return $properties;
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
	    $this->onExecuteBefore('deleteListItems',array(&$ids));
        
		foreach($ids as $id)
		{
			$query = $this->_db->getQuery(true);
			$query->delete('#__ksenmart_properties')->where('id='.$id);
			$this->_db->setQuery($query);
			$this->_db->query();
			$query = $this->_db->getQuery(true);
			$query->delete('#__ksenmart_property_values')->where('property_id='.$id);
			$this->_db->setQuery($query);
			$this->_db->query();
			$query = $this->_db->getQuery(true);
			$query->delete('#__ksenmart_product_properties_values')->where('property_id='.$id);
			$this->_db->setQuery($query);
			$this->_db->query();
			$query = $this->_db->getQuery(true);
			$query->delete('#__ksenmart_product_categories_properties')->where('property_id='.$id);
			$this->_db->setQuery($query);
			$this->_db->query();
		}
        
        $this->onExecuteAfter('deleteListItems',array(&$ids));
		return true;
	}	
	
	function getProperty()
	{
	    $this->onExecuteBefore('getProperty');
        
		$id=JRequest::getInt('id');
		$property=KSSystem::loadDbItem($id,'properties');
		$property->type=$id>0?$property->type:'text';
		
		$query = $this->_db->getQuery(true);
		$query->select('category_id')->from('#__ksenmart_product_categories_properties')->where('property_id='.$id);
		$this->_db->setQuery($query);
		$property->categories = $this->_db->loadColumn();
		
		$query = $this->_db->getQuery(true);
		$query->select('*')->from('#__ksenmart_property_values')->where('property_id='.$id)->order('ordering');
		$this->_db->setQuery($query);
		$property->values = $this->_db->loadObjectList();		
        
        $this->onExecuteAfter('getProperty', array(&$property));
		return $property;
	}	
	
	function saveProperty($data)
	{
	    $this->onExecuteBefore('saveProperty', array(&$data));
        
		$data['alias']=KSFunctions::CheckAlias($data['alias'],$data['id']);
		$data['alias']=$data['alias']==''?KSFunctions::GenAlias($data['title']):$data['alias'];
		$data['published']=isset($data['published'])?$data['published']:0;
		$data['edit_price']=isset($data['edit_price'])?$data['edit_price']:0;
		$table = $this->getTable('properties');
		
		if (empty($data['id'])) {
			$query=$this->_db->getQuery(true);
			$query->update('#__ksenmart_properties')->set('ordering=ordering+1');
			$this->_db->setQuery($query);
			$this->_db->query();
		}
		
		if (!$table->bindCheckStore($data)) {
			$this->setError($table->getError());
			return false;
		}
		$id = $table->id;
		
		$in = array();
		foreach ($data['categories'] as $v) {
			$table = $this->getTable('PropertiesCategories');
			$d = array(
				'category_id'=>$v,
				'property_id'=>$id
			);
			if ($table->load($d)){
				$d['id']=$table->id;
			}
			if (!$table->bindCheckStore($d)) {
				$this->setError($table->getError());
				return false;
			}
			$in[] = $table->id;
		}
		$query = $this->_db->getQuery(true);
		$query->delete('#__ksenmart_product_categories_properties')->where('property_id='.$id);
		if (count($in))
			$query->where('id not in ('.implode(', ', $in).')');
		$this->_db->setQuery($query);
		$this->_db->query();
		
		$in = array();
		foreach ($data['values'] as $k=>$v) {
			$v['property_id'] = $id;
			$table = $this->getTable('PropertiesValues');
			if ($k>0)
				$v['id'] = $k;
			$v['alias']=KSFunctions::CheckAlias($v['alias'],$v['id']);
			$v['alias']=$v['alias']==''?KSFunctions::GenAlias($v['title']):$v['alias'];			
			if (!$table->bindCheckStore($v)) {
				$this->setError($table->getError());
				return false;
			}
			$in[] = $table->id;
		}
		$query = $this->_db->getQuery(true);
		$query->delete('#__ksenmart_property_values')->where('property_id='.$id);
		if (count($in))
			$query->where('id not in ('.implode(',', $in).')');
		$this->_db->setQuery($query);
		$this->_db->query();
		$query = $this->_db->getQuery(true);
		$query->delete('#__ksenmart_product_properties_values')->where('property_id='.$id);
		if (count($in))
			$query->where('value_id not in ('.implode(',', $in).')');
		$this->_db->setQuery($query);
		$this->_db->query();		
		
		$on_close='window.parent.PropertiesList.refreshList();';
		$return=array('id'=>$id,'on_close'=>$on_close);
		
        $this->onExecuteAfter('saveProperty', array(&$return));
		return $return;
	}
}