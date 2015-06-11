<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

class JFormFieldCommentProduct extends JFormField {
	
	protected $type = 'CommentProduct';
	
	public function getInput() {
		$db = JFactory::getDBO();
		
		$params = JComponentHelper::getParams('com_ksenmart');
		$html = '';
		$html.= '<div class="positions">';
		$html.= '	<div id="ksm-slidemodule-commentproduct-container">';
		if ($this->value > 0) {
			$query = $db->getQuery(true);
			$query->select('p.*')->from('#__ksenmart_products as p')->where('p.id=' . $this->value);
			$query = KSMedia::setItemMainImageToQuery($query);
			$db->setQuery($query);
			$product = $db->loadObject();
			if (!count($product)) {
				$product = new stdClass();
				$product->title = JText::_('ksm_comments_comment_deletedproduct_title');
				$product->small_img = KSMedia::resizeImage('', 'products', $params->get('admin_product_medium_image_width') , $params->get('admin_product_medium_image_heigth'));
			} else $product->small_img = KSMedia::resizeImage($product->filename, $product->folder, $params->get('admin_product_thumb_image_width') , $params->get('admin_product_thumb_image_heigth') , json_decode($product->params, true));
			$html.= '	<div class="position">';
			$html.= '		<div class="col1">';
			$html.= '			<div class="img"><img alt="" src="' . $product->small_img . '"></div>';
			$html.= '			<div class="name">' . $product->title . '</div>';
			$html.= '		</div>';
			$html.= '		<a href="#" class="del"></a>';
			$html.= '		<input type="hidden" name="' . $this->name . '" value="' . $this->value . '">';
			$html.= '	</div>';
		} else {
			$html.= '	<div class="position no-items">';
			$html.= '		<div class="col1">' . JText::_('ksm_comments_comment_no_product') . '</div>';
			$html.= '		<input type="hidden" name="' . $this->name . '" value="' . $this->value . '">';
			$html.= '	</div >';
		}
		$html.= '	</div>';
		if ($this->value == 0) {
			$html.= '<div class="row">';
			$html.= '	<a href="' . JRoute::_('index.php?option=com_ksenmart&view=catalog&layout=search&items_tpl=commentproduct&items_to=ksm-slidemodule-commentproduct-container&tmpl=component') . '" class="add">' . JText::_('ksm_add') . '</a>';
			$html.= '</div>';
		}
		$html.= '</div>';
		
		$script = '
		jQuery(document).ready(function(){
				
			jQuery("body").on("click", ".ksm-slidemodule-commentproduct .add", function(){
				var url=jQuery(this).attr("href");
				jQuery(".ksm-slidemodule-commentproduct input[type=\'hidden\']").each(function(){
					url+="&excluded[]="+jQuery(this).val();
				});
				url+="&excluded[]=";
				var width=jQuery(window).width();
				var height=jQuery(window).height();
				openPopupWindow(url,width,height);
				return false;
			});
			
			jQuery("body").on("click", ".ksm-slidemodule-commentproduct .del", function(){
				jQuery(this).parents(".position:first").remove();
				jQuery("#ksm-slidemodule-commentproduct-container").html("<input type=hidden id=jformproduct_id name=' . $this->name . ' value=0>");
				onChangeProduct();
				return false;
			});
			
		});
		
		function onChangeProduct()
		{
			var data={};
			var vars={};
			var form=jQuery(".form");
			data["model"]="comments";
			data["form"]="comment";
			data["fields"]=["product_id"];
			vars["product_id"]=form.find("#jformproduct_id").val();			
			data["vars"]=vars;
			data["id"]=form.find(".id").val();
			KMRenewFormFields(data);
		}	

		function afterAddingItems()
		{
			jQuery(".ksm-slidemodule-commentproduct .position:first").remove();
			onChangeProduct();
		}		
		';
		$document = JFactory::getDocument();
		$document->addScriptDeclaration($script);
		
		return $html;
	}
}