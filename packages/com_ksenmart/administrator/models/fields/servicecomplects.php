<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

JFormHelper::loadFieldClass('checkboxes');
class JFormFieldServiceComplects extends JFormFieldCheckboxes {
	
	protected $type = 'ServiceComplects';
	
	public function getInput() {
		$this->value = !is_array($this->value) ? array() : $this->value;
		
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')->from('#__ksenmart_complects')->order('ordering');
		$db->setQuery($query);
		$complects = $db->loadObjectList('id');
		
		$html = '<ul>';
		if (count($complects) > 0) {
			
			foreach ($complects as $complect) {
				$checked = '';
				$active = '';
				if (in_array($complect->id, $this->value)) {
					$checked = ' checked="checked" ';
					$active = ' active ';
				}
				$html.= '<li class="' . $active . '">';
				$html.= '<label>' . JText::_($complect->title) . '<input type="checkbox" ' . $checked . ' value="' . $complect->id . '" name="' . $this->name . '" onclick="' . $this->element['onclick'] . '" /></label>';
				$html.= '</li>';
			}
		} else {
			$html.= '<li>';
			$html.= '<label>' . JText::_('ksm_complects_service_no_complects') . '</label>';
			$html.= '</li>';
		}
		$html.= '</ul>';
		
		return $html;
	}
}
