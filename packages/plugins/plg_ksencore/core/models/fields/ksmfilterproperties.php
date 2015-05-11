<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

class JFormFieldKSMFilterProperties extends JFormField {
	
	public $type = 'KSMFilterProperties';
	
	public function getInput() {
		$db = JFactory::getDBO();
		$html = '<div style="margin-left:-180px;">';
		$query = $db->getQuery(true);
		$query->select('*')->from('#__ksenmart_properties')->order('ordering');
		$db->setQuery($query);
		$properties = $db->loadObjectList();
		foreach($properties as $property){
			$view = isset($this->value[$property->id]['view']) ? $this->value[$property->id]['view'] : 'checkbox';
			$display = isset($this->value[$property->id]['display']) ? $this->value[$property->id]['display'] : 'row';
			$html .= '<div class="property">';
			$html .= '	<h3>'.$property->title.'</h3>';
			$html .= '	<div class="control-group">';
			$html .= '		<div class="control-label"><label>'.JText::_('MOD_KM_FILTER_VIEW').'</label></div>';
			$html .= '		<div class="controls">';
			$html .= '			<select name="'.$this->name.'['.$property->id.'][view]">';
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
			$html .= '			<select name="'.$this->name.'['.$property->id.'][display]">';
			$html .= '				<option value="row" '.($display == 'row' ? 'selected' : '').'>'.JText::_('MOD_KM_FILTER_DISPLAY_ROW').'</option>';
			$html .= '				<option value="inline" '.($display == 'inline' ? 'selected' : '').'>'.JText::_('MOD_KM_FILTER_DISPLAY_INLINE').'</option>';
			$html .= '			</select>';
			$html .= '		</div>';
			$html .= '	</div>';				
			$html .= '	<hr>';
			$html .= '</div>';
		}
		$html .= '</div>';
		
		return $html;
	}
	
	public function getLabel() {
		$html = '';
		
		return $html;
	}	

}