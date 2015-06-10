<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

class JFormFieldOrderAddressFields extends JFormField {
	
	protected $type = 'OrderAddressFields';
	
	public function getInput() {
		$db = JFactory::getDBO();
		$session = JFactory::getSession();
		$shipping_id = $this->form->getValue('shipping_id');
		$user_id = $this->form->getValue('user_id');
		$query = $db->getQuery(true);
		$query->select('*')->from('#__ksen_user_addresses')->where('user_id=' . (int)$user_id);
		$db->setQuery($query);
		$user_addresses = $db->loadObjectList();		
		$html = '';
		
		$query = $db->getQuery(true);
		$query->select('*')->from('#__ksenmart_shipping_fields')->where('shipping_id=' . (int)$shipping_id)->where('position=' . $db->quote('address'))->where('published=1')->order('ordering');
		$db->setQuery($query);
		$address_fields = $db->loadObjectList();
		$html.= '<div class="positions">';
		if (count($user_addresses)>0){
			$html.= '<div class="position">';
			$html.= '	<select name="user_address" id="user-addresses">';
			$html.= '		<option value="" selected>'.JText::_('ksm_orders_order_address_lbl').'</option>';
			foreach ($user_addresses as $user_address) {
				$html.= '	<option value="'.$user_address->id.'" data-city="'.$user_address->city.'" data-zip="'.$user_address->zip.'" data-street="'.$user_address->street.'" data-house="'.$user_address->house.'" data-entrance="'.$user_address->entrance.'" data-floor="'.$user_address->floor.'" data-flat="'.$user_address->flat.'">'.KSSystem::formatAddress($user_address).'</option>';
			}
			$html.= '	</select>';
			$html.= '</div>';
		}
		if (count($address_fields)) {
			
			foreach ($address_fields as $address_field) {
				$html.= '<div class="position">';
				if ($address_field->system && isset($this->value[$address_field->title])) $value = $this->value[$address_field->title];
				elseif (!$address_field->system && isset($this->value[$address_field->id])) $value = $this->value[$address_field->id];
				else $value = '';
				$name = $address_field->system ? $address_field->title : $address_field->id;
				$address_field->title = $address_field->system ? JText::_('ksm_orders_order_field_' . $address_field->title) : $address_field->title;
				
				$html.= '<label class="inputname">' . $address_field->title . '</label>';
				if ($address_field->type == 'select') {
					$query = $db->getQuery(true);
					$query->select('*')->from('#__ksenmart_shipping_fields_values')->where('field_id=' . $address_field->id);
					$db->setQuery($query);
					$address_field->values = $db->loadObjectList();
					$html.= '<select name="' . $this->name . '[' . $name . ']" class="sel">';
					
					foreach ($address_field->values as $value) $html.= '<option value="' . $value->id . '" ' . ($value->id == $value ? 'selected' : '') . '>' . $value->title . '</option>';
					$html.= '</select>';
				} else {
					$html.= '<input type="text" class="inputbox address_field" ' . ($address_field->system == 1 ? 'id="address_' . $name . '"' : '') . ' name="' . $this->name . '[' . $name . ']" value="' . $value . '">';
				}
				$html.= '</div>';
			}
		} else {
			$html.= '	<div class="position empty-position">';
			$html.= '		<h3>' . JText::_('KSM_ORDERS_ORDER_NO_ADDRESSFIELDS') . '</h3>';
			$html.= '	</div>';
		}
		$html.= '</div>';
		
		$script = '
		jQuery(document).ready(function(){
		
			jQuery("body").on("change","#user-addresses",function(){
				var address_id = jQuery(this).val();
				if (address_id!="")
				{
					var option = jQuery(this).find("option[value=\'"+address_id+"\']");
					jQuery("#address_city").val(option.data().city);
					jQuery("#address_zip").val(option.data().zip);
					jQuery("#address_street").val(option.data().street);
					jQuery("#address_house").val(option.data().house);
					jQuery("#address_entrance").val(option.data().entrance);
					jQuery("#address_floor").val(option.data().floor);
					jQuery("#address_flat").val(option.data().flat);
				}
			});
			
		});
		';
		$document = JFactory::getDocument();
		$document->addScriptDeclaration($script);		

		return $html;
	}
}