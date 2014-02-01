<?php
defined( '_JEXEC' ) or die;
 
class ModKMSeoTypesHelper
{

    public static function getSeoTypes()
    {
		$app = JFactory::getApplication();
		$selected_seo_type=$app->getUserStateFromRequest('com_ksenmart.seo.seo_type','seo_type','text');
		$db=JFactory::getDBO();	
		$query=$db->getQuery(true);
		$query->select('*')->from('#__ksenmart_seo_types');
		$db->setQuery($query);
		$seo_types=$db->loadObjectList();
		foreach($seo_types as &$seo_type)
			if ($seo_type->title==$selected_seo_type)
				$seo_type->selected=true;
			else	
				$seo_type->selected=false;
		return $seo_types;
    }
	
} 
?>