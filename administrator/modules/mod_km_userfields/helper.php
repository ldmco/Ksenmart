<?php
defined( '_JEXEC' ) or die;
 
class ModKMUserFieldsHelper
{

    public static function getUserFields()
    {
		$db	= JFactory::getDbo();
		$query	= $db->getQuery(true);
		$query->select('*')->from('#__ksenmart_user_fields')->order('ordering');
		$db->setQuery($query);
		$userfields=$db->loadObjectList();
		return $userfields;
    }
	
} 
?>