<?php
defined( '_JEXEC' ) or die;
 
class ModKMExportImportTypesHelper
{

    public static function getTypes()
    {
		$app = JFactory::getApplication();
		$selected_type=$app->getUserStateFromRequest('com_ksenmart.exportimport.type','type','text');
		$db=JFactory::getDBO();	
		$query=$db->getQuery(true);
		$query->select('*')->from('#__ksenmart_exportimport_types');
		$db->setQuery($query);
		$types=$db->loadObjectList();
		foreach($types as &$type)
			if ($type->name==$selected_type)
				$type->selected=true;
			else	
				$type->selected=false;
		return $types;
    }
	
} 
?>