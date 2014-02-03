<?php
defined('_JEXEC') or die;
jimport('joomla.application.component.controlleradmin');

class KsenMartControllerAllSettings extends KsenMartController {

    function save() {
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
        JClientHelper::setCredentialsFromRequest('ftp');
        $app = JFactory::getApplication();
        $model = $this->getModel('allsettings');
        $form = $model->getForm();
        $data = JRequest::getVar('jform', array(), 'post', 'array');
        $id = JRequest::getInt('id');

        if(!JFactory::getUser()->authorise('core.admin', 'com_ksenmart')) {
            JFactory::getApplication()->redirect('index.php', JText::_('JERROR_ALERTNOAUTHOR'));
            return;
        }

        $valid_data = array();

        foreach($form as $f) {
            $return = $model->validate($f, $data);

            // Check for validation errors.
            if($return === false) {
                // Get the validation messages.
                $errors = $model->getErrors();

                // Push up to three validation messages out to the user.
                for($i = 0, $n = count($errors); $i < $n && $i < 3; $i++) {
                    if($errors[$i] instanceof Exception) {
                        $app->enqueueMessage($errors[$i]->getMessage(), 'warning');
                    } else {
                        $app->enqueueMessage($errors[$i], 'warning');
                    }
                }

                // Save the data in the session.
                $app->setUserState('com_ksenmart.allsettings.global.data', $data);

                // Redirect back to the edit screen.
                $this->setRedirect(JRoute::_('index.php?option=com_ksenmart&view=allsettings', false));
                return false;
            } else  $valid_data = array_merge($valid_data, $return);
        }

        $data = array(
            'params' => $valid_data,
            'id' => $id,
            'option' => 'com_ksenmart');
        $return = $model->save($data);
        if($return === false) {
            $app->setUserState('com_ksenmart.allsettings.global.data', $data);
            $message = JText::sprintf('JERROR_SAVE_FAILED', $model->getError());
            $this->setRedirect('index.php?option=com_ksenmart&view=allsettings', $message, 'error');
            return false;
        }

        KMUpdater::sendShopEmail();
        $this->setRedirect('index.php?option=com_ksenmart&view=allsettings');

        return true;
    }

    function del_images_cache() {
        $path = JPATH_ROOT . DS . 'media' . DS . 'ksenmart' . DS . 'images' . DS . 'products';
        $folders = scandir($path);
        foreach($folders as $folder)
            if($folder != '.' && $folder != 'original' && $folder != '..' && is_dir($path . DS . $folder)) JFolder::delete($path . DS . $folder);
        exit();
    }

    public function getKMVersion(){
		$kmdestination=JPATH_ROOT.'/administrator/components/com_ksenmart';
		
		$tmpInstaller = new JInstaller;
		$tmpInstaller->setPath('source',$kmdestination);
		$manifest = $tmpInstaller->getManifest();
		$version =  $manifest->version;
		echo $version;
		JFactory::getApplication()->close();
    }
}