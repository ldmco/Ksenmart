<?php 
defined( '_JEXEC' ) or die;

if (!class_exists('KsenmartTable')){
	require JPATH_COMPONENT_ADMINISTRATOR . DS . 'tables' .DS.'ksenmart.php' ;
}

class KsenmartTableRegions extends KsenmartTable
{

	function __construct(&$_db)
	{
		parent::__construct('#__ksenmart_regions', 'id', $_db);
	}

	function bind($src, $ignore=array()){
		return parent::bind($src, $ignore);
	}
	
}