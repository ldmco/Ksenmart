<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

class JFormFieldCommentUser extends JFormField {
	
	protected $type = 'CommentUser';
	
	public function getInput() {
		$db = JFactory::getDBO();
		
		$params = JComponentHelper::getParams('com_ksenmart');
		$html = '';
		$html.= '<div class="positions">';
		$html.= '	<div id="ksm-slidemodule-commentuser-container">';
		$user = KSUsers::getUser($this->value);
		$html.= '		<div class="position">';
		$html.= '			<div class="col1">';
		$html.= '				<div class="img"><img alt="" src="' . $user->small_img . '"></div>';
		$html.= '				<div class="name">' . $user->name . '<br>' . $user->email . '</div>';
		$html.= '			</div>';
		if ($user->id > 0) $html.= '		<a href="#" class="del"></a>';
		$html.= '			<input type="hidden" name="' . $this->name . '" value="' . $user->id . '">';
		$html.= '		</div>';
		$html.= '	</div>';
		if ($user->id == 0) {
			$html.= '<div class="row">';
			$html.= '	<a href="' . JRoute::_('index.php?option=com_ksen&view=users&layout=search&items_tpl=commentuser&items_to=ksm-slidemodule-commentuser-container&tmpl=component') . '" class="add">' . JText::_('ksm_add') . '</a>';
			$html.= '</div>';
		}
		$html.= '</div>';

		$script = '
		jQuery(document).ready(function(){
				
			jQuery("body").on("click", ".ksm-slidemodule-commentuser .add", function(){
				var url=jQuery(this).attr("href");
				jQuery(".ksm-slidemodule-commentuser input[type=\'hidden\']").each(function(){
					url+="&excluded[]="+jQuery(this).val();
				});
				url+="&excluded[]=";
				var width=jQuery(window).width();
				var height=jQuery(window).height();
				openPopupWindow(url,width,height);
				return false;
			});
			
			jQuery("body").on("click", ".ksm-slidemodule-commentuser .del", function(){
				jQuery(this).parents(".position:first").remove();
				jQuery("#ksm-slidemodule-commentuser-container").html("<input type=hidden id=jformuser_id name=' . $this->name . ' value=0>");
				onChangeUser();
				return false;
			});
			
		});
		
		function onChangeUser()
		{
			var data={};
			var vars={};
			var form=jQuery(".form");
			data["model"]="comments";
			data["form"]="comment";
			data["fields"]=["user_id"];
			vars["user_id"]=form.find("#jformuser_id").val();			
			data["vars"]=vars;
			data["id"]=form.find(".id").val();
			KMRenewFormFields(data);
		}	

		function afterAddingUsers()
		{
			jQuery(".ksm-slidemodule-commentuser .position:first").remove();
			onChangeUser();
		}		
		';
		$document = JFactory::getDocument();
		$document->addScriptDeclaration($script);
		
		return $html;
	}
}