<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

KSSystem::import('views.viewks');
class KsenMartViewCatalog extends JViewKS {
    
    function display($tpl = null) {
        $document       = JFactory::getDocument();
        $app            = JFactory::getApplication();
        $path           = $app->getPathway();
        $this->params   = JComponentHelper::getParams('com_ksenmart');
        $session        = JFactory::getSession();
        $user           = KSUsers::getUser();
        $this->state    = $this->get('State');
        $layout_view    = $session->get('layout', $user->settings->catalog_layout);
        $layout         = $this->getLayout();

        switch($layout) {
            case 'items':
                $rows = $this->get('Items');
                $this->assignRef('rows', $rows);
                $this->setLayout('catalog_ajax_items');
            break;
            case 'manufacturers':
                $model   = $this->getModel();
                $brands  = $this->get('Manufacturers');
                $brands  = $model->groupBrandsByLet($brands);
                $letters = $model->getLetters($brands);
                
                $document->setTitle(JText::_('KSM_MANUFACTURERS_PATHWAY_ITEM'));
                $path->addItem(JText::_('KSM_MANUFACTURERS_PATHWAY_ITEM'), JRoute::_('index.php?option=com_ksenmart&view=catalog&layout=manufacturers'));
                $document->addScript(JURI::base() . 'components/com_ksenmart/js/manufactures.js', 'text/javascript', true);
                
                $this->assignRef('brands', $brands);
                $this->assignRef('letters', $letters);
            break;
            case 'countries':
                $model   = $this->getModel();
                $brands  = $this->get('ManufacturersListGroupByCountry');
                
                $document->setTitle(JText::_('KSM_MANUFACTURERS_PATHWAY_ITEM'));
                $path->addItem(JText::_('KSM_MANUFACTURERS_PATHWAY_ITEM'), JRoute::_('index.php?option=com_ksenmart&view=catalog&layout=manufacturers'));
                
                $this->assignRef('brands', $brands);
            break;
            default:

                $model      = $this->getModel();
                
                $document->addScript(JURI::base() . 'components/com_ksenmart/js/catalog.js', 'text/javascript', true);
                $document->addScript(JURI::base() . 'components/com_ksenmart/js/highslide/highslide-with-gallery.js', 'text/javascript', true);
                $document->addScript(JURI::base() . 'components/com_ksenmart/js/highslide.js', 'text/javascript', true);
                $document->addStyleSheet(JURI::base() . 'components/com_ksenmart/js/highslide/highslide.css');
                
                if(count($this->state->get('com_ksenmart.categories', array())) == 1) {
                    $category   = $this->get('Category');
                    $title      = $this->get('CategoryTitle');

                    if(!$category) {
                        JError::raiseError(404, 'Категории не существует');
                    }

                    $document->setTitle($title);
                    $model->setCategoryMetaData();
                    
                    $this->assignRef('category', $category);
                    $this->setLayout('category');
                } elseif(count($this->state->get('com_ksenmart.manufacturers', array())) == 1) {
                    $manufacturer   = $this->get('Manufacturer');
                    $title          = $this->get('ManufacturerTitle');

                    if(!$manufacturer) {
                        JError::raiseError(404, 'Производитель не существует');
                    }

                    $document->setTitle($title);
                    $path->addItem(JText::_('KSM_MANUFACTURERS_PATHWAY_ITEM'), JRoute::_('index.php?option=com_ksenmart&view=catalog&layout=manufacturers'));
                    $model->setManufacturerMetaData();
                    
                    $this->assignRef('manufacturer', $manufacturer);
                    $this->setLayout('manufacturer');
                } elseif(count($this->state->get('com_ksenmart.countries', array())) == 1) {
                    $country    = $this->get('Country');
                    $title      = $this->get('CountryTitle');

                    if(!$country) {
                        JError::raiseError(404, 'Страна не существует');
                    }
                    
                    $document->setTitle($title);
                    $model->setCountryMetaData();
                    
                    $this->assignRef('country', $country);
                    $this->setLayout('country');
                } elseif(count($this->state->get('com_ksenmart.users', array())) == 1) {
                    $manufacturer   = $this->get('Manufacturer');
                    $title          = $this->get('ManufacturerTitle');
                    
                    $document->setTitle($title);
                    $model->setManufacturerMetaData();
                    
                    $this->assignRef('manufacturer', $manufacturer);
                    $this->setLayout('manufacturer');
                } else {
                    $title  = $this->get('CatalogTitle');
                    
                    $document->setTitle($title);
                    if($layout == 'default'){
                        $this->setLayout('catalog');
                    }
                }
                if(!JFactory::getConfig()->get('config.caching', 0)) {
                    $catalog_path = $this->get('CatalogPath');
                    $k = 0;
                    foreach($catalog_path as $c_path) {
                        $k++;
                        if($k == count($catalog_path)){
                            $path->addItem($c_path['title']);
                        }else{
                            $path->addItem($c_path['title'], $c_path['link']);
                        }
                    }
                }

				$pagination = $this->get('Pagination');
				$rows       = $this->get('Items');
				$sort_links = $this->get('SortLinks');

				$this->assignRef('sort_links', $sort_links);
				$this->assignRef('rows', $rows);
				$this->assignRef('pagination', $pagination);
				$this->assignRef('sort_links', $sort_links);

                break;
        }
        $this->assignRef('layout_view', $layout_view);
        parent::display($tpl);
    }
}