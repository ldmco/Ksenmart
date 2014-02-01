<?php
defined( '_JEXEC' ) or die;
 
class ModKMCommentRatesHelper
{

    public static function getRates()
    {
		$db	= JFactory::getDbo();
		$query	= $db->getQuery(true);
		$query->select('*')->from('#__ksenmart_comment_rates')->order('ordering');
		$db->setQuery($query);
		$rates=$db->loadObjectList();
		return $rates;
    }
	
} 
?>