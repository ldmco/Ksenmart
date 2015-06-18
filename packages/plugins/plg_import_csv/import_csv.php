<?php defined('_JEXEC') or die('Restricted access');

if (!class_exists('KMPlugin')) {
	require (JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_ksenmart' . DS . 'classes' . DS . 'kmplugin.php');
}

class plgKMExportimportImport_csv extends KMPlugin {
	
	var $view = null;
	var $csv_file = null;
	
	function __construct(&$subject, $config) {
		parent::__construct($subject, $config);
		$this->csv_file = JPATH_ROOT.'/administrator/components/com_ksenmart/tmp/import.csv';
	}
	
	function onAfterDisplayAdminKSMexportimportdefault_text($view, &$tpl, &$html){
		if ($this->_name != $view->type)
			return false;
		
		$this->view = $view;
		$jinput = JFactory::getApplication()->input;
		$step = $jinput->get('step', 'upload');

		switch($step){
			case 'upload':
				$html = $this->getUploadStep();
				break;
			case 'parse':
				$html = $this->getParseStep();				
				break;
			case 'import':
				$html = $this->getImportStep();				
				break;				
		}

		return true;
	}
	
	function getUploadStep(){
		$html = KSSystem::loadPluginTemplate($this->_name, $this->_type, $this->view, 'upload');
		
		return $html;
	}
	
	function getParseStep(){
		$jinput = JFactory::getApplication()->input;
		$this->view->encoding = $jinput->get('encoding', 'cp1251');		

		if (!$this->uploadImportCSVFile()){
			return false;
		}
		
		$this->view->properties = $this->getProperties();
		$this->view->options = $this->getCSVOptions();	
		
		$post_max_size = (int)ini_get('post_max_size');
		$upload_max_filesize = (int)ini_get('upload_max_filesize');	
		$this->view->max_filesize = $upload_max_filesize > $post_max_size ? $post_max_size : $upload_max_filesize;
		$this->view->upload_dir = JPATH_ROOT.'/media/com_ksenmart/import/';
		
		$html = KSSystem::loadPluginTemplate($this->_name, $this->_type, $this->view, 'parse');
		
		return $html;
	}

	function getImportStep(){
		$jinput = JFactory::getApplication()->input;
		$this->view->encoding = $jinput->get('encoding', 'cp1251');	

		if (!$this->view->info = $this->importCSV()){
			return false;
		}
		
		$html = KSSystem::loadPluginTemplate($this->_name, $this->_type, $this->view, 'result');
		
		return $html;
	}
	
    function uploadImportCSVFile() {
        if (!isset($_FILES['csvfile'])){
			$this->redirect('index.php?option=com_ksenmart&view=exportimport', JText::_('KSM_EXPORTIMPORT_IMPORT_CSV_CHOOSE_FILE_ERROR'));
			return false;
		}
        if ($_FILES['csvfile']['tmp_name'] == ''){
			$this->redirect('index.php?option=com_ksenmart&view=exportimport', JText::_('KSM_EXPORTIMPORT_IMPORT_CSV_CHOOSE_FILE_ERROR'));
			return false;
		}
        if (substr($_FILES['csvfile']['name'], strlen($_FILES['csvfile']['name']) - 4, 4) != '.csv'){
			$this->redirect('index.php?option=com_ksenmart&view=exportimport', JText::_('KSM_EXPORTIMPORT_IMPORT_CSV_EXTENSION_ERROR'));
			return false;
		}
        if (file_exists($this->csv_file))
			unlink($this->csv_file);
        copy($_FILES['csvfile']['tmp_name'], $this->csv_file);

        return true;
    }	
	
    function getProperties() {
		$db = JFactory::getDBO();
		
        $query = $db->getQuery(true);
        $query->select('*')->from('#__ksenmart_properties')->order('ordering');
        $db->setQuery($query);
        $properties = $db->loadObjectList();

        return $properties;
    } 

    function getCSVOptions() {
        if (!$f = fopen($this->csv_file, 'rt')){
			$this->redirect('index.php?option=com_ksenmart&view=exportimport', JText::_('KSM_EXPORTIMPORT_IMPORT_CSV_UPLOAD_ERROR'));
			return false;			
		}
        $data = fgetcsv($f, 10000, ';');
        fclose($f);
        $options = '<option value=""></option>';
        for($k = 0; $k < count($data); $k++)
            if($data[$k] != '') $options .= '<option value="' . $k . '">' . $this->encode($data[$k]) . '</option>';

        return $options;
    }	
	
    function importCSV() {
		$db = JFactory::getDBO();
		
        if($_FILES['photos_zip']['tmp_name'] != '') {
            $import_dir = JPATH_ROOT.'/media/com_ksenmart/import/';
            JFolder::delete($import_dir);
            JFolder::create($import_dir, 0777);
            copy($_FILES['photos_zip']['tmp_name'], $import_dir . 'import.zip');
            $result = JArchive::extract(JPath::clean($import_dir . 'import.zip'), JPath::clean($import_dir));
        }
		
        $unic = JRequest::getVar('unic');
        if (!$f = fopen($this->csv_file, 'rt')){
			$this->redirect('index.php?option=com_ksenmart&view=exportimport', JText::_('KSM_EXPORTIMPORT_IMPORT_CSV_UPLOAD_ERROR'));
			return false;			
		}
		
        $info = array('insert' => '', 'update' => '');
        $relatives = array();
		$sets = array();
		
        for($k = 0; $data = fgetcsv($f, 10000, ";"); $k++) {
            if($k == 0) {
                $headers = $data;
                continue;
            }
            $product_data = array();
            if($k > 0) {
                if(isset($_POST['id']) && $_POST['id'] != '') $product_data['id'] = $this->encode($data[$_POST['id']]);
                if(isset($_POST['title']) && $_POST['title'] != '') $product_data['title'] = $this->encode($data[$_POST['title']]);
                if(isset($_POST['parent_id']) && $_POST['parent_id'] != '') $product_data['parent_id'] = $this->encode($data[$_POST['parent_id']]);
                if(isset($_POST['categories']) && $_POST['categories'] != '') $product_data['categories'] = $this->encode($data[$_POST['categories']]);
                if(isset($_POST['childs_group']) && $_POST['childs_group'] != '') $product_data['childs_group'] = $this->encode($data[$_POST['childs_group']]);
                if(isset($_POST['price']) && $_POST['price'] != '') $product_data['price'] = str_replace(' ', '', $this->encode($data[$_POST['price']]));
                if(isset($_POST['promotion_price']) && $_POST['promotion_price'] != '') $product_data['promotion_price'] = (float)str_replace(' ', '', $this->encode($data[$_POST['promotion_price']]));
                if(isset($_POST['price_type']) && $_POST['price_type'] != '') $product_data['price_type'] = str_replace(' ', '', $this->encode($data[$_POST['price_type']]));
                if(isset($_POST['product_code']) && $_POST['product_code'] != '') $product_data['product_code'] = $this->encode($data[$_POST['product_code']]);
                if(isset($_POST['product_packaging']) && $_POST['product_packaging'] != '') $product_data['product_packaging'] = (float)str_replace(' ', '', $this->encode($data[$_POST['product_packaging']]));
                if(isset($_POST['product_unit']) && $_POST['product_unit'] != '') $product_data['product_unit'] = str_replace(' ', '', $this->encode($data[$_POST['product_unit']]));
                if(isset($_POST['in_stock']) && $_POST['in_stock'] != '') $product_data['in_stock'] = (float)$this->encode($data[$_POST['in_stock']]);
                if(isset($_POST['promotion']) && $_POST['promotion'] != '') $product_data['promotion'] = (int)$this->encode($data[$_POST['promotion']]);
                if(isset($_POST['recommendation']) && $_POST['recommendation'] != '') $product_data['recommendation'] = $this->encode($data[$_POST['recommendation']]);
                if(isset($_POST['hot']) && $_POST['hot'] != '') $product_data['hot'] = $this->encode($data[$_POST['hot']]);
                if(isset($_POST['new']) && $_POST['new'] != '') $product_data['new'] = $this->encode($data[$_POST['new']]);
                if(isset($_POST['manufacturer']) && $_POST['manufacturer'] != '') $product_data['manufacturer'] = $this->encode($data[$_POST['manufacturer']]);
                if(isset($_POST['country']) && $_POST['country'] != '') $product_data['country'] = $this->encode($data[$_POST['country']]);
                if(isset($_POST['introcontent']) && $_POST['introcontent'] != '') $product_data['introcontent'] = $this->encode($data[$_POST['introcontent']]);
                if(isset($_POST['content']) && $_POST['content'] != '') $product_data['content'] = $this->encode($data[$_POST['content']]);
                if(isset($_POST['photos']) && $_POST['photos'] != '') $product_data['photos'] = $this->encode($data[$_POST['photos']]);
                if(isset($_POST['set']) && $_POST['set'] != '') $product_data['set'] = $this->encode($data[$_POST['set']]);
                if(isset($_POST['relative']) && $_POST['relative'] != '') $product_data['relative'] = $this->encode($data[$_POST['relative']]);
				if(isset($_POST['tags']) && $_POST['tags'] != '') $product_data['tags'] = $this->encode($data[$_POST['tags']]);
                if(isset($_POST['metatitle']) && $_POST['metatitle'] != '') $product_data['metatitle'] = $this->encode($data[$_POST['metatitle']]);
                if(isset($_POST['metadescription']) && $_POST['metadescription'] != '') $product_data['metadescription'] = $this->encode($data[$_POST['metadescription']]);
                if(isset($_POST['metakeywords']) && $_POST['metakeywords'] != '') $product_data['metakeywords'] = $this->encode($data[$_POST['metakeywords']]);

                $query = $db->getQuery(true);
                $query->select('*')->from('#__ksenmart_properties')->order('ordering');
                $db->setQuery($query);
                $properties = $db->loadObjectList();
                foreach($properties as $property)
                    if(isset($_POST['property_' . $property->id]) && $_POST['property_' . $property->id] != '') $product_data['property_' . $property->id] = $this->encode($data[$_POST['property_' . $property->id]]);

                $query = $db->getQuery(true);
                $query->select('id')->from('#__ksenmart_currencies')->where('`default`=1');
                $db->setQuery($query);
                $def_price_type = $db->loadResult();

                $query = $db->getQuery(true);
                $query->select('id')->from('#__ksenmart_product_units');
                $db->setQuery($query, 0, 1);
                $def_unit = $db->loadResult();

                if(isset($product_data['parent_id']) && $product_data['parent_id'] != '') {
                    $query = $db->getQuery(true);
                    $query->select('id')->from('#__ksenmart_products')->where($unic . '=' . $db->quote($product_data['parent_id']))->where('parent_id=0');
                    $db->setQuery($query);
                    $parent_id = $db->loadResult();
                    if(empty($parent_id)) $product_data['parent_id'] = 0;
                    else {
                        $product_data['type'] = 'child';
                        $product_data['parent_id'] = $parent_id;
                        $query = $db->getQuery(true);
                        $query->update('#__ksenmart_products')->set('is_parent=1')->where('id=' . $parent_id);
                        $db->setQuery($query);
                        $db->query();
                    }
                }

                if(isset($product_data['childs_group']) && $product_data['childs_group'] != '' && $product_data['parent_id'] != 0) {
                    $query = $db->getQuery(true);
                    $query->select('*')->from('#__ksenmart_products_child_groups')->where('title like ' . $db->quote($product_data['childs_group']))->where('product_id=' . $product_data['parent_id']);
                    $db->setQuery($query);
                    $childs_group = $db->loadObject();
                    if(count($childs_group) == 0) {
                        $qvalues = array($db->quote($product_data['childs_group']), $product_data['parent_id']);
                        $query = $db->getQuery(true);
                        $query->insert('#__ksenmart_products_child_groups')->columns('title,product_id')->values(implode(',', $qvalues));
                        $db->setQuery($query);
                        $db->query();
                        $childs_group_id = $db->insertid();
                        $product_data['childs_group'] = $childs_group_id;
                    } else  $product_data['childs_group'] = $childs_group->id;
                }

                if(isset($product_data['price_type']) && $product_data['price_type'] != '') {
                    $query = $db->getQuery(true);
                    $query->select('*')->from('#__ksenmart_currencies')->where('title=' . $db->quote($product_data['price_type']));
                    $db->setQuery($query);
                    $price_type = $db->loadObject();
                    if(count($price_type) == 0) $product_data['price_type'] = $def_price_type;
                    else  $product_data['price_type'] = $price_type->id;
                }

                if(isset($product_data['product_unit']) && $product_data['product_unit'] != '') {
                    $query = $db->getQuery(true);
                    $query->select('*')->from('#__ksenmart_product_units')->where('form1=' . $db->quote($product_data['product_unit']));
                    $db->setQuery($query);
                    $unit = $db->loadObject();
                    if(count($unit) == 0) {
                        $qvalues = array(
                            $db->quote($product_data['product_unit']),
                            $db->quote($product_data['product_unit']),
                            $db->quote($product_data['product_unit']));
                        $query = $db->getQuery(true);
                        $query->insert('#__ksenmart_product_units')->columns('form1,form2,form5')->values(implode(',', $qvalues));
                        $db->setQuery($query);
                        $db->query();
                        $unit_id = $db->insertid();
                        $product_data['product_unit'] = $unit_id;
                    } else  $product_data['product_unit'] = $unit->id;
                }

                if(isset($product_data['promotion_price']) && $product_data['promotion_price'] != '') {
                    $product_data['promotion'] = 1;
                    $product_data['old_price'] = $product_data['price'];
                    $product_data['price'] = $product_data['promotion_price'];
                }

                if(isset($product_data['country']) && $product_data['country'] != '') {
                    $query = $db->getQuery(true);
                    $query->select('*')->from('#__ksenmart_countries')->where('title=' . $db->quote($product_data['country']));
                    $db->setQuery($query);
                    $country = $db->loadObject();
                    if(count($country) == 0) {
                        $alias = KSFunctions::GenAlias($product_data['country']);
                        $qvalues = array(
                            $db->quote($product_data['country']),
                            $db->quote($alias),
                            1,
                            $db->quote($product_data['country']));
                        $query = $db->getQuery(true);
                        $query->insert('#__ksenmart_countries')->columns('title,alias,published,metatitle')->values(implode(',', $qvalues));
                        $db->setQuery($query);
                        $db->query();
                        $country_id = $db->insertid();
                        $product_data['country'] = $country_id;
                    } else  $product_data['country'] = $country->id;
                }

                if(isset($product_data['manufacturer']) && $product_data['manufacturer'] != '') {
                    $query = $db->getQuery(true);
                    $query->select('*')->from('#__ksenmart_manufacturers')->where('title=' . $db->quote($product_data['manufacturer']));
                    $db->setQuery($query);
                    $manufacturer = $db->loadObject();
                    if(count($manufacturer) == 0) {
                        $alias = KSFunctions::GenAlias($product_data['manufacturer']);
                        $qvalues = array(
                            $db->quote($product_data['manufacturer']),
                            $db->quote($alias),
                            $db->quote($product_data['country']),
                            1,
                            $db->quote($product_data['manufacturer']));
                        $query = $db->getQuery(true);
                        $query->insert('#__ksenmart_manufacturers')->columns('title,alias,country,published,metatitle')->values(implode(',', $qvalues));
                        $db->setQuery($query);
                        $db->query();
                        $manufacturer_id = $db->insertid();
                        $product_data['manufacturer'] = $manufacturer_id;
                    } else  $product_data['manufacturer'] = $manufacturer->id;
                }

				$prd_cats = array();
				if(isset($product_data['categories']) && $product_data['categories'] != '')
				{
					$categories = explode(';', $product_data['categories']);
					foreach($categories as $cats) {
						$parent = 0;
						$prd_cat = 0;
						$cats = explode(':', $cats);
						foreach($cats as $cat) {
							$cat = trim($cat);
							if($cat != '') {
								$query = $db->getQuery(true);
								$query->select('*')->from('#__ksenmart_categories')->where('title=' . $db->quote($cat))->where('parent_id=' . $parent);
								$db->setQuery($query);
								$category = $db->loadObject();
								if(!$category) {
									$alias = KSFunctions::GenAlias($cat);
									$qvalues = array(
										$db->quote($cat),
										$db->quote($alias),
										$parent,
										1);
									$query = $db->getQuery(true);
									$query->insert('#__ksenmart_categories')->columns('title,alias,parent_id,published')->values(implode(',', $qvalues));
									$db->setQuery($query);
									$db->query();
									$prd_cat = $db->insertid();
									$parent = $prd_cat;
								} else {
									$prd_cat = $category->id;
									$parent = $prd_cat;
								}
								$prd_cats[] = $prd_cat;
							}
						}
					}
					if(isset($product_data['parent_id']) && $product_data['parent_id'] != 0) {
						$prd_cats = array();
						$query = $db->getQuery(true);
						$query->select('*')->from('#__ksenmart_products_categories')->where('product_id=' . $product_data['parent_id']);
						$db->setQuery($query);
						$cats = $db->loadObjectList();
						foreach($cats as $cat) $prd_cats[] = $cat->category_id;
					}
				}

                if($unic != '') {
                    $query = $db->getQuery(true);
                    $query->select('*')->from('#__ksenmart_products')->where($unic . '=' . $db->quote($product_data[$unic]));
					if(isset($product_data['parent_id']) && $product_data['parent_id'] != 0) {
						$query->where('parent_id=' . $product_data['parent_id']);
					}
                    $db->setQuery($query);
                    $product = $db->loadObjectList();
                }

                if(count($product) == 0) {
					$product_data['type'] = isset($product_data['type']) ? $product_data['type'] : 'product';					
					$product_data['type'] = isset($product_data['set']) && !empty($product_data['set']) ? 'set' : $product_data['type'];					

                    $values = array();
					if (isset($product_data['id'])) $values['id'] = $product_data['id'];
					if (isset($product_data['parent_id'])) $values['parent_id'] = (int)$product_data['parent_id'];
					if (isset($product_data['childs_group'])) $values['childs_group'] = (int)$product_data['childs_group'];
					if (isset($product_data['title']))
					{
						$alias = KSFunctions::GenAlias($product_data['title']);
						$values['title'] = $db->quote($product_data['title']);
						$values['alias'] = $db->quote($alias);
					}
					if (isset($product_data['price'])) $values['price'] = $db->quote($product_data['price']);
					if (isset($product_data['old_price'])) $values['old_price'] = $db->quote($product_data['old_price']);
					if (isset($product_data['price_type'])) $values['price_type'] = $db->quote($product_data['price_type']);
					if (isset($product_data['in_stock'])) $values['in_stock'] = $db->quote($product_data['in_stock']);
					if (isset($product_data['product_code'])) $values['product_code'] = $db->quote($product_data['product_code']);
					if (isset($product_data['product_packaging'])) $values['product_packaging'] = (real)$product_data['product_packaging'];
					if (isset($product_data['product_unit'])) $values['product_unit'] = (int)$product_data['product_unit'];
					if (isset($product_data['introcontent'])) $values['introcontent'] = $db->quote($product_data['introcontent']);
					if (isset($product_data['content'])) $values['content'] = $db->quote($product_data['content']);
					if (isset($product_data['promotion'])) $values['promotion'] = (int)$product_data['promotion'];
					if (isset($product_data['recommendation'])) $values['recommendation'] = (int)$product_data['recommendation'];
					if (isset($product_data['hot'])) $values['hot'] = (int)$product_data['hot'];
					if (isset($product_data['new'])) $values['new'] = (int)$product_data['new'];
					if (isset($product_data['manufacturer'])) $values['manufacturer'] = (int)$product_data['manufacturer'];
					if (isset($product_data['metatitle'])) $values['metatitle'] = $db->quote($product_data['metatitle']);
					if (isset($product_data['metadescription'])) $values['metadescription'] = $db->quote($product_data['metadescription']);
					if (isset($product_data['metakeywords'])) $values['metakeywords'] = $db->quote($product_data['metakeywords']);
					if (isset($product_data['type'])) $values['type'] = $db->quote($product_data['type']);
					$values['published'] = 1;
					$values['date_added'] = 'NOW()';

                    $query = $db->getQuery(true);
                    $query->update('#__ksenmart_products')->set('ordering=ordering+1');
                    $db->setQuery($query);
                    $db->query();
                    $query = $db->getQuery(true);
                    $query->insert('#__ksenmart_products')->columns(implode(',', array_keys($values)))->values(implode(',', $values));
                    $db->setQuery($query);
                    $db->query();
                    $product_id = $db->insertid();

                    $is_default = true;
                    foreach($prd_cats as $prd_cat) {
                        $qvalues = array(
                            $product_id,
                            $prd_cat,
                            (int)$is_default);
                        $query = $db->getQuery(true);
                        $query->insert('#__ksenmart_products_categories')->columns('product_id,category_id,is_default')->values(implode(',', $qvalues));
                        $db->setQuery($query);
                        $db->query();
                        $is_default = false;
                    }

                    $info['insert']++;
                } else {
                    $product_id = $product[0]->id;

                    $to_update = array();
                    $to_update[] = 'date_added=NOW()';
                    if(isset($product_data['title'])) $to_update[] = 'title=' . $db->quote($product_data['title']);
                    if(isset($product_data['product_code'])) $to_update[] = 'product_code=' . $db->quote($product_data['product_code']);
                    if(isset($product_data['in_stock'])) $to_update[] = 'in_stock=' . $db->quote($product_data['in_stock']);
                    if(isset($product_data['content'])) $to_update[] = 'content=' . $db->quote($product_data['content']);
                    if(isset($product_data['introcontent'])) $to_update[] = 'introcontent=' . $db->quote($product_data['introcontent']);
                    if(isset($product_data['product_packaging'])) $to_update[] = 'product_packaging=' . $db->quote($product_data['product_packaging']);
                    if(isset($product_data['metatitle'])) $to_update[] = 'metatitle=' . $db->quote($product_data['metatitle']);
                    if(isset($product_data['metadescription'])) $to_update[] = 'metadescription=' . $db->quote($product_data['metadescription']);
                    if(isset($product_data['metakeywords'])) $to_update[] = 'metakeywords=' . $db->quote($product_data['metakeywords']);
                    if(isset($product_data['price'])) $to_update[] = 'price=' . $db->quote($product_data['price']);
                    if(isset($product_data['manufacturer'])) $to_update[] = 'manufacturer=' . $db->quote($product_data['manufacturer']);
                    if(isset($product_data['price_type'])) $to_update[] = 'price_type=' . $db->quote($product_data['price_type']);
                    if(isset($product_data['product_unit'])) $to_update[] = 'product_unit=' . $db->quote($product_data['product_unit']);
                    if(isset($product_data['old_price'])) $to_update[] = 'old_price=' . $db->quote($product_data['old_price']);
                    if(isset($product_data['promotion'])) $to_update[] = 'promotion=' . $db->quote($product_data['promotion']);
                    if(isset($product_data['recommendation'])) $to_update[] = 'recommendation=' . $db->quote($product_data['recommendation']);
                    if(isset($product_data['hot'])) $to_update[] = 'hot=' . $db->quote($product_data['hot']);
                    if(isset($product_data['new'])) $to_update[] = 'new=' . $db->quote($product_data['new']);

                    foreach($prd_cats as $prd_cat) {
                        $query = $db->getQuery(true);
                        $query->select('*')->from('#__ksenmart_products_categories')->where('product_id=' . $product_id)->where('category_id=' . $prd_cat);
                        $db->setQuery($query);
                        $db_cat = $db->loadObject();
                        if(count($db_cat) == 0) {
                            $qvalues = array($product_id, $prd_cat);
                            $query = $db->getQuery(true);
                            $query->insert('#__ksenmart_products_categories')->columns('product_id,category_id')->values(implode(',', $qvalues));
                            $db->setQuery($query);
                            $db->query();
                        }
                    }

                    $query = $db->getQuery(true);
                    $query->update('#__ksenmart_products')->set($to_update)->where('id=' . $product_id);
                    $db->setQuery($query);
                    $db->query();

                    if(isset($product_data['photos']) && $product_data['photos'] != '') {
                        $query = $db->getQuery(true);
                        $query->select('*')->from('#__ksenmart_files')->where(array(
                            "media_type='image'",
                            "owner_type='product'",
                            "owner_id=" . $product_id))->order('ordering');
                        $db->setQuery($query);
                        $images = $db->loadObjectList('id');
                        $i = count($images);
                        foreach($images as $image) $this->delPhoto($image->filename, $image->folder);
                    }

                    $info['update']++;
                }

                if(isset($product_data['relative']) && $product_data['relative'] != '') $relatives[$product_id] = $product_data['relative'];
                if(isset($product_data['set']) && $product_data['set'] != '') $sets[$product_id] = $product_data['set'];
				
				if(isset($product_data['tags']) && $product_data['tags'] != '') {
					$product_data['tags'] = explode(';', $product_data['tags']);
					foreach($product_data['tags'] as $key=>$value)
					{
						$value = trim($value);
                        $query = $db->getQuery(true);
                        $query->select('id')->from('#__tags')->where('title='.$db->quote($value));
                        $db->setQuery($query);
                        $tag_id = $db->loadResult();	
						if (!empty($tag_id))
							$product_data['tags'][$key] = $tag_id;
						else
							$product_data['tags'][$key] = '#new#'.$value;
					}

					$tableProducts = $this->getTable('Products');
					JObserverMapper::attachAllObservers($tableProducts);
					JObserverMapper::addObserverClassToClass('JTableObserverTags', 'KsenmartTableProducts', array('typeAlias' => 'com_ksenmart.product'));					
					$tableProducts->load($product_id);
					$tagsObserver = $tableProducts->getObserverOfClass('JTableObserverTags');
					$result = $tagsObserver->setNewTags($product_data['tags'], true);				
				}				

                if(isset($product_data['photos']) && $product_data['photos'] != '') {
                    $product_data['photos'] = explode(';', $product_data['photos']);
                    $i = 1;
                    foreach($product_data['photos'] as $photo) {
                        $photo = trim($photo);
						if(!empty($photo))
						{
							$file = basename($photo);
							$nameParts = explode('.', $file);
							$file = microtime(true) . '.' . $nameParts[count($nameParts) - 1];	
							$copied = false;
							if (strpos($photo, 'http://') !== false)
							{
								if($photo_content = file_get_contents($photo)) {
									if (file_put_contents(JPATH_ROOT . DS . 'media' . DS . 'com_ksenmart' . DS . 'images' . DS . 'products' . DS . 'original' . DS . $file, $photo_content)){
										$copied = true;
									}
								}						
							}
							else
							{
								if(file_exists(JPATH_ROOT . DS . 'media' . DS . 'com_ksenmart' . DS . 'import' . DS  . $photo)) {
									if(copy(JPATH_ROOT . DS . 'media' . DS . 'com_ksenmart' . DS . 'import' . DS  . $photo, JPATH_ROOT . DS . 'media' . DS . 'com_ksenmart' . DS . 'images' . DS . 'products' . DS . 'original' . DS .  $file)) {
										$copied = true;
									}
								}
							}
							if ($copied)
							{
								$mime = mime_content_type(JPATH_ROOT . DS . 'media' . DS . 'com_ksenmart' . DS . 'images' . DS . 'products' . DS . 'original' . DS .  $file);
								$qvalues = array(
									$product_id,
									$db->quote('image'),
									$db->quote('product'),
									$db->quote('products'),
									$db->quote($file),
									$db->quote($mime),
									$db->quote(''),
									$i);
								$query = $db->getQuery(true);
								$query->insert('#__ksenmart_files')->columns('owner_id,media_type,owner_type,folder,filename,mime_type,title,ordering')->values(implode(',', $qvalues));
								$db->setQuery($query);
								$db->query();
								$i++;							
							}
						}
                    }
                }

                foreach($properties as $property) {
                    if(isset($product_data['property_' . $property->id]) && $product_data['property_' . $property->id] != '') {
                        switch($property->type) {
                            case 'text':
                                if($product_data['property_' . $property->id] != '') {
                                    $query = $db->getQuery(true);
                                    $query->select('*')->from('#__ksenmart_property_values')->where('property_id=' . $property->id)->where('title=' . $db->quote($product_data['property_' . $property->id]));
                                    $db->setQuery($query);
                                    $prop_value = $db->loadObject();
                                    if(count($prop_value) == 0) {
                                        $alias = KSFunctions::GenAlias($product_data['property_' . $property->id]);
                                        $qvalues = array(
                                            $property->id,
                                            $db->quote($product_data['property_' . $property->id]),
                                            $db->quote($alias));
                                        $query = $db->getQuery(true);
                                        $query->insert('#__ksenmart_property_values')->columns('property_id,title,alias')->values(implode(',', $qvalues));
                                        $db->setQuery($query);
                                        $db->query();
                                        $prop_value_id = $db->insertid();
                                    } else  $prop_value_id = $prop_value->id;
									$query = $db->getQuery(true);
                                    $query->select('*')->from('#__ksenmart_product_properties_values')->where('product_id=' . $product_id)->where('property_id=' . $property->id)->where('value_id=' . $prop_value_id);
                                    $db->setQuery($query);
                                    $prod_prop_value = $db->loadObject();
                                    if(count($prod_prop_value) == 0) {
                                        $qvalues = array(
                                            $product_id,
                                            $property->id,
                                            $prop_value_id,
                                            $db->quote($product_data['property_' . $property->id]));
                                        $query = $db->getQuery(true);
                                        $query->insert('#__ksenmart_product_properties_values')->columns('product_id,property_id,value_id,text')->values(implode(',', $qvalues));
                                        $db->setQuery($query);
                                        $db->query();
                                    }
                                }
                                break;
                            default:
                                $prop_vals = explode(';', $product_data['property_' . $property->id]);
                                $prop_values = '';
                                foreach($prop_vals as $prop_val) {
                                    if($prop_val != '') {
										$val_parts = explode('=', $prop_val);
										if(count($val_parts) == 2){
											$prop_val = $val_parts[0];
											$val_price = $val_parts[1];
										} else {
											$val_price = '';
										}
                                        $query = $db->getQuery(true);
                                        $query->select('*')->from('#__ksenmart_property_values')->where('property_id=' . $property->id)->where('title=' . $db->quote($prop_val));
                                        $db->setQuery($query);
                                        $prop_value = $db->loadObject();
                                        if(count($prop_value) == 0) {
                                            $alias = KSFunctions::GenAlias($prop_val);
                                            $qvalues = array(
                                                $property->id,
                                                $db->quote($prop_val),
                                                $db->quote($alias));
                                            $query = $db->getQuery(true);
                                            $query->insert('#__ksenmart_property_values')->columns('property_id,title,alias')->values(implode(',', $qvalues));
                                            $db->setQuery($query);
                                            $db->query();
                                            $prop_value_id = $db->insertid();
                                        } else  $prop_value_id = $prop_value->id;
										$query = $db->getQuery(true);
                                        $query->select('*')->from('#__ksenmart_product_properties_values')->where('product_id=' . $product_id)->where('property_id=' . $property->id)->where('value_id=' . $prop_value_id);
                                        $db->setQuery($query);
                                        $prod_prop_value = $db->loadObject();
                                        if(count($prod_prop_value) == 0) {
                                            $qvalues = array(
                                                $product_id,
                                                $property->id,
                                                $prop_value_id,
												$db->quote($val_price));
                                            $query = $db->getQuery(true);
                                            $query->insert('#__ksenmart_product_properties_values')->columns('product_id,property_id,value_id,price')->values(implode(',', $qvalues));
                                            $db->setQuery($query);
                                            $db->query();
                                        } else {
											$query = $db->getQuery(true);
											$query->update('#__ksenmart_product_properties_values')->set('price=' . $db->quote($val_price))->where('id=' . $prod_prop_value->id);
											$db->setQuery($query);
                                            $db->query();
										}
                                    }
                                }
                        }

                        foreach($prd_cats as $prd_cat) {
                            $query = $db->getQuery(true);
                            $query->select('id')->from('#__ksenmart_product_categories_properties')->where('category_id=' . $prd_cat)->where('property_id=' . $property->id);
                            $db->setQuery($query);
                            $res = $db->loadResult();
                            if(empty($res)) {
                                $qvalues = array($prd_cat, $property->id);
                                $query = $db->getQuery(true);
                                $query->insert('#__ksenmart_product_categories_properties')->columns('category_id,property_id')->values(implode(',', $qvalues));
                                $db->setQuery($query);
                                $db->query();
                            }
                        }

                    }
                }

            }
        }

        foreach($relatives as $product_id => $relative) {
            $relative = explode(';', $relative);
            foreach($relative as $relative_title) {
                if(!empty($relative_title)) {
                    $query = $db->getQuery(true);
                    $query->select('id')->from('#__ksenmart_products')->where($unic . ' like ' . $db->quote($relative_title));
                    $db->setQuery($query);
                    $relative_id = $db->loadResult();
                    if(!empty($relative_id)) {
                        $query = $db->getQuery(true);
                        $query->select('*')->from('#__ksenmart_products_relations')->where('product_id=' . $product_id)->where('relative_id=' . $relative_id)->where('relation_type=' . $db->quote('relation'));
                        $db->setQuery($query);
                        $db_rel = $db->loadObject();
                        if(count($db_rel) == 0) {
                            $qvalues = array(
                                $product_id,
                                $relative_id,
                                $db->quote('relation'));
                            $query = $db->getQuery(true);
                            $query->insert('#__ksenmart_products_relations')->columns('product_id,relative_id,relation_type')->values(implode(',', $qvalues));
                            $db->setQuery($query);
                            $db->query();
                        }
                    }
                }
            }
        }

        foreach($sets as $product_id => $set) {
            $set = explode(';', $set);
            foreach($set as $set_title) {
                if(!empty($set_title)) {
                    $query = $db->getQuery(true);
                    $query->select('id')->from('#__ksenmart_products')->where($unic . ' like ' . $db->quote($set_title));
                    $db->setQuery($query);
                    $relative_id = $db->loadResult();
                    if(!empty($relative_id)) {
                        $query = $db->getQuery(true);
                        $query->select('*')->from('#__ksenmart_products_relations')->where('product_id=' . $product_id)->where('relative_id=' . $relative_id)->where('relation_type=' . $db->quote('set'));
                        $db->setQuery($query);
                        $db_set = $db->loadObject();
                        if(count($db_set) == 0) {
                            $qvalues = array(
                                $product_id,
                                $relative_id,
                                $db->quote('set'));
                            $query = $db->getQuery(true);
                            $query->insert('#__ksenmart_products_relations')->columns('product_id,relative_id,relation_type')->values(implode(',', $qvalues));
                            $db->setQuery($query);
                            $db->query();
                        }
                    }
                }
            }
        }
        fclose($f);
        $dir = scandir(JPATH_ROOT.'/administrator/components/com_ksenmart/tmp/');
        foreach($dir as $d)
            if($d != '.' && $d != '..') unlink(JPATH_ROOT.'/administrator/components/com_ksenmart/tmp/'.$d);

        return $info;
    }	
	
    function delPhoto($filename, $folder) {
		$db = JFactory::getDBO();
        $files = scandir(JPATH_ROOT . DS . 'media' . DS . 'com_ksenmart' . DS . 'images' . DS . $folder);
        foreach($files as $file) {
            if($file != '.' && $file != '..' && is_dir(JPATH_ROOT . DS . 'media' . DS . 'com_ksenmart' . DS . 'images' . DS .  $folder .  DS . $file))
                if(file_exists(JPATH_ROOT  . DS . 'media' . DS . 'com_ksenmart' . DS . 'images' . DS  . $folder  . DS .  $file . DS . $filename)) unlink(JPATH_ROOT . DS . 'media' . DS . 'com_ksenmart' . DS . 'images' . DS  . $folder . DS . $file . DS . $filename);
        }
        $query = $db->getQuery(true);
        $query->delete('#__ksenmart_files');
        $where = array("filename='$filename'");
        $query->where($where);
        $db->setQuery($query);
        $db->query();

        return true;
    }	
	
	function getTable($table){
        JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_ksenmart/tables');
        $table = JTable::getInstance($table, 'KsenmartTable', array());

		return $table;
	}
	
    function encode($string) {
        if ($this->view->encoding == 'cp1251')
			setLocale(LC_ALL, 'ru_RU.CP1251');

        if ($this->view->encoding == 'cp1251')
			$string = trim(iconv('WINDOWS-1251', 'UTF-8', $string));

        return $string;
    }	
	
	function redirect($url, $message, $messageType = 'message'){
		$app = JFactory::getApplication();

		$app->enqueueMessage($message, $messageType);
		$app->redirect($url);	

		return true;
	}
	
}