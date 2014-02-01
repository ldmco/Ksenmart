<?php 
defined( '_JEXEC' ) or die;

class JFormFieldRegion extends JFormField {

	protected $type = 'Region';
	
	public function getInput(){
	
		$db = JFactory::getDbo();
		$q = $db->getQuery(true);
		$q->select('id as value, title as text')->from('#__ksenmart_regions')->order('ordering');
		$db->setQuery($q);
		$regions = $db->loadObjectList();
		$emptyvalue=new stdClass();
		$emptyvalue->value=0;
		$emptyvalue->text=JText::_('ksm_countries_choose_region');
		array_unshift($regions,$emptyvalue);
		
		$script='		
		jQuery(".form input[name=\''.$this->name.'\'").live("change",function(){
			if (typeof onChangeRegion == "function") {
				onChangeRegion();
			}	
		});
		';
		$document = JFactory::getDocument();
		$document->addScriptDeclaration($script);	
		
		return JHTML::_('select.genericlist', $regions, $this->name, array('class'=>"sel", 'style'=>'width:180px;'), 'value', 'text', $this->value );
	}
	
}