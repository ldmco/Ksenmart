<?php
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JFormHelper::loadFieldClass('checkboxes');

class JFormFieldproductproperties extends JFormField
{

	protected $type = 'ProductProperties';

	public function getInput()
	{
		$app               = JFactory::getApplication();
		$db                = JFactory::getDbo();
		$active_properties = $app->input->get('active_properties', array(), 'array');
		$html              = '';
		$query             = $db->getQuery(true);
		$query->select('*')->from('#__ksenmart_properties')->where('published=1');
		$db->setQuery($query);
		$properties = $db->loadObjectList('id');
		$query      = $db->getQuery(true);
		$query->select('*')
			->from('#__ksenmart_property_values')
			->where('property_id IN (' . implode(',', array_keys($properties)) . ')')
			->order('ordering');
		$db->setQuery($query);
		$values = $db->loadObjectList('id');

		foreach ($values as $v)
		{
			if (array_key_exists($v->property_id, $properties))
			{
				if (!isset($properties[$v->property_id]->values)) $properties[$v->property_id]->values = array();
				$properties[$v->property_id]->values[$v->id] = $v;
			}
		}

		$html .= '<div class="properties">';
		$html .= '	<h3 class="headname">' . JText::_($this->element['label']);
		if ($this->element['add_button'])
		{
			$html .= '<a class="add km-modal" rel=\'{"x":"90%","y":"90%"}\' href="' . JRoute::_('index.php?option=com_ksenmart&view=' . $this->element['view'] . '&layout=' . $this->element['item_type'] . '&tmpl=component') . '"></a>';
		}
		$html .= '<a href="#" class="sh hides"></a></h3>';
		$html .= '	<ul>';
		if (count($this->value) == 0)
		{
			$html .= '	<li>';
			$html .= '		<div class="property"><span class="name">' . JText::_('ksm_catalog_product_no_properties') . '</span></div>';
			$html .= '	</li>';
		}

		foreach ($this->value as $p)
		{
			$product_values = $p->values;
			$html           .= '	<li>';
			$html           .= '		<div class="property">';
			$html           .= '			<span class="name">' . $p->title . '</span>';
			$property       = $properties[$p->id];

			switch ($property->type)
			{
				case 'text':
					$text = '';
					$flag = false;

					foreach ($product_values as $pv)
					{
						if ($pv->property_id == $property->id)
						{
							$text = htmlspecialchars($pv->text);
							$flag = true;
						}
					}
					if (!$flag) $text = $property->default;
					$html .= '<a class="edit_property km-modal icon-pencil-2" rel=\'{"x":"90%","y":"90%"}\' href="' . JRoute::_('index.php?option=com_ksenmart&view=' . $this->element['view'] . '&layout=' . $this->element['item_type'] . '&id=' . $property->id . '&tmpl=component') . '"></a>';
					$html .= '	<div class="item">';
					$html .= '		<input type="text" name="' . $this->name . '[' . $property->id . '][text]" class="inputbox" value="' . $text . '" size="30"> ' . $property->suffix;
					$html .= '	</div>';
					$html .= '</div>';

					break;
				case 'select':
					$html .= '<a class="edit_property km-modal icon-pencil-2" rel=\'{"x":"90%","y":"90%"}\' href="' . JRoute::_('index.php?option=com_ksenmart&view=' . $this->element['view'] . '&layout=' . $this->element['item_type'] . '&id=' . $property->id . '&tmpl=component') . '"></a>';
					$html .= '<a href="#" onClick="generateChilds(this)" class="generate_childs">' . JText::_('ksm_catalog_product_generate_childs') . '</a>';
					$html .= '	<a href="#" class="sh show"></a>';
					$html .= '</div>';
					$html .= '<ul class="property-ul" property_id="' . $property->id . '" style="' . (in_array($property->id, $active_properties) ? 'display:block;' : '') . '">';
					if (property_exists($property, 'values'))
					{

						foreach ($property->values as $property_value)
						{
							$checked = '';
							$active  = '';
							$price   = '';

							foreach ($product_values as $pv)
							{
								if ($pv->value_id == $property_value->id)
								{
									$checked = 'checked';
									$active  = 'active';
									if ($property->edit_price == 1)
									{
										$price = $pv->price;
									}
								}
							}
							$html .= '<li>';
							$html .= '	<div class="property ' . $active . '">';
							$html .= '		<label>';
							$html .= '			<span class="name">';
							$html .= '  				<input type="checkbox" name="' . $this->name . '[' . $property->id . '][' . $property_value->id . '][id]" value="' . $property_value->id . '" onclick="setPropertyActive(this);" ' . $checked . '> ';
							$html .= $property_value->title . ' ' . $property->suffix;
							$html .= '			</span>';
							$html .= '		</label>';
							if ($property->edit_price == 1)
							{
								$html .= '	<div class="item">';
								$html .= '		<label>' . JText::_('ksm_catalog_product_properties_under_price') . '</label>';
								$html .= '		<input type="text" name="' . $this->name . '[' . $property->id . '][' . $property_value->id . '][price]" style="width:80px;" class="inputbox" value="' . $price . '" >';
								$html .= '	</div>';
							}
							$html .= '	</div>';
							$html .= '</li>';
						}
						$html .= '<input class="generate_property" type="hidden" name="' . $this->name . '[' . $property->id . '][generated]" value="0" >';
					}
					else
					{
						$html .= '<li>';
						$html .= '	<div class="property">';
						$html .= '		<label>';
						$html .= '			<span class="name">' . JText::_('ksm_catalog_product_properties_no_values') . '</span>';
						$html .= '		</label>';
						$html .= '</li>';
					}
					$html .= '</ul>';

					break;
			}
			$html .= '	</li>';
		}
		$html .= '	</ul>';
		$html .= '</div>';

		$script   = '
		jQuery(document).ready(function(){
			
			jQuery("body").on("click", ".properties h3 .sh", function(){
				if (jQuery(this).is(".hides"))
				{
					jQuery(this).removeClass("hides");
					jQuery(this).addClass("show");
					jQuery(this).parents(".properties").find("ul:first").hide();				
				}
				else
				{
					jQuery(this).removeClass("show");
					jQuery(this).addClass("hides");
					jQuery(this).parents(".properties").find("ul:first").show();	
				}	
				return false;
			});	
			
			jQuery("body").on("click", ".properties li .sh", function(){
				if (jQuery(this).is(".hides"))
				{
					jQuery(this).removeClass("hides");
					jQuery(this).addClass("show");
					jQuery(this).parents("li:first").find("ul:first").hide();				
				}
				else
				{
					jQuery(this).removeClass("show");
					jQuery(this).addClass("hides");
					jQuery(this).parents("li:first").find("ul:first").show();	
				}	
				return false;
			});		

		});
		
		function updateProperties(){
			var data = {};
			var form = jQuery(".form");
			data["model"] = "catalog";
			data["form"] = "product";
			data["fields"] = ["properties"];
			data["id"] = form.find(".id").val();
			KMRenewFormFields(data);
		}
		
		function setPropertyActive(obj){
			var item=jQuery(obj).parents(".property:first");
			var property = item.closest("li");
			if (item.is(".active"))
				item.removeClass("active");
			else
				item.addClass("active");
		}		
		
		function generateChilds(obj){
			var property = jQuery(obj).closest("li");
			property.find(".generate_property").val(1);
			jQuery(\'.form input[name="close"]\').val(0);
	        jQuery(\'.form input[name="jform[is_parent]"]\').val(1);
	        jQuery(\'.form [type="submit"]\').click();
	        return false;
		}';
		$document = JFactory::getDocument();
		$document->addScriptDeclaration($script);


		return $html;
	}
}
