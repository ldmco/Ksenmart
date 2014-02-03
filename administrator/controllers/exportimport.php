<?php	 		 		 	
defined( '_JEXEC' ) or die;
jimport('joomla.application.component.controlleradmin');

class KsenMartControllerExportImport extends KsenMartController
{

	function import_from_csv_parse()
	{
		$model=$this->getModel('exportimport');
        if (!$return=$model->uploadImportCSVFile()) {
            $this->setRedirect('index.php?option=com_ksenmart&view=exportimport&type=import_from_csv',JText::_('KSM_SERVER_SIDE_SAVE_ERROR') . implode('<br>', $model->getErrors()));
            return false;
        }		
		$this->setRedirect('index.php?option=com_ksenmart&view=exportimport&layout=import_from_csv_parse&type=import_from_csv');
		return true;
	}

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
