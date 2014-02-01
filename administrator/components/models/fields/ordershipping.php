<?php 
defined( '_JEXEC' ) or die;

class JFormFieldOrderShipping extends JFormField {

	protected $type = 'OrderShipping';
	
	public function getInput(){
	
		$db = JFactory::getDbo();
		$region_id=$this->form->getValue('region_id');
        $shippings = array();
		$shipping_selected  = 0;
		$html='';
        
        if(!empty($region_id))
		{
			$query = $db->getQuery(true);
			$query->select('id as value,title as text,regions')->from('#__ksenmart_shippings')->where('published=1')->order('ordering');
			$db->setQuery($query);
			$rows = $db->loadObjectList();
			foreach($rows as $row) {
				$row->regions = json_decode($row->regions, true);
				foreach($row->regions as $country) {
					if(in_array($region_id, $country)) {
						$shipping_selected = $row->value == $this->value ? $row->value : $shipping_selected;
						$shippings[] = $row;
					}
				}
			}
		}
		$this->form->setValue('shipping_id',null,$shipping_selected);
		if (count($shippings))
		{
			$emptyvalue=new stdClass();
			$emptyvalue->value=0;
			$emptyvalue->text=JText::_('ksm_shippings_choose_shipping');
			array_unshift($shippings,$emptyvalue);		
			$html=JHTML::_('select.genericlist', $shippings, $this->name, array('class'=>"sel", 'style'=>'width:180px;'), 'value', 'text', $this->value );
		}
		else
		{
			$html.='<label class="inputname" style="width:auto;">'.JText::_('ksm_orders_order_no_shippings').'</label>';
			$html.='<input type="hidden" id="jformshipping_id" name="'.$this->name.'" value="0">';
		}
			
		
		$script='		
		jQuery(".form input[name=\''.$this->name.'\'").live("change",function(){
			if (typeof onChangeShipping == "function") {
				onChangeShipping();
			}	
		});
		';
		$document = JFactory::getDocument();
		$document->addScriptDeclaration($script);				

		return $html;
	}
}