<?php	 		 		 	
defined( '_JEXEC' ) or die;
jimport( 'joomla.application.component.viewkmadmin');

class  KsenMartViewProperties extends JViewKMAdmin
{

    function display($tpl = null)
    {
		$this->path->addItem(JText::_('ksm_trade'),'index.php?option=com_ksenmart&view=panel&component_type=trade');
		$this->path->addItem(JText::_('ksm_properties'));
		switch ($this->getLayout())
		{
            case 'property':
                $this->document->addScript(JURI::base() . 'components/com_ksenmart/js/property.js');
                $model = $this->getModel();
                $property = $model->getProperty();
				$model->form='property';
                $form = $model->getForm();
                if ($form) $form->bind($property);
                $this->title = JText::_('ksm_properties_property_editor');
                $this->form=$form;
				$this->property=$property;
                break;		
			default:
				$this->document->addScript(JURI::base().'components/com_ksenmart/js/jquery.custom.min.js');
				$this->document->addScript(JURI::base().'components/com_ksenmart/js/list.js');
				$this->document->addScript(JURI::base().'components/com_ksenmart/js/listmodule.js');
				$this->items=$this->get('ListItems');
				$this->total=$this->get('Total');
		}	
        parent::display($tpl);
    }

}