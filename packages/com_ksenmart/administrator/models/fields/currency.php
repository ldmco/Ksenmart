<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

class JFormFieldCurrency extends JFormField {
	protected $type = 'Currency';
	
	public function getInput() {
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query
			->select($db->qn(array(
				'c.id',
				'c.title',
			),
			array(
				'value',
				'text',
			)))
			->from($db->qn('#__ksenmart_currencies', 'c'))
		;
		$db->setQuery($query);
		$currencies = $db->loadObjectList();

		if(!$this->value) {
			$this->value = KSMPrice::_getDefaultCurrency();
		}
		
		return JHTML::_('select.genericlist', $currencies, $this->name, array(
			'class' => 'sel',
			'id'    => $this->name,
			'style' => 'width:40px;'
		), 'value', 'text', $this->value);
	}
}