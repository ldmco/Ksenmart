<?php
defined( '_JEXEC' ) or die;
 
class ModKMAllSettingsGroupsHelper
{

    public static function getForms()
    {
		$forms=array();
		if (!class_exists('KsenMartModelAllSettings'))
			require_once JPATH_ADMINISTRATOR.'/components/com_ksenmart/models/allsettings.php';
		$model = new KsenMartModelAllSettings();	
		$forms = $model->getForm();
		return $forms;
    }
	
} 
?>