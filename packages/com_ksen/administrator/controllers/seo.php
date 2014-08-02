<?php	 		 		 	
defined( '_JEXEC' ) or die;
jimport('joomla.application.component.controlleradmin');

class KsenControllerSeo extends KsenController
{
	
	function save_urls_configs()
	{
		$config=JRequest::getVar('config',array());
		$model=$this->getModel('seo');
		$model->saveUrlsConfigs($config);
		$extension = $model->getState('extension');
		$this->setRedirect('index.php?option=com_ksen&view=seo&extension='.$extension);
	}
	
	function save_meta_configs()
	{
		$config=JRequest::getVar('config',array());
		$model=$this->getModel('seo');
		$model->saveMetaConfigs($config);
		$extension = $model->getState('extension');
		$this->setRedirect('index.php?option=com_ksen&view=seo&extension='.$extension);
	}	
	
	function save_titles_configs()
	{
		$config=JRequest::getVar('config',array());
		$model=$this->getModel('seo');
		$model->saveTitlesConfigs($config);
		$extension = $model->getState('extension');
		$this->setRedirect('index.php?option=com_ksen&view=seo&extension='.$extension);
	}
	
	function save_seo_text()
	{
        $model = $this->getModel('seo');
		$extension = $model->getState('extension');
        $data = JRequest::getVar('jform', array(), 'post', 'array');
        
        $model->form = 'seotext'.$extension;
        $form = $model->getForm();
        if (!$form) {
            JError::raiseError(500, $model->getError());
            return false;
        }
        $id = (int)$data['id'];
        
        if (!$return = $model->saveSeoText($data)) {
            $this->setRedirect('index.php?option=com_ksen&view=seo&layout=seotext&id=' . $id . '&tmpl=component&extension='.$extension, JText::_('KSM_SERVER_SIDE_SAVE_ERROR') . implode('<br>', $model->getErrors()));
            return false;
        }
        $on_close = $return['on_close'];
        
        $this->closePopup($on_close);
	}	

}