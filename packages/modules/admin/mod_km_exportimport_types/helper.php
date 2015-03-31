<?php
defined( '_JEXEC' ) or die;
 
class ModKMExportImportTypesHelper
{

    public static function getTypes()
    {
		$app = JFactory::getApplication();
		$selected_type=$app->getUserStateFromRequest('com_ksenmart.exportimport.type','type','text');
		$db=JFactory::getDBO();	
		$query = $db->getQuery(true);
		$query->select('name,element')->from('#__extensions')->where('folder="kmexportimport"')->where('enabled=1');
		$db->setQuery($query);
		$types = $db->loadObjectList('element');
		
		foreach($types as &$type)
			if ($type->element==$selected_type)
				$type->selected=true;
			else	
				$type->selected=false;
			
		return $types;
    }
	
} 
?>