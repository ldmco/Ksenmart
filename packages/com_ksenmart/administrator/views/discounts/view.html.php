<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

KSSystem::import('views.viewksadmin');
class KsenMartViewDiscounts extends JViewKSAdmin {
	
	public function display($tpl = null) {
		$this->path->addItem(JText::_('ksm_trade') ,'index.php?option=com_ksen&widget_type=trade&extension=com_ksenmart');
		$this->path->addItem(JText::_('ksm_discounts'));
		
		switch ($this->getLayout()) {
			case 'discount_params':
				$discount = $this->get('Discount');
				$this->state->set('discount_type', JRequest::getVar('type', ''));
				$this->paramsform = $this->get('DiscountParamsForm');
			break;
			case 'discount':
				$this->document->addScript(JURI::base() . 'components/com_ksenmart/js/discount.js');
				$this->document->addScript(JURI::base() . 'components/com_ksenmart/js/jquery.custom.min.js');
				$this->document->addScript(JURI::base() . 'components/com_ksenmart/js/jquery.ui.datepicker-ru.js');
				$model = $this->getModel();
				$discount = $model->getDiscount();
				$model->form = 'discount';
				$form = $model->getForm();
				if ($form) $form->bind($discount);
				$this->title = JText::_('ksm_discounts_discount_editor');
				$this->form = $form;
				$this->paramsform = $this->get('DiscountParamsForm');
			default:
				$this->items = $this->get('ListItems');
				$this->total = $this->get('Total');
			}
			parent::display($tpl);
	}
}