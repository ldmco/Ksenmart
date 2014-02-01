<?php	 		 		 	
defined( '_JEXEC' ) or die;

jimport( 'joomla.application.component.viewkmadmin');

class  KsenMartViewUpdater extends JViewKMAdmin
{

    function display($tpl = null)
    {
		$this->document->addScript(JURI::base().'components/com_ksenmart/js/updater.js');		
		$this->path->addItem(JText::_('updater'));
		$updates=KMUpdaterFunctions::getUpdates();
		$this->assignRef('updates', $updates);
		parent::display($tpl);
    }        
        
}