<?php
defined('_JEXEC') or die;
jimport('joomla.application.component.controlleradmin');

class KsenMartControllerPanel extends KsenMartController {

	function save_widgets_users_config(){
		
		$groups=JRequest::getVar('groups',array());
		$user_id = JFactory::getUser()->id;
		$message=array();
		$errors=0;
		
		$model=$this->getModel('panel');
		if (!$model->saveWidgetsConfig($user_id,$groups))
		{
			$errors++;
			$message=$model->getErrors();
		}
		
        $response = array(
            'message' => $message,
            'errors' => $errors
		);
        $response = json_encode($response);
        JFactory::getDocument()->setMimeEncoding('application/json');
        echo $response;
        JFactory::getApplication()->close();		
	}

}