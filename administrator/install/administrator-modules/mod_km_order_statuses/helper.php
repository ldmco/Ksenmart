<?php
defined( '_JEXEC' ) or die;
 
class ModKMOrderStatusesHelper
{

    public static function getStatuses()
    {
		$app = JFactory::getApplication();
		$view=JRequest::getVar('view','orders');
		$context = 'com_ksenmart.'.$view;
		if ($layout = JRequest::getVar('layout','default')) {
			$context.='.'.$layout;
		}		
		$selected_statuses=$app->getUserStateFromRequest($context . '.statuses', 'statuses', array());
		$db=JFactory::getDBO();	
		$query=$db->getQuery(true);
		$query->select('*')->from('#__ksenmart_order_statuses');
		$db->setQuery($query);
		$statuses=$db->loadObjectList();
		foreach($statuses as &$status)
		{
			if (in_array($status->id,$selected_statuses))
				$status->selected=true;
			else	
				$status->selected=false;
			$status->title=$status->system?JText::_('ksm_orders_'.$status->title):$status->title;
		}		
		return $statuses;
    }
	
} 
?>