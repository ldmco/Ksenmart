<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

class JFormFieldKSMFilterPrice extends JFormField {
	
	public $type = 'KSMFilterPrice';
	
	public function getInput() {
		$db = JFactory::getDBO();
		$view = isset($this->value['view']) ? $this->value['view'] : 'slider';
		$html = '<div style="margin-left:-180px;">';
		$html .= '<div class="'.$this->element['name'].'">';
		$html .= '	<h3>'.JText::_($this->element['title']).'</h3>';
		$html .= '	<div class="control-group">';
		$html .= '		<div class="control-label"><label>'.JText::_('MOD_KM_FILTER_VIEW').'</label></div>';
		$html .= '		<div class="controls">';
		$html .= '			<select name="'.$this->name.'[view]">';
		$html .= '				<option value="none" '.($view == 'none' ? 'selected' : '').'>'.JText::_('MOD_KM_FILTER_VIEW_NONE').'</option>';
		$html .= '				<option value="slider" '.($view == 'slider' ? 'selected' : '').'>'.JText::_('MOD_KM_FILTER_VIEW_SLIDER').'</option>';
		$html .= '			</select>';
		$html .= '		</div>';
		$html .= '	</div>';			
		$html .= '	<hr>';
		$html .= '</div>';
		$html .= '</div>';
		
		return $html;
	}
	
	public function getLabel() {
		$html = '';
		
		return $html;
	}	

}