<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

if (!class_exists ('KMPlugin')) {
	require(JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_ksenmart'.DS.'classes'.DS.'kmplugin.php');
}

abstract class KMPaymentPlugin extends KMPlugin {

	function __construct (& $subject, $config) {
		parent::__construct ($subject, $config);
	}
	
}