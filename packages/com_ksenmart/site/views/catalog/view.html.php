<?php
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

KSSystem::import('views.viewks');

class KsenMartViewCatalog extends JViewKS {

	function display($tpl = null) {
		$document     = JFactory::getDocument();
		$app          = JFactory::getApplication();
		$path         = $app->getPathway();
		$this->params = JComponentHelper::getParams('com_ksenmart');
		$session      = JFactory::getSession();
		$user         = KSUsers::getUser();
		$this->state  = $this->get('State');
		$layout_view  = $session->get('layout', $user->settings->catalog_layout);
		$layout       = $this->getLayout();

		switch ($layout) {
			case 'manufacturers':
				$model   = $this->getModel();
				$brands  = $this->get('Manufacturers');
				$brands  = $model->groupBrandsByLet($brands);
				$letters = $model->getLetters($brands);

				$document->setTitle(JText::_('KSM_MANUFACTURERS_PATHWAY_ITEM'));
				$path->addItem(JText::_('KSM_MANUFACTURERS_PATHWAY_ITEM'), JRoute::_('index.php?option=com_ksenmart&view=catalog&layout=manufacturers'));
				$document->addScript(JUri::base() . 'components/com_ksenmart/js/manufactures.js', 'text/javascript', true);

				$this->brands  = $brands;
				$this->letters = $letters;
				break;
			default:
				$model = $this->getModel();
				//$document->addScript(JUri::base() . 'components/com_ksenmart/js/catalog.js', 'text/javascript', true);
				JHtml::script('com_ksenmart/catalog.js', false, true);

				if (count($this->state->get('com_ksenmart.categories', array())) == 1) {
					$category = $this->get('Category');
					$title    = $this->get('CategoryTitle');

					if (!$category) {
						JError::raiseError(404, 'Категории не существует');
					}

					$document->setTitle($title);
					$model->setCategoryMetaData();

					$this->category = $category;
					if($this->params->get('show_categories_from_catalog')){
						$this->categories = $model->getCategories();
					}
					$this->setLayout('category');
				} elseif (count($this->state->get('com_ksenmart.manufacturers', array())) == 1) {
					$manufacturer = $this->get('Manufacturer');
					$title        = $this->get('ManufacturerTitle');

					if (!$manufacturer) {
						JError::raiseError(404, 'Производитель не существует');
					}

					$document->setTitle($title);
					$path->addItem(JText::_('KSM_MANUFACTURERS_PATHWAY_ITEM'), JRoute::_('index.php?option=com_ksenmart&view=catalog&layout=manufacturers'));
					$model->setManufacturerMetaData();

					$this->manufacturer = $manufacturer;
					$this->setLayout('manufacturer');
				} elseif (count($this->state->get('com_ksenmart.countries', array())) == 1) {
					$country = $this->get('Country');
					$title   = $this->get('CountryTitle');

					if (!$country) {
						JError::raiseError(404, 'Страна не существует');
					}

					$document->setTitle($title);
					$model->setCountryMetaData();

					$this->country = $country;
					$this->setLayout('country');
				} elseif (count($this->state->get('com_ksenmart.users', array())) == 1) {
					$manufacturer = $this->get('Manufacturer');
					$title        = $this->get('ManufacturerTitle');

					$document->setTitle($title);
					$model->setManufacturerMetaData();

					$this->manufacturer = $manufacturer;
					$this->setLayout('manufacturer');
				} else {
					$title = $this->get('CatalogTitle');

					$document->setTitle($title);
					if ($layout == 'default') {
						$this->setLayout('catalog');
					}
					if($this->params->get('show_categories_from_catalog')){
						$this->categories = $model->getCategories();
					}
				}
				if (!JFactory::getConfig()->get('config.caching', 0)) {
					$catalog_path = $this->get('CatalogPath');
					$k            = 0;
					foreach ($catalog_path as $c_path) {
						$k++;
						if ($k == count($catalog_path)) {
							$path->addItem($c_path['title']);
						} else {
							$path->addItem($c_path['title'], $c_path['link']);
						}
					}
				}

				JHtml::script('com_ksen/lazyloadimage.js', false, true);
				$pagination = $this->get('Pagination');
				$rows       = $this->get('Items');
				$sort_links = $this->get('SortLinks');

				$this->sort_links = $sort_links;
				$this->rows       = $rows;
				$this->pagination = $pagination;
				$this->sort_links = $sort_links;

				break;
		}
		$this->layout_view = $layout_view;
		parent::display($tpl);
	}
}