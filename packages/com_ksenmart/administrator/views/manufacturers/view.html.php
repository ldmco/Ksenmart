<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

KSSystem::import('views.viewksadmin');
class KsenMartViewManufacturers extends JViewKSAdmin {

    function display($tpl = null) {
		$this->path->addItem(JText::_('ks_panel'), 'index.php?option=com_ksen&extension=com_ksenmart');
        $this->path->addItem(JText::_('ksm_catalog'), 'index.php?option=com_ksenmart&view=catalog');
		$this->path->addItem(JText::_('ks_categories'));
				
        switch($this->getLayout()) {
            case 'manufacturer':
                $this->document->addScript(JURI::base() . 'components/com_ksenmart/js/manufacturer.js');
                $model = $this->getModel();
                $manufacturer = $model->getManufacturer();
                $model->form = 'manufacturer';
                $form = $model->getForm();
                if($form) $form->bind($manufacturer);
                $this->title = JText::_('ksm_catalog_manufacturer_editor');
                $this->form = $form;
                break;
            default:
                $this->items = $this->get('ListItems');
                $this->total = $this->get('Total');
        }
		
        parent::display($tpl);
    }

}