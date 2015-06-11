<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

class plgSystemRoistatInstallerScript {
    
    public function install($parent) {

        if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);

        $app = JFactory::getApplication();
        $path = JPATH_ROOT . DS . 'plugins' . DS . 'system' . DS . 'roistat' . DS . 'install';

        if (!JFile::copy($path . DS . 'libraries' . DS . 'roistat.php', JPATH_ROOT . DS . 'plugins' . DS . 'system' . DS . 'ksencore' . DS . 'core' . DS . 'libraries' . DS . 'roistat.php')) {
            $app->enqueueMessage('Couldnt move file');
        }
        JFolder::delete($path);
    }

    public function update($parent) {
    	if(version_compare($parent->get('manifest')->version, '1.0', '==')){
	        if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);

	        $app = JFactory::getApplication();
	        $path = JPATH_ROOT . DS . 'plugins' . DS . 'system' . DS . 'roistat' . DS . 'install';

	        if (!JFile::copy($path . DS . 'libraries' . DS . 'roistat.php', JPATH_ROOT . DS . 'plugins' . DS . 'system' . DS . 'ksencore' . DS . 'core' . DS . 'libraries' . DS . 'roistat.php')) {
	            $app->enqueueMessage('Couldnt move file');
	        }
	        JFolder::delete($path);

        	$db = $parent->get('db');
	        $table = JTable::getInstance('extension');
	        $table->load(array('name' => 'roistat'));
	        $table->save(array(
                'enabled' => 1
            ));
	        $query = 'ALTER TABLE `#__ksenmart_orders` ADD `roistat` INT(11) NOT NULL AFTER `status_id`';
	        $db->setQuery($query);
	        $db->execute();
	        
	        $query = 'INSERT INTO `#__ksenmart_exportimport_types` (`id`, `name`) VALUES (NULL, \'roistat\');';
	        $db->setQuery($query);
	        $db->execute();
    	}
    }
}