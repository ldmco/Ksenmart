<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

class com_ksenInstallerScript {
	
	function install($parent) {
		$app = JFactory::getApplication();
		$app->enqueueMessage('
			<p></p>
			<a href="index.php?option=com_ksenmart&task=catalog.installSampleData" class="btn btn-primary btn-large" title="Устновить демо-данные">Устновить демо-данные</a>
		');
		$path = JPATH_ROOT . DIRECTORY_SEPARATOR . 'administrator' . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_ksenmart' . DIRECTORY_SEPARATOR . 'install';
		//$parent->getParent()->setRedirectURL('index.php?option=com_ksenmart');
	}
	
	function update($parent) {}
	
	function preflight($type, $parent) {}
	
	function postflight($type, $parent) {
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->delete('#__menu')->where('link='.$db->quote('index.php?option=com_ksen'));
		$db->setQuery($query);
		$db->query();
	}

}