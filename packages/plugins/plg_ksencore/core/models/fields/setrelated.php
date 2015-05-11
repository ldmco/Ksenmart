<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

JFormHelper::loadFieldClass('checkboxes');
class JFormFieldSetRelated extends JFormField {
	
	protected $type = 'SetRelated';
	
	public function getInput() {
		$params = JComponentHelper::getParams('com_ksenmart');
		$html = '';
		$html.= '<div class="positions set-positions">';
		$html.= '	<div class="heads">';
		$html.= '		<div class="col1">' . JText::_('KSM_CATALOG_SET_RELATED_NAME') . '</div>';
		$html.= '		<div class="col2">' . JText::_('KSM_CATALOG_SET_RELATED_PRODUCT_CODE') . '</div>';
		$html.= '		<div class="col3">' . JText::_('KSM_CATALOG_SET_RELATED_PRICE') . '</div>';
		$html.= '		<div class="col4">' . JText::_('KSM_CATALOG_SET_RELATED_IN_STOCK') . '</div>';
		$html.= '	</div>';
		$html.= '	<div class="positions-items" id="setrelated-container" style="display:block;">';
		
		foreach ($this->value as $product) {
			$html.= '	<div class="position">';
			$html.= '		<div class="col1">';
			$html.= '			<div class="img"><img alt="" src="' . $product->small_img . '"></div>';
			$html.= '			<div class="name">' . $product->title . '</div>';
			$html.= '			<div class="links">';
			$html.= '				<a class="delete-pos" href="#">' . JText::_('KSM_DELETE') . '</a>';
			$html.= '			</div>';
			$html.= '		</div>';
			$html.= '		<div class="col2">';
			$html.= '			' . $product->product_code . '&nbsp;';
			$html.= '		</div>';
			$html.= '		<div class="col3">';
			$html.= '			' . $product->val_price;
			$html.= '		</div>';
			$html.= '		<div class="col4">';
			$html.= '			' . $product->in_stock;
			$html.= '		</div>';
			$html.= '		<input type="hidden" class="price" value="' . $product->price . '">';
			$html.= '		<input type="hidden" name="' . $this->name . '[' . $product->id . '][relative_id]" class="pos-id" value="' . $product->id . '">';
			$html.= '	</div>';
		}
		$html.= '		<div class="position empty-position no-items" style="' . (count($this->value) > 0 ? 'display:none;' : '') . '">';
		$html.= '			<h3>' . JText::_('ksm_catalog_set_no_relative') . '</h3>';
		$html.= '		</div>';
		$html.= '	</div>';
		$html.= '	<div class="row">';
		$html.= '		<a href="' . JRoute::_('index.php?option=com_ksenmart&view=catalog&layout=search&items_tpl=setrelateditems&items_to=setrelated-container&tmpl=component') . '" class="add">' . JText::_('ksm_add') . '</a>';
		$html.= '	</div>';
		$html.= '</div>';
		
		$script = '
		jQuery(document).ready(function(){
				
			jQuery("body").on("click", ".set-positions .add", function(){
				var url=jQuery(this).attr("href");
				jQuery(".set-positions .pos-id").each(function(){
					url+="&excluded[]="+jQuery(this).val();
				});
				url+="&excluded[]=";
				var width=jQuery(window).width();
				var height=jQuery(window).height();
				openPopupWindow(url,width,height);
				return false;
			});
			
			jQuery("body").on("click", ".set-positions .delete-pos", function(){
				jQuery(this).parents(".position:first").remove();
				if (jQuery(".set-positions .position").length==1)
					jQuery(".set-positions .no-items").show();
				afterAddingItems();	
				return false;
			});
			
		});
		
		function afterAddingItems()
		{
			var old_price=0;
			jQuery(".set-positions .price").each(function(){
				old_price+=parseInt(jQuery(this).val());
			});
			jQuery("input[name=\'jform[old_price]\']").val(old_price);
		}		
		';
		$document = JFactory::getDocument();
		$document->addScriptDeclaration($script);
		
		return $html;
	}
}