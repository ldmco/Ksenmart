<?php	 		 		 	
defined( '_JEXEC' ) or die;
jimport( 'joomla.application.component.viewkmadmin');

class  KsenMartViewDiscounts extends JViewKMAdmin
{

    function display($tpl = null)
    {
		$this->path->addItem(JText::_('ksm_trade'),'index.php?option=com_ksenmart&view=panel&component_type=trade');
		$this->path->addItem(JText::_('ksm_discounts'));		
		switch ($this->getLayout())
		{
			case 'discount_params':
				$discount=$this->get('Discount');
				$this->state->set('discount_type',JRequest::getVar('type',''));
				$this->paramsform=$this->get('DiscountParamsForm');					
				break;
            case 'discount':
				$this->document->addScript(JURI::base().'components/com_ksenmart/js/discount.js');	
				$this->document->addScript(JURI::base().'components/com_ksenmart/js/jquery.custom.min.js');	
				$this->document->addScript(JURI::base().'components/com_ksenmart/js/jquery.ui.datepicker-ru.js');
				$this->document->addStyleSheet(JURI::base().'components/com_ksenmart/css/ui-lightness/jquery-ui-1.8.20.custom.css');				
                $model = $this->getModel();
                $discount = $model->getDiscount();
				$model->form='discount';
                $form = $model->getForm();
                if ($form) $form->bind($discount);
                $this->title = JText::_('ksm_discounts_discount_editor');
                $this->form=$form;
				$this->paramsform=$this->get('DiscountParamsForm');						
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