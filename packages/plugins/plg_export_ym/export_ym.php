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

class plgKMExportimportExport_ym extends KMPlugin {
	
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
			case 'saveconfig':
				$this->saveConfig();
				$html = $this->getConfigStep();
				break;				
		}

		return true;
	}
	
	function onBeforeViewKSMCatalog($view){
		$app = JFactory::getApplication();
		$db = JFactory::getDBO();
		$jinput = $app->input;
		$exportym = $jinput->get('exportym', null);
		
		if (empty($exportym))
			return false;
		
		$currencies = '';
		$categories = '';
		$offers = '';
		$Itemid = '&amp;Itemid='.KSSystem::getShopItemid();

		$query = $db->getQuery(true);
		$query->select('value')->from('#__ksenmart_yandeximport')->where('setting = '.$db->quote('shopname'));
		$db->setQuery($query);
		$shopname = $db->loadResult();

		$query = $db->getQuery(true);
		$query->select('value')->from('#__ksenmart_yandeximport')->where('setting = '.$db->quote('company'));
		$db->setQuery($query);
		$company = $db->loadResult();

		$query = $db->getQuery(true);
		$query->select('value')->from('#__ksenmart_yandeximport')->where('setting = '.$db->quote('categories'));
		$db->setQuery($query);
		$cats = $db->loadResult();		
		$cats = json_decode($cats, true);
		if (!is_array($cats)) $cats = array();
		if (!count($cats)) $cats[] = 0;	

		$query = $db->getQuery(true);
		$query->select('rate')->from('#__ksenmart_currencies')->where('code = '.$db->quote('RUR'));
		$db->setQuery($query);
		$rur_rate = $db->loadResult();	
		
		$query = $db->getQuery(true);
		$query->select('rate')->from('#__ksenmart_currencies')->where('code = '.$db->quote('RUR'));
		$db->setQuery($query);
		$rur_rate = $db->loadResult();		

		$query = $db->getQuery(true);
		$query->select('*')->from('#__ksenmart_currencies');
		$db->setQuery($query);
		$rows = $db->loadObjectList();	
		foreach($rows as $row)
		{
			$currencies.= '<currency id="' . $row->code . '" rate="' . round($rur_rate / $row->rate, 4) . '"/>';
		}		
		
		$query = $db->getQuery(true);
		$query->select('*')->from('#__ksenmart_categories')->where('id in (' . implode(',', $cats) . ')');
		$db->setQuery($query);
		$rows = $db->loadObjectList();	
		foreach($rows as $row)
		{
			$categories.= '<category id="' . $row->id . '" ' . ($row->parent_id != 0 ? 'parentId="' . $row->parent_id . '"' : '') . '>' . $row->title . '</category>';
		}			

		$query = $db->getQuery(true);
		$query->select('p.*,pc.category_id')->from('#__ksenmart_products as p');
		$query->select('(select filename from #__ksenmart_files where owner_id=p.id and owner_type='.$db->quote('product').' and media_type='.$db->quote('image').' order by ordering limit 1) as picture');
		$query->select('(select title from #__ksenmart_manufacturers where id=p.manufacturer) as manufacturer_name');
		$query->select('(select code from #__ksenmart_currencies where id=p.price_type) as code');
		$query->innerjoin('#__ksenmart_products_categories as pc on pc.product_id=p.id');
		$query->where('pc.category_id in (' . implode(',', $cats) . ')')->where('p.published=1')->where('p.price>0')->where('p.type='.$db->quote('product'));
		$db->setQuery($query);
		$rows = $db->loadObjectList();			
		foreach($rows as $row)
		{
			if ($row->picture != '')
				$row->picture = JURI::root() . 'media/com_ksenmart/images/products/original/' . $row->picture;
			else
				$row->picture = JURI::root() . 'media/com_ksenmart/images/products/original/no.jpg';
			$offers.= '<offer id="' . $row->id . '" available="' . ($row->in_stock > 0 ? 'true' : 'false') . '" bid="1">
				<url>' . JURI::root() . 'index.php?option=com_ksenmart&amp;view=product&amp;id=' . $row->id . ':' . $row->alias . $Itemid . '</url>
				<price>' . $row->price . '</price>
				<currencyId>' . $row->code . '</currencyId>
				<categoryId>' . $row->category_id . '</categoryId>
				<picture>' . $row->picture . '</picture>	
				<delivery>true</delivery>
				<name>' . htmlspecialchars($row->title, ENT_QUOTES) . '</name>
				<vendor>' . htmlspecialchars($row->manufacturer_name, ENT_QUOTES) . '</vendor>	
				<description>' . htmlspecialchars($row->content, ENT_QUOTES) . '</description>
			</offer>';
		}

		header('Content-Type: text/xml;charset:utf-8');
		echo '<?xml version="1.0" encoding="utf-8"?>
		<!DOCTYPE yml_catalog SYSTEM "shops.dtd">
		<yml_catalog date="' . date('Y-m-d H:i') . '">
		<shop>
			<name>' . $shopname . '</name>
			<company>' . $company . '</company>
			<url>' . JURI::root() . '</url>
			<platform>KsenMart based on Joomla</platform>
			<version>3.0</version>
			<agency>L.D.M. Co</agency>
			<email>boss.dm@gmail.com</email>	
			<currencies>
				' . $currencies . '
			</currencies>
			<categories>
				' . $categories . '
			</categories>
			<offers>
				' . $offers . '
			</offers>
		</shop>
		</yml_catalog>
		';

		$app->close();
	}	
	
	function getConfigStep(){
		$this->view->form = $this->getForm();
		$data = $this->getFormData();
		$this->view->form->bind($data);		
		$html = KSSystem::loadPluginTemplate($this->_name, $this->_type, $this->view, 'config');
		
		return $html;
	}
	
	function saveConfig(){
		$app = JFactory::getApplication();
		$db = JFactory::getDBO();
		$jinput = $app->input;
		$jform = $jinput->get('jform', array(), 'array');		
		$categories = isset($jform['categories']) ? $jform['categories'] : array();
		$categories = json_encode($categories);
        $shopname = $jform['shopname'];
        $company = $jform['company'];

        $query = $db->getQuery(true);
        $query->update('#__ksenmart_yandeximport')->set('value=' . $db->quote($categories))->where('setting=' . $db->quote('categories'));
        $db->setQuery($query);
        $db->query();

        $query = $db->getQuery(true);
        $query->update('#__ksenmart_yandeximport')->set('value=' . $db->quote($shopname))->where('setting=' . $db->quote('shopname'));
        $db->setQuery($query);
        $db->query();

        $query = $db->getQuery(true);
        $query->update('#__ksenmart_yandeximport')->set('value=' . $db->quote($company))->where('setting=' . $db->quote('company'));
        $db->setQuery($query);
        $db->query();		
		
		return true;
	}
	
    function getFormData() {
		$db = JFactory::getDBO();
		
        $query = $db->getQuery(true);
        $query->select('*')->from('#__ksenmart_yandeximport');
        $db->setQuery($query);
        $settings = $db->loadObjectList('setting');

        $data = new stdClass();
        $data->categories = json_decode($settings['categories']->value, true);
        $data->shopname = $settings['shopname']->value;
        $data->company = $settings['company']->value;

        return $data;
    }	

    function getForm() {
        
        JKSForm::addFormPath(JPATH_ROOT.'/plugins/kmexportimport/export_ym/assets/forms');
        JKSForm::addFieldPath(JPATH_ROOT.'/administrator/components/com_ksenmart/models/fields');
        
        $form = JKSForm::getInstance('com_ksenmart.exportym', 'exportym', array(
            'control' => 'jform',
            'load_data' => true
        ));
        
        if (empty($form)) 
			return false;
        
        return $form;
    }
	
}