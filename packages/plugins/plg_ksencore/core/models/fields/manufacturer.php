<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

JFormHelper::loadFieldClass('radio');
class JFormFieldManufacturer extends JFormFieldRadio {
	
	protected $type = 'Manufacturer';
	
	public function getInput() {
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('*')->from('#__ksenmart_manufacturers')->order('ordering');
		$db->setQuery($query);
		$manufacturers = $db->loadObjectList('id');
		
		$html = '<ul>';
		if (count($manufacturers) > 0) {
			
			foreach ($manufacturers as $manufacturer) {
				$checked = '';
				$active = '';
				if ($this->value == $manufacturer->id) {
					$checked = ' checked="checked" ';
					$active = ' active ';
				}
				$html.= '<li class="' . $active . '">';
				$html.= '<label>' . JText::_($manufacturer->title) . '<input type="radio" ' . $checked . ' value="' . $manufacturer->id . '" name="' . $this->name . '" onclick="setActiveOne(this);" /></label>';
				$html.= '</li>';
			}
		} else {
			$html.= '<li>';
			$html.= '<label>' . JText::_('ksm_catalog_product_no_manufacturers') . '</label>';
			$html.= '</li>';
		}
		$html.= '</ul>';
		
		return $html;
	}
}