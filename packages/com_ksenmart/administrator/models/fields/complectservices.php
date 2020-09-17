<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

JFormHelper::loadFieldClass('checkboxes');
class JFormFieldComplectServices extends JFormFieldCheckboxes {
	
	protected $type = 'ComplectServices';
	
	public function getInput() {
		$this->value = !is_array($this->value) ? array() : $this->value;
		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('*')->from('#__ksenmart_services')->order('ordering');
		$db->setQuery($query);
		$services = $db->loadObjectList('id');
		
		$html = '<ul>';
		if (count($services) > 0) {
			
			foreach ($services as $service) {
				$checked = '';
				$active = '';
				if (in_array($service->id, $this->value)) {
					$checked = ' checked="checked" ';
					$active = ' active ';
				}
				$html.= '<li class="' . $active . '">';
				$html.= '<label>' . JText::_($service->title) . '<input type="checkbox" ' . $checked . ' value="' . $service->id . '" name="' . $this->name . '" onclick="' . $this->element['onclick'] . '" /></label>';
				$html.= '</li>';
			}
		} else {
			$html.= '<li>';
			$html.= '<label>' . JText::_('ksm_complects_complect_no_services') . '</label>';
			$html.= '</li>';
		}
		$html.= '</ul>';
		
		return $html;
	}
}
