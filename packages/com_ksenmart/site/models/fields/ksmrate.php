<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

class JFormFieldKSMRate extends JFormField {
	
	protected $type = 'KSMRate';
	protected $star_img = 'components/com_ksenmart/images/star2-small.png';
	protected $star_active_img = 'components/com_ksenmart/images/star-small.png';
		
	public function getInput() {
		$document = JFactory::getDocument();
		$this->star_img = JURI::root().$this->star_img;
		$this->star_active_img = JURI::root().$this->star_active_img;
		$html = '';
		
		$html.= '<img rate="1" class="ksm-rate-star" src="'.($this->value >= 1 ? $this->star_active_img : $this->star_img).'" alt="" />';
		$html.= '<img rate="2" class="ksm-rate-star" src="'.($this->value >= 2 ? $this->star_active_img : $this->star_img).'" alt="" />';
		$html.= '<img rate="3" class="ksm-rate-star" src="'.($this->value >= 3 ? $this->star_active_img : $this->star_img).'" alt="" />';
		$html.= '<img rate="4" class="ksm-rate-star" src="'.($this->value >= 4 ? $this->star_active_img : $this->star_img).'" alt="" />';
		$html.= '<img rate="5" class="ksm-rate-star" src="'.($this->value >= 5 ? $this->star_active_img : $this->star_img).'" alt="" />';	
		$html.= '<input type="hidden" class="ksm-rate-value" name="' . $this->name . '" value="' . $this->value . '" >';
		
		$script = '
		jQuery(document).ready(function() {
			
			jQuery("body").on("click", ".ksm-rate-star", function(e) {
				var form = jQuery(this).parents("form");
				var rate = jQuery(this).attr("rate");

				form.find(".ksm-rate-value").val(rate);
				form.find(".ksm-rate-star").attr("src", "'.$this->star_img.'");

				for (var k = 1; k <= rate; k++) {
					form.find(".ksm-rate-star[rate=\'" + k + "\']").attr("src", "'.$this->star_active_img.'");
				}
				
				return true;
			});
			
		});
		';
		$document->addScriptDeclaration($script);
		
		return $html;
	}
}
