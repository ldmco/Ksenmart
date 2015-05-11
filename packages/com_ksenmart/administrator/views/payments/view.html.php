<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

KSSystem::import('views.viewksadmin');
class KsenMartViewPayments extends JViewKSAdmin {
    
    function display($tpl = null) {
        $this->path->addItem(JText::_('ksm_trade'),'index.php?option=com_ksen&widget_type=trade&extension=com_ksenmart');
        $this->path->addItem(JText::_('ksm_payments'));
        
        
        switch ($this->getLayout()) {
            case 'payment_params':
                $payment = $this->get('Payment');
                $this->paramsform = $this->get('PaymentParamsForm');
            break;
            case 'payment':
                $this->document->addScript(JURI::base() . 'components/com_ksenmart/js/payment.js');
                $model = $this->getModel();
                $payment = $model->getPayment();
                $model->form = 'payment';
                $form = $model->getForm();
                if ($form) $form->bind($payment);
                $this->title = JText::_('ksm_payments_payment_editor');
                $this->form = $form;
                $this->paramsform = $this->get('PaymentParamsForm');
                
                break;
            default:
                $this->items = $this->get('ListItems');
                $this->total = $this->get('Total');
            }
            parent::display($tpl);
    }
}