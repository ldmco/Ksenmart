<?php
defined('_JEXEC') or die;
jimport('joomla.application.component.viewkmadmin');

class KsenMartViewPayments extends JViewKMAdmin {

    function display($tpl = null)
	{
        $this->path->addItem(JText::_('ksm_trade'), 'index.php?option=com_ksenmart&view=panel&component_type=trade');
        $this->path->addItem(JText::_('ksm_payments'));	
        
        switch($this->getLayout()) {
			case 'payment_params':
				$payment=$this->get('Payment');
				$this->paramsform=$this->get('PaymentParamsForm');					
				break;		
            case 'payment':
                $this->document->addScript(JURI::base() . 'components/com_ksenmart/js/payment.js');
                $model = $this->getModel();
                $payment = $model->getPayment();
				$model->form='payment';
                $form = $model->getForm();
                if ($form) $form->bind($payment);
                $this->title = JText::_('ksm_payments_payment_editor');
                $this->form=$form;
				$this->paramsform=$this->get('PaymentParamsForm');	
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