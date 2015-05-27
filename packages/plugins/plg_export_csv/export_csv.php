<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

if (!class_exists('KMPlugin')) {
	require (JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_ksenmart' . DS . 'classes' . DS . 'kmplugin.php');
}
KSSystem::import('models.form.ksform');

class plgKMExportimportExport_csv extends KMPlugin {
	
	var $view = null;
	
	function __construct(&$subject, $config) {
		parent::__construct($subject, $config);
	}
	
	function onAfterDisplayAdminKSMexportimportdefault_text($view, &$tpl, &$html){
		if ($this->_name != $view->type)
			return false;
		
		$this->view = $view;
		$jinput = JFactory::getApplication()->input;
		$step = $jinput->get('step', 'config');

		switch($step){
			case 'config':
				$html = $this->getConfigStep();
				break;
		}

		return true;
	}
	
	function onAfterViewAdminKSMExportimport($view){
		if ($this->_name != $view->type)
			return false;
		
		$this->view = $view;
		$jinput = JFactory::getApplication()->input;
		$step = $jinput->get('step', 'config');

		switch($step){
			case 'export':
				$html = $this->getExportStep();				
				break;
		}

		return true;
	}	
	
	function getConfigStep(){
		$this->view->form = $this->getForm();
		$html = KSSystem::loadPluginTemplate($this->_name, $this->_type, $this->view, 'config');
		
		return $html;
	}
	
	function getExportStep(){
		$app = JFactory::getApplication();
		$db = JFactory::getDBO();
		$jinput = $app->input;
		$jform = $jinput->get('jform', array(), 'array');		
		$categories = isset($jform['categories']) ? $jform['categories'] : array();
		$unic = $jform['unic'];
		$header = array(
			JText::_('ksm_exportimport_export_csv_product_id'),
			JText::_('ksm_exportimport_export_csv_product_name'),
			JText::_('ksm_exportimport_export_csv_product_parent'),
			JText::_('ksm_exportimport_export_csv_product_category'),
			JText::_('ksm_exportimport_export_csv_product_childs_group'),
			JText::_('ksm_exportimport_export_csv_product_price'),
			JText::_('ksm_exportimport_export_csv_product_promotion_price'),
			JText::_('ksm_exportimport_export_csv_product_currency'),
			JText::_('ksm_exportimport_export_csv_product_code'),
			JText::_('ksm_exportimport_export_csv_product_unit'),
			JText::_('ksm_exportimport_export_csv_product_packaging'),
			JText::_('ksm_exportimport_export_csv_product_in_stock'),
			JText::_('ksm_exportimport_export_csv_product_promotion'),
			JText::_('ksm_exportimport_export_csv_product_recommendation'),
			JText::_('ksm_exportimport_export_csv_product_hot'),
			JText::_('ksm_exportimport_export_csv_product_new'),
			JText::_('ksm_exportimport_export_csv_product_introcontent'),
			JText::_('ksm_exportimport_export_csv_product_content'),
			JText::_('ksm_exportimport_export_csv_product_manufacturer'),
			JText::_('ksm_exportimport_export_csv_product_country'),
			JText::_('ksm_exportimport_export_csv_product_set'),
			JText::_('ksm_exportimport_export_csv_product_relative'),
			JText::_('ksm_exportimport_export_csv_product_tags'),
			JText::_('ksm_exportimport_export_csv_product_photos')
		);
		$cats_tree = array();
        $query = $db->getQuery(true);
        $query->select('id,title,parent_id')->from('#__ksenmart_categories');
        $db->setQuery($query);
        $cats = $db->loadObjectList();
		foreach($cats as $cat){
			$cat_tree = array($cat->title);
			$parent = $cat->parent_id;
			while ($parent != 0) {
				$query = $db->getQuery(true);
				$query->select('title,parent_id')->from('#__ksenmart_categories')->where('id=' . $parent);
				$db->setQuery($query);
				$category = $db->loadObject();
				if (!empty($category))
				{
					$cat_tree[] = $category->title;
					$parent = $category->parent_id;
				}
				else 
				{
					$parent = 0;
				}
			}
			$cat_tree = array_reverse($cat_tree);
			$cats_tree[$cat->id] = implode(':', $cat_tree);
		}
        $query = $db->getQuery(true);
        $query->select('id,title')->from('#__ksenmart_properties')->where('published = 1');
        $db->setQuery($query);
        $properties = $db->loadObjectList();
		foreach($properties as $property){
			$header[] = $property->title;
		}
		$f = fopen(JPATH_ROOT.'/administrator/components/com_ksenmart/tmp/export.csv', 'w');
		fputcsv($f, $header, ';');
		
        $query = $db->getQuery(true);
        $query->select('p.*,m.title as manufacturer_title,c.title as country_title')->from('#__ksenmart_products as p')->order('p.id');
		$query->leftjoin('#__ksenmart_manufacturers as m on m.id = p.manufacturer');
		$query->leftjoin('#__ksenmart_countries as c on c.id = m.country');
		$query->select('(select '.$unic.' from #__ksenmart_products where id = p.parent_id) as parent_title');
		$query->select('(select title from #__ksenmart_products_child_groups where id = p.childs_group) as group_title');
		$query->select('(select title from #__ksenmart_currencies where id = p.price_type) as currency_title');
		$query->select('(select form1 from #__ksenmart_product_units where id = p.product_unit) as unit_title');
        if(count($categories) > 0) {
            $query->innerjoin('#__ksenmart_products_categories as pc on pc.product_id=p.id');
            $query->where('pc.category_id in (' . implode(', ', $categories) . ')');
        }
        $query->group('p.id');	
		$db->setQuery($query);
		$products = $db->loadObjectList();
		foreach($products as $product){
			$cats = array();
			$set = '';
			$rels = '';
			$tags = '';
			$photos = '';
			
			if($product->promotion && $product->old_price != 0) {
				$product->promotion_price = $product->price;
				$product->price = $product->old_price;
			} else {
				$product->promotion_price = 0;
			}
			
			$query = $db->getQuery(true);
			$query->select('pc.category_id')->from('#__ksenmart_products_categories as pc')->where('pc.product_id='.$product->id);
			$db->setQuery($query);
			$prd_cats = $db->loadColumn();	
			foreach($prd_cats as $prd_cat)
				$cats[] = $cats_tree[$prd_cat];
			$cats = implode(';', $cats);
			
			$query = $db->getQuery(true);
			$query->select('p.'.$unic)->from('#__ksenmart_products_relations as pr')
			->leftjoin('#__ksenmart_products as p on p.id=pr.relative_id')
			->where('pr.product_id='.$product->id)->where('pr.relation_type='.$db->quote('set'));
			$db->setQuery($query);
			$set = $db->loadColumn();
			$set = implode(';', $set);
			
			$query = $db->getQuery(true);
			$query->select('p.'.$unic)->from('#__ksenmart_products_relations as pr')
			->leftjoin('#__ksenmart_products as p on p.id=pr.relative_id')
			->where('pr.product_id='.$product->id)->where('pr.relation_type='.$db->quote('relation'));
			$db->setQuery($query);
			$rels = $db->loadColumn();
			$rels = implode(';', $rels);			
			
			$tagHelper = new JHelperTags;
			$tags = $tagHelper->getTagIds(array($product->id), 'com_ksenmart.product');	
			if (!empty($tags))
			{
				$query = $db->getQuery(true);
				$query->select('title')->from('#__tags')->where('id in ('.$tags.')');
				$db->setQuery($query);
				$tags = $db->loadColumn();	
				$tags = implode(';', $tags);
			}
			
			$query = $db->getQuery(true);
			$query->select('f.filename')->from('#__ksenmart_files as f')
			->where('f.owner_id='.$product->id)->where('f.owner_type='.$db->quote('product'));
			$db->setQuery($query);
			$photos = $db->loadColumn();
			$photos = implode(';', $photos);			
			
			$arr = array(
				$product->id,
				$product->title,
				$product->parent_title,
				$cats,
				$product->group_title,
				$product->price,
				$product->promotion_price,
				$product->currency_title,
				$product->product_code,
				$product->unit_title,
				$product->product_packaging,
				$product->in_stock,
				$product->promotion,
				$product->recommendation,
				$product->hot,
				$product->new,
				$product->introcontent,
				$product->content,
				$product->manufacturer_title,
				$product->country_title,
				$set,
				$rels,
				$tags,
				$photos
			);
			foreach($properties as $property){
				$props = array();
				$query = $db->getQuery(true);
				$query->select('pv.title,ppv.price')->from('#__ksenmart_product_properties_values as ppv')
				->leftjoin('#__ksenmart_property_values as pv on pv.id=ppv.value_id')
				->where('ppv.product_id='.$product->id)->where('ppv.property_id='.$property->id);
				$db->setQuery($query);
				$prd_props = $db->loadObjectList();
				foreach($prd_props as $prd_prop){
					$prop = $prd_prop->title;
					if (!empty($prd_prop->price)){
						$prop .= '='.$prd_prop->price;
					}
					$props[] = $prop;
				}
				$arr[] = implode(';', $props);
			}			
			fputcsv($f, $arr, ';');
		}
		fclose($f);		
		$contents = file_get_contents(JPATH_ROOT.'/administrator/components/com_ksenmart/tmp/export.csv'); 
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename="export.csv"');
		echo $contents;				

		$app->close();
	}

    function getForm() {
        
        JKSForm::addFormPath(JPATH_ROOT.'/plugins/kmexportimport/export_csv/assets/forms');
        JKSForm::addFieldPath(JPATH_ROOT.'/administrator/components/com_ksenmart/models/fields');
        
        $form = JKSForm::getInstance('com_ksenmart.exportcsv', 'exportcsv', array(
            'control' => 'jform',
            'load_data' => true
        ));
        
        if (empty($form)) 
			return false;
        
        return $form;
    }
	
}