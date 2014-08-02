<?php 
defined( '_JEXEC' ) or die;

if (!class_exists('KsenTable')){
	require JPATH_COMPONENT_ADMINISTRATOR . DS . 'tables' .DS.'ksen.php' ;
}

class KsenTableSeoTexts extends KsenTable
{
	/**
	 * Constructor
	 *
	 * @since	1.5
	 */
	function __construct(&$_db)
	{
		parent::__construct('#__ksen_seo_texts', 'id', $_db);
		//$date = JFactory::getDate();
		
	}


	function bind($src, $ignore=array()){
		return parent::bind($src, $ignore);
	}
	
	
	
}
