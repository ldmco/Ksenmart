<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

JFormHelper::loadFieldClass('checkboxes');
class JFormFieldProductRelated extends JFormField {
	
	protected $type = 'ProductRelated';
	
	public function getInput() {
		$params = JComponentHelper::getParams('com_ksenmart');
		$html = '';
		$html.= '<div class="positions" ng-controller="ProductRelationsCtrl">';
		$html.= '	<div id="ksm-slidemodule-productrelated-container">';
		if (count($this->value)) {
			
			foreach ($this->value as $product) {
				$html.= '<div class="position">';
				$html.= '	<div class="col1">';
				$html.= '		<div class="img"><img alt="" src="' . $product->small_img . '"></div>';
				$html.= '		<div class="name">' . $product->title . '</div>';
				$html.= '	</div>';
				$html.= '	<a href="javascript:void(0);" class="del" ng-click="remove($event)"></a>';
				$html.= '	<input type="hidden" name="' . $this->name . '[' . $product->id . '][relative_id]" value="' . $product->id . '">';
				$html.= '</div>';
			}
		}
		$html.= '		<div class="position no-items" ' . (count($this->value) ? 'style="display:none;"' : '') . '>';
		$html.= '			<div class="col1">' . JText::_('KSM_PLUGINS_RELATEDPRODUCTS_NOITEMS') . '</div>';
		$html.= '		</div >';
		$html.= '	</div>';
		$html.= '	<div class="row">';
		$html.= '		<a ui-sref="add_relative" href="' . JRoute::_('index.php?option=com_ksenmart&view=catalog&layout=search&items_tpl=productrelateditems&items_to=ksm-slidemodule-productrelated-container&tmpl=component') . '" class="add">' . JText::_('KS_ADD') . '</a>';
		$html.= '	</div>';
		$html.= '</div>';
		
		$script = '
		jQuery(document).ready(function(){
				
			jQuery("body").on("click", ".ksm-slidemodule-productrelated .add", function(){
				var url=jQuery(this).attr("href");
				jQuery(".ksm-slidemodule-productrelated input[type=\'hidden\']").each(function(){
					url+="&excluded[]="+jQuery(this).val();
				});
				url+="&excluded[]=";
				var width=jQuery(window).width();
				var height=jQuery(window).height();
				openPopupWindow(url,width,height);
				return false;
			});
			
			jQuery("body").on("click", ".ksm-slidemodule-productrelated .del", function(){
				jQuery(this).parents(".position:first").remove();
				if (jQuery(".ksm-slidemodule-productrelated .position").length==1)
					jQuery(".ksm-slidemodule-productrelated .no-items").show();
				return false;
			});
			
		});
		';
		$document = JFactory::getDocument();
		$document->addScriptDeclaration($script);
		
		return $html;
	}
}