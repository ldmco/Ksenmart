<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

KSSystem::import('models.modelksadmin');
class KsenMartModelReports extends JModelKSAdmin {

	function __construct() {
		parent::__construct();
	}
	
	function populateState()
	{
	    $this->onExecuteBefore('populateState');
        
		$app = JFactory::getApplication();
		
		$params = JComponentHelper::getParams('com_ksenmart');
		
		$report=$app->getUserStateFromRequest('com_ksenmart.reports.report', 'report','text');
		$this->setState('report',$report);
		$this->context.='.'.$report;

		$value = $app->getUserStateFromRequest($this->context.'list.limit', 'limit', $params->get('admin_product_limit',20), 0);
		$limit = $value;
		$this->setState('list.limit',$limit);	
		
		$value = $app->getUserStateFromRequest($this->context . '.limitstart', 'limitstart', 0);
		$limitstart = ($limit != 0 ? (floor($value / $limit) * $limit) : 0);
		$this->setState('list.start',$limitstart);	
		
		$order_dir=$app->getUserStateFromRequest($this->context . '.order_dir', 'order_dir', 'asc');
		$this->setState('order_dir',$order_dir);
		$order_type=$app->getUserStateFromRequest($this->context . '.order_type', 'order_type', 'id');
		$this->setState('order_type',$order_type);		
		
		$from_date=$app->getUserStateFromRequest($this->context . '.from_date', 'from_date', date('d.m.Y'));
		$this->setState('from_date',$from_date);
		$to_date=$app->getUserStateFromRequest($this->context . '.to_date', 'to_date', date('d.m.Y'));
		$this->setState('to_date',$to_date);
        
        $this->onExecuteAfter('populateState');			
	}	
	
	function getListItems()
	{
	    $this->onExecuteBefore('getListItems');
        
		$report=$this->getState('report');
		switch($report)
		{
			case 'productsReport':
				$items=$this->getProducts();
				break;
			case 'watchedReport':
				$items=$this->getWatchedProducts();
				break;	
			case 'favoritesReport':
				$items=$this->getFavoritesProducts();
				break;				
			case 'ordersReport':
				$items=$this->getOrders();
				break;				
		}
        
        $this->onExecuteAfter('getListItems',array(&$items));
		return $items;
	}	
	
	function getProducts()
	{
	    $this->onExecuteBefore('getProducts');
        
		$order_dir=$this->getState('order_dir');
		$order_type=$this->getState('order_type');
		if ($order_type!='ordered')
			$order_type='p.'.$order_type;		
		$query=$this->_db->getQuery(true);		
		$query->select('SQL_CALC_FOUND_ROWS p.*,(select count(id) from #__ksenmart_order_items where product_id=p.id) as ordered')->from('#__ksenmart_products as p')->order($order_type.' '.$order_dir);
		$query=KSMedia::setItemMainImageToQuery($query);
		$this->_db->setQuery($query,$this->getState('list.start'),$this->getState('list.limit'));
		$items=$this->_db->loadObjectList();
		$query=$this->_db->getQuery(true);
		$query->select('FOUND_ROWS()');
		$this->_db->setQuery($query);
		$this->total=$this->_db->loadResult();		
		foreach($items as &$item)
			$item->img = KSMedia::resizeImage($item->filename,$item->folder,30,30,json_decode($item->params,true));
            
        $this->onExecuteAfter('getProducts',array(&$items));
		return $items;	
	}
	
	function getWatchedProducts()
	{
	    $this->onExecuteBefore('getWatchedProducts');
        
		$order_dir=$this->getState('order_dir');
		$order_type=$this->getState('order_type');
		if ($order_type!='watched')
			$order_type='p.'.$order_type;	
		$query=$this->_db->getQuery(true);
		$query->select('*')->from('#__ksen_users');
		$this->_db->setQuery($query);
		$users=$this->_db->loadObjectList();
		$watched=array(0);
		foreach($users as $user)
		{
			$user->watched=json_decode($user->watched,true);
			if (is_array($user->watched))
				foreach($user->watched as $w)
					if (!in_array($w,$watched) && $w!='')
						$watched[]=$w;
		}
		$query=$this->_db->getQuery(true);		
		$query->select('SQL_CALC_FOUND_ROWS p.*,(select count(id) from #__ksen_users where INSTR(watched,p.id)!=0) as watched')->from('#__ksenmart_products as p')->order($order_type.' '.$order_dir);
		$query->where('p.id in ('.implode(',',$watched).')');		
		$query=KSMedia::setItemMainImageToQuery($query);
		$this->_db->setQuery($query,$this->getState('list.start'),$this->getState('list.limit'));
		$items=$this->_db->loadObjectList();
		$query=$this->_db->getQuery(true);
		$query->select('FOUND_ROWS()');
		$this->_db->setQuery($query);
		$this->total=$this->_db->loadResult();		
		foreach($items as &$item)
			$item->img = KSMedia::resizeImage($item->filename,$item->folder,30,30,json_decode($item->params,true));
        
        $this->onExecuteAfter('getWatchedProducts', array(&$items));
		return $items;		
	}	

	function getFavoritesProducts()
	{
	    $this->onExecuteBefore('getFavoritesProducts');
        
		$order_dir=$this->getState('order_dir');
		$order_type=$this->getState('order_type');
		if ($order_type!='favorites')
			$order_type='p.'.$order_type;	
		$query=$this->_db->getQuery(true);
		$query->select('*')->from('#__ksen_users');
		$this->_db->setQuery($query);
		$users=$this->_db->loadObjectList();
		$favorites=array(0);
		foreach($users as $user)
		{
			$user->favorites=json_decode($user->favorites,true);
			if (is_array($user->favorites))
				foreach($user->favorites as $f)
					if (!in_array($f,$favorites) && $f!='')
						$favorites[]=$f;
		}
		$query=$this->_db->getQuery(true);		
		$query->select('SQL_CALC_FOUND_ROWS p.*,(select count(id) from #__ksen_users where INSTR(favorites,p.id)!=0) as favorites')->from('#__ksenmart_products as p')->order($order_type.' '.$order_dir);
		$query->where('p.id in ('.implode(',',$favorites).')');		
		$query=KSMedia::setItemMainImageToQuery($query);
		$this->_db->setQuery($query,$this->getState('list.start'),$this->getState('list.limit'));
		$items=$this->_db->loadObjectList();
		$query=$this->_db->getQuery(true);
		$query->select('FOUND_ROWS()');
		$this->_db->setQuery($query);
		$this->total=$this->_db->loadResult();		
		foreach($items as &$item)
			$item->img = KSMedia::resizeImage($item->filename,$item->folder,30,30,json_decode($item->params,true));
		
        $this->onExecuteAfter('getFavoritesProducts', array(&$items));
        return $items;		
	}	
	
	function getOrders()
	{
	    $this->onExecuteBefore('getOrders');
        
		$order_dir=$this->getState('order_dir');
		$order_type=$this->getState('order_type');
		$from_date=$this->getState('from_date');
		$to_date=$this->getState('to_date');
		$from_date=date('Y-m-d',strtotime($from_date)).' 00:00:00';
		$to_date=date('Y-m-d',strtotime($to_date)).' 23:59:59';		
		$query=$this->_db->getQuery(true);
		$query->select('*')->from('#__ksenmart_orders')->where('date_add>'.$this->_db->quote($from_date))->where('date_add<'.$this->_db->quote($to_date));
		$this->_db->setQuery($query);
		$total_orders=$this->_db->loadObjectList();	
		$this->total=count($total_orders);		
		$total_cost=0;
		for($i=0;$i<count($total_orders);$i++)
			$total_cost+=$total_orders[$i]->cost;	
		$query=$this->_db->getQuery(true);
		$query->select('o.*,(select title from #__ksenmart_order_statuses where id=o.status_id) as status_name')
		->from('#__ksenmart_orders as o')
		->where('o.date_add>'.$this->_db->quote($from_date))->where('o.date_add<'.$this->_db->quote($to_date))
		->order('o.'.$order_type.' '.$order_dir);
		$this->_db->setQuery($query,$this->getState('list.start'),$this->getState('list.limit'));
		$orders=$this->_db->loadObjectList();	
		foreach($orders as &$order)
		{
			$order->user=KSUsers::getUser($order->user_id);
			$order->cost_val=KSMPrice::showPriceWithTransform($order->cost);
			$order->date=date('d.m.Y',strtotime($order->date_add));
			$order->status_name='';
			$order->customer_info='';
			$query=$this->_db->getQuery(true);
			$query->select('*')->from('#__ksenmart_order_statuses')->where('id='.$order->status_id);
			$this->_db->setQuery($query);
			$status=$this->_db->loadObject();	
			if (!empty($status))
				$order->status_name=$status->system?JText::_('ksm_orders_'.$status->title):$status->title;	
			$order->customer_fields=json_decode($order->customer_fields,true);
			if (isset($order->customer_fields['lastname']) && !empty($order->customer_fields['lastname']))
				$order->customer_info.=$order->customer_fields['lastname'].' ';
			if (isset($order->customer_fields['name']) && !empty($order->customer_fields['name']))
				$order->customer_info.=$order->customer_fields['name'].' ';
			if (isset($order->customer_fields['surname']) && !empty($order->customer_fields['surname']))
				$order->customer_info.=$order->customer_fields['surname'];	
			if (isset($order->customer_fields['email']) && !empty($order->customer_fields['email']))
				$order->customer_info.='<br>'.$order->customer_fields['email'];	
			if (isset($order->customer_fields['phone']) && !empty($order->customer_fields['phone']))
				$order->customer_info.='<br>'.$order->customer_fields['phone'];		
			if (empty($order->customer_info))
				$order->customer_info=JText::_('ksm_orders_default_customer_info');
		}
		$this->total_cost=$total_cost;
        
        $this->onExecuteAfter('getOrders', array(&$orders));
		return $orders;		
	}	
	
	function getTotal()
	{
		$this->onExecuteBefore('getTotal');
		
		$total=$this->total;
		
		$this->onExecuteAfter('getTotal',array(&$total));
		return $total;
	}
	
	function getTotalCost()
	{
		$this->onExecuteBefore('getTotalCost');
		
		$total_cost=$this->total_cost;
		
		$this->onExecuteAfter('getTotalCost',array(&$total_cost));
		return $total_cost;
	}	
}