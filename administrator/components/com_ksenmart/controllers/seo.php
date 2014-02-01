<?php	 		 		 	
defined( '_JEXEC' ) or die;
jimport('joomla.application.component.controlleradmin');

class KsenMartControllerSeo extends KsenMartController
{
	
	function save_urls_configs()
	{
		$config=JRequest::getVar('config',array());
		$model=$this->getModel('seo');
		$model->saveUrlsConfigs($config);
		$this->setRedirect('index.php?option=com_ksenmart&view=seo');
	}
	
	function save_meta_configs()
	{
		$config=JRequest::getVar('config',array());
		$model=$this->getModel('seo');
		$model->saveMetaConfigs($config);
		$this->setRedirect('index.php?option=com_ksenmart&view=seo');
	}	
	
	function save_titles_configs()
	{
		$config=JRequest::getVar('config',array());
		$model=$this->getModel('seo');
		$model->saveTitlesConfigs($config);
		$this->setRedirect('index.php?option=com_ksenmart&view=seo');
	}

}