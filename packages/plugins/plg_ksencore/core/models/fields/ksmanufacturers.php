<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

JFormHelper::loadFieldClass('list');
class JFormFieldKSManufacturers extends JFormFieldList {
	
	protected $type = 'KSManufacturers';

	protected function getOptions() {
		$options            = array();
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query
			->select('*')
			->from('#__ksenmart_manufacturers')
			->where('published=1')
			->order('ordering')
		;

		$db->setQuery($query);
		$manufacturers = $db->loadObjectList();

		foreach($manufacturers as $manufacturer) {
			$options[]    = JHtml::_('select.option', $manufacturer->id, $manufacturer->title);
		}

		return $options;
	}

}
