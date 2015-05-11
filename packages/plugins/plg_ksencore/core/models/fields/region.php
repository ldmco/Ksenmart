<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

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
		jQuery(document).ready(function(){
				
			jQuery("body").on("change", "#jformregion_id", function(){
				if (typeof onChangeRegion == "function") {
					onChangeRegion();
				}	
			});
			
		});
		';
		$document = JFactory::getDocument();
		$document->addScriptDeclaration($script);	
		
		return JHTML::_('select.genericlist', $regions, $this->name, array('class'=>"sel", 'style'=>'width:180px;'), 'value', 'text', $this->value );
	}
	
}