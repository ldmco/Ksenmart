<?php 
defined( '_JEXEC' ) or die;
JFormHelper::loadFieldClass('checkboxes'); 

class JFormFieldProductRelated extends JFormField {
	
	protected $type = 'ProductRelated';

	public function getInput() {
		$params = JComponentHelper::getParams('com_ksenmart');
		$html='';
		$html.='<div class="positions">';
		$html.='	<div id="ksm-slidemodule-productrelated-container">';
		if (count($this->value)) {
			foreach ($this->value as $product) {
				$html.='<div class="position">';
				$html.='	<div class="col1">';
				$html.='		<div class="img"><img alt="" src="'.$product->small_img.'"></div>';
				$html.='		<div class="name">'.$product->title.'</div>';
				$html.='	</div>';
				$html.='	<a href="#" class="del"></a>';
				$html.='	<input type="hidden" name="'.$this->name.'['.$product->id.'][relative_id]" value="'.$product->id.'">';
				$html.='</div>';
			}
		}
		$html.='		<div class="position no-items" '.(count($this->value)?'style="display:none;"':'').'>';
		$html.='			<div class="col1">'.JText::_('ksm_catalog_product_no_relative').'</div>';
		$html.='		</div >';
		$html.='	</div>';
		$html.='	<div class="row">';
		$html.='		<a href="'.JRoute::_('index.php?option=com_ksenmart&view=catalog&layout=search&items_tpl=productrelateditems&items_to=ksm-slidemodule-productrelated-container&tmpl=component').'" class="add">'.JText::_('ksm_add').'</a>';
		$html.='	</div>';
		$html.='</div>';

		$script='
		jQuery(".ksm-slidemodule-productrelated .add").live("click", function(){
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
		
		jQuery(".ksm-slidemodule-productrelated .del").live("click", function(){
			jQuery(this).parents(".position:first").remove();
			if (jQuery(".ksm-slidemodule-productrelated .position").length==1)
				jQuery(".ksm-slidemodule-productrelated .no-items").show();
			return false;
		});
		';
		$document = JFactory::getDocument();
		$document->addScriptDeclaration($script);		
		return $html;
	}
}