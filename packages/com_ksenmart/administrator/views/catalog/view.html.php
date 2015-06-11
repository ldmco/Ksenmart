<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

KSSystem::import('views.viewksadmin');
class KsenMartViewCatalog extends JViewKSAdmin {

    function display($tpl = null) {
		$this->path->addItem(JText::_('ksm_trade'), 'index.php?option=com_ksen&widget_type=trade&extension=com_ksenmart');
        $this->path->addItem(JText::_('ksm_catalog'));
				
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
            case 'set':
                $this->document->addScript(JURI::base() . 'components/com_ksenmart/js/set.js');
                $model = $this->getModel();
                $this->set = $model->getSet();
                $model->form = 'set';
                $form = $model->getForm();
                if($form) $form->bind($this->set);
                $this->title = JText::_('ksm_catalog_set_editor');
                $this->form = $form;
                break;
            case 'product':
                $this->document->addScript(JURI::base() . 'components/com_ksenmart/js/product.js');
                $model = $this->getModel();
                $this->product = $model->getProduct();
                $model->form = 'product';
                $form = $model->getForm();
                if($form) $form->bind($this->product);
                $this->title = JText::_('ksm_catalog_product_editor');
                $this->form = $form;
                break;
            case 'child':
                $this->document->addScript(JURI::base() . 'components/com_ksenmart/js/child.js');
                $model = $this->getModel();
                $this->child = $model->getChild();
                $model->form = 'child';
                $form = $model->getForm();
                if($form) $form->bind($this->child);
                $this->title = JText::_('ksm_catalog_child_editor');
                $this->form = $form;
                break;		
            case 'childgroup':
                $this->document->addScript(JURI::base() . 'components/com_ksenmart/js/childgroup.js');
                $model = $this->getModel();
                $childgroup = $model->getChildGroup();
                $model->form = 'childgroup';
                $form = $model->getForm();
                if($form) $form->bind($childgroup);
                $this->title = JText::_('ksm_catalog_child_group_editor');
                $this->form = $form;
                break;				
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
			 case 'search':
				$this->document->addScript(JURI::base().'components/com_ksenmart/js/jquery.custom.min.js');
				$this->document->addScript(JURI::base().'components/com_ksenmart/js/list.js');
				$this->document->addScript(JURI::base().'components/com_ksenmart/js/listmodule.js');
				$this->document->addScript(JURI::base().'components/com_ksenmart/js/catalogsearch.js');
				$this->title = JText::_('ksm_catalog_search');
				$this->items=$this->get('ListItems');
				$this->total=$this->get('Total');
				break;
            default:
                $this->document->addScript(JURI::base() . 'components/com_ksenmart/js/jquery.custom.min.js');
                $this->document->addScript(JURI::base() . 'components/com_ksenmart/js/list.js');
                $this->document->addScript(JURI::base() . 'components/com_ksenmart/js/listmodule.js');
                $this->document->addScript(JURI::base() . 'components/com_ksenmart/js/catalog.js');
                $this->items = $this->get('ListItems');
                $this->total = $this->get('Total');
        }
		
        parent::display($tpl);
    }

}