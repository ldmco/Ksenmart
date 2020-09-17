<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

KSSystem::import('views.viewksadmin');
class KsenMartViewCategories extends JViewKSAdmin {

    function display($tpl = null) {
		$this->path->addItem(JText::_('ks_panel'), 'index.php?option=com_ksen&extension=com_ksenmart');
        $this->path->addItem(JText::_('ksm_catalog'), 'index.php?option=com_ksenmart&view=catalog');
		$this->path->addItem(JText::_('ks_categories'));
				
        switch($this->getLayout()) {
            case 'category':
                $this->document->addScript(JURI::base() . 'components/com_ksenmart/js/category.js');
                $model = $this->getModel();
                $category = $model->getCategory();
                $model->form = 'category';
                $form = $model->getForm();
                if($form) $form->bind($category);
                $this->title = JText::_('ksm_catalog_category_editor');
                $this->form = $form;
                break;
            default:
                $this->document->addScript(JURI::base() . 'components/com_ksenmart/js/categories.js');
                $this->items = $this->get('ListItems');
                $this->total = $this->get('Total');
        }
		
        parent::display($tpl);
    }

}