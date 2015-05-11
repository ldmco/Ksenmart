<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

class JFormFieldOrderStatus extends JFormField {
	
	protected $type = 'OrderStatus';
	
	public function getInput() {
		
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('id as value,title as text,system')->from('#__ksenmart_order_statuses');
		$db->setQuery($query);
		$statuses = $db->loadObjectList();
		
		foreach ($statuses as & $status) {
			$status->text = $status->system ? JText::_('ksm_orders_' . $status->text) : $status->text;
		}
		
		
		return JHTML::_('select.genericlist', $statuses, $this->name, array(
			'class' => "sel",
			'style' => 'width:180px;'
		) , 'value', 'text', $this->value);
	}
}