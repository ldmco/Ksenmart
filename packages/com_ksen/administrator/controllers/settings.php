<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');
class KsenControllerSettings extends KsenController {
    
    function save() {
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
        JClientHelper::setCredentialsFromRequest('ftp');
        $app = JFactory::getApplication();
        $model = $this->getModel('settings');
        $form = $model->getForm();
        $extension = JRequest::getVar('extension', null);
        $data = JRequest::getVar('jform', array() , 'post', 'array');
        $id = JRequest::getInt('id');
        
        if (!JFactory::getUser()->authorise('core.admin', $extension)) {
            JFactory::getApplication()->redirect('index.php', JText::_('JERROR_ALERTNOAUTHOR'));
            
            return;
        }
        
        $valid_data = array();
        
        
        foreach ($form as $f) {
            $return = $model->validate($f, $data);
            // Check for validation errors.
            if ($return === false) {
                // Get the validation messages.
                $errors = $model->getErrors();
                // Push up to three validation messages out to the user.
                
                for ($i = 0, $n = count($errors);$i < $n && $i < 3;$i++) {
                    if ($errors[$i] instanceof Exception) {
                        $app->enqueueMessage($errors[$i]->getMessage() , 'warning');
                    } else {
                        $app->enqueueMessage($errors[$i], 'warning');
                    }
                }
                // Save the data in the session.
                $app->setUserState($extension.'.settings.global.data', $data);
                // Redirect back to the edit screen.
                $this->setRedirect(JRoute::_('index.php?option=com_ksen&view=settings&extension='.$extension, false));
                
                return false;
            } else $valid_data = array_merge($valid_data, $return);
        }
        
        $data = array(
            'params' => $valid_data,
            'id' => $id,
            'option' => $extension
        );
        $return = $model->save($data);
        if ($return === false) {
            $app->setUserState($extension.'.settings.global.data', $data);
            $message = JText::sprintf('JERROR_SAVE_FAILED', $model->getError());
            $this->setRedirect('index.php?option=com_ksen&view=settings&extension='.$extension, $message, 'error');
            
            return false;
        }

        $this->setRedirect('index.php?option=com_ksen&view=settings&extension='.$extension);
        
        return true;
    }

    public function del_images_cache() {
        $extension = JRequest::getVar('extension', null);
        $path = JPATH_ROOT.'/media/'.$extension.'/images';

        $folders = JFolder::folders($path, '.', false, false , array('tmp', 'icons'));
        foreach($folders as $folder){
            $subfolders = JFolder::folders($path.'/'.$folder, '.', false, false , array('original', 'thumb'));
            foreach($subfolders as $subfolder){
                JFolder::delete($path.'/'.$folder.'/'.$subfolder);
            }
        }
        $this->setRedirect('index.php?option=com_ksen&view=settings&extension=' . $extension);

        return true;
    }
}