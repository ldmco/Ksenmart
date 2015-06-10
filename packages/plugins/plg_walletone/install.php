<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

class plgKMPaymentWalletoneInstallerScript {
    
    public function postflight($type, $parent) {
        
        if (!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
        $path = JPATH_ROOT . DS . 'plugins' . DS . 'kmpayment' . DS . 'walletone' . DS . 'install' . DS;
        $app  = JFactory::getApplication();
        
        if (!JFile::copy($path . 'helpers' . DS . 'walletone.php', JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_ksenmart' . DS . 'helpers' . DS . 'common' . DS . 'walletone.php')) {
            $app->enqueueMessage('Couldnt move file');
        }
        
        JFolder::delete($path);
    }

    public function update($parent) {
        if(version_compare($parent->get('manifest')->version, '1.0.0', '==')){
            if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);

            $app = JFactory::getApplication();
            $path = JPATH_ROOT . DS . 'plugins' . DS . 'kmpayment' . DS . 'walletone' . DS . 'install' . DS;

            if (!JFile::copy($path . 'helpers' . DS . 'walletone.php', JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_ksenmart' . DS . 'helpers' . DS . 'common' . DS . 'walletone.php')) {
                $app->enqueueMessage('Couldnt move file');
            }
            JFolder::delete($path);

            $db = $parent->get('db');
            $table = JTable::getInstance('extension');
            if($table->load(array('element' => 'walletone', 'folder' => 'kmpayment'))){
                $table->save(array(
                    'enabled' => 1
                ));
            }
        }
    }
}