<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

class JFormFieldOrderItems extends JFormField {
	
	protected $type = 'OrderItems';
	
	public function getInput() {
		$html = '';
		$html.= '<div class="positions order-positions">';
		$html.= '	<div class="heads">';
		$html.= '		' . JText::_($this->element['label']);
		$html.= '	</div>';
		$html.= '	<div class="positions-items" id="orderitems-container" style="display:block;">';
		
		foreach ($this->value as $item) {
			$inputname = $this->name . '[' . $item->id . ']';
			$html.= '	<div class="position">';
			$html.= '		<div class="col1">';
			$html.= '			<div class="img"><img alt="" src="' . $item->small_img . '"></div>';
			$html.= '			<div class="price">' . $item->val_price . '</div>';
			$html.= '		</div>';
			$html.= '		<div class="col2">';
			$html.= '			<div class="name">' . $item->title . '</div>';
			$html.= '			<div class="product_code">' . JText::_('ksm_catalog_product_code') . ':  ' . $item->product_code . '</div>';
			$html.= '			<div class="properties">';
			
			foreach ($item->properties as $property) {
				
				switch ($property->view) {
					case 'select':
						if ($property->edit_price || count($property->values) > 1) {
							$html.= '<div class="row">';
							$html.= '	<label class="inputname">' . $property->title . '</label>';
							$html.= '	<select class="sel" style="width:180px;" name="' . $this->name . '[' . $item->id . '][properties][' . $property->id . '][]">';
							$html.= '		<option value="">' . JText::_('ksm_orders_order_item_choose_property_value') . '</option>';
							
							foreach ($property->values as $value) {
								$html.= '	<option value="' . $value->id . '" ' . ($value->selected ? 'selected' : '') . '>' . $value->title . '</option>';
							}
							$html.= '	</select>';
							$html.= '</div>';
						}
					break;
					case 'radio':
						if ($property->edit_price || count($property->values) > 1) {
							$html.= '<div class="row">';
							$html.= '	<label class="inputname">' . $property->title . '</label>';
							
							foreach ($property->values as $value) {
								$html.= '<input type="radio" class="radio" name="' . $this->name . '[' . $item->id . '][properties][' . $property->id . '][]" value="' . $value->id . '" ' . ($value->selected ? 'checked' : '') . '>&nbsp;' . $value->title . '&nbsp;&nbsp;&nbsp;';
							}
							$html.= '</div>';
						}
					break;
					case 'checkbox':
						$html.= '<div class="row">';
						$html.= '	<label class="inputname">' . $property->title . '</label>';
						
						foreach ($property->values as $value) {
							$html.= '<div class="checkb">';
							$html.= '	<input type="checkbox" name="' . $this->name . '[' . $item->id . '][properties][' . $property->id . '][]" value="' . $value->id . '" ' . ($value->selected ? 'checked' : '') . '>';
							$html.= '	<label>' . $value->title . '<label>';
							$html.= '</div>';
						}
						$html.= '</div>';
					break;
				}
			}
			$html.= '			</div>';
			$html.= '		</div>';
			$html.= '		<div class="col3">';
			$html.= '			<div class="quants">';
			$html.= '				<span class="minus"></span>';
			$html.= '				<input name="' . $this->name . '[' . $item->id . '][count]" class="inputbox pos-count" value=' . $item->count . '>';
			$html.= '				<span class="plus"></span>';
			$html.= '			</div>';
			$html.= '			<div class="total">' . JText::_('ksm_orders_order_item_total_price') . $item->val_total_price . '</div>';
			$html.= '		</div>';
			$html.= '		<a class="del" href="#"></a>';
			$html.= '		<input type="hidden" class="product_packaging" value="' . $item->product_packaging . '">';
			$html.= '		<input type="hidden" name="' . $this->name . '[' . $item->id . '][basic_price]" class="pos-prd-basic-price" value="' . $item->basic_price . '">';
			$html.= '		<input type="hidden" name="' . $this->name . '[' . $item->id . '][price]" class="pos-prd-price" value="' . $item->price . '">';
			$html.= '		<input type="hidden" name="' . $this->name . '[' . $item->id . '][product_id]" class="pos-prd-id" value="' . $item->product_id . '">';
			$html.= '		<input type="hidden" name="' . $this->name . '[' . $item->id . '][id]" class="pos-id" value="' . $item->id . '">';
			$html.= '	</div>';
		}
		$html.= '		<div class="position empty-position" style="' . (count($this->value) ? 'display:none;' : '') . '">';
		$html.= '			<h3>' . JText::_('ksm_orders_order_no_items') . '</h3>';
		$html.= '		</div>';
		$html.= '	</div>';
		$html.= '	<div class="row">';
		$html.= '		<a href="' . JRoute::_('index.php?option=com_ksenmart&view=catalog&layout=search&items_tpl=orderitems&items_to=orderitems-container&tmpl=component') . '" class="add">' . JText::_('KSM_CATALOG_PRODUCT_ADD_CHILD') . '</a>';
		$html.= '	</div>';
		$html.= '</div>';
		
		$script = '
		jQuery(document).ready(function(){
				
			jQuery("body").on("click", ".order-positions .add", function(){
				var url=jQuery(this).attr("href");
				jQuery(".order-positions .pos-prd-id").each(function(){
					url+="&excluded[]="+jQuery(this).val();
				});
				url+="&excluded[]=";
				var width=jQuery(window).width();
				var height=jQuery(window).height();
				openPopupWindow(url,width,height);
				return false;
			});
			
			jQuery("body").on("click", ".order-positions .quants .minus", function(){
				var item=jQuery(this).parents(".position:first");
				var	count=parseFloat(item.find(".quants .inputbox").val());
				var product_packaging=parseFloat(item.find(".product_packaging").val());
				count = Math.ceil(count/product_packaging)*product_packaging;
				count-=product_packaging;
				if (count<product_packaging) count=product_packaging;
				count=KMFixProductCount(count);
				item.find(".quants .inputbox").val(count)
				onChangeItems();
				return true;
			});
			
			jQuery("body").on("click", ".order-positions .quants .plus", function(){
				var item=jQuery(this).parents(".position:first");
				var	count=parseFloat(item.find(".quants .inputbox").val());
				var product_packaging=parseFloat(item.find(".product_packaging").val());
				count = Math.ceil(count/product_packaging)*product_packaging;
				count+=product_packaging;
				if (count<product_packaging) count=product_packaging;
				count=KMFixProductCount(count);
				item.find(".quants .inputbox").val(count)
				onChangeItems();
				return true;
			});		
			
			jQuery("body").on("keypress", ".order-positions .quants .inputbox", function(e){
				if (e.keyCode==13)
				{	
					var item=jQuery(this).parents(".position:first");
					var	count=parseFloat(item.find(".quants .inputbox").val());
					var product_packaging=parseFloat(item.find(".product_packaging").val());
					count = Math.ceil(count/product_packaging)*product_packaging;
					if (count<product_packaging) count=product_packaging;
					count=KMFixProductCount(count);
					item.find(".quants .inputbox").val(count)
					onChangeItems();
					return true;			
				}
			});
			
			jQuery("body").on("click", ".order-positions .del", function(){
				jQuery(this).parents(".position:first").remove();
				if (jQuery(".order-positions .position").length==1)
					jQuery(".order-positions .empty-position").show();
				onChangeItems();	
				return false;
			});
			
			jQuery("body").on("change", ".order-positions .properties input", function(){
				onChangeItems();	
			});	

		});
		
		function onChangeItems()
		{
			var data={};
			var vars={};
			var form=jQuery(".form");
			data["model"]="orders";
			data["form"]="order";
			data["fields"]=["items","costs"];
			vars["user_id"]=form.find("#jformuser_id").val();			
			vars["region_id"]=form.find("#jformregion_id").val();
			vars["shipping_id"]=form.find("#jformshipping_id").val();
			vars["items"]=getOrderItems();
			data["vars"]=vars;
			data["id"]=form.find(".id").val();
			KMRenewFormFields(data);
		}		
		
		function afterAddingItems()
		{
			onChangeItems();
		}		
		';
		$document = JFactory::getDocument();
		$document->addScriptDeclaration($script);
		
		
		return $html;
	}
}
