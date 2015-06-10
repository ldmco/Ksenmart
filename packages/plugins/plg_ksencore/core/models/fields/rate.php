<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

class JFormFieldRate extends JFormField {
	
	protected $type = 'Rate';
	
	public function getInput() {
		$html = '';
		
		$html.= '<div class="rate">';
		
		for ($k = 1;$k < 6;$k++) {
			$html.= '<label>';
			if (floor($this->value) >= $k) $html.= '<img rel="' . $k . '" src="' . JURI::base() . 'components/com_ksenmart/assets/images/c-star.png" alt="" />';
			else $html.= '<img rel="' . $k . '" src="' . JURI::base() . 'components/com_ksenmart/assets/images/c-star2.png" alt="" />';
			$html.= '	<input type="radio" name="' . $this->name . '" onclick="setRate(this);" value="' . $k . '" ' . (floor($this->value) == $k ? 'checked' : '') . ' />';
			$html.= '</label>';
		}
		$html.= '</div>';
		
		$script = '
		function setRate(obj)
		{
			var rate=jQuery(obj).val();
			var rate_block=jQuery(obj).parents(".rate");
			rate_block.find("img").attr("src","components/com_ksenmart/assets/images/c-star2.png");
			for(var k=1;k<=rate;k++)
				rate_block.find("img[rel=\'"+k+"\']").attr("src","components/com_ksenmart/assets/images/c-star.png");
		}
		';
		$document = JFactory::getDocument();
		$document->addScriptDeclaration($script);
		
		return $html;
	}
}