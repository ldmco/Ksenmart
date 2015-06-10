<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

JFormHelper::loadFieldClass('checkboxes');
class JFormFieldProperties extends JFormFieldCheckboxes {
	
	protected $type = 'Properties';
	
	public function getInput() {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')->from('#__ksenmart_properties')->order('ordering');
		$db->setQuery($query);
		$properties = $db->loadObjectList('id');
		
		$html = '<ul>';
		
		if (count($properties) > 0) {
			
			foreach ($properties as $property) {
				$checked = '';
				$active = '';
				if (in_array($property->id, $this->value)) {
					$checked = 'checked="checked"';
					$active = 'active';
				}
				$html.= '<li class="' . $active . '">';
				$html.= '<label>' . $property->title . '<input type="checkbox" ' . $checked . ' value="' . $property->id . '" name="' . $this->name . '" onclick="' . $this->element['onclick'] . '" /></label>';
				$html.= '</li>';
			}
		} else {
			$html.= '<li>';
			$html.= '<label>' . JText::_('ksm_properties_no_properties') . '</label>';
			$html.= '</li>';
		}
		
		$html.= '</ul>';
		
		return $html;
	}
}