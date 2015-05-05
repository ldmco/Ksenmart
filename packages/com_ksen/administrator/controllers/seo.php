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
	
}