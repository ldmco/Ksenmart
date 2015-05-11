<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

KSSystem::import('models.modelksadmin');
class KsenMartModelShippings extends JModelKSAdmin {

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
		
		$methods=$app->getUserStateFromRequest($this->context . '.methods', 'methods', array());
		$methods=array_filter($methods,'KSFunctions::filterStrArray');
		$this->setState('methods',$methods);			
		
		$shipping_type=JRequest::getVar('type',null);
		$this->setState('shipping_type', $shipping_type);
		$this->setState('shipping_params', null);	
		$this->setState('shipping_id', null);
        
        $this->onExecuteAfter('populateState');				
	}	
	
	function getListItems()
	{
	    $this->onExecuteBefore('getListItems');
        
		$methods=$this->getState('methods');
		$order_dir=$this->getState('order_dir');
		$order_type=$this->getState('order_type');
		$query=$this->_db->getQuery(true);		
		$query->select('SQL_CALC_FOUND_ROWS s.*,e.name as plugin_name')->from('#__ksenmart_shippings as s')
		->leftjoin('#__extensions as e on e.element=s.type and e.folder="kmshipping"')
		->order($order_type.' '.$order_dir);
		if (count($methods)>0)
			$query->where('e.element in (\''.implode('\',\'',$methods).'\')');	
		$query=KSMedia::setItemMainImageToQuery($query,'shipping','s.');		
		$this->_db->setQuery($query,$this->getState('list.start'),$this->getState('list.limit'));
		$items=$this->_db->loadObjectList();	
		$query=$this->_db->getQuery(true);
		$query->select('FOUND_ROWS()');
		$this->_db->setQuery($query);
		$this->total=$this->_db->loadResult();	
		foreach($items as &$item)
		{
			$item->folder='shippings';
			$item->small_img = KSMedia::resizeImage($item->filename,$item->folder,$this->params->get('admin_product_thumb_image_width',36),$this->params->get('admin_product_thumb_image_heigth',36),json_decode($item->params,true));
			$item->medium_img = KSMedia::resizeImage($item->filename, $item->folder,$this->params->get('admin_product_medium_image_width',120),$this->params->get('admin_product_medium_image_heigth',120),json_decode($item->params,true));
		}
		
        $this->onExecuteAfter('getListItems',array(&$items));
		return $items;
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
			$query->delete('#__ksenmart_shippings')->where('id='.$id);
			$this->_db->setQuery($query);
			$this->_db->query();		
			$query = $this->_db->getQuery(true);
			$query->select('id')->from('#__ksenmart_shipping_fields')->where('shipping_id='.$id);
			$this->_db->setQuery($query);
			$fids=$this->_db->loadColumn();	
			if (count($fids)>0)
			{
				$query = $this->_db->getQuery(true);
				$query->delete('#__ksenmart_shipping_fields')->where('id in ('.implode(',',$fids).')');
				$this->_db->setQuery($query);
				$this->_db->query();	
				$query = $this->_db->getQuery(true);
				$query->delete('#__ksenmart_shipping_fields_values')->where('field_id in ('.implode(',',$fids).')');
				$this->_db->setQuery($query);
				$this->_db->query();
			}		
		}
        
        $this->onExecuteAfter('deleteListItems',array(&$ids));
		return true;
	}	
	
	function getShipping()
	{
	    $this->onExecuteBefore('getShipping');
        
		$id=JRequest::getInt('id');
		$shipping=KSSystem::loadDbItem($id,'shippings');
		$shipping=KSMedia::setItemMedia($shipping,'shipping');
		$shipping->user_fields=array();
		$shipping->address_fields=array();

		if (empty($shipping->params))
			$shipping->params='{}';
		if (empty($shipping->regions))
			$shipping->regions='{}';
		if (empty($shipping->type))
			$shipping->type=$this->getState('shipping_type');
		$shipping->params=json_decode($shipping->params,true);
		$shipping->regions=json_decode($shipping->regions,true);
		
		if ($id>0)
		{
			$query = $this->_db->getQuery(true);
			$query->select('*')->from('#__ksenmart_shipping_fields')->where('shipping_id='.(int)$shipping->id)->where('position='.$this->_db->quote('customer'))->order('ordering');
			$this->_db->setQuery($query);
			$shipping->user_fields = $this->_db->loadAssocList('id');
			foreach($shipping->user_fields as &$user_field)
			{
				if ($user_field['type']=='select')
				{
					$query = $this->_db->getQuery(true);
					$query->select('*')->from('#__ksenmart_shipping_fields_values')->where('field_id='.$user_field['id']);
					$this->_db->setQuery($query);
					$user_field['values']= $this->_db->loadObjectList('id');			
				}
			}
			$query = $this->_db->getQuery(true);
			$query->select('*')->from('#__ksenmart_shipping_fields')->where('shipping_id='.(int)$shipping->id)->where('position='.$this->_db->quote('address'))->order('ordering');
			$this->_db->setQuery($query);
			$shipping->address_fields = $this->_db->loadAssocList('id');
			foreach($shipping->address_fields as &$address_field)
			{
				if ($address_field['type']=='select')
				{
					$query = $this->_db->getQuery(true);
					$query->select('*')->from('#__ksenmart_shipping_fields_values')->where('field_id='.$address_field['id']);
					$this->_db->setQuery($query);
					$address_field['values']= $this->_db->loadObjectList('id');			
				}
			}
		}
		
		$this->setState('shipping_type',$shipping->type);
		$this->setState('shipping_params',$shipping->params);
		$this->setState('shipping_id',$shipping->id);
		
        $this->onExecuteAfter('getShipping', array(&$shipping));
        return $shipping;
	}
	
	function getShippingParamsForm()
	{
	    $this->onExecuteBefore('getShippingParamsForm');
        
		$type=$this->getState('shipping_type',null);
		$params=$this->getState('shipping_params',null);
		$shipping_id=$this->getState('shipping_id',null);
		$query=$this->_db->getQuery(true);
		$query->select('enabled')->from('#__extensions')->where('element='.$this->_db->quote($type))->where('folder="kmshipping"');
		$this->_db->setQuery($query);
		$enabled=$this->_db->loadResult();
		if (empty($enabled) || !$enabled)
			return false;		
		$dispatcher	= JDispatcher::getInstance();
		$results = $dispatcher->trigger('onDisplayParamsForm',array($type,$params,$shipping_id));
		if (isset($results[0]) && $results[0])
        {
            $this->onExecuteAfter('getShippingParamsForm', array(&$results[0]));
            return $results[0];
        }
			
		return false;	
	}	
	
	function SaveShipping($data)
	{
	    $this->onExecuteBefore('SaveShipping', array(&$data));
        
		$data['params']=isset($data['params']) && is_array($data['params'])?json_encode($data['params']):json_encode(array());
		$data['regions']=isset($data['regions']) && is_array($data['regions'])?$data['regions']:array();
		$data['published']=isset($data['published'])?$data['published']:0;
		$data['user_fields']=isset($data['user_fields'])?$data['user_fields']:array();
		$data['images']=isset($data['images'])?$data['images']:array();
		foreach($data['regions'] as &$country)
			$country=array_filter($country,'KSFunctions::filterArray');
		unset($country);	
		$data['regions']=json_encode($data['regions']);
		$table = $this->getTable('shippings');
		
		if (empty($data['id'])) {
			$query=$this->_db->getQuery(true);
			$query->update('#__ksenmart_shippings')->set('ordering=ordering+1');
			$this->_db->setQuery($query);
			$this->_db->query();
		}
		
		if (!$table->bindCheckStore($data)) {
			$this->setError($table->getError());
			return false;
		}
		$id = $table->id;
		KSMedia::saveItemMedia($id,$data,'shipping','shippings');
		
		$in = array();
		foreach ($data['user_fields'] as $key=>$field) {
			$key = (int)$key;
			if ($key>0) {
				$field['id'] = $key;
			}
			$field['shipping_id'] = $id;
			$field['required']=isset($field['required'])?$field['required']:0;
			$table = $this->getTable('ShippingFields');
			if (!$table->bindCheckStore($field)) {
				$this->setError($table->getError());
				return false;
			}
			$in[] = $table->id;
			if ($field['type']=='select')
			{
				foreach ($field['values'] as $key=>$value) {
					$key = (int)$key;
					if ($key>0) {
						$value['id'] = $key;
					}
					$value['field_id'] = $table->id;
					$vtable = $this->getTable('ShippingFieldsValues');
					if (!$vtable->bindCheckStore($value)) {
						$this->setError($vtable->getError());
						return false;
					}
				}	
			}
		}
		foreach ($data['address_fields'] as $key=>$field) {
			$key = (int)$key;
			if ($key>0) {
				$field['id'] = $key;
			}
			$field['shipping_id'] = $id;
			$field['required']=isset($field['required'])?$field['required']:0;
			$table = $this->getTable('ShippingFields');
			if (!$table->bindCheckStore($field)) {
				$this->setError($table->getError());
				return false;
			}
			$in[] = $table->id;
			if ($field['type']=='select')
			{
				foreach ($field['values'] as $key=>$value) {
					$key = (int)$key;
					if ($key>0) {
						$value['id'] = $key;
					}
					$value['field_id'] = $table->id;
					$vtable = $this->getTable('ShippingFieldsValues');
					if (!$vtable->bindCheckStore($value)) {
						$this->setError($vtable->getError());
						return false;
					}
				}	
			}
		}		
		$query = $this->_db->getQuery(true);
		$query->select('id')->from('#__ksenmart_shipping_fields')->where('shipping_id='.$id);
		if (count($in)){
			$query->where('id not in ('.implode(', ', $in).')');
		}
		$this->_db->setQuery($query);
		$ids=$this->_db->loadColumn();	
		if (count($ids)>0)
		{
			$query = $this->_db->getQuery(true);
			$query->delete('#__ksenmart_shipping_fields')->where('id in ('.implode(',',$ids).')');
			$this->_db->setQuery($query);
			$this->_db->query();	
			$query = $this->_db->getQuery(true);
			$query->delete('#__ksenmart_shipping_fields_values')->where('field_id in ('.implode(',',$ids).')');
			$this->_db->setQuery($query);
			$this->_db->query();
		}	
		
		$dispatcher	= JDispatcher::getInstance();
		$dispatcher->trigger('onAfterSaveShipping',array($id));		
		
		$on_close='window.parent.ShippingsList.refreshList();';
		$return=array('id'=>$id,'on_close'=>$on_close);
        
        $this->onExecuteAfter('SaveShipping', array(&$return));
		return $return;
	}
}
