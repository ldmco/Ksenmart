<?php	 		 		 	
defined( '_JEXEC' ) or die;
jimport('joomla.application.component.controlleradmin');

class KsenMartControllerUpdater extends KsenMartController
{

	function update_part()
	{
		$component=JRequest::getVar('component','');
		$active=JRequest::getVar('active',1);
		KMUpdaterFunctions::updatePart($component,$active);
		exit(0);	
	}
	
	function check_license()
	{
		$result=KMUpdaterFunctions::checkLicense();
		if ($result=='false')
			echo JText::_('no_license');
		exit(0);		
	}
	
	function update_ksenmart()
	{
		KMUpdaterFunctions::updateKsenmart();
		$this->setRedirect(JRoute::_('index.php?option=com_ksenmart&view=updater', false));
	}
	
	function download_update_ksenmart()
	{
		KMUpdaterFunctions::downloadUpdateKsenmart();
		parent::display();
	}	
	
}
