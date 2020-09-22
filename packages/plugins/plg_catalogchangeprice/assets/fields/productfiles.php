<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

JFormHelper::loadFieldClass('checkboxes');
class JFormFieldProductFiles extends JFormField {
	
	protected $type = 'ProductFiles';
	
	public function getInput() {
		$params = JComponentHelper::getParams('com_ksenmart');
		$html = '';
		$html.= '<div class="positions">';
		$html.= '	<div id="ksm-slidemodule-productfiles-container">';
		if (count($this->value)) {
			
			foreach ($this->value as $file) {
				if (is_array($file))
				{
					$html.= '<div class="position">';
					$html.= '	<div class="col1">';
					$html.= '		<div class="name">' . $file['name'] . '</div>';
					$html.= '	</div>';
					$html.= '	<a href="javascript:void(0);" class="del" data-filename="' . $file['filename'] . '"></a>';
					$html.= '</div>';					
				}
				else
				{
					$html.= '<div class="position">';
					$html.= '	<div class="col1">';
					$html.= '		<div class="name">' . $file->title . '</div>';
					$html.= '	</div>';
					$html.= '	<a href="javascript:void(0);" class="del" data-id="' . $file->id . '"></a>';
					$html.= '</div>';					
				}
			}
		}
		$html.= '		<div class="position no-items" ' . (count($this->value) ? 'style="display:none;"' : '') . '>';
		$html.= '			<div class="col1">' . JText::_('KSM_PLUGIN_SPEKTRX_NOITEMS_LBL') . '</div>';
		$html.= '		</div >';
		$html.= '	</div>';
		$html.= '	<div class="row">';
		$html.= '		<a href="javascript:void(0);" class="add">' . JText::_('KS_ADD') . '</a>';
		$html.= '		<input type="file" name="newfile" value="" style="display:none;">';
		$html.= '	</div>';
		$html.= '</div>';
		
		$script = '
		jQuery(document).ready(function(){
			
			jQuery(".form").attr("enctype", "multipart/form-data");
				
			jQuery("body").on("click", ".ksm-slidemodule-productfiles .add", function(){
				jQuery("input[name=\'newfile\']").click();

				return false;
			});
			
			jQuery("body").on("change", "input[name=\'newfile\']", function(e){
				e.stopPropagation();
				e.preventDefault();		
				
				var name = jQuery("input[name=\'newfile\']").val();
				if (name == "")
				{
					return false;
				}

				jQuery(".form input[name=\'task\']").remove();
				var formData = new FormData(jQuery(".form").get(0));
				jQuery(".form").append("<input type=\'hidden\' name=\'task\' value=\'save_form_item\'>");
				
				jQuery.ajax({
					url: "index.php?option=com_ksenmart&task=pluginAction&plugin=spektrx&action=uploadFile&format=html",
					type: "post",
					contentType: false, 
					processData: false, 
					data: formData,
					dataType: "json",
					error: function(){
					},
					success: function(response){
						jQuery("#ksm-slidemodule-productfiles-container").find(".no-items").hide();
						jQuery("#ksm-slidemodule-productfiles-container").append(response.html);	
						jQuery("input[name=\'newfile\']").val("");
					}
				});				
			});			
			
			jQuery("body").on("click", ".ksm-slidemodule-productfiles .del", function(){
				var position = jQuery(this).parents(".position:first");
				var data = {}
				data["task"] = "pluginAction";
				data["plugin"] = "spektrx";
				data["format"] = "html";
				if (jQuery(this).data().id)
				{
					data["action"] = "deleteProductFile";
					data["id"] = jQuery(this).data().id;					
				}
				else
				{
					data["action"] = "deleteSessionFile";
					data["filename"] = jQuery(this).data().filename;						
				}
				jQuery.ajax({
					url: "index.php?option=com_ksenmart",
					type: "post",
					data: data,
					dataType: "json",
					success: function(response){
						position.remove();
						if (jQuery(".ksm-slidemodule-productfiles .position").length==1)
							jQuery(".ksm-slidemodule-productfiles .no-items").show();
					}
				});					

				return false;
			});
			
		});
		';
		$style = '
		.ksm-slidemodule-productfiles .positions {
			margin: 0px;
		}
		.ksm-slidemodule-productfiles .positions .col1 {
			width: 250px;
			float: left;
			margin: 0 20px 0 0;
		}	
		.ksm-slidemodule-productfiles .del {
			right: 5px!important;
		}		
		';
		$document = JFactory::getDocument();
		$document->addScriptDeclaration($script);
		$document->addStyleDeclaration($style);
		
		return $html;
	}
}