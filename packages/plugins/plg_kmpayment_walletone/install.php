<?php defined('_JEXEC') or die;

class plgKMPaymentWalletoneInstallerScript {
    
    public function postflight($type, $parent) {
        
        if (!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
        $path = JPATH_ROOT . DS . 'plugins' . DS . 'kmpayment' . DS . 'walletone' . DS . 'install' . DS;
        $app  = JFactory::getApplication();
        
        if (!JFolder::move($path . 'helpers', JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_ksenmart' . DS . 'helpers')) {
            $app->enqueueMessage('Couldnt move file');
        }
        
        JFolder::delete($path);
    }

    public function update($parent) {
    	if(version_compare($parent->get('manifest')->version, '1.0', '==')){
	        if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);

	        $app = JFactory::getApplication();
	        $path = JPATH_ROOT . DS . 'plugins' . DS . 'kmpayment' . DS . 'walletone' . DS . 'install' . DS;

	        if (!JFolder::move($path . 'helpers', JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_ksenmart' . DS . 'helpers')) {
	            $app->enqueueMessage('Couldnt move file');
	        }
	        JFolder::delete($path);

        	$db = $parent->get('db');
	        $table = JTable::getInstance('extension');
	        $table->load(array('name' => 'walletone', 'folder' => 'kmpayment'));
	        $table->save(array(
                'enabled' => 1
            ));
    	}
    }
}