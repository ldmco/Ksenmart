<?php defined('_JEXEC') or die;

KSSystem::import('views.viewksadmin');
class KsenMartViewExportImport extends JViewKSAdmin {
	
	function display($tpl = null) {
		$this->path->addItem(JText::_('ksm_trade') ,'index.php?option=com_ksen&widget_type=trade&extension=com_ksenmart');
		$this->path->addItem(JText::_('ksm_exportimport'));
		$this->type = $this->state->get('type');
		
		parent::display($tpl);
	}
}
