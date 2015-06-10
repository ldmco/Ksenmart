<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

KSSystem::import('models.modelksadmin');
class KsenMartModelPayments extends JModelKSAdmin {

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
		
		$types=$app->getUserStateFromRequest($this->context . '.types', 'types', array());
		$types=array_filter($types,'KSFunctions::filterStrArray');
		$this->setState('types',$types);

		$payment_type=JRequest::getVar('type',null);
		$this->setState('payment_type', $payment_type);
		$this->setState('payment_params', null);	
		$this->setState('payment_id', null);
        
        $this->onExecuteAfter('populateState');		
	}

	function getListItems()
	{
	    $this->onExecuteBefore('getListItems');
        
		$types=$this->getState('types');
		$order_dir=$this->getState('order_dir');
		$order_type=$this->getState('order_type');
		$query=$this->_db->getQuery(true);		
		$query->select('SQL_CALC_FOUND_ROWS kp.*,e.name as plugin_name')->from('#__ksenmart_payments as kp')
		->leftjoin('#__extensions as e on e.element=kp.type and e.folder="kmpayment"')
		->order($order_type.' '.$order_dir);
		if (count($types)>0)
			$query->where('e.element in (\''.implode('\',\'',$types).'\')');		
		$query=KSMedia::setItemMainImageToQuery($query,'payment','kp.');
		$this->_db->setQuery($query,$this->getState('list.start'),$this->getState('list.limit'));
		$items=$this->_db->loadObjectList();	
		$query=$this->_db->getQuery(true);
		$query->select('FOUND_ROWS()');
		$this->_db->setQuery($query);
		$this->total=$this->_db->loadResult();
		foreach($items as &$item)
		{
			$item->folder='payments';
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
			$query->delete('#__ksenmart_payments')->where('id='.$id);
			$this->_db->setQuery($query);
			$this->_db->query();		
		}
		
        $this->onExecuteAfter('deleteListItems',array(&$ids));
		return true;
	}	

    function getPayment($vars=array())
	{
	    $this->onExecuteBefore('getPayment',array(&$vars));
        
		$id=JRequest::getInt('id');
		$payment=KSSystem::loadDbItem($id,'payments');
		$payment=KSMedia::setItemMedia($payment,'payment');
		if (empty($payment->params))
			$payment->params='{}';
		if (empty($payment->regions))
			$payment->regions='{}';
		if (empty($payment->type))
			$payment->type=$this->getState('payment_type');	
		$payment->params=json_decode($payment->params,true);
		$payment->regions=json_decode($payment->regions,true);			
		
		$this->setState('payment_type',$payment->type);
		$this->setState('payment_params',$payment->params);
		$this->setState('payment_id',$payment->id);
		
        $this->onExecuteAfter('getPayment',array(&$payment));
        return $payment;
    }
	
	function getPaymentParamsForm()
	{
	    $this->onExecuteBefore('getPaymentParamsForm');
        
		$type=$this->getState('payment_type',null);
		$params=$this->getState('payment_params',null);
		$payment_id=$this->getState('payment_id',null);
		$query=$this->_db->getQuery(true);
		$query->select('enabled')->from('#__extensions')->where('element='.$this->_db->quote($type))->where('folder="kmpayment"');
		$this->_db->setQuery($query);
		$enabled=$this->_db->loadResult();
		if (empty($enabled) || !$enabled)
			return false;		
		$dispatcher	= JDispatcher::getInstance();
		$results = $dispatcher->trigger('onDisplayParamsForm',array($type,$params,$payment_id));
		if (isset($results[0]) && $results[0])
        {
            $this->onExecuteAfter('getPaymentParamsForm', array(&$results[0]));
            return $results[0];
        }
		return false;	
	}	

    function savePayment($data)
	{
	    $this->onExecuteBefore('savePayment', array(&$data));
        
		$data['params']=isset($data['params']) && is_array($data['params'])?json_encode($data['params']):json_encode(array());
		$data['regions']=isset($data['regions']) && is_array($data['regions'])?$data['regions']:array();
		$data['published']=isset($data['published'])?$data['published']:0;
		$data['images']=isset($data['images'])?$data['images']:array();
		foreach($data['regions'] as &$country)
			$country=array_filter($country,'KSFunctions::filterArray');
		unset($country);	
		$data['regions']=json_encode($data['regions']);
		$table = $this->getTable('payments');
		
		if (empty($data['id'])) {
			$query=$this->_db->getQuery(true);
			$query->update('#__ksenmart_payments')->set('ordering=ordering+1');
			$this->_db->setQuery($query);
			$this->_db->query();
		}
		
		if (!$table->bindCheckStore($data)) {
			$this->setError($table->getError());
			return false;
		}
		$id = $table->id;
		KSMedia::saveItemMedia($id,$data,'payment','payments');
		
		$dispatcher	= JDispatcher::getInstance();
		$dispatcher->trigger('onAfterSavePayment',array($id));		
		
		$on_close='window.parent.PaymentsList.refreshList();';
		$return=array('id'=>$id,'on_close'=>$on_close);
		
        $this->onExecuteAfter('savePayment', array(&$return));
		return $return;
    }
}
