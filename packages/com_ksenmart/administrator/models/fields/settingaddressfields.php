<?php
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class JFormFieldSettingAddressFields extends JFormField {

	protected $type = 'SettingAddressFields';

	public function getInput() {
		if (count($this->value) == 0 || $this->value == '') $this->value = array();
		$empty = array(
			'type'      => 'text',
			'position'  => 'address',
			'required'  => 1,
			'system'    => 1,
			'published' => 0
		);
		if (empty($this->value['13'])) {
			$empty['title']     = 'address';
			$empty['ordering']  = 0;
			$empty['published'] = 1;
			$this->value['13']  = $empty;
		}
		if (empty($this->value['6'])) {
			$empty['title'] = 'city';
			$empty['ordering'] = 1;
			$this->value['6'] = $empty;
		}
		if (empty($this->value['7'])) {
			$empty['title']    = 'zip';
			$empty['ordering'] = 2;
			$this->value['7']  = $empty;
		}
		if (empty($this->value['8'])) {
			$empty['title'] = 'street';
			$empty['ordering'] = 3;
			$this->value['8'] = $empty;
		}
		if (empty($this->value['9'])) {
			$empty['title'] = 'house';
			$empty['ordering'] = 4;
			$this->value['9'] = $empty;
		}
		if (empty($this->value['10'])) {
			$empty['title'] = 'entrance';
			$empty['ordering'] = 5;
			$this->value['10'] = $empty;
		}
		if (empty($this->value['11'])) {
			$empty['title'] = 'floor';
			$empty['ordering'] = 6;
			$this->value['11'] = $empty;
		}
		if (empty($this->value['12'])) {
			$empty['title'] = 'flat';
			$empty['ordering'] = 7;
			$this->value['12'] = $empty;
		}

		$html = '';
		$html .= '<div class="shipping-address-fields">';
		$html .= '		<div class="row fields-row">';

		foreach ($this->value as $key => $field) {
			$html .= '	<div class="row">';
			$html .= '		<div class="field switch">';
			$html .= '			<label class="inputname">' . JText::_(($field['system'] == 1 ? 'ksm_settings_shipping_field_' : '') . $field['title']) . '</label>';
			if ($field['type'] == 'select') {
				$html .= '		<select class="sel" id="address-field-' . $key . '" style="width:113px;">';

				foreach ($field['values'] as $value) {
					$html .= '		<option>' . $value->title . '</option>';
				}
				$html .= '		</select>';
			} else {
				//$html.= '		<input type="text" class="inputbox" value="" />';
			}
			$html .= '			<label class="cb-enable ' . ($field['published'] == 1 ? 'selected' : '') . '"><span>' . JText::_('ksm_enable') . '</span></label>';
			$html .= '			<label class="cb-disable ' . ($field['published'] == 0 ? 'selected' : '') . '"><span>' . JText::_('ksm_disable') . '</span></label>';
			$html .= '			<div class="checkb">';
			$html .= '				<input type="hidden" name="' . $this->name . '[' . $key . '][required]" value="0" />';
			$html .= '				<input type="checkbox" name="' . $this->name . '[' . $key . '][required]" value="1" ' . ($field['required'] == 1 ? 'checked' : '') . ' />';
			$html .= '				<span>' . JText::_('ksm_settings_shipping_required_field') . '</span>';
			$html .= '			</div>';
			$html .= '			<input type="hidden" name="' . $this->name . '[' . $key . '][position]" value="' . $field['position'] . '" >';
			$html .= '			<input type="hidden" name="' . $this->name . '[' . $key . '][type]" value="' . $field['type'] . '" >';
			$html .= '			<input type="hidden" name="' . $this->name . '[' . $key . '][title]" value="' . $field['title'] . '" >';
			$html .= '			<input type="hidden" name="' . $this->name . '[' . $key . '][ordering]" value="' . $field['ordering'] . '" class="ordering" >';
			$html .= '			<input type="hidden" name="' . $this->name . '[' . $key . '][system]" value="' . $field['system'] . '" >';
			$html .= '			<input type="hidden" name="' . $this->name . '[' . $key . '][published]" value="' . $field['published'] . '" class="published" >';
			$html .= '			<input type="hidden" name="' . $this->name . '[' . $key . '][id]" value="' . $key . '">';
			if ($field['type'] == 'select') {

				foreach ($field['values'] as $value) {
					$html .= '	<input type="hidden" name="' . $this->name . '[' . $key . '][values][' . $value->id . '][title]" value="' . $value->title . '" >';
				}
			}
			$html .= '		</div>';
			if ($field['system'] != 1) $html .= '	<a class="del"></a>';
			$html .= '	</div>';
		}
		$html .= '	</div>';
		$html .= '</div>';
		$script   = '
		jQuery(document).ready(function(){
			jQuery("body").on("click", ".shipping-address-fields .fields-row .cb-enable", function(){
				jQuery(this).addClass("selected");
				jQuery(this).parents(".row:first").find(".cb-disable").removeClass("selected");
				jQuery(this).parents(".row:first").find(".published").val("1");
			});		
			jQuery("body").on("click", ".shipping-address-fields .fields-row .cb-disable", function(){
				jQuery(this).addClass("selected");
				jQuery(this).parents(".row:first").find(".cb-enable").removeClass("selected");			
				jQuery(this).parents(".row:first").find(".published").val("0");
			});				
		});
		';
		$document = JFactory::getDocument();
		$document->addScriptDeclaration($script);

		return $html;
	}
}
