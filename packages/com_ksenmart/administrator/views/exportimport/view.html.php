<?php defined('_JEXEC') or die;

KSSystem::import('views.viewksadmin');
class KsenMartViewExportImport extends JViewKSAdmin {
	
	function display($tpl = null) {
		$this->path->addItem(JText::_('ksm_trade') ,'index.php?option=com_ksen&widget_type=trade&extension=com_ksenmart');
		$this->path->addItem(JText::_('ksm_exportimport'));
		$this->document->addScript(JURI::base() . 'components/com_ksenmart/js/exportimport.js');
		$this->type = $this->state->get('type');
		
		switch ($this->type) {
			case 'export_to_yandexmarket':
				$model = $this->getModel();
				$model->form = 'exporttoym';
				$this->form = $model->getForm();
				$data = $model->getYMFormData();
				$this->form->bind($data);
			break;
			case 'import_from_csv':
				
				switch ($this->getLayout()) {
					case 'import_from_csv_parse':
						$this->properties = $this->get('Properties');
						$this->options = $this->get('CSVOptions');
						$this->type = 'import_from_csv_parse';
					break;
					case 'import_from_csv_result':
						$this->info = $this->get('ImportInfo');
						$this->type = 'import_from_csv_result';
					break;
				}
			break;
		}
		parent::display($tpl);
	}
}
