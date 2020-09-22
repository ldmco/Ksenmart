<?php defined('_JEXEC') or die('Restricted access');

if (!class_exists('KMPlugin')) {
	require (JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_ksenmart' . DS . 'classes' . DS . 'kmplugin.php');
}

class plgKMExportimportImport_ym extends KMPlugin {

	var $view = null;
	var $ym_file = null;
	var $ks_categories = null;
	var $categories = array();
	var $countries = array();
	var $manufacturers = array();
	var $encoding = null;
	var $properties_type = null;
	var $uniq = null;
	private $_db = null;

	function __construct(&$subject, $config) {
		parent::__construct($subject, $config);
		$this->ym_file = JPATH_ROOT.'/administrator/components/com_ksenmart/tmp/import.xml';
	}

	public function onAjaxImport_ymUploadYm(){
		$jinput = JFactory::getApplication()->input;
		$step = $jinput->get('step', 'upload');
		$this->encoding = $jinput->get('encoding', 'cp1251');
		$this->properties_type = $jinput->get('properties_type', 'no');
		$this->step_import = $jinput->get('step_import', 1);
		$this->uniq = $jinput->get('uniq', 0);
		$this->start = $jinput->get('start', 0);

		$status = $this->importYM();
		$response = array(
			'end' => null,
			'status' => $status
		);
		$response = json_encode($response);
		JFactory::getDocument()->setMimeEncoding('application/json');
		JFactory::getApplication()->close($response);
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

		if (!$this->uploadImportYMFile()){
			return false;
		}

		$post_max_size = (int)ini_get('post_max_size');
		$upload_max_filesize = (int)ini_get('upload_max_filesize');
		$this->view->max_filesize = $upload_max_filesize > $post_max_size ? $post_max_size : $upload_max_filesize;

		$html = KSSystem::loadPluginTemplate($this->_name, $this->_type, $this->view, 'parse');

		return $html;
	}

	function getImportStep(){
		$jinput = JFactory::getApplication()->input;
		$this->view->encoding = $jinput->get('encoding', 'cp1251');
		$this->view->properties_type = $jinput->get('properties_type', 'no');
		echo $this->view->properties_type;

		if (!$this->uploadImportYMFile()){
			return false;
		}

		if (!$this->view->info = $this->importYM()){
			return false;
		}

		$html = KSSystem::loadPluginTemplate($this->_name, $this->_type, $this->view, 'result');

		return $html;
	}

	function uploadImportYMFile() {
		if (!isset($_FILES['ymfile'])){
			$this->redirect('index.php?option=com_ksenmart&view=exportimport', JText::_('KSM_EXPORTIMPORT_IMPORT_CSV_CHOOSE_FILE_ERROR'));
			return false;
		}
		if ($_FILES['ymfile']['tmp_name'] == ''){
			$this->redirect('index.php?option=com_ksenmart&view=exportimport', JText::_('KSM_EXPORTIMPORT_IMPORT_CSV_CHOOSE_FILE_ERROR'));
			return false;
		}
		if (substr($_FILES['ymfile']['name'], strlen($_FILES['ymfile']['name']) - 4, 4) != '.xml'){
			$this->redirect('index.php?option=com_ksenmart&view=exportimport', JText::_('KSM_EXPORTIMPORT_IMPORT_CSV_EXTENSION_ERROR'));
			return false;
		}
		if (file_exists($this->ym_file))
			unlink($this->ym_file);
		copy($_FILES['ymfile']['tmp_name'], $this->ym_file);

		return true;
	}

	function getCSVOptions() {
		if (!$f = fopen($this->ym_file, 'rt')){
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

	function importYM() {
		$unic = 'ym_id';
		if(!file_exists($this->ym_file)){
			$this->redirect('index.php?option=com_ksenmart&view=exportimport', JText::_('KSM_EXPORTIMPORT_IMPORT_CSV_UPLOAD_ERROR'));
			return false;
		}
		if(!$xml = simplexml_load_file($this->ym_file)){
			$this->redirect('index.php?option=com_ksenmart&view=exportimport', JText::_('KSM_EXPORTIMPORT_IMPORT_CSV_UPLOAD_ERROR'));
			return false;
		}
		if(!$xml){
			$this->redirect('index.php?option=com_ksenmart&view=exportimport', JText::_('KSM_EXPORTIMPORT_IMPORT_CSV_UPLOAD_ERROR'));
			return false;
		}

		$info = array('insert' => '', 'update' => '');
		$relatives = array();
		$sets = array();
		if(empty($this->_db)) $this->_db = JFactory::getDBO();
		$query = $this->_db->getQuery(true);
		$query->select('id, id_ym')->from('#__ksenmart_categories');
		$this->_db->setQuery($query);
		$this->ks_categories = $this->_db->loadObjectList('id_ym');

		if(isset($xml->shop)){
			if($this->step_import == 1){
				if(isset($xml->shop->categories)){
					$main_categories = array();
					foreach($xml->shop->categories->category as $category){
						$new_category = new stdClass();
						$new_category->id = (int)$category->attributes()->id;
						$new_category->title = (string)$category;
						if(isset($category->attributes()->parentId)){
							$new_category->parent_id = (int)$category->attributes()->parentId;
							$this->categories[$new_category->parent_id][] = $new_category;
						} else {
							$main_categories[$new_category->id] = $new_category;
						}
					}
					foreach($main_categories as $k => $category){
						$this->setCatChilds($category, 0);
					}
				}
				return true;
			}
			if($this->step_import == 2){
				if(isset($xml->shop->offers)){
					$cur_count = 0;
					foreach($xml->shop->offers->offer as $product){
						if($cur_count < $this->start){
							$cur_count++;
							continue;
						}
						if($cur_count > $this->start + 100) return true;
						$cur_count++;
						$product_data = array();
						if($this->uniq)
							$product_data['product_code'] = (int)$product->attributes()->id;
						$product_data['id_ym'] = (int)$product->attributes()->id;
						if((string)$product->attributes()->available == 'true'){
							$product_data['published'] = 1;
						} else {
							$product_data['published'] = 0;
						}
						$product_data['title'] = trim((string)$product->name);
						$product_data['price'] = (float)$product->price;
						$product_data['content'] = (string)$product->description;
						$country = (string)$product->country_of_origin;
						$cid = 0;
						if(!empty($country)){
							if(empty($this->countries)){
								$query = $this->_db->getQuery(true);
								$query->select('id,title')->from('#__ksenmart_countries');
								$this->_db->setQuery($query);
								$this->countries = $this->_db->loadObjectList('title');
							}
							if(!isset($this->countries[$country])){
								$alias = KSFunctions::GenAlias($country);
								$qvalues = array(
									$this->_db->quote($country),
									$this->_db->quote($alias)
								);
								$query = $this->_db->getQuery(true);
								$query->insert('#__ksenmart_countries')->columns('title,alias')->values(implode(',', $qvalues));
								$this->_db->setQuery($query);
								$this->_db->Query();
								$cid = $this->_db->insertid();

								$country_obj = new stdClass();
								$country_obj->title = $country;
								$country_obj->id = $cid;
								$this->countries[$country] = $country_obj;
							} else {
								$cid = $this->countries[$country]->id;
							}
						}
						$manufacturer = (string)$product->vendor;
						$mid = 0;
						if(!empty($manufacturer)){
							if(empty($this->manufacturers)){
								$query = $this->_db->getQuery(true);
								$query->select('id,title')->from('#__ksenmart_manufacturers');
								$this->_db->setQuery($query);
								$this->manufacturers = $this->_db->loadObjectList('title');
							}
							if(!isset($this->manufacturers[$manufacturer])){
								$alias = KSFunctions::GenAlias($manufacturer);
								$qvalues = array(
									$this->_db->quote($manufacturer),
									$this->_db->quote($alias),
									$cid
								);
								$query = $this->_db->getQuery(true);
								$query->insert('#__ksenmart_manufacturers')->columns('title,alias,country')->values(implode(',', $qvalues));
								$this->_db->setQuery($query);
								$this->_db->Query();
								$mid = $this->_db->insertid();

								$manufacturer_obj = new stdClass();
								$manufacturer_obj->title = $manufacturer;
								$manufacturer_obj->id = $cid;
								$this->manufacturers[$manufacturer] = $manufacturer_obj;
							} else {
								$mid = $this->manufacturers[$manufacturer]->id;
							}
						}
						$product_data['manufacturer'] = $mid;
						if($this->properties_type == 'no'){
							if(isset($product->param)){
								$product_data['content'] .= '<ul>';
								foreach($product->param as $property){
									$ptitle = trim((string)$property->attributes()->name);
									$value = trim((string)$property);
									$product_data['content'] .= '<li>' . $ptitle . ': ' . $value . '</li>';
								}
								$product_data['content'] .= '</ul>';
							}
						}
						if(empty($this->products)){
							$query = $this->_db->getQuery(true);
							$query->select('id_ym,id,product_code')->from('#__ksenmart_products');
							$this->_db->setQuery($query);
							if($this->uniq)
								$this->products = $this->_db->loadObjectList('product_code');
							else
								$this->products = $this->_db->loadObjectList('id_ym');
						}
						if(isset($this->products[$product_data['id_ym']])){
							$to_update = array();
							$to_update[] = 'date_added=NOW()';
							$to_update[] = 'title=' . $this->_db->quote($product_data['title']);
							$to_update[] = 'content=' . $this->_db->quote($product_data['content']);
							//$to_update[] = 'id_ym=' . $this->_db->quote($product_data['id_ym']);
							$to_update[] = 'price=' . (int)$product_data['price'];

							$query = $this->_db->getQuery(true);
							$query->update('#__ksenmart_products')->set($to_update);
							if($this->uniq)
								$query->where('product_code=' . $product_data['product_code']);
							else
								$query->where('id_ym=' . $product_data['id_ym']);
							$this->_db->setQuery($query);
							$this->_db->Query();

							$pid = $this->products[$product_data['id_ym']]->id;
						} else {
							$alias = KSFunctions::GenAlias($product_data['title']);
							$qvalues = array(
								$this->_db->quote($product_data['title']),
								$this->_db->quote($alias),
								$this->_db->quote($product_data['content']),
								$product_data['price'],
								$this->_db->quote('product'),
								$product_data['manufacturer'],
								'NOW()',
								$product_data['published'],
								$this->_db->quote($product_data['id_ym']),
								$this->_db->quote($product_data['product_code'])
							);
							$query = $this->_db->getQuery(true);
							$query->update('#__ksenmart_products')->set('ordering=ordering+1');
							$this->_db->setQuery($query);
							$this->_db->query();
							$query = $this->_db->getQuery(true);
							$query->insert('#__ksenmart_products')->columns('title,alias,content,price,type,manufacturer,date_added,published,id_ym,product_code')->values(implode(',', $qvalues));
							$this->_db->setQuery($query);
							$this->_db->Query();
							$pid = $this->_db->insertid();

							/*$product_obj = new stdClass();
							$product_obj->id_ym = $product_data['id_ym'];
							$product_obj->id = $pid;
							$this->products[$product_data['id_ym']] = $product_obj;*/
						}
						if(isset($product->categoryId)){
							$cats = array();
							$query = $this->_db->getQuery(true);
							$query->select('category_id')->from('#__ksenmart_products_categories')->where('product_id=' . $pid);
							$this->_db->setQuery($query);
							$cats = $this->_db->loadColumn();
							if(empty($cats)){
								$is_default = true;
							} else {
								$is_default = false;
							}
							foreach($product->categoryId as $cat){
								$catid = (string)$cat;
								if(isset($this->ks_categories[$catid])){
									$cid = $this->ks_categories[$catid]->id;
									if(!empty($cid)){
										if(empty($cats) || !in_array($cid, $cats)){
											$cats[] = $cid;
											$query = $this->_db->getQuery(true);
											$query->insert('#__ksenmart_products_categories')->columns('product_id,category_id,is_default')->values($pid . ',' . $cid . ',' . (int)$is_default);
											$this->_db->setQuery($query);
											$this->_db->Query();
											$is_default = false;
										}
									}
								}
							}
							$prod_cats = $cats;
							if(count($cats) > 0){
								$query = $this->_db->getQuery(true);
								$query->delete('#__ksenmart_products_categories')->where('product_id=' . $pid)->where('category_id not in (' . implode(',', $cats) . ')');
								$this->_db->setQuery($query);
								$this->_db->Query();
							}
						}
						if(isset($product->picture)){
							$i = 0;
							foreach($product->picture as $photo){
								$photo = trim($photo);
								if(!empty($photo)){
									$file = basename($photo);
									$nameParts = explode('.', $file);
									$file = microtime(true) . '.' . $nameParts[count($nameParts) - 1];
									$copied = false;
									if (strpos($photo, 'http://') !== false){
										if($photo_content = file_get_contents($photo)) {
											if (file_put_contents(JPATH_ROOT . DS . 'media' . DS . 'com_ksenmart' . DS . 'images' . DS . 'products' . DS . 'original' . DS . $file, $photo_content)){
												$copied = true;
											}
										}
									} else {
										if(file_exists(JPATH_ROOT . DS . 'plugins' . DS . 'kmexportimport' . DS . '1C_exchange' . DS . 'import' . DS . $photo)){
											if(copy(JPATH_ROOT . DS . 'plugins' . DS . 'kmexportimport' . DS . '1C_exchange' . DS . 'import' . DS . $photo, JPATH_ROOT . DS . 'media' . DS . 'com_ksenmart' . DS . 'images' . DS . 'products' . DS . 'original' . DS .  $file)){
												$copied = true;
											}
										}
									}
									if ($copied){
										$mime = mime_content_type(JPATH_ROOT . DS . 'media' . DS . 'com_ksenmart' . DS . 'images' . DS . 'products' . DS . 'original' . DS .  $file);
										$qvalues = array(
											$pid,
											$this->_db->quote('image'),
											$this->_db->quote('product'),
											$this->_db->quote('products'),
											$this->_db->quote($file),
											$this->_db->quote($mime),
											$this->_db->quote(''),
											$i);
										$query = $this->_db->getQuery(true);
										$query->insert('#__ksenmart_files')->columns('owner_id,media_type,owner_type,folder,filename,mime_type,title,ordering')->values(implode(',', $qvalues));
										$this->_db->setQuery($query);
										$this->_db->query();
										$i++;
									}
								}
							}
						}
						if(isset($product->param)){
							foreach($product->param as $property){
								if($this->properties_type == 'yes'){
									$ptitle = trim((string)$property->attributes()->name);
									if(empty($this->properties)){
										$query = $this->_db->getQuery(true);
										$query->select('id, title')->from('#__ksenmart_properties');
										$this->_db->setQuery($query);
										$this->properties = $this->_db->loadObjectList('title');
									}
									if(!isset($this->properties[$ptitle])){
										$alias = KSFunctions::GenAlias($ptitle);
										$qvalues = array(
											$this->_db->quote($ptitle),
											$this->_db->quote($alias),
											$this->_db->quote('text'),
											1
										);
										$query = $this->_db->getQuery(true);
										$query->insert('#__ksenmart_properties')->columns('title,alias,type,published')->values(implode(',', $qvalues));
										$this->_db->setQuery($query);
										$this->_db->Query();
										$prop_id = $this->_db->insertid();

										$prop_obj = new stdClass();
										$prop_obj->title = $ptitle;
										$prop_obj->id = $prop_id;
										$this->properties[$ptitle] = $prop_obj;
									} else {
										$prop_id = $this->properties[$ptitle]->id;
									}
									$query = $this->_db->getQuery(true);
									$query->select('id')->from('#__ksenmart_product_categories_properties')->where('property_id=' . (int)$prop_id);
									$this->_db->setQuery($query);
									$pcats = $this->_db->loadColumn();

									foreach($prod_cats as $cat){
										if(!in_array($cat, $pcats)){
											$qvalues = array(
												(int)$prop_id,
												(int)$cat
											);
											$query = $this->_db->getQuery(true);
											$query->insert('#__ksenmart_product_categories_properties')->columns('property_id,category_id')->values(implode(',', $qvalues));
											$this->_db->setQuery($query);
											$this->_db->Query();
										}
									}
									$value = trim((string)$property);
									$query = $this->_db->getQuery(true);
									$query->select('id')->from('#__ksenmart_property_values')->where('title=' . $this->_db->q($value))->where('property_id=' . $prop_id);
									$this->_db->setQuery($query);
									$vid = $this->_db->loadResult();

									if(empty($vid)){
										$alias = KSFunctions::GenAlias($value);
										$qvalues = array(
											$this->_db->quote($value),
											$this->_db->quote($alias),
											$prop_id
										);
										$query = $this->_db->getQuery(true);
										$query->insert('#__ksenmart_property_values')->columns('title,alias,property_id')->values(implode(',', $qvalues));
										$this->_db->setQuery($query);
										$this->_db->Query();
										$vid = $this->_db->insertid();
									}
									$query = $this->_db->getQuery(true);
									$query->select('*')->from('#__ksenmart_product_properties_values')->where('product_id=' . $pid)->where('property_id=' . $prop_id)->where('value_id=' . $vid);
									$this->_db->setQuery($query);
									$prod_prop_value = $this->_db->loadObject();
									if(count($prod_prop_value) == 0) {
										$qvalues = array(
											$pid,
											$prop_id,
											$vid,
											$this->_db->quote($value));
										$query = $this->_db->getQuery(true);
										$query->insert('#__ksenmart_product_properties_values')->columns('product_id,property_id,value_id,text')->values(implode(',', $qvalues));
										$this->_db->setQuery($query);
										$this->_db->query();
									}
								}
							}
						}
					}
				}
			}
			return false;
		}
		$dir = scandir(JPATH_ROOT.'/administrator/components/com_ksenmart/tmp/');
		foreach($dir as $d)
			if($d != '.' && $d != '..') unlink(JPATH_ROOT.'/administrator/components/com_ksenmart/tmp/'.$d);

		return;
	}

	protected function setCatChilds($cat, $parent){
		$cat->title = trim($cat->title);
		if(!empty($cat->title)){
			if(!isset($this->ks_categories[$cat->id])){
				$alias = KSFunctions::GenAlias($cat->title);
				$qvalues = array(
					$this->_db->quote($cat->title),
					$this->_db->quote($alias),
					(int)$parent,
					(int)$cat->id
				);
				$query = $this->_db->getQuery(true);
				$query->insert('#__ksenmart_categories')->columns('title,alias,parent_id,id_ym')->values(implode(',', $qvalues));
				$this->_db->setQuery($query);
				$this->_db->Query();
				$cid = $this->_db->insertid();
				$new_cat = new stdClass();
				$new_cat->id = $cid;
				$new_cat->ym_id = $cat->id;
				$this->ks_categories[$cat->id] = $new_cat;

				if(isset($this->categories[$cat->id])){
					foreach($this->categories[$cat->id] as $category){
						$this->setCatChilds($category, $cid);
					}
				}
			}
		}
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