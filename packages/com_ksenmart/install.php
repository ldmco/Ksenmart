<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

class com_ksenmartInstallerScript {
	
	public function preflight($type, $parent) {}
	
	public function postflight($type, $parent) {
		jimport('joomla.installer.helper');

		if(!defined('DIRECTORY_SEPARATOR')) define('DIRECTORY_SEPARATOR', DIRECTORY_SEPARATOR);

		$path = JPATH_ROOT . DIRECTORY_SEPARATOR . 'administrator' . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_ksenmart' . DIRECTORY_SEPARATOR . 'install';
		if (!JFile::move($path . DIRECTORY_SEPARATOR . 'administrator-templates-system' . DIRECTORY_SEPARATOR . 'ksenmart.php', JPATH_ROOT . DIRECTORY_SEPARATOR . 'administrator' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'system' . DIRECTORY_SEPARATOR . 'ksenmart.php')) {
			$app = JFactory::getApplication();
			$app->enqueueMessage('Couldnt move file');
		}
		if (!JFile::move($path . DIRECTORY_SEPARATOR . 'administrator-templates-system' . DIRECTORY_SEPARATOR . 'ksenmart-full.php', JPATH_ROOT . DIRECTORY_SEPARATOR . 'administrator' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'system' . DIRECTORY_SEPARATOR . 'ksenmart-full.php')) {
			$app = JFactory::getApplication();
			$app->enqueueMessage('Couldnt move file');
		}
		if (!JFile::move($path . DIRECTORY_SEPARATOR . 'templates-system' . DIRECTORY_SEPARATOR . 'ksenmart.php', JPATH_ROOT . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'system' . DIRECTORY_SEPARATOR . 'ksenmart.php')) {
			$app = JFactory::getApplication();
			$app->enqueueMessage('Couldnt move file');
		}
		JFolder::create(JPATH_ROOT . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'ksenmart');
		JFolder::copy($path . DIRECTORY_SEPARATOR . 'images-ksenmart', JPATH_ROOT . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'ksenmart', null, 1);
		
		JFolder::delete($path);
	}

	public function update($parent){
		$version = $parent->getParent()->getManifest()->version;
		if(version_compare($version, '3.1.2b', '==')){
			$db = JFactory::getDBO();
			$query = '
				CREATE TABLE IF NOT EXISTS `#__ksenmart_files` (
				  `id` int(10) NOT NULL AUTO_INCREMENT,
				  `owner_id` int(10) NOT NULL,
				  `media_type` varchar(15) NOT NULL,
				  `owner_type` varchar(256) NOT NULL,
				  `folder` varchar(32) NOT NULL,
				  `filename` varchar(256) NOT NULL,
				  `mime_type` varchar(32) NOT NULL,
				  `title` varchar(256) NOT NULL,
				  `ordering` int(10) NOT NULL,
				  `params` text NOT NULL,
				  PRIMARY KEY (`id`),
				  KEY `owner_id` (`owner_id`),
				  KEY `media_type` (`media_type`),
				  KEY `owner_type` (`owner_type`),
				  KEY `folder` (`folder`)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
			';
			$db->setQuery($query);
			$db->execute();

			$query = 'INSERT `#__ksenmart_files` SELECT * FROM `#__ksen_files` AS `ksf` WHERE `ksf`.`owner_type`!=\'user\';';
			$db->setQuery($query);
			$db->execute();
		}
	}
	
	public function uninstall($parent) {
		jimport('joomla.installer.helper');
		if (file_exists(JPATH_ROOT . DIRECTORY_SEPARATOR . 'administrator' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'system' . DIRECTORY_SEPARATOR . 'ksenmart.php') && !JFile::delete(JPATH_ROOT . DIRECTORY_SEPARATOR . 'administrator' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'system' . DIRECTORY_SEPARATOR . 'ksenmart.php')) {
			$app = JFactory::getApplication();
			$app->enqueueMessage('Couldnt delete file');
		}
		if (file_exists(JPATH_ROOT . DIRECTORY_SEPARATOR . 'administrator' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'system' . DIRECTORY_SEPARATOR . 'ksenmart-full.php') && !JFile::delete(JPATH_ROOT . DIRECTORY_SEPARATOR . 'administrator' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'system' . DIRECTORY_SEPARATOR . 'ksenmart-full.php')) {
			$app = JFactory::getApplication();
			$app->enqueueMessage('Couldnt delete file');
		}
		if (file_exists(JPATH_ROOT . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'system' . DIRECTORY_SEPARATOR . 'ksenmart.php') && !JFile::delete(JPATH_ROOT . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'system' . DIRECTORY_SEPARATOR . 'ksenmart.php')) {
			$app = JFactory::getApplication();
			$app->enqueueMessage('Couldnt delete file');
		}
	}
}
