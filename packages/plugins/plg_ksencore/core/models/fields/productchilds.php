<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

JFormHelper::loadFieldClass('checkboxes');
class JFormFieldProductChilds extends JFormField {
	
	protected $type = 'ProductChilds';
	
	public function getInput() {
		$product_id = JRequest::getVar('id', 0);
		$db = JFactory::getDbo();
		$html = '';
		$html.= '<div class="positions">';
		$html.= '	<div class="heads">';
		$html.= '		<div class="col1">' . JText::_('KSM_CATALOG_PRODUCT_CHILD_NAME') . '</div>';
		$html.= '		<div class="col2">' . JText::_('KSM_CATALOG_PRODUCT_CHILD_PRODUCT_CODE') . '</div>';
		$html.= '		<div class="col3">' . JText::_('KSM_CATALOG_PRODUCT_CHILD_PRICE') . '</div>';
		$html.= '		<div class="col4">' . JText::_('KSM_CATALOG_PRODUCT_CHILD_IN_STOCK') . '</div>';
		$html.= '		<div class="col5">' . JText::_('KSM_CATALOG_PRODUCT_CHILD_STATUS') . '</div>';
		$html.= '	</div>';
		
		foreach ($this->value as $group) {
			$html.= '<div class="positions-group ' . ($group->id == 0 ? 'empty-group' : '') . '">';
			if ($group->id != 0) {
				$html.= '<h3 class="headname">' . $group->title;
				$html.= '	<a href="#" class="sh hides"></a>';
				$html.= '	<a class="del" href="#"></a>';
				$html.= '</h3>';
			}
			$html.= '	<div class="positions-items" ' . ($group->id == 0 ? 'style="display:block;"' : '') . '>';
			
			foreach ($group->products as $product) {
				$inputname = $this->name . '[' . $product->id . ']';
				$active = ($product->published == 1) ? 'active' : '';
				$checked = ($product->published == 1) ? 'checked' : '';
				$html.= '	<div class="position ' . $active . '">';
				$html.= '		<div class="col1">';
				$html.= '			<div class="img"><img alt="" src="' . $product->small_img . '"></div>';
				$html.= '			<div class="name">' . $product->title . '</div>';
				$html.= '			<div class="links">';
				$html.= '				<a class="edit-pos km-modal" rel=\'{"x":"100%","y":"100%"}\' href="' . JRoute::_('index.php?option=com_ksenmart&view=catalog&layout=child&id=' . $product->id . '&parent_id=' . $product_id . '&tmpl=component') . '">' . JText::_('KSM_EDIT') . '</a>';
				$html.= '				<a class="delete-pos" href="#">' . JText::_('KSM_DELETE') . '</a>';
				$html.= '			</div>';
				$html.= '		</div>';
				$html.= '		<div class="col2">';
				$html.= '			' . $product->product_code . '&nbsp;';
				$html.= '		</div>';
				$html.= '		<div class="col3">';
				$html.= '			<input  type="text" class="inputbox" name="' . $inputname . '[price]" value="' . $product->price . '">';
				$html.= '		</div>';
				$html.= '		<div class="col4">';
				$html.= '			<input type="text" name="' . $inputname . '[in_stock]" value="' . $product->in_stock . '" class="inputbox">';
				$html.= '		</div>';
				$html.= '		<div class="col5">';
				$html.= '			<input type="checkbox" name="' . $inputname . '[published]" class="checkbox" value="1" ' . $checked . '>&nbsp;';
				$html.= '		</div>';
				$html.= '		<input type="hidden" name="' . $inputname . '[id]" class="pos-id" value="' . $product->id . '">';
				$html.= '		<input type="hidden" name="' . $inputname . '[childs_group]" class="childs_group" value="' . $product->childs_group . '">';
				$html.= '		<input type="hidden" name="' . $inputname . '[ordering]" class="ordering" value="' . $product->ordering . '">';
				$html.= '	</div>';
			}
			$html.= '		<div class="position empty-position" style="' . (count($group->products) > 0 ? 'display:none;' : '') . '">';
			$html.= '			<h3>' . JText::_('KSM_CATALOG_PRODUCT_EMPTY_CHILDS_GROUP') . '</h3>';
			$html.= '		</div>';
			$html.= '		<input type="hidden" name="child_groups[' . $group->id . '][id]" class="group-id" value="' . $group->id . '">';
			$html.= '		<input type="hidden" name="child_groups[' . $group->id . '][ordering]" class="group-ordering" value="' . $group->ordering . '">';
			$html.= '	</div>';
			$html.= '</div>';
		}
		$html.= '	<div class="row">';
		$html.= '		<a class="add add-child-group km-modal" rel=\'{"x":"500","y":"150"}\' href="' . JRoute::_('index.php?option=com_ksenmart&view=catalog&layout=childgroup&product_id=' . $product_id . '&tmpl=component') . '">' . JText::_('KSM_CATALOG_PRODUCT_ADD_CHILD_GROUP') . '</a>';
		$html.= '		<a class="add add-child km-modal" rel=\'{"x":"100%","y":"100%"}\' href="' . JRoute::_('index.php?option=com_ksenmart&view=catalog&layout=child&parent_id=' . $product_id . '&tmpl=component') . '">' . JText::_('KSM_CATALOG_PRODUCT_ADD_CHILD') . '</a>';
		$html.= '	</div>';
		$html.= '</div>';
		$script = '
		jQuery(document).ready(function(){
		
			jQuery(".positions").sortable({
				items:".positions-group",
				handle:".headname",
				stop:function(){
					refreshChilds();
				}
			});	
			
			jQuery(".positions-items").sortable({
				cancel:".empty-position",
				connectWith:".positions-items",
				stop:function(){
					refreshChilds();
				}
			});			
			
			jQuery("body").on("click", ".positions-group .sh", function(){
				if (jQuery(this).is(".hides"))
				{
					jQuery(this).removeClass("hides");
					jQuery(this).addClass("show");
					jQuery(this).parents(".positions-group").find(".positions-items").show();	
				}
				else
				{
					jQuery(this).removeClass("show");
					jQuery(this).addClass("hides");
					jQuery(this).parents(".positions-group").find(".positions-items").hide();				
				}	
				return false;
			});	
			
			jQuery("body").on("click", ".position .delete-pos", function(){
				if (confirm(Joomla.JText._("KSM_DELETE_CONFIRMATION"))) {
					var item=jQuery(this).parents(".position:first");
					var data={"items":[]};
					data["items"].push(item.find(".pos-id").val());
					data["task"]="delete_list_items";
					data["view"]="catalog";	
					jQuery.ajax({
						url:"index.php?option=com_ksenmart",
						data:data,
						dataType:"json",
						async:false,
						success:function(responce){	
							if (responce.errors != 0)
								KMShowMessage(responce.message.join("<br>"));
							else
								item.remove();
						}
					});
					refreshChilds();
				}				
				return false;
			});		

			jQuery("body").on("click", ".positions-group .del", function(){
				if (confirm(Joomla.JText._("KSM_DELETE_CONFIRMATION")))
				{
					var item=jQuery(this).parents(".positions-group:first");
					var data={};
					data["group_id"]=item.find(".group-id").val();
					data["task"]="catalog.delete_child_group";
					jQuery.ajax({
						url:"index.php?option=com_ksenmart",
						data:data,
						async:false,
						success:function(responce){			
							item.find(".position").each(function(){
								jQuery(this).clone().appendTo(".empty-group .positions-items");
							});
							item.remove();
						}
					});
					refreshChilds();
				}				
				return false;
			});		

			jQuery("body").on("click", ".position .checkbox", function(){
				var item=jQuery(this).parents(".position:first");
				if (item.is(".active"))
					item.removeClass("active");
				else
					item.addClass("active");
			});			
			
			jQuery().on("click", ".position .inputbox", function(){
				jQuery(this).focus();
			});
			
		});
		
		function refreshChilds()
		{
			var group_ordering=1;		
			jQuery(".positions-group").each(function(){
				ordering=1;		
				var group_id=jQuery(this).find(".group-id").val();
				jQuery(this).find(".group-ordering").val(group_ordering);
				if (jQuery(this).find(".position").length>1)
					jQuery(this).find(".empty-position").hide();
				else	
					jQuery(this).find(".empty-position").show();
				jQuery(this).find(".position").each(function(){
					jQuery(this).find(".ordering").val(ordering);
					jQuery(this).find(".childs_group").val(group_id);
					ordering++;
				});
				group_ordering++;
			});		
		}
		';
		$document = JFactory::getDocument();
		$document->addScriptDeclaration($script);
		
		return $html;
	}
}
