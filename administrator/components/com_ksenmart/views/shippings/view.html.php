<?php	 		 		 	
defined( '_JEXEC' ) or die;
jimport( 'joomla.application.component.viewkmadmin');

class KsenMartViewShippings extends JViewKMAdmin
{

    function display($tpl = null)
    {
		$this->path->addItem(JText::_('ksm_trade'),'index.php?option=com_ksenmart&view=panel&component_type=trade');
		$this->path->addItem(JText::_('ksm_shippings'));			
		switch ($this->getLayout())
		{
			case 'shipping_params':
				$shipping=$this->get('Shipping');
				$this->paramsform=$this->get('ShippingParamsForm');					
				break;
            case 'shipping':
				$this->document->addScript(JURI::base().'components/com_ksenmart/js/shipping.js');	
				$this->document->addScript(JURI::base().'components/com_ksenmart/js/jquery.custom.min.js');	
				$this->document->addScript(JURI::base().'components/com_ksenmart/js/jquery.ui.datepicker-ru.js');
				$this->document->addStyleSheet(JURI::base().'components/com_ksenmart/css/ui-lightness/jquery-ui-1.8.20.custom.css');				
                $model = $this->getModel();
                $shipping = $model->getShipping();
				$model->form='shipping';
                $form = $model->getForm();
                if ($form) $form->bind($shipping);
                $this->title = JText::_('ksm_shippings_shipping_editor');
                $this->form=$form;
				$this->paramsform=$this->get('ShippingParamsForm');	
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