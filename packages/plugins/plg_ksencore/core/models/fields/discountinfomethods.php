<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

class JFormFieldDiscountInfoMethods extends JFormField {
	
	protected $type = 'DiscountInfoMethods';
	
	public function getInput() {
		$methods = array(
			'module',
			'email'
		);
		$html = '';
		$html.= '<ul>';
		
		foreach ($methods as $method) {
			$html.= '<li class="' . (in_array($method, $this->value) ? 'active' : '') . '">';
			$html.= '	<label>' . JText::_('ksm_discount_info_method_' . $method) . '<input onclick="setActive(this,this.parentNode.parentNode);" type="checkbox" name="' . $this->name . '[]" value="' . $method . '" ' . (in_array($method, $this->value) ? 'checked' : '') . ' style="visibility:hidden;" ></label>';
			$html.= '</li>';
		}
		$html.= '</ul>';
		
		return $html;
	}
}