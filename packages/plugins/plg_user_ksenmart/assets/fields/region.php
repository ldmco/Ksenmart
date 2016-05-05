<?php 
defined('_JEXEC') or die;

class JFormFieldRegion extends JFormFieldList 
{

	protected $type = 'Region';
	
	public function getOptions()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query
			->select('id as value, title as text')
			->from('#__ksenmart_regions')
			->order('ordering')
		;
		$db->setQuery($query);
		$regions = $db->loadObjectList();
		
		return array_merge(parent::getOptions(), $regions);
	}
	
}