<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

JFormHelper::loadFieldClass('list');
//JHtml::_('formbehavior.chosen', 'select');
class JFormFieldKSProducts extends JFormFieldList {
	
	protected $type = 'KSProducts';

	protected function getOptions() {
		$options            = array();
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query
			->select('*')
			->from('#__ksenmart_products')
			->where('published=1')
			->order('ordering')
		;

		$db->setQuery($query);
		$products = $db->loadObjectList();

		foreach($products as $product) {
			$options[]    = JHtml::_('select.option', $product->id, $product->title);
		}

		return $options;
	}

}
