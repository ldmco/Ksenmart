<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

JFormHelper::loadFieldClass('checkboxes');
class JFormFieldExportDiscounts extends JFormFieldCheckboxes {
	
	protected $type = 'ExportDiscounts';
	
	public function getInput(){
		$this->value = !is_array($this->value) ? array() : $this->value;
		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('*')->from('#__ksenmart_discounts')->where('enabled=1');
		$db->setQuery($query);
		$discounts = $db->loadObjectList('id');
		
		$html = '<ul>';
		if (count($discounts) > 0) {
			foreach ($discounts as $discount) {
				$checked = '';
				$active = '';
				if (in_array($discount->id, $this->value)) {
					$checked = ' checked="checked" ';
					$active = ' active ';
				}
				$html.= '<li class="' . $active . '">';
				$html.= '<label>' . JText::_($discount->title) . '<input type="checkbox" ' . $checked . ' value="' . $discount->id . '" name="' . $this->name . '" onclick="' . $this->element['onclick'] . '" /></label>';
				$html.= '</li>';
			}
		} else {
			$html.= '<li>';
			$html.= '<label>' . JText::_('ksm_exportimport_export_ym_extend_no_discounts') . '</label>';
			$html.= '</li>';
		}
		$html .= '</ul>';
		
		return $html;
	}
	
}