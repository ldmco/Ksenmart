<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

class JFormFieldUserAddresses extends JFormField {
	
	protected $type = 'UserAddresses';
	
	public function getInput() {
		$html = '';
		
		$html.= '<div class="positions">';
		$html.= '	<div id="ksm-slidemodule-useraddresses-container">';
		
		foreach ($this->value as $value) {
			$html.= '	<div class="position">';
			$html.= '		<div class="row">';
			$html.= '			<label class="inputname">' . JText::_('ks_users_user_address_city') . '</label>';
			$html.= '			<input class="inputbox width150px" name="' . $this->name . '[' . $value->id . '][city]" value="' . $value->city . '">';
			$html.= '			<label class="inputname">' . JText::_('ks_users_user_address_zip') . '</label>';
			$html.= '			<input class="inputbox width150px" name="' . $this->name . '[' . $value->id . '][zip]" value="' . $value->zip . '">';
			$html.= '			<label class="inputname">' . JText::_('ks_users_user_address_street') . '</label>';
			$html.= '			<input class="inputbox width150px" name="' . $this->name . '[' . $value->id . '][street]" value="' . $value->street . '">';
			$html.= '		</div>';
			$html.= '		<div class="row" style="margin-bottom:0px;">';
			$html.= '			<label class="inputname">' . JText::_('ks_users_user_address_house') . '</label>';
			$html.= '			<input class="inputbox" name="' . $this->name . '[' . $value->id . '][house]" value="' . $value->house . '">';
			$html.= '			<label class="inputname">' . JText::_('ks_users_user_address_entrance') . '</label>';
			$html.= '			<input class="inputbox" name="' . $this->name . '[' . $value->id . '][entrance]" value="' . $value->entrance . '">';			
			$html.= '			<label class="inputname">' . JText::_('ks_users_user_address_floor') . '</label>';
			$html.= '			<input class="inputbox" name="' . $this->name . '[' . $value->id . '][floor]" value="' . $value->floor . '">';
			$html.= '			<label class="inputname">' . JText::_('ks_users_user_address_flat') . '</label>';
			$html.= '			<input class="inputbox" name="' . $this->name . '[' . $value->id . '][flat]" value="' . $value->flat . '">';
			$html.= '		</div>';
			$html.= '		<a class="del"></a>';
			$html.= '	</div>';
		}
		$html.= '		<div class="position empty-position" style="' . (count($this->value) > 0 ? 'display:none;' : '') . '">';
		$html.= '			<h3>' . JText::_('ks_users_user_empty_addresses') . '</h3>';
		$html.= '		</div>';
		$html.= '	</div>';
		$html.= '	<div class="row">';
		$html.= '		<a class="add">' . JText::_('ks_add') . '</a>';
		$html.= '	</div>';
		$html.= '</div>';
		
		$script = '
		jQuery(document).ready(function(){
				
			jQuery("body").on("click", ".ksm-slidemodule-useraddresses .add", function(){
				var html="";
				var count=jQuery(".ksm-slidemodule-useraddresses .position").length;
				
				html+="<div class=\"position\">";
				html+="		<div class=\"row\">";
				html+="			<label class=\"inputname\">' . JText::_('ks_users_user_address_city') . '</label>";
				html+="			<input type=\"text\" name=\"' . $this->name . '[-"+count+"][city]\" class=\"inputbox width150px\">";
				html+="			<label class=\"inputname\">' . JText::_('ks_users_user_address_zip') . '</label>";
				html+="			<input type=\"text\" name=\"' . $this->name . '[-"+count+"][zip]\" class=\"inputbox width150px\">";
				html+="			<label class=\"inputname\">' . JText::_('ks_users_user_address_street') . '</label>";
				html+="			<input type=\"text\" name=\"' . $this->name . '[-"+count+"][street]\" class=\"inputbox width150px\">";				
				html+="		</div>";
				html+="		<div class=\"row\">";
				html+="			<label class=\"inputname\">' . JText::_('ks_users_user_address_house') . '</label>";
				html+="			<input type=\"text\" name=\"' . $this->name . '[-"+count+"][house]\" class=\"inputbox\">";
				html+="			<label class=\"inputname\">' . JText::_('ks_users_user_address_entrance') . '</label>";
				html+="			<input type=\"text\" name=\"' . $this->name . '[-"+count+"][entrance]\" class=\"inputbox\">";				
				html+="			<label class=\"inputname\">' . JText::_('ks_users_user_address_floor') . '</label>";
				html+="			<input type=\"text\" name=\"' . $this->name . '[-"+count+"][floor]\" class=\"inputbox\">";
				html+="			<label class=\"inputname\">' . JText::_('ks_users_user_address_flat') . '</label>";
				html+="			<input type=\"text\" name=\"' . $this->name . '[-"+count+"][flat]\" class=\"inputbox\">";			
				html+="		</div>";
				html+="		<a class=\"del\"></a>";
				html+="</div>";
				jQuery("#ksm-slidemodule-useraddresses-container .empty-position").hide();
				jQuery("#ksm-slidemodule-useraddresses-container").append(html);
				return false;	
			});	
		
			jQuery("body").on("click", ".ksm-slidemodule-useraddresses .del", function(){
				jQuery(this).parents(".position").remove();
				if (jQuery("#ksm-slidemodule-useraddresses-container .position").length==1)
					jQuery("#ksm-slidemodule-useraddresses-container .empty-position").show();
			});	
			
		});
		';
		$document = JFactory::getDocument();
		$document->addScriptDeclaration($script);
		
		return $html;
	}
}