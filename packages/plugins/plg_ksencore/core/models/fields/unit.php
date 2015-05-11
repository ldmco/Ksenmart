<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

class JFormFieldUnit extends JFormField {
	protected $type = 'unit';
	
	public function getInput() {
		static $units = 0;
		if ($units == 0) {
			$db = JFactory::getDbo();
			$q = $db->getQuery(true);
			$q->select('id as value, form1 as text')->from('#__ksenmart_product_units');
			
			$db->setQuery($q);
			
			$units = $db->loadObjectList();
		}
		
		return JHTML::_('select.genericlist', $units, $this->name, array(
			'class' => "sel",
			'style' => 'width:40px;'
		) , 'value', 'text', $this->value);
	}
}
