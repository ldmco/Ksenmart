<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

KSSystem::import('models.modelksadmin');
class KsenMartModelExportImport extends JModelKSAdmin {

    protected function populateState($ordering = null, $direction = null){
        $this->onExecuteBefore('populateState');

        $app    = JFactory::getApplication();
        $params = JComponentHelper::getParams('com_ksenmart');

        $type = $app->getUserStateFromRequest('com_ksenmart.exportimport.type', 'type', 'text');
        $this->setState('type', $type);
        $this->context .= '.' . $type;

        $encoding = $app->getUserStateFromRequest($this->context . '.encoding', 'encoding', 'cp1251');
        $this->setState('encoding', $encoding);

        $this->onExecuteAfter('populateState');
    }

    function getProperties($public = true) {
        $this->onExecuteBefore('getProperties');

        $query = $this->_db->getQuery(true);
        $query->select('*')->from('#__ksenmart_properties')->order('ordering');
        $this->_db->setQuery($query);
        $properties = $this->_db->loadObjectList();

        $this->onExecuteAfter('getProperties', array(&$properties));
        return $properties;
    }

    function getCSVOptions() {
        $this->onExecuteBefore('getCSVOptions');

        $encoding = $this->getState('encoding');
        if($encoding == 'cp1251') setLocale(LC_ALL, 'ru_RU.CP1251');

        $f = fopen(JPATH_COMPONENT  . DS . 'tmp' . DS . 'import.csv', "rt") or die("Ошибка!");
        $data = fgetcsv($f, 10000, ";");
        fclose($f);
        $options = '<option value=""></option>';
        for($k = 0; $k < count($data); $k++)
            if($data[$k] != '') $options .= '<option value="' . $k . '">' . $this->encode($data[$k]) . '</option>';

        $this->onExecuteAfter('getCSVOptions', array(&$options));
        return $options;
    }

    function uploadImportCSVFile() {
        $this->onExecuteBefore('uploadImportCSVFile');

        if(!isset($_FILES['csvfile'])) return false;
        if($_FILES['csvfile']['tmp_name'] == '') return false;
        if(substr($_FILES['csvfile']['name'], strlen($_FILES['csvfile']['name']) - 4, 4) != '.csv') return false;
        if(file_exists(JPATH_COMPONENT . DS . 'tmp' . DS . 'import.csv')) unlink(JPATH_COMPONENT . DS . 'tmp' . DS . 'import.csv');
        copy($_FILES['csvfile']['tmp_name'], JPATH_COMPONENT . DS . 'tmp' . DS . 'import.csv');

        $this->onExecuteAfter('uploadImportCSVFile');
        return true;
    }

    function getImportInfo() {
        $this->onExecuteBefore('getImportInfo');

        $encoding = $this->getState('encoding');
        if($encoding == 'cp1251') setLocale(LC_ALL, 'ru_RU.CP1251');

        if($_FILES['photos_zip']['tmp_name'] != '') {
            $import_dir = JPATH_ROOT . DS . 'media' . DS . 'com_ksenmart' . DS . 'import' . DS ;
            JFolder::delete($import_dir);
            JFolder::create($import_dir, 0777);
            copy($_FILES['photos_zip']['tmp_name'], $import_dir . 'import.zip');
            $result = JArchive::extract(JPath::clean($import_dir . 'import.zip'), JPath::clean($import_dir));
        }
        $unic = JRequest::getVar('unic');
        $f = fopen(JPATH_COMPONENT . DS . 'tmp' . DS . 'import.csv', "rt") or die("Ошибка!");
        $info = array('insert' => '', 'update' => '');
        $relatives = array();
        for($k = 0; $data = fgetcsv($f, 10000, ";"); $k++) {
            if($k == 0) {
                $headers = $data;
                continue;
            }
            $product_data = array();
            if($k > 0) {
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
                if(isset($_POST['promotion']) && $_POST['promotion'] != '') $product_data['promotion'] = $this->encode($data[$_POST['promotion']]);
                if(isset($_POST['manufacturer']) && $_POST['manufacturer'] != '') $product_data['manufacturer'] = $this->encode($data[$_POST['manufacturer']]);
                if(isset($_POST['country']) && $_POST['country'] != '') $product_data['country'] = $this->encode($data[$_POST['country']]);
                if(isset($_POST['content']) && $_POST['content'] != '') $product_data['content'] = $this->encode($data[$_POST['content']]);
                if(isset($_POST['photos']) && $_POST['photos'] != '') $product_data['photos'] = $this->encode($data[$_POST['photos']]);
                if(isset($_POST['relative']) && $_POST['relative'] != '') $product_data['relative'] = $this->encode($data[$_POST['relative']]);
				if(isset($_POST['tags']) && $_POST['tags'] != '') $product_data['tags'] = $this->encode($data[$_POST['tags']]);
                if(isset($_POST['metatitle']) && $_POST['metatitle'] != '') $product_data['metatitle'] = $this->encode($data[$_POST['metatitle']]);
                else  $product_data['metatitle'] = '';
                if(isset($_POST['metadescription']) && $_POST['metadescription'] != '') $product_data['metadescription'] = $this->encode($data[$_POST['metadescription']]);
                else  $product_data['metadescription'] = '';
                if(isset($_POST['metakeywords']) && $_POST['metakeywords'] != '') $product_data['metakeywords'] = $this->encode($data[$_POST['metakeywords']]);
                else  $product_data['metakeywords'] = '';

                $product_data['type'] = 'product';
                $query = $this->_db->getQuery(true);
                $query->select('*')->from('#__ksenmart_properties')->order('ordering');
                $this->_db->setQuery($query);
                $properties = $this->_db->loadObjectList();
                foreach($properties as $property)
                    if(isset($_POST['property_' . $property->id]) && $_POST['property_' . $property->id] != '') $product_data['property_' . $property->id] = $this->encode($data[$_POST['property_' . $property->id]]);

                $query = $this->_db->getQuery(true);
                $query->select('id')->from('#__ksenmart_currencies')->where('`default`=1');
                $this->_db->setQuery($query);
                $def_price_type = $this->_db->loadResult();

                $query = $this->_db->getQuery(true);
                $query->select('id')->from('#__ksenmart_product_units');
                $this->_db->setQuery($query, 0, 1);
                $def_unit = $this->_db->loadResult();

                if(isset($product_data['parent_id']) && $product_data['parent_id'] != '') {
                    $query = $this->_db->getQuery(true);
                    $query->select('id')->from('#__ksenmart_products')->where($unic . '=' . $this->_db->quote($product_data['parent_id']))->where('parent_id=0');
                    $this->_db->setQuery($query);
                    $parent_id = $this->_db->loadResult();
                    if(empty($parent_id)) $product_data['parent_id'] = 0;
                    else {
                        $product_data['type'] = 'child';
                        $product_data['parent_id'] = $parent_id;
                        $query = $this->_db->getQuery(true);
                        $query->update('#__ksenmart_products')->set('is_parent=1')->where('id=' . $parent_id);
                        $this->_db->setQuery($query);
                        $this->_db->query();
                    }
                } else  $product_data['parent_id'] = 0;

                if(isset($product_data['childs_group']) && $product_data['childs_group'] != '' && $product_data['parent_id'] != 0) {
                    $query = $this->_db->getQuery(true);
                    $query->select('*')->from('#__ksenmart_products_child_groups')->where('title like ' . $this->_db->quote($product_data['childs_group']))->where('product_id=' . $product_data['parent_id']);
                    $this->_db->setQuery($query);
                    $childs_group = $this->_db->loadObject();
                    if(count($childs_group) == 0) {
                        $qvalues = array($this->_db->quote($product_data['childs_group']), $product_data['parent_id']);
                        $query = $this->_db->getQuery(true);
                        $query->insert('#__ksenmart_products_child_groups')->columns('title,product_id')->values(implode(',', $qvalues));
                        $this->_db->setQuery($query);
                        $this->_db->query();
                        $childs_group_id = $this->_db->insertid();
                        $product_data['childs_group'] = $childs_group_id;
                    } else  $product_data['childs_group'] = $childs_group->id;
                } else  $product_data['childs_group'] = 0;

                if(isset($product_data['price_type']) && $product_data['price_type'] != '') {
                    $query = $this->_db->getQuery(true);
                    $query->select('*')->from('#__ksenmart_currencies')->where('title=' . $this->_db->quote($product_data['price_type']));
                    $this->_db->setQuery($query);
                    $price_type = $this->_db->loadObject();
                    if(count($price_type) == 0) $product_data['price_type'] = $def_price_type;
                    else  $product_data['price_type'] = $price_type->id;
                } else  $product_data['price_type'] = $def_price_type;

                if(isset($product_data['product_unit']) && $product_data['product_unit'] != '') {
                    $query = $this->_db->getQuery(true);
                    $query->select('*')->from('#__ksenmart_product_units')->where('form1=' . $this->_db->quote($product_data['product_unit']));
                    $this->_db->setQuery($query);
                    $unit = $this->_db->loadObject();
                    if(count($unit) == 0) {
                        $qvalues = array(
                            $this->_db->quote($product_data['product_unit']),
                            $this->_db->quote($product_data['product_unit']),
                            $this->_db->quote($product_data['product_unit']));
                        $query = $this->_db->getQuery(true);
                        $query->insert('#__ksenmart_product_units')->columns('form1,form2,form5')->values(implode(',', $qvalues));
                        $this->_db->setQuery($query);
                        $this->_db->query();
                        $unit_id = $this->_db->insertid();
                        $product_data['product_unit'] = $unit_id;
                    } else  $product_data['product_unit'] = $unit->id;
                } else  $product_data['product_unit'] = $def_unit;

                if(isset($product_data['promotion_price']) && $product_data['promotion_price'] != '') {
                    $product_data['promotion'] = 1;
                    $product_data['old_price'] = $product_data['price'];
                    $product_data['price'] = $product_data['promotion_price'];
                } else {
                    $product_data['promotion'] = 0;
                    $product_data['old_price'] = 0;
                }

                if(isset($product_data['country']) && $product_data['country'] != '') {
                    $query = $this->_db->getQuery(true);
                    $query->select('*')->from('#__ksenmart_countries')->where('title=' . $this->_db->quote($product_data['country']));
                    $this->_db->setQuery($query);
                    $country = $this->_db->loadObject();
                    if(count($country) == 0) {
                        $alias = KSFunctions::GenAlias($product_data['country']);
                        $qvalues = array(
                            $this->_db->quote($product_data['country']),
                            $this->_db->quote($alias),
                            1,
                            $this->_db->quote($product_data['country']));
                        $query = $this->_db->getQuery(true);
                        $query->insert('#__ksenmart_countries')->columns('title,alias,published,metatitle')->values(implode(',', $qvalues));
                        $this->_db->setQuery($query);
                        $this->_db->query();
                        $country_id = $this->_db->insertid();
                        $product_data['country'] = $country_id;
                    } else  $product_data['country'] = $country->id;
                } else  $product_data['country'] = 0;

                if(isset($product_data['manufacturer']) && $product_data['manufacturer'] != '') {
                    $query = $this->_db->getQuery(true);
                    $query->select('*')->from('#__ksenmart_manufacturers')->where('title=' . $this->_db->quote($product_data['manufacturer']));
                    $this->_db->setQuery($query);
                    $manufacturer = $this->_db->loadObject();
                    if(count($manufacturer) == 0) {
                        $alias = KSFunctions::GenAlias($product_data['manufacturer']);
                        $qvalues = array(
                            $this->_db->quote($product_data['manufacturer']),
                            $this->_db->quote($alias),
                            $this->_db->quote($product_data['country']),
                            1,
                            $this->_db->quote($product_data['manufacturer']));
                        $query = $this->_db->getQuery(true);
                        $query->insert('#__ksenmart_manufacturers')->columns('title,alias,country,published,metatitle')->values(implode(',', $qvalues));
                        $this->_db->setQuery($query);
                        $this->_db->query();
                        $manufacturer_id = $this->_db->insertid();
                        $product_data['manufacturer'] = $manufacturer_id;
                    } else  $product_data['manufacturer'] = $manufacturer->id;
                } else  $product_data['manufacturer'] = 0;

                $categories = explode(';', $product_data['categories']);
                $prd_cats = array();
                foreach($categories as $cats) {
                    $parent = 0;
                    $prd_cat = 0;
                    $cats = explode(':', $cats);
                    foreach($cats as $cat) {
                        $cat = trim($cat);
                        if($cat != '') {
                            $query = $this->_db->getQuery(true);
                            $query->select('*')->from('#__ksenmart_categories')->where('title=' . $this->_db->quote($cat))->where('parent_id=' . $parent);
                            $this->_db->setQuery($query);
                            $category = $this->_db->loadObject();
                            if(!$category) {
                                $alias = KSFunctions::GenAlias($cat);
                                $qvalues = array(
                                    $this->_db->quote($cat),
                                    $this->_db->quote($alias),
                                    $parent,
                                    1);
                                $query = $this->_db->getQuery(true);
                                $query->insert('#__ksenmart_categories')->columns('title,alias,parent_id,published')->values(implode(',', $qvalues));
                                $this->_db->setQuery($query);
                                $this->_db->query();
                                $prd_cat = $this->_db->insertid();
                                $parent = $prd_cat;
                            } else {
                                $prd_cat = $category->id;
                                $parent = $prd_cat;
                            }
                            $prd_cats[] = $prd_cat;
                        }
                    }
                }
                if($product_data['parent_id'] != 0) {
                    $prd_cats = array();
                    $query = $this->_db->getQuery(true);
                    $query->select('*')->from('#__ksenmart_products_categories')->where('product_id=' . $product_data['parent_id']);
                    $this->_db->setQuery($query);
                    $cats = $this->_db->loadObjectList();
                    foreach($cats as $cat) $prd_cats[] = $cat->category_id;
                }

                if(!isset($product_data['price'])) $product_data['price'] = 0;
                if(!isset($product_data['product_code'])) $product_data['product_code'] = '';
                if(!isset($product_data['product_packaging'])) $product_data['product_packaging'] = 1;
                if(!isset($product_data['in_stock'])) $product_data['in_stock'] = 1;
                if(!isset($product_data['content'])) $product_data['content'] = '';

                if($unic != '') {
                    $query = $this->_db->getQuery(true);
                    $query->select('*')->from('#__ksenmart_products')->where($unic . '=' . $this->_db->quote($product_data[$unic]))->where('parent_id=' . $product_data['parent_id']);
                    $this->_db->setQuery($query);
                    $product = $this->_db->loadObjectList();
                }

                if(count($product) == 0) {
                    $alias = KSFunctions::GenAlias($product_data['title']);
                    $values = array(
                        'parent_id' => $product_data['parent_id'],
                        'childs_group' => $product_data['childs_group'],
                        'title' => $this->_db->quote($product_data['title']),
                        'alias' => $this->_db->quote($alias),
                        'price' => $this->_db->quote($product_data['price']),
                        'old_price' => $this->_db->quote($product_data['old_price']),
                        'price_type' => $product_data['price_type'],
                        'in_stock' => $product_data['in_stock'],
                        'product_code' => $this->_db->quote($product_data['product_code']),
                        'product_packaging' => $product_data['product_packaging'],
                        'product_unit' => $product_data['product_unit'],
                        'content' => $this->_db->quote($product_data['content']),
                        'promotion' => $product_data['promotion'],
                        'manufacturer' => $product_data['manufacturer'],
                        'published' => 1,
                        'metatitle' => $this->_db->quote($product_data['metatitle']),
                        'metadescription' => $this->_db->quote($product_data['metadescription']),
                        'metakeywords' => $this->_db->quote($product_data['metakeywords']),
                        'date_added' => 'NOW()',
                        'type' => $this->_db->quote($product_data['type']));

                    $query = $this->_db->getQuery(true);
                    $query->update('#__ksenmart_products')->set('ordering=ordering+1');
                    $this->_db->setQuery($query);
                    $this->_db->query();
                    $query = $this->_db->getQuery(true);
                    $query->insert('#__ksenmart_products')->columns(implode(',', array_keys($values)))->values(implode(',', $values));
                    $this->_db->setQuery($query);
                    $this->_db->query();
                    $product_id = $this->_db->insertid();

                    $is_default = true;
                    foreach($prd_cats as $prd_cat) {
                        $qvalues = array(
                            $product_id,
                            $prd_cat,
                            (int)$is_default);
                        $query = $this->_db->getQuery(true);
                        $query->insert('#__ksenmart_products_categories')->columns('product_id,category_id,is_default')->values(implode(',', $qvalues));
                        $this->_db->setQuery($query);
                        $this->_db->query();
                        $is_default = false;
                    }

                    $info['insert']++;
                } else {
                    $product_id = $product[0]->id;

                    $to_update = array();
                    $to_update[] = 'date_added=NOW()';
                    if(isset($product_data['title'])) $to_update[] = 'title=' . $this->_db->quote($product_data['title']);
                    if(isset($product_data['product_code'])) $to_update[] = 'product_code=' . $this->_db->quote($product_data['product_code']);
                    if(isset($product_data['in_stock'])) $to_update[] = 'in_stock=' . $this->_db->quote($product_data['in_stock']);
                    if(isset($product_data['content'])) $to_update[] = 'content=' . $this->_db->quote($product_data['content']);
                    if(isset($product_data['introcontent'])) $to_update[] = 'introcontent=' . $this->_db->quote($product_data['introcontent']);
                    if(isset($product_data['product_packaging'])) $to_update[] = 'product_packaging=' . $this->_db->quote($product_data['product_packaging']);
                    if($product_data['metatitle'] != '') $to_update[] = 'metatitle=' . $this->_db->quote($product_data['metatitle']);
                    if($product_data['metadescription'] != '') $to_update[] = 'metadescription=' . $this->_db->quote($product_data['metadescription']);
                    if($product_data['metakeywords'] != '') $to_update[] = 'metakeywords=' . $this->_db->quote($product_data['metakeywords']);
                    if(isset($product_data['price'])) $to_update[] = 'price=' . $this->_db->quote($product_data['price']);
                    if(isset($product_data['manufacturer'])) $to_update[] = 'manufacturer=' . $this->_db->quote($product_data['manufacturer']);
                    if(isset($product_data['price_type'])) $to_update[] = 'price_type=' . $this->_db->quote($product_data['price_type']);
                    if(isset($product_data['product_unit'])) $to_update[] = 'product_unit=' . $this->_db->quote($product_data['product_unit']);
                    if(isset($product_data['old_price'])) $to_update[] = 'old_price=' . $this->_db->quote($product_data['old_price']);
                    if(isset($product_data['promotion'])) $to_update[] = 'promotion=' . $this->_db->quote($product_data['promotion']);


                    foreach($prd_cats as $prd_cat) {
                        $query = $this->_db->getQuery(true);
                        $query->select('*')->from('#__ksenmart_products_categories')->where('product_id=' . $product_id)->where('category_id=' . $prd_cat);
                        $this->_db->setQuery($query);
                        $db_cat = $this->_db->loadObject();
                        if(count($db_cat) == 0) {
                            $qvalues = array($product_id, $prd_cat);
                            $query = $this->_db->getQuery(true);
                            $query->insert('#__ksenmart_products_categories')->columns('product_id,category_id')->values(implode(',', $qvalues));
                            $this->_db->setQuery($query);
                            $this->_db->query();
                        }
                    }

                    $query = $this->_db->getQuery(true);
                    $query->update('#__ksenmart_products')->set($to_update)->where('id=' . $product_id);
                    $this->_db->setQuery($query);
                    $this->_db->query();

                    if(isset($product_data['photos']) && $product_data['photos'] != '') {
                        $query = $this->_db->getQuery(true);
                        $query->select('*')->from('#__ksenmart_files')->where(array(
                            "media_type='image'",
                            "owner_type='product'",
                            "owner_id=" . $product_id))->order('ordering');
                        $this->_db->setQuery($query);
                        $images = $this->_db->loadObjectList('id');
                        $i = count($images);
                        foreach($images as $image) $this->delPhoto($image->filename, $image->folder);
                    }

                    $info['update']++;
                }

                if(isset($product_data['relative']) && $product_data['relative'] != '') $relatives[$product_id] = $product_data['relative'];
				
				if(isset($product_data['tags']) && $product_data['tags'] != '') {
					$product_data['tags'] = explode(',', $product_data['tags']);
					foreach($product_data['tags'] as $key=>$value)
					{
						$value = trim($value);
                        $query = $this->_db->getQuery(true);
                        $query->select('id')->from('#__tags')->where('title='.$this->_db->quote($value));
                        $this->_db->setQuery($query);
                        $tag_id = $this->_db->loadResult();	
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
                    $product_data['photos'] = explode(',', $product_data['photos']);
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

                foreach($properties as $property) {
                    if(isset($product_data['property_' . $property->id]) && $product_data['property_' . $property->id] != '') {
                        switch($property->type) {
                            case 'text':
                                if($product_data['property_' . $property->id] != '') {
                                    $query = $this->_db->getQuery(true);
                                    $query->select('*')->from('#__ksenmart_property_values')->where('property_id=' . $property->id)->where('title=' . $this->_db->quote($product_data['property_' . $property->id]));
                                    $this->_db->setQuery($query);
                                    $prop_value = $this->_db->loadObject();
                                    if(count($prop_value) == 0) {
                                        $alias = KSFunctions::GenAlias($product_data['property_' . $property->id]);
                                        $qvalues = array(
                                            $property->id,
                                            $this->_db->quote($product_data['property_' . $property->id]),
                                            $this->_db->quote($alias));
                                        $query = $this->_db->getQuery(true);
                                        $query->insert('#__ksenmart_property_values')->columns('property_id,title,alias')->values(implode(',', $qvalues));
                                        $this->_db->setQuery($query);
                                        $this->_db->query();
                                        $prop_value_id = $this->_db->insertid();
                                    } else  $prop_value_id = $prop_value->id;
									$query = $this->_db->getQuery(true);
                                    $query->select('*')->from('#__ksenmart_product_properties_values')->where('product_id=' . $product_id)->where('property_id=' . $property->id)->where('value_id=' . $prop_value_id);
                                    $this->_db->setQuery($query);
                                    $prod_prop_value = $this->_db->loadObject();
                                    if(count($prod_prop_value) == 0) {
                                        $qvalues = array(
                                            $product_id,
                                            $property->id,
                                            $prop_value_id,
                                            $this->_db->quote($product_data['property_' . $property->id]));
                                        $query = $this->_db->getQuery(true);
                                        $query->insert('#__ksenmart_product_properties_values')->columns('product_id,property_id,value_id,text')->values(implode(',', $qvalues));
                                        $this->_db->setQuery($query);
                                        $this->_db->query();
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
                                        $query = $this->_db->getQuery(true);
                                        $query->select('*')->from('#__ksenmart_property_values')->where('property_id=' . $property->id)->where('title=' . $this->_db->quote($prop_val));
                                        $this->_db->setQuery($query);
                                        $prop_value = $this->_db->loadObject();
                                        if(count($prop_value) == 0) {
                                            $alias = KSFunctions::GenAlias($prop_val);
                                            $qvalues = array(
                                                $property->id,
                                                $this->_db->quote($prop_val),
                                                $this->_db->quote($alias));
                                            $query = $this->_db->getQuery(true);
                                            $query->insert('#__ksenmart_property_values')->columns('property_id,title,alias')->values(implode(',', $qvalues));
                                            $this->_db->setQuery($query);
                                            $this->_db->query();
                                            $prop_value_id = $this->_db->insertid();
                                        } else  $prop_value_id = $prop_value->id;
										$query = $this->_db->getQuery(true);
                                        $query->select('*')->from('#__ksenmart_product_properties_values')->where('product_id=' . $product_id)->where('property_id=' . $property->id)->where('value_id=' . $prop_value_id);
                                        $this->_db->setQuery($query);
                                        $prod_prop_value = $this->_db->loadObject();
                                        if(count($prod_prop_value) == 0) {
                                            $qvalues = array(
                                                $product_id,
                                                $property->id,
                                                $prop_value_id,
												$this->_db->quote($val_price));
                                            $query = $this->_db->getQuery(true);
                                            $query->insert('#__ksenmart_product_properties_values')->columns('product_id,property_id,value_id,price')->values(implode(',', $qvalues));
                                            $this->_db->setQuery($query);
                                            $this->_db->query();
                                        } else {
											$query = $this->_db->getQuery(true);
											$query->update('#__ksenmart_product_properties_values')->set('price=' . $this->_db->quote($val_price))->where('id=' . $prod_prop_value->id);
											$this->_db->setQuery($query);
                                            $this->_db->query();
										}
                                    }
                                }
                        }

                        foreach($prd_cats as $prd_cat) {
                            $query = $this->_db->getQuery(true);
                            $query->select('id')->from('#__ksenmart_product_categories_properties')->where('category_id=' . $prd_cat)->where('property_id=' . $property->id);
                            $this->_db->setQuery($query);
                            $res = $this->_db->loadResult();
                            if(empty($res)) {
                                $qvalues = array($prd_cat, $property->id);
                                $query = $this->_db->getQuery(true);
                                $query->insert('#__ksenmart_product_categories_properties')->columns('category_id,property_id')->values(implode(',', $qvalues));
                                $this->_db->setQuery($query);
                                $this->_db->query();
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
                    $query = $this->_db->getQuery(true);
                    $query->select('id')->from('#__ksenmart_products')->where('title like ' . $this->_db->quote($relative_title));
                    $this->_db->setQuery($query);
                    $relative_id = $this->_db->loadResult();
                    if(!empty($relative_id)) {
                        $query = $this->_db->getQuery(true);
                        $query->select('*')->from('#__ksenmart_products_relations')->where('product_id=' . $product_id)->where('relative_id=' . $relative_id)->where('relation_type=' . $this->_db->quote('relation'));
                        $this->_db->setQuery($query);
                        $db_rel = $this->_db->loadObject();
                        if(count($db_rel) == 0) {
                            $qvalues = array(
                                $product_id,
                                $relative_id,
                                $this->_db->quote('relation'));
                            $query = $this->_db->getQuery(true);
                            $query->insert('#__ksenmart_products_relations')->columns('product_id,relative_id,relation_type')->values(implode(',', $qvalues));
                            $this->_db->setQuery($query);
                            $this->_db->query();
                        }
                    }
                }
            }
        }

        fclose($f);
        $dir = scandir(JPATH_COMPONENT . DS . 'tmp' . DS );
        foreach($dir as $d)
            if($d != '.' && $d != '..') unlink(JPATH_COMPONENT . DS . 'tmp' . DS  . $d);

        $this->onExecuteAfter('getImportInfo', array(&$info));
        return $info;
    }

    function encode($string) {
        $this->onExecuteBefore('encode', array(&$string));

        $encoding = $this->getState('encoding');
        if($encoding == 'cp1251') $string = trim(iconv('WINDOWS-1251', 'UTF-8', $string));

        $this->onExecuteAfter('encode', array(&$string));
        return $string;
    }

    function saveYandexmarket($data) {
        $this->onExecuteBefore('saveYandexmarket', array(&$data));

        $categories = isset($data['categories']) ? json_encode($data['categories']) : '{}';
        $shopname = $data['shopname'];
        $company = $data['company'];

        $query = $this->_db->getQuery(true);
        $query->update('#__ksenmart_yandeximport')->set('value=' . $this->_db->quote($categories))->where('setting=' . $this->_db->quote('categories'));
        $this->_db->setQuery($query);
        $this->_db->query();

        $query = $this->_db->getQuery(true);
        $query->update('#__ksenmart_yandeximport')->set('value=' . $this->_db->quote($shopname))->where('setting=' . $this->_db->quote('shopname'));
        $this->_db->setQuery($query);
        $this->_db->query();

        $query = $this->_db->getQuery(true);
        $query->update('#__ksenmart_yandeximport')->set('value=' . $this->_db->quote($company))->where('setting=' . $this->_db->quote('company'));
        $this->_db->setQuery($query);
        $this->_db->query();

        $this->onExecuteAfter('saveYandexmarket', array(&$data));
    }

    function delPhoto($filename, $folder) {
        $this->onExecuteBefore('delPhoto', array(&$filename, &$folder));

        $files = scandir(JPATH_ROOT . DS . 'media' . DS . 'com_ksenmart' . DS . 'images' . DS . $folder);
        foreach($files as $file) {
            if($file != '.' && $file != '..' && is_dir(JPATH_ROOT . DS . 'media' . DS . 'com_ksenmart' . DS . 'images' . DS .  $folder .  DS . $file))
                if(file_exists(JPATH_ROOT  . DS . 'media' . DS . 'com_ksenmart' . DS . 'images' . DS  . $folder  . DS .  $file . DS . $filename)) unlink(JPATH_ROOT . DS . 'media' . DS . 'com_ksenmart' . DS . 'images' . DS  . $folder . DS . $file . DS . $filename);
        }
        $query = $this->_db->getQuery(true);
        $query->delete('#__ksenmart_files');
        $where = array("filename='$filename'");
        $query->where($where);
        $this->_db->setQuery($query);
        $this->_db->query();

        $this->onExecuteAfter('delPhoto', array(&$filename, &$folder));
        return true;
    }

    function delProduct($product_id) {
        $this->onExecuteBefore('delProduct', array(&$product_id));

        $query = $this->_db->getQuery(true);
        $query->delete('#__ksenmart_product_properties_values');
        $query->where("product_id='$product_id'");
        $this->_db->setQuery($query);
        $this->_db->query();
        $query = $this->_db->getQuery(true);
        $query->delete('#__ksenmart_products_categories');
        $query->where("product_id='$product_id'");
        $this->_db->setQuery($query);
        $this->_db->query();
        $query = $this->_db->getQuery(true);
        $query->delete('#__ksenmart_products_child_groups');
        $query->where("product_id='$product_id'");
        $this->_db->setQuery($query);
        $this->_db->query();
        $query = $this->_db->getQuery(true);
        $query->delete('#__ksenmart_products_relations');
        $query->where("product_id='$product_id'");
        $this->_db->setQuery($query);
        $this->_db->query();
        $query = $this->_db->getQuery(true);
        $query->select('*')->from('#__ksenmart_files')->where(array(
            "media_type='image'",
            "owner_type='product'",
            "owner_id=" . $product_id))->order('ordering');
        $this->_db->setQuery($query);
        $images = $this->_db->loadObjectList('id');
        foreach($images as $image) $this->delPhoto($image->filename, $image->folder);
        $query = $this->_db->getQuery(true);
        $query->delete('#__ksenmart_products');
        $query->where("id='$product_id'");
        $this->_db->setQuery($query);
        $this->_db->query();
        $query = $this->_db->getQuery(true);
        $query->select('id')->from('#__ksenmart_products')->where('parent_id=' . $product_id);
        $this->_db->setQuery($query);
        $childs = $this->_db->loadObjectList();
        foreach($childs as $child) $this->delProduct($child->id);

        $this->onExecuteAfter('delProduct', array(&$product_id));
    }

    function getYMFormData() {
        $this->onExecuteBefore('getYMFormData');

        $query = $this->_db->getQuery(true);
        $query->select('*')->from('#__ksenmart_yandeximport');
        $this->_db->setQuery($query);
        $settings = $this->_db->loadObjectList('setting');

        $data = new stdClass();
        $data->categories = json_decode($settings['categories']->value, true);
        $data->shopname = $settings['shopname']->value;
        $data->company = $settings['company']->value;

        $this->onExecuteBefore('getYMFormData', array(&$data));
        return $data;
    }
	
	function getExportCSV($data){
		$categories = isset($data['categories']) ? $data['categories'] : array();
		$header = array(
			JText::_('ksm_exportimport_product_name'),
			JText::_('ksm_exportimport_product_parent'),
			JText::_('ksm_exportimport_product_category'),
			JText::_('ksm_exportimport_product_childs_group'),
			JText::_('ksm_exportimport_product_price'),
			JText::_('ksm_exportimport_product_promotion_price'),
			JText::_('ksm_exportimport_product_currency'),
			JText::_('ksm_exportimport_product_code'),
			JText::_('ksm_exportimport_product_unit'),
			JText::_('ksm_exportimport_product_packaging'),
			JText::_('ksm_exportimport_product_in_stock'),
			JText::_('ksm_exportimport_product_promotion'),
			JText::_('ksm_exportimport_product_manufacturer'),
			JText::_('ksm_exportimport_product_country'),
			JText::_('ksm_exportimport_product_relative'),
			JText::_('ksm_exportimport_product_photos')
		);
		$cats_tree = array();
        $query = $this->_db->getQuery(true);
        $query->select('id,title,parent_id')->from('#__ksenmart_categories');
        $this->_db->setQuery($query);
        $cats = $this->_db->loadObjectList();
		foreach($cats as $cat){
			$cat_tree = array($cat->title);
			$parent = $cat->parent_id;
			while ($parent != 0) {
				$query = $this->_db->getQuery(true);
				$query->select('title,parent_id')->from('#__ksenmart_categories')->where('id=' . $parent);
				$this->_db->setQuery($query);
				$category = $this->_db->loadObject();
				$cat_tree[] = $category->title;
				$parent = $category->parent_id;
			}
			$cat_tree = array_reverse($cat_tree);
			$cats_tree[$cat->id] = implode(':', $cat_tree);
		}
        $query = $this->_db->getQuery(true);
        $query->select('id,title')->from('#__ksenmart_properties')->where('published = 1');
        $this->_db->setQuery($query);
        $properties = $this->_db->loadObjectList();
		foreach($properties as $property){
			$header[] = $property->title;
		}
		$f = fopen(JPATH_COMPONENT.'/tmp/export.csv', 'w');
		fputcsv($f, $header, ';');
		
        $query = $this->_db->getQuery(true);
        $query->select('p.*,m.title as manufacturer_title,c.title as country_title')->from('#__ksenmart_products as p')->order('p.ordering');
		$query->leftjoin('#__ksenmart_manufacturers as m on m.id = p.manufacturer');
		$query->leftjoin('#__ksenmart_countries as c on c.id = m.country');
		$query->select('(select title from #__ksenmart_products where id = p.parent_id) as parent_title');
		$query->select('(select title from #__ksenmart_currencies where id = p.price_type) as currency_title');
		$query->select('(select form1 from #__ksenmart_product_units where id = p.product_unit) as unit_title');
        if(count($categories) > 0) {
            $query->innerjoin('#__ksenmart_products_categories as pc on pc.product_id=p.id');
            $query->where('pc.category_id in (' . implode(', ', $categories) . ')');
        }
		$query->where('p.type!='.$this->_db->quote('set'));
        $query->group('p.id');	
		$this->_db->setQuery($query);
		$products = $this->_db->loadObjectList();
		foreach($products as $product){
			$cats = array();
			$rels = '';
			$photos = '';
			
			if($product->promotion && $product->old_price != 0) {
				$product->promotion_price = $product->price;
				$product->price = $product->old_price;
			} else {
				$product->promotion_price = 0;
			}
			
			$query = $this->_db->getQuery(true);
			$query->select('pc.category_id')->from('#__ksenmart_products_categories as pc')->where('pc.product_id='.$product->id);
			$this->_db->setQuery($query);
			$prd_cats = $this->_db->loadColumn();	
			foreach($prd_cats as $prd_cat)
				$cats[] = $cats_tree[$prd_cat];
			$cats = implode(';', $cats);
			
			$query = $this->_db->getQuery(true);
			$query->select('p.title')->from('#__ksenmart_products_relations as pr')
			->leftjoin('#__ksenmart_products as p on p.id=pr.relative_id')
			->where('pr.product_id='.$product->id)->where('pr.relation_type='.$this->_db->quote('relation'));
			$this->_db->setQuery($query);
			$rels = $this->_db->loadColumn();
			$rels = implode(';', $rels);
			
			$query = $this->_db->getQuery(true);
			$query->select('f.filename')->from('#__ksenmart_files as f')
			->where('f.owner_id='.$product->id)->where('f.owner_type='.$this->_db->quote('product'));
			$this->_db->setQuery($query);
			$photos = $this->_db->loadColumn();
			$photos = implode(';', $photos);			
			
			$arr = array(
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
				$product->manufacturer_title,
				$product->country_title,
				$rels,
				$photos
			);
			foreach($properties as $property){
				$props = array();
				$query = $this->_db->getQuery(true);
				$query->select('pv.title,ppv.price')->from('#__ksenmart_product_properties_values as ppv')
				->leftjoin('#__ksenmart_property_values as pv on pv.id=ppv.value_id')
				->where('ppv.product_id='.$product->id)->where('ppv.property_id='.$property->id);
				$this->_db->setQuery($query);
				$prd_props = $this->_db->loadObjectList();
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
		$contents = file_get_contents(JPATH_COMPONENT.'/tmp/export.csv'); 
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename="export.csv"');
		echo $contents;		
	}
}
