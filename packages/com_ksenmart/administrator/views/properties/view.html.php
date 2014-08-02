<?php defined('_JEXEC') or die;

KSSystem::import('views.viewksadmin');
class KsenMartViewProperties extends JViewKSAdmin {
	
	function display($tpl = null) {
		$this->path->addItem(JText::_('ksm_trade') , 'index.php?option=com_ksen&widget_type=trade&extension=com_ksenmart');
		$this->path->addItem(JText::_('ksm_properties'));
		
		switch ($this->getLayout()) {
			case 'property':
				$this->document->addScript(JURI::base() . 'components/com_ksenmart/js/property.js');
				$model = $this->getModel();
				$property = $model->getProperty();
				$model->form = 'property';
				$form = $model->getForm();
				if ($form) $form->bind($property);
				$this->title = JText::_('ksm_properties_property_editor');
				$this->form = $form;
				$this->property = $property;
				
				break;
			default:
				$this->items = $this->get('ListItems');
				$this->total = $this->get('Total');
			}
			parent::display($tpl);
	}
}