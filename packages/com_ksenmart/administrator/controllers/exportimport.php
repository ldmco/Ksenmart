<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

class KsenMartControllerExportImport extends KsenMartController
{

	function save_yandexmarket()
	{
		$data = JRequest::getVar('jform', array(), 'get', 'array');
		
		$model=$this->getModel('exportimport');
		$model->saveYandexmarket($data);
		
        $response = array(
            'message' => array(),
            'errors' => 0
		);
        $response = json_encode($response);
        JFactory::getDocument()->setMimeEncoding('application/json');
        echo $response;		
		JFactory::getApplication()->close();	
	}
	
}
