<?php 
defined( '_JEXEC' ) or die;
JFormHelper::loadFieldClass('checkboxes'); 

class JFormFieldManufacturers extends JFormFieldCheckboxes{
	
	protected $type = 'Manufacturers';
	
	public function getInput(){
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('*')->from('#__ksenmart_manufacturers')->order('ordering');
		$db->setQuery($query);
		$manufacturers = $db->loadObjectList('id');
		
		$html = '<ul>';
		if (count($manufacturers)>0)
		{
			foreach($manufacturers as $manufacturer){
				$checked = '';
				$active ='';				
				if (in_array($manufacturer->id,$this->value)) { 
					$checked = ' checked="checked" ';
					$active = ' active ';
				}
				$html.='<li class="'.$active.'">';
				$html.='<label>'.JText::_($manufacturer->title).'<input type="checkbox" '.$checked.' value="'.$manufacturer->id.'" name="'.$this->name.'" onclick="'.$this->element['onclick'].'" /></label>';
				$html.='</li>';
			}
		}
		else
		{
			$html.='<li>';
			$html.='<label>'.JText::_('ksm_catalog_product_no_manufacturers').'</label>';
			$html.='</li>';		
		}
		$html.='</ul>';

		return $html;
	}
	
}