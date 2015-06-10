<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

class JFormFieldCurrency extends JFormField {
	protected $type = 'Currency';
	
	public function getInput() {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('id as value, title as text')->from('#__ksenmart_currencies');
		$db->setQuery($query);
		$currencies = $db->loadObjectList();
		
		return JHTML::_('select.genericlist', $currencies, $this->name, array(
			'class' => 'sel',
			'id' => $this->name,
			'style' => 'width:40px;'
		) , 'value', 'text', $this->value);
	}
}