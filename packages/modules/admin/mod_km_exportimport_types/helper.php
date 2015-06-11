<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
 
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