<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

class JFormFieldPropertyValues extends JFormField {
	
	protected $type = 'PropertyValues';
	
	public function getInput() {
		$html = '';
		$k = 1;
		
		$html.= '<div class="positions">';
		$html.= '	<div id="ksm-slidemodule-propertyvalues-container">';
		
		foreach ($this->value as $value) {
			$html.= '	<div class="position">';
			$html.= '		<div class="property-value">';
			$html.= '			<label class="inputname">' . JText::_('ksm_properties_property_value') . ' ' . $k . '</label>';
			$html.= '			<input type="text" name="' . $this->name . '[' . $value->id . '][title]" value="' . $value->title . '" class="inputbox">';
			$html.= '			<div class="property-image" id="property-image' . $value->id . '">';
			if ($value->image != '') $html.= '			<img height="30px" src="' . JURI::root() . $value->image . '">';
			$html.= '				<input type="hidden" name="' . $this->name . '[' . $value->id . '][image]" value="' . $value->image . '">';
			$html.= '			</div>';
			$html.= '			<a class="property-get-image" ' . ($value->image != '' ? 'style="display:none;"' : '') . ' href="index.php?option=com_media&amp;view=images&amp;tmpl=component&amp;asset=com_ksenmart&amp;author=&amp;fieldid=property-image' . $value->id . '&amp;folder=properties" rel=\'{handler: "iframe", size: {x: 800, y: 500}}\' onclick=\'SqueezeBox.open(this, {parse: "rel"});return false;\' >' . JText::_('ksm_properties_property_choose_image') . '</a>';
			$html.= '			<a class="property-clear-image" ' . ($value->image != '' ? '' : 'style="display:none;"') . ' href="#" >' . JText::_('ksm_properties_property_clear_image') . '</a>';
			$html.= '			<a class="show-value-alias" href="#" >' . JText::_('ksm_alias_lbl') . '</a>';
			$html.= '			<a class="del"></a>';
			$html.= '			<div class="clr"></div>';
			$html.= '		</div>';
			$html.= '		<div class="property-value-alias" style="display:none;">';
			$html.= '			<label class="inputname">' . JText::_('ksm_alias_lbl') . ' ' . $k . '</label>';
			$html.= '			<input type="text" name="' . $this->name . '[' . $value->id . '][alias]" value="' . $value->alias . '" class="inputbox">';
			$html.= '		</div>';
			$html.= '		<input type="hidden" name="' . $this->name . '[' . $value->id . '][ordering]" value="' . $value->ordering . '" class="ordering">';
			$html.= '</div>';
			$k++;
		}
		$html.= '		<div class="position empty-position" style="' . (count($this->value) > 0 ? 'display:none;' : '') . '">';
		$html.= '			<h3>' . JText::_('KSM_PROPERTIES_PROPERTY_EMPTY_VALUES') . '</h3>';
		$html.= '		</div>';
		$html.= '	</div>';
		$html.= '	<div class="row">';
		$html.= '		<a class="add" href="javascript:void(0);">' . JText::_('ksm_add') . '</a>';
		$html.= '	</div>';
		$html.= '</div>';
		
		$script = '
		jQuery(document).ready(function(){
			
			jQuery("#ksm-slidemodule-propertyvalues-container").sortable({
				stop:function(){
					refreshValues();
				}
			});	

			jQuery("body").on("click", ".ksm-slidemodule-propertyvalues .add", function(){
				
				var html="";
				var count=jQuery(".ksm-slidemodule-propertyvalues .position").length;
				
				html+="<div class=\"position\">";
				html+="		<div class=\"property-value\">";
				html+="			<label class=\"inputname\">' . JText::_('ksm_properties_property_value') . ' "+count+"</label>";
				html+="			<input type=\"text\" name=\"' . $this->name . '[-"+count+"][title]\" class=\"inputbox\">";
				html+="			<div class=\"property-image\" id=\"property-image"+count+"\">";
				html+="				<input type=\"hidden\" name=\"' . $this->name . '[-"+count+"][image]\">";
				html+="			</div>";
				html+="			<a class=\"property-get-image\" href=\"index.php?option=com_media&amp;view=images&amp;tmpl=component&amp;asset=com_ksenmart&amp;author=&amp;fieldid=property-image"+count+"&amp;folder=properties\" rel=\'{handler: \"iframe\", size: {x: 800, y: 500}}\' onclick=\'SqueezeBox.open(this, {parse: \"rel\"});return false;\' >' . JText::_('ksm_properties_property_choose_image') . '</a>";
				html+="			<a class=\"property-clear-image\" style=\"display:none;\" href=\"#\" >' . JText::_('ksm_properties_property_clear_image') . '</a>";
				html+="			<a class=\"show-value-alias\" href=\"#\" >' . JText::_('ksm_alias_lbl') . '</a>";
				html+="			<a class=\"del\"></a>";			
				html+="			<div class=\"clr\"></div>";
				html+="		</div>";
				html+="		<div class=\"property-value-alias\" style=\"display:none;\">";
				html+="			<label class=\"inputname\">' . JText::_('ksm_alias_lbl') . ' "+count+"</label>";
				html+="			<input type=\"text\" name=\"' . $this->name . '[-"+count+"][alias]\" class=\"inputbox\">";
				html+="		</div>";
				html+="		<input type=\"hidden\" name=\"' . $this->name . '[-"+count+"][ordering]\" class=\"ordering\">";
				html+="</div>";
				jQuery("#ksm-slidemodule-propertyvalues-container .empty-position").hide();
				jQuery("#ksm-slidemodule-propertyvalues-container").append(html);
				jQuery("#ksm-slidemodule-propertyvalues-container").sortable({
					stop:function(){
						refreshValues();
					}
				});
				refreshValues();
				return false;	
			});	
			
			jQuery("body").on("click", ".property-clear-image", function(){
				jQuery(this).parent().find(".property-image img").remove();
				jQuery(this).parent().find(".property-image input").val("");
				jQuery(this).parent().find(".property-get-image").show();
				jQuery(this).hide();
				return false;
			});
			
			jQuery("body").on("click", ".show-value-alias", function(){
				var alias=jQuery(this).parents(".property-value").next();
				if (alias.is(":visible"))
					alias.hide();
				else
					alias.show();
				return false;
			});	
			
			jQuery("body").on("click", ".ksm-slidemodule-propertyvalues .del", function(){
				jQuery(this).parents(".position").remove();
				if (jQuery("#ksm-slidemodule-propertyvalues-container .position").length==1)
					jQuery("#ksm-slidemodule-propertyvalues-container .empty-position").show();
			});	
			
		});

		function jInsertFieldValue(value, id) {
			var div=jQuery("#"+id);
			div.parent().find(".property-get-image").hide();
			div.parent().find(".property-clear-image").show();
			div.append("<img height=\"30px\" src=\"/"+value+"\">");
			div.find("input").val(value);
		}		
		
		function refreshValues()
		{
			var ordering=1;		
			jQuery(".ksm-slidemodule-propertyvalues .position").each(function(){
				jQuery(this).find(".ordering").val(ordering);
				ordering++;
			});		
		}		
		';
		$document = JFactory::getDocument();
		$document->addScript(JURI::base() . 'components/com_ksenmart/js/jquery.custom.min.js');
		$document->addScriptDeclaration($script);
		
		return $html;
	}
}