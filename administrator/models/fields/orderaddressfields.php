<?php 
defined( '_JEXEC' ) or die;

class JFormFieldOrderAddressFields extends JFormField {

	protected $type = 'OrderAddressFields';
	
	public function getInput(){
		$db=JFactory::getDBO();
        $session = JFactory::getSession();
		$shipping_id=$this->form->getValue('shipping_id');
		$html='';
		
        $query = $db->getQuery(true);
        $query->select('*')->from('#__ksenmart_shipping_fields')->where('shipping_id=' . (int)$shipping_id)->where('position=' . $db->quote('address'))->where('published=1')->order('ordering');
        $db->setQuery($query);
        $address_fields = $db->loadObjectList();
		$html.='<div class="positions">';
		if (count($address_fields))
		{		
			foreach($address_fields as $address_field)
			{
				$html.='<div class="position">';
				if ($address_field->system && isset($this->value[$address_field->title]))
					$value=$this->value[$address_field->title];
				elseif (!$address_field->system && isset($this->value[$address_field->id]))
					$value=$this->value[$address_field->id];
				else
					$value='';
				$name=$address_field->system?$address_field->title:$address_field->id;
				$address_field->title=$address_field->system?JText::_('ksm_orders_order_field_'.$address_field->title):$address_field->title;
				
				$html.='<label class="inputname">'. $address_field->title.'</label>';
				if($address_field->type == 'select')
				{
					$query = $db->getQuery(true);
					$query->select('*')->from('#__ksenmart_shipping_fields_values')->where('field_id=' . $address_field->id);
					$db->setQuery($query);
					$address_field->values = $db->loadObjectList();
					$html.='<select name="'.$this->name.'['.$name.']" class="sel">';
					foreach($address_field->values as $value)
						$html.='<option value="'.$value->id.'" '.($value->id==$value?'selected':'').'>'.$value->title.'</option>';
					$html.='</select>';
				}
				else
				{
					$html.='<input type="text" class="inputbox address_field" '.($address_field->system==1?'id="address_'.$name.'"':'').' name="'.$this->name.'['.$name.']" value="'.$value.'">';
				}
				$html.='</div>';
			}		
		}
		else
		{
			$html.='	<div class="position empty-position">';
			$html.='		<h3>'.JText::_('KSM_ORDERS_ORDER_NO_ADDRESSFIELDS').'</h3>';		
			$html.='	</div>';		
		}	
		$html.='</div>';		
		
		return $html;
	}
}