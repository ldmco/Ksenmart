<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

KSSystem::import('views.viewksadmin');
class  KsenMartViewCurrencies extends JViewKSAdmin
{

    function display($tpl = null)
    {
		$this->path->addItem(JText::_('ksm_trade'),'index.php?option=com_ksen&widget_type=trade&extension=com_ksenmart');
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