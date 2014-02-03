<?php	 		 		 	
defined( '_JEXEC' ) or die;
jimport( 'joomla.application.component.viewkmadmin');

class  KsenMartViewCurrencies extends JViewKMAdmin
{

    function display($tpl = null)
    {
		$this->path->addItem(JText::_('ksm_trade'),'index.php?option=com_ksenmart&view=panel&component_type=trade');
		$this->path->addItem(JText::_('ksm_currencies'));		
		switch ($this->getLayout())
		{
            case 'currency':
                $this->document->addScript(JURI::base() . 'components/com_ksenmart/js/currency.js');
                $model = $this->getModel();
                $currency = $model->getCurrency();
				$model->form='currency';
                $form = $model->getForm();
                if ($form) $form->bind($currency);
                $this->title = JText::_('ksm_currencies_currency_editor');
                $this->form=$form;
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