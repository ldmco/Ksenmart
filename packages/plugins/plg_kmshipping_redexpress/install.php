<?php defined('_JEXEC') or die;

class plgKMShippingRedExpressInstallerScript {
    
    public function postflight($type, $parent) {
        
        if (!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
        $path = JPATH_ROOT . DS . 'plugins' . DS . 'kmshipping' . DS . 'redexpress' . DS . 'install' . DS;
        $app  = JFactory::getApplication();
        
        if (!JFolder::move($path . 'helpers', JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_ksenmart' . DS . 'helpers')) {
            $app->enqueueMessage('Couldnt move file');
        }       
        
        JFolder::delete($path);
    }
}