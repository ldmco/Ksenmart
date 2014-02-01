<?php
defined( '_JEXEC' ) or die;
 
class ModKMSearchHelper
{

    public static function getSearchWord()
    {
		$app = JFactory::getApplication();
		$view=JRequest::getVar('view','orders');
		$context = 'com_ksenmart.'.$view;
		if ($layout = JRequest::getVar('layout','default')) {
			$context.='.'.$layout;
		}	
		$searchword=$app->getUserStateFromRequest($context . '.searchword', 'searchword', '');
		return $searchword;
    }
	
} 
?>