<?php
defined( '_JEXEC' ) or die( '=;)' );
$params=$this->params;
if (!empty($this->rows))
{
	foreach($this->rows as $product)
	{
		require('item.php');
	}	
}	
?>