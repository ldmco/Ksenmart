<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

JFormHelper::loadFieldClass('checkboxes');
class JFormFieldPropertiesValues extends JFormFieldCheckboxes {
	
	protected $type = 'PropertiesValues';
	
	public function getInput() {
		$this->value = !is_array($this->value) ? array() : $this->value;
		
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')->from('#__ksenmart_properties')->order('ordering');
		$db->setQuery($query);
		$properties = $db->loadObjectList('id');
		
		foreach ($properties as & $property) {
			$query = $db->getQuery(true);
			$query->select('*')->from('#__ksenmart_property_values')->where('property_id=' . $property->id);
			$db->setQuery($query);
			$property->values = $db->loadObjectList();
		}
		unset($property);
		
		$html = '<ul>';
		
		if (count($properties) > 0) {
			
			foreach ($properties as $property) {
				$class = 'show';
				$opened = false;
				
				foreach ($property->values as $value) {
					if (in_array($value->id, $this->value)) {
						$opened = true;
						
						break;
					}
				}
				if ($opened) $class = 'hides';
				$html.= '<li>';
				$html.= '	<span>' . $property->title . '<a href="#" class="sh ' . $class . '"></a></span>';
				$style = '';
				if ($opened) $style = 'style="display:block;"';
				$html.= '	<ul ' . $style . '>';
				if (count($property->values) > 0) {
					
					foreach ($property->values as $value) {
						$checked = '';
						$active = '';
						if (in_array($value->id, $this->value)) {
							$checked = 'checked="checked"';
							$active = 'active';
						}
						$html.= '<li class="' . $active . '">';
						$html.= '	<label>' . $value->title . '<input type="checkbox" ' . $checked . ' value="' . $value->id . '" name="' . $this->name . '" onclick="' . $this->element['onclick'] . '" /></label>';
						$html.= '</li>';
					}
				} else {
					$html.= '	<li>';
					$html.= '		<label>' . JText::_('ksm_properties_no_property_values') . '</label>';
					$html.= '	</li>';
				}
				$html.= '	</ul>';
				$html.= '</li>';
			}
		} else {
			$html.= '<li>';
			$html.= '	<label>' . JText::_('ksm_properties_no_properties') . '</label>';
			$html.= '</li>';
		}
		
		$html.= '</ul>';
		
		$script = '
		jQuery(document).ready(function(){
				
			jQuery("body").on("click", ".ksm-slidemodule-propertiesvalues ul li a.show", function(){
				jQuery(this).removeClass("show");
				jQuery(this).addClass("hides");
				jQuery(this).parents("li:first").find("ul:first").show();		
				return false;
			});
			
			jQuery("body").on("click", ".ksm-slidemodule-propertiesvalues ul li a.hides", function(){
				jQuery(this).removeClass("hides");
				jQuery(this).addClass("show");
				jQuery(this).parents("li:first").find("ul:first").hide();
				return false;
			});		
			
		});
		';
		$document = JFactory::getDocument();
		$document->addScriptDeclaration($script);
		
		
		return $html;
	}
}
