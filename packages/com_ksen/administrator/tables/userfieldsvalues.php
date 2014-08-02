<?php 
defined( '_JEXEC' ) or die;

if (!class_exists('KsenTable')){
	require JPATH_COMPONENT_ADMINISTRATOR . DS . 'tables' .DS.'ksen.php' ;
}

class KsenTableUserFieldsValues extends KsenTable
{

	function __construct(&$_db)
	{
		parent::__construct('#__ksen_user_fields_values', 'id', $_db);
	}


	function bind($src, $ignore=array()){
		return parent::bind($src, $ignore);
	}
	
}