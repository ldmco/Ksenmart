<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

KSSystem::import('views.viewksadmin');
class KsenMartViewComplects extends JViewKSAdmin {

    function display($tpl = null) {
		$this->path->addItem(JText::_('ks_panel'), 'index.php?option=com_ksen&extension=com_ksenmart');
        $this->path->addItem(JText::_('ksm_complects'));
				
        switch($this->getLayout()) {
            case 'service':
                $this->document->addScript(JURI::base() . 'components/com_ksenmart/js/service.js');
                $model = $this->getModel();
                $this->service = $model->getService();
                $model->form = 'service';
                $form = $model->getForm();
                if($form) $form->bind($this->service);
                $this->title = JText::_('ksm_complects_service_editor');
                $this->form = $form;
                break;
	        case 'complect':
		        $this->document->addScript(JURI::base() . 'components/com_ksenmart/js/complect.js');
		        $model = $this->getModel();
		        $this->complect = $model->getComplect();
		        $model->form = 'complect';
		        $form = $model->getForm();
		        if($form) $form->bind($this->complect);
		        $this->title = JText::_('ksm_complects_complect_editor');
		        $this->form = $form;
		        break;
	        case 'servicesgroup':
		        $this->document->addScript(JURI::base() . 'components/com_ksenmart/js/group.js');
		        $model = $this->getModel();
		        $this->servicesgroup = $model->getServicesgroup();
		        $model->form = 'servicesgroup';
		        $form = $model->getForm();
		        if($form) $form->bind($this->servicesgroup);
		        $this->title = JText::_('ksm_complects_group_editor');
		        $this->form = $form;
		        break;
	        case 'modifier':
		        $model = $this->getModel();
		        $this->modifier = $model->getModifier();
		        $model->form = 'modifier';
		        $form = $model->getForm();
		        if($form) $form->bind($this->modifier);
		        $this->title = JText::_('ksm_complects_modifier_editor');
		        $this->form = $form;
		        break;
            default:
                $this->document->addScript(JURI::base() . 'components/com_ksenmart/js/jquery.custom.min.js');
                $this->document->addScript(JURI::base() . 'components/com_ksenmart/js/list.js');
                $this->document->addScript(JURI::base() . 'components/com_ksenmart/js/listmodule.js');
                $this->document->addScript(JURI::base() . 'components/com_ksenmart/js/complects.js');
                $this->items = $this->get('ListItems');
                $this->total = $this->get('Total');
        }
		
        parent::display($tpl);
    }

}