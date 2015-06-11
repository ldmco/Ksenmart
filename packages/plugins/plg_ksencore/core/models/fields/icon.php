<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

class JFormFieldIcon extends JFormField {
	
	protected $type = 'Icon';
	
	public function getInput() {
		$html = '';
		$k = 1;
		
		$html.= '<div class="positions">';
		$html.= '	<div id="ksm-slidemodule-iconset-container">';
		$html.= '	<div class="position">';
		$html.= '		<div class="icon_block">';
		$html.= '			<label class="inputname">' . JText::_($this->element['label']) . '</label>';
		$html.= '			<div class="icon" id="icon">';
		if (!empty($this->value)) $html.= '			<img height="30px" src="' . JURI::root() . $this->value . '">';
		$html.= '				<input type="hidden" name="' . $this->name . '" value="' . $this->value . '">';
		$html.= '			</div>';
		$html.= '			<a class="get-icon" ' . ($this->value != '' ? 'style="display:none;"' : '') . ' href="index.php?option=com_media&amp;view=images&amp;tmpl=component&amp;asset=com_ksenmart&amp;author=&amp;fieldid=icon&amp;folder=shipping_icons" rel=\'{handler: "iframe", size: {x: 800, y: 500}}\' onclick=\'SqueezeBox.open(this, {parse: "rel"});return false;\' >' . JText::_('ksm_properties_property_choose_image') . '</a>';
		$html.= '			<a class="clear-icon" ' . ($this->value != '' ? '' : 'style="display:none;"') . ' href="#" >' . JText::_('ksm_properties_property_clear_image') . '</a>';
		$html.= '			<div class="clr"></div>';
		$html.= '		</div>';
		$html.= '</div>';
		$html.= '		<div class="position empty-position" style="' . (count($this->value) > 0 ? 'display:none;' : '') . '">';
		$html.= '			<h3>' . JText::_('KSM_PROPERTIES_PROPERTY_EMPTY_VALUES') . '</h3>';
		$html.= '		</div>';
		$html.= '	</div>';
		$html.= '</div>';
		
		JHtml::_('behavior.modal');
		$script = '		
		jQuery(document).ready(function(){
		
			jQuery("body").on("click", ".clear-icon", function(){
				jQuery(this).parent().find(".icon img").remove();
				jQuery(this).parent().find(".icon input").val("");
				jQuery(this).parent().find(".get-icon").show();
				jQuery(this).hide();
				return false;
			});

			function jInsertFieldValue(value, id) {
				var div=jQuery("#"+id);
				div.parent().find(".get-icon").hide();
				div.parent().find(".clear-icon").show();
				div.append("<img height=\"30px\" src=\"/"+value+"\">");
				div.find("input").val(value);
			}	

		});
		';
		$document = JFactory::getDocument();
		$document->addScript(JURI::base() . 'components/com_ksenmart/js/jquery.custom.min.js');
		$document->addScriptDeclaration($script);
		
		return $html;
	}
}