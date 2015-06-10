<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

class JFormFieldShippingCustomerFields extends JFormField {
	
	protected $type = 'ShippingCustomerFields';
	
	public function getInput() {
		if (count($this->value) == 0) {
			$this->value = array(
				'-1' => array(
					'position' => 'customer',
					'type' => 'text',
					'title' => 'name',
					'required' => 1,
					'system' => 1,
					'ordering' => 1,
					'published' => 1
				) ,
				'-2' => array(
					'position' => 'customer',
					'type' => 'text',
					'title' => 'last_name',
					'required' => 1,
					'system' => 1,
					'ordering' => 2,
					'published' => 1
				) ,
				'-3' => array(
					'position' => 'customer',
					'type' => 'text',
					'title' => 'middle_name',
					'required' => 1,
					'system' => 1,
					'ordering' => 3,
					'published' => 1
				) ,
				'-4' => array(
					'position' => 'customer',
					'type' => 'text',
					'title' => 'phone',
					'required' => 1,
					'system' => 1,
					'ordering' => 4,
					'published' => 1
				) ,
				'-5' => array(
					'position' => 'customer',
					'type' => 'text',
					'title' => 'email',
					'required' => 1,
					'system' => 1,
					'ordering' => 5,
					'published' => 1
				)
			);
		}
		$html = '';
		$html.= '<div class="shipping-user-fields">';
		$html.= '		<div class="row fields-row">';
		
		foreach ($this->value as $key => $field) {
			$html.= '	<div class="row">';
			$html.= '		<div class="field switch">';
			$html.= '			<label class="inputname">' . JText::_(($field['system'] == 1 ? 'ksm_shippings_shipping_field_' : '') . $field['title']) . '</label>';
			if ($field['type'] == 'select') {
				$html.= '		<select class="sel" id="user-field-' . $key . '" style="width:113px;">';
				
				foreach ($field['values'] as $value) {
					$html.= '		<option>' . $value->title . '</option>';
				}
				$html.= '		</select>';
			} else {
				$html.= '		<input type="text" class="inputbox" value="" />';
			}
			$html.= '			<label class="cb-enable ' . ($field['published'] == 1 ? 'selected' : '') . '"><span>' . JText::_('ksm_enable') . '</span></label>';
			$html.= '			<label class="cb-disable ' . ($field['published'] == 0 ? 'selected' : '') . '"><span>' . JText::_('ksm_disable') . '</span></label>';
			$html.= '			<div class="checkb">';
			$html.= '				<input type="checkbox" name="' . $this->name . '[' . $key . '][required]" value="1" ' . ($field['required'] == 1 ? 'checked' : '') . ' />';
			$html.= '				<span>' . JText::_('ksm_shippings_shipping_required_field') . '</span>';
			$html.= '			</div>';
			$html.= '			<input type="hidden" name="' . $this->name . '[' . $key . '][position]" value="' . $field['position'] . '" >';
			$html.= '			<input type="hidden" name="' . $this->name . '[' . $key . '][type]" value="' . $field['type'] . '" >';
			$html.= '			<input type="hidden" name="' . $this->name . '[' . $key . '][title]" value="' . $field['title'] . '" >';
			$html.= '			<input type="hidden" name="' . $this->name . '[' . $key . '][ordering]" value="' . $field['ordering'] . '" class="ordering" >';
			$html.= '			<input type="hidden" name="' . $this->name . '[' . $key . '][system]" value="' . $field['system'] . '">';
			$html.= '			<input type="hidden" name="' . $this->name . '[' . $key . '][published]" value="' . $field['published'] . '" class="published" >';
			if ($field['type'] == 'select') {
				
				foreach ($field['values'] as $value) {
					$html.= '	<input type="hidden" name="' . $this->name . '[' . $key . '][values][' . $value->id . '][title]" value="' . $value->title . '" >';
				}
			}
			$html.= '		</div>';
			if ($field['system'] != 1) $html.= '	<a class="del"></a>';
			$html.= '	</div>';
		}
		$html.= '	</div>';
		$html.= '	<div class="row">';
		$html.= '		<a href="#" class="add">' . JText::_('ksm_shippings_shipping_add_field') . '</a>';
		$html.= '	</div>';
		$html.= '</div>';
		$html.= '<div id="popup-window-user-fields" class="popup-window">';
		$html.= '	<div style="width: 480px;margin-left: -230px;margin-top: -175px;">';
		$html.= '		<div class="popup-window-inner">';
		$html.= '			<div class="heading">';
		$html.= '				<h3>' . JText::_('ksm_shippings_shipping_new_field') . '</h3>';
		$html.= '				<div class="save-close">';
		$html.= '					<button class="saves">' . JText::_('ksm_add') . '</button>';
		$html.= '					<button class="close" onclick="return false;"></button>';
		$html.= '				</div>';
		$html.= '			</div>';
		$html.= '			<div class="contents">';
		$html.= '				<div class="contents-inner">';
		$html.= '					<div class="slide_module">';
		$html.= '						<div class="row">';
		$html.= '							<label class="inputname">' . JText::_('ksm_shippings_shipping_new_field_name') . '</label>';
		$html.= '							<input type="text" class="inputbox new-field-name" />';
		$html.= '						</div>';
		$html.= '						<div class="row">';
		$html.= '							<label class="inputname">' . JText::_('ksm_shippings_shipping_new_field_type') . '</label>';
		$html.= '							<select class="sel" id="new-user-field-type" style="width:113px;">';
		$html.= '								<option value="text">' . JText::_('ksm_shippings_shipping_new_field_type_text') . '</text>';
		$html.= '								<option value="select">' . JText::_('ksm_shippings_shipping_new_field_type_select') . '</text>';
		$html.= '							</select>';
		$html.= '						</div>';
		$html.= '						<div class="row values-row" style="display:none;">';
		$html.= '						</div>';
		$html.= '						<div class="row add-row" style="display:none;">';
		$html.= '							<a href="#" class="add">' . JText::_('ksm_shippings_shipping_add_value') . '</a>';
		$html.= '						</div>';
		$html.= '					</div>';
		$html.= '				</div>';
		$html.= '			</div>';
		$html.= '		</div>';
		$html.= '	</div>';
		$html.= '</div>';
		$script = '
		jQuery(document).ready(function(){
			jQuery("body").on("click", ".shipping-user-fields .fields-row .cb-enable", function(){
				jQuery(this).addClass("selected");
				jQuery(this).parents(".row:first").find(".cb-disable").removeClass("selected");
				jQuery(this).parents(".row:first").find(".published").val("1");
			});		
			jQuery("body").on("click", ".shipping-user-fields .fields-row .cb-disable", function(){
				jQuery(this).addClass("selected");
				jQuery(this).parents(".row:first").find(".cb-enable").removeClass("selected");			
				jQuery(this).parents(".row:first").find(".published").val("0");
			});				
			jQuery("body").on("click", ".shipping-user-fields .fields-row .del", function(){
				jQuery(this).parents(".row:first").remove();
			});
			jQuery("body").on("click", ".shipping-user-fields .add", function(){
				jQuery("#popup-window-user-fields").show();
				return false;
			});
			jQuery("body").on("change", "#new-user-field-type", function(){
				if (jQuery(this).val()=="text")
				{
					jQuery("#popup-window-user-fields .add-row").hide();
					jQuery("#popup-window-user-fields .values-row").hide();
				}
				else
				{
					jQuery("#popup-window-user-fields .add-row").show();
					jQuery("#popup-window-user-fields .values-row").show();				
				}
			});	
			jQuery("body").on("click", "#popup-window-user-fields .add", function(){
				var count=jQuery("#popup-window-user-fields .values-row .row").length;
				count++;
				var html="";
				html+="<div class=\'row\'>";
				html+="		<label class=\'inputname\'>' . JText::_('ksm_shippings_shipping_new_field_value') . ' "+count+"</label>";
				html+="		<input type=\'text\' class=\'inputbox\' />";				
				html+="</div>";
				jQuery("#popup-window-user-fields .values-row").append(html);
				return false;
			});
			jQuery("body").on("click", "#popup-window-user-fields .saves", function(){
				var title=jQuery("#popup-window-user-fields .new-field-name").val();
				if (title=="")
					return false;
				var type=jQuery("#new-user-field-type").val();
				var ordering=jQuery(".shipping-user-fields .fields-row .row").length;
				ordering++;
				var key=ordering*(-1);
				var html="";
				html+="<div class=\'row\'>";
				html+="		<div class=\'field switch\'>";		
				html+="			<label class=\'inputname\'>"+title+"</label>";		
				if (type=="select")
				{
					html+="		<select class=\'sel\' id=\'new-user-field-"+ordering+"\' style=\'width:113px;\'>";		
					jQuery("#popup-window-user-fields .values-row .row .inputbox").each(function(){
						html+="		<option>"+jQuery(this).val()+"</option>";		
					});
					html+="		</select>";		
				}
				else
				{
					html+="		<input type=\'text\' class=\'inputbox\' />";		
				}	
				html+="			<label class=\'cb-enable selected\'><span>' . JText::_('ksm_enable') . '</span></label>";		
				html+="			<label class=\'cb-disable\'><span>' . JText::_('ksm_disable') . '</span></label>";		
				html+="			<div class=\'checkb\'>";		
				html+="				<input type=\'checkbox\' name=\'' . $this->name . '["+key+"][required]\' value=\'1\' checked />";		
				html+="				<span>' . JText::_('ksm_shippings_shipping_required_field') . '</span>";		
				html+="			</div>";		
				html+="			<input type=\'hidden\' name=\'' . $this->name . '["+key+"][position]\' value=\'customer\' >";		
				html+="			<input type=\'hidden\' name=\'' . $this->name . '["+key+"][type]\' value=\'"+type+"\' >";		
				html+="			<input type=\'hidden\' name=\'' . $this->name . '["+key+"][title]\' value=\'"+title+"\' >";		
				html+="			<input type=\'hidden\' name=\'' . $this->name . '["+key+"][ordering]\' value=\'"+ordering+"\' class=\'ordering\' >";		
				html+="			<input type=\'hidden\' name=\'' . $this->name . '["+key+"][published]\' value=\'1\' class=\'published\' >";		
				if (type=="select")
				{
					var k=-1;
					jQuery("#popup-window-user-fields .values-row .row .inputbox").each(function(){
						html+="	<input type=\'hidden\' name=\'' . $this->name . '["+key+"][values]["+k+"][title]\' value=\'"+jQuery(this).val()+"\' >";		
						k--;
					});
				}				
				html+="		</div>";			
				html+="		<a class=\'del\'></a>";			
				html+="</div>";	
				jQuery(".shipping-user-fields .fields-row").append(html);
				jQuery("#popup-window-user-fields .new-field-name").val("");
				jQuery("#popup-window-user-fields .values-row").html("");
				jQuery("#popup-window-user-fields").hide();
				var params = {changedEl: "select.sel"};
				cuSel(params);				
				return false;
			});
		});
		';
		$document = JFactory::getDocument();
		$document->addScriptDeclaration($script);
		
		return $html;
	}
}
