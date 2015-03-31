<?php defined('_JEXEC') or die;

class plgSystemKsentplinstallerInstallerScript {

    public function postflight($type, $parent) {
        
        if (!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
        $path = JPATH_ROOT . '/plugins/system/ksentplinstaller/install/';
        $app  = JFactory::getApplication();
        
        if (!JFile::copy($path . 'installer.php', JPATH_ROOT . '/plugins/system/ksencore/core/helpers/common/installer.php')) {
            $app->enqueueMessage('Couldnt move file');
        }
        
        JFolder::delete($path);
    }
	
}