<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

class JFormFieldKSMFilterField extends JFormField {
	
	public $type = 'KSMFilterField';
	
	public function getInput() {
		$db = JFactory::getDBO();
		$view = isset($this->value['view']) ? $this->value['view'] : 'checkbox';
		$display = isset($this->value['display']) ? $this->value['display'] : 'row';
		$html = '<div style="margin-left:-180px;">';
		$html .= '<div class="'.$this->element['name'].'">';
		$html .= '	<h3>'.JText::_($this->element['title']).'</h3>';
		$html .= '	<div class="control-group">';
		$html .= '		<div class="control-label"><label>'.JText::_('MOD_KM_FILTER_VIEW').'</label></div>';
		$html .= '		<div class="controls">';
		$html .= '			<select name="'.$this->name.'[view]">';
		$html .= '				<option value="none" '.($view == 'none' ? 'selected' : '').'>'.JText::_('MOD_KM_FILTER_VIEW_NONE').'</option>';
		$html .= '				<option value="list" '.($view == 'list' ? 'selected' : '').'>'.JText::_('MOD_KM_FILTER_VIEW_LIST').'</option>';
		$html .= '				<option value="checkbox" '.($view == 'checkbox' ? 'selected' : '').'>'.JText::_('MOD_KM_FILTER_VIEW_CHECKBOX').'</option>';
		$html .= '				<option value="radio" '.($view == 'radio' ? 'selected' : '').'>'.JText::_('MOD_KM_FILTER_VIEW_RADIO').'</option>';
		$html .= '				<option value="text" '.($view == 'text' ? 'selected' : '').'>'.JText::_('MOD_KM_FILTER_VIEW_TEXT').'</option>';
		$html .= '				<option value="images" '.($view == 'images' ? 'selected' : '').'>'.JText::_('MOD_KM_FILTER_VIEW_IMAGES').'</option>';
		$html .= '			</select>';
		$html .= '		</div>';
		$html .= '	</div>';			
		$html .= '	<div class="control-group">';
		$html .= '		<div class="control-label"><label>'.JText::_('MOD_KM_FILTER_DISPLAY').'</label></div>';
		$html .= '		<div class="controls">';
		$html .= '			<select name="'.$this->name.'[display]">';
		$html .= '				<option value="row" '.($display == 'row' ? 'selected' : '').'>'.JText::_('MOD_KM_FILTER_DISPLAY_ROW').'</option>';
		$html .= '				<option value="inline" '.($display == 'inline' ? 'selected' : '').'>'.JText::_('MOD_KM_FILTER_DISPLAY_INLINE').'</option>';
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