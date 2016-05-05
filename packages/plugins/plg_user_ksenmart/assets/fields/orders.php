<?php 
defined('_JEXEC') or die;

class JFormFieldOrders extends JFormField 
{

	protected $type = 'Orders';
	
	public function getInput()
	{
		$db = JFactory::getDbo();
		foreach($this->value as &$order)
		{
			$order = KSMOrders::getOrder($order);
			$order->items = KSMOrders::getOrderItems($order->id);
			
			$query = $db->getQuery(true);
			$query
				->select('title')
				->from('#__ksenmart_shippings')
				->where('id = '.$order->shipping_id)
			;
			$db->setQuery($query);
			$order->shipping_title = $db->loadResult();				
		}
		
		$view = new stdClass();
		$view->orders = $this->value;	
		$html = KSSystem::loadPluginTemplate('ksenmart', 'user', $view, 'orders_edit');
		
		return $html;
	}

}