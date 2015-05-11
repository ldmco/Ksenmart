<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

class KsenMartControllerPayment extends KsenMartController {

    function set_payment_status() {
        $payment = JRequest::getVar('payment', 0);
        $value = JRequest::getVar('value', 0);
        $model = $this->getModel('payments');
        $model->setPaymentStatus($payment, $value);
        exit();
    }

    function del_payments() {
        $model = $this->getModel('payments');
        $model->delPayments();
        exit();
    }

    function save_payment() {
        $model = $this->getModel('payments');
        $model->SavePayment();
        $this->setRedirect('index.php?option=com_ksenmart&view=payments&layout=saved&tmpl=component');
    }

    function display() {
        parent::display();
    }

}