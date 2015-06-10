<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

if (!class_exists('KsenmartTable')){
	require JPATH_COMPONENT_ADMINISTRATOR . DS . 'tables' .DS.'ksenmart.php' ;
}

class KsenmartTableOrderItems extends KsenmartTable
{

	function __construct(&$_db)
	{
		parent::__construct('#__ksenmart_order_items', 'id', $_db);
	}

	function bind($src, $ignore=array()){
		return parent::bind($src, $ignore);
	}
	
}