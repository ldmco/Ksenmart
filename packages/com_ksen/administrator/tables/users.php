<?php 
defined( '_JEXEC' ) or die;

if (!class_exists('KsenTable')){
	require JPATH_COMPONENT_ADMINISTRATOR . DS . 'tables' .DS.'ksen.php' ;
}

class KsenTableUsers extends KsenTable
{

	function __construct(&$_db)
	{
		parent::__construct('#__users', 'id', $_db);
	}

	function bind($src, $ignore=array()){
		return parent::bind($src, $ignore);
	}
	
}