<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

class JFormFieldImageDisplace extends JFormField {
	protected $type = 'ImageDisplace';
	
	public function getInput() {
		$html = '	
		<div class="field switch displace">
			<label class="cb-enable ' . ($this->value ? 'selected' : '') . '" data-value="1"><span>Да</span></label>
			<label class="cb-disable ' . (!$this->value ? 'selected' : '') . '" data-value="0"><span>Нет</span></label>
		</div>
		<input type="hidden" name="' . $this->name . '" value="' . $this->value . '" />';
		
		return $html;
	}
}