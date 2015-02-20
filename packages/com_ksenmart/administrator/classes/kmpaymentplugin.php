<?php
defined ('_JEXEC') or die;

if (!class_exists ('KMPlugin')) {
	require(JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_ksenmart'.DS.'classes'.DS.'kmplugin.php');
}

abstract class KMPaymentPlugin extends KMPlugin {

	function __construct (& $subject, $config) {
		parent::__construct ($subject, $config);
	}
	
}