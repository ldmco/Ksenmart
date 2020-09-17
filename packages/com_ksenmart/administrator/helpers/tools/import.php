<?php

/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

KSSystem::import('helpers.corehelper');

class KSMImport extends KSCoreHelper
{

	private static $_products = array();
	private static $_columns = array();
	private static $_manufacturers = array();
	private static $_countries = array();
	private static $_currencies = array();
	private static $_units = array();
	private static $_categories = array();
	private static $_properties = array();

	/**
	 * Сохраняет товар в БД
	 *
	 * @param       $product
	 * @param array $params
	 *
	 * @return bool|mixed|void
	 *
	 * @since version 4.1.6
	 */
	public static function saveProduct($product, $params = array())
	{
		if (empty($params['uniq'])) $params['uniq'] = 'title';
		if (empty($product[$params['uniq']])) return false;

		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('id')
			->from('#__ksenmart_products')
			->where($db->qn($params['uniq']) . '=' . $db->q($product['title']));
		$db->setQuery($query);
		$pid = $db->loadResult();

		if (empty($pid))
		{
			$pid = self::addProduct($product, $params);
		}
		else
		{
			$pid = self::updateProduct($product, $pid, $params);
		}

		return $pid;
	}

	/**
	 * Добавляет новый товар
	 *
	 * @param       $product
	 * @param array $params
	 *
	 * @return mixed
	 *
	 * @since version 4.1.6
	 */
	public static function addProduct($product, $params = array())
	{
		if (empty($product['title'])) return false;

		$values  = array();
		$columns = self::getColumns();
		$db      = JFactory::getDbo();

		self::setParent($product, $params);
		if (!empty($product['country']) && !is_numeric($product['country']))
		{
			$product['country'] = self::getCountry($product['country']);
		}
		if (empty($product['country'])) $product['country'] = 0;

		if (!empty($product['manufacturer']) && !is_numeric($product['manufacturer']))
		{
			$product['manufacturer'] = self::getManufacturer($product['manufacturer'], $product['country']);
		}

		if (empty($product['price_type'])) $product['price_type'] = '';
		if (!is_numeric($product['price_type']))
		{
			$product['price_type'] = self::getCurrency($product['price_type']);
		}

		if (empty($product['product_unit'])) $product['product_unit'] = '';
		if (!is_numeric($product['product_unit']))
		{
			$product['product_unit'] = self::getUnit($product['product_unit']);
		}

		$categories = array();
		if (!empty($product['parent_id']))
		{
			$query = $db->getQuery(true);
			$query->select('id')
				->from('#__ksenmart_products_categories')
				->where('product_id=' . (int) $product['parent_id']);
			$db->setQuery($query);
			$categories = $db->loadColumn();
		}
		if (empty($categories) && !empty($product['categories']))
		{
			$categories = self::addCategories($product['categories']);
		}

		if (empty($product['type'])) $product['type'] = 'product';
		if (!empty($product['set'])) $product['type'] = 'set';
		$product['alias']     = KSFunctions::GenAlias($product['title']);
		$product['published'] = 1;

		foreach ($columns as $key => $column)
		{
			if (!isset($product[$key])) continue;
			$product[$key] = trim($product[$key]);

			switch ($column)
			{
				case 'int':
					$product[$key] = str_replace(' ', '', $product[$key]);
					$values[$key]  = (int) $product[$key];
					break;
				case 'float':
					$product[$key] = str_replace(' ', '', $product[$key]);
					$values[$key]  = $db->q((float) $product[$key]);
					break;
				default:
					$values[$key] = $db->q($product[$key]);
			}
		}
		$values['date_added'] = 'NOW()';

		$query = $db->getQuery(true);
		$query->update('#__ksenmart_products')->set('ordering=ordering+1');
		$db->setQuery($query);
		$db->execute();

		$query = $db->getQuery(true);
		$query->insert('#__ksenmart_products')
			->columns(implode(',', array_keys($values)))
			->values(implode(',', $values));
		$db->setQuery($query);
		$db->execute();
		$product_id = $db->insertid();

		if (!empty($categories)) self::addProductCategories($categories, $product_id);
		//if (!empty($product['tags'])) self::addTags($product['tags'], $product_id);
		if (!empty($product['photos'])) self::addPhotos($product['photos'], $product_id);
		if (!empty($product['properties'])) self::addProperties($product['properties'], $product_id, $categories, $params);
		if (!empty($product['relatives'])) self::addRelatives($product['relatives'], $product_id);

		return $product_id;
	}

	/**
	 * Обновляет информацию о товаре
	 *
	 * @param       $product
	 * @param int   $pid
	 * @param array $params
	 *
	 * @return int
	 *
	 * @since version 4.1.6
	 */
	public static function updateProduct($product, $pid = 0, $params = array())
	{
		if (empty($pid)) return false;
		if (empty($params['category'])) $params['category'] = 'new';
		if (empty($params['photos'])) $params['photos'] = 'new';

		$updates = array();
		$columns = self::getColumns();
		$db      = JFactory::getDbo();

		self::setParent($product, $params);
		if (!empty($product['country']) && !is_numeric($product['country']))
		{
			$product['country'] = self::getCountry($product['country']);
		}
		if (empty($product['country'])) $product['country'] = 0;

		if (!empty($product['manufacturer']) && !is_numeric($product['manufacturer']))
		{
			$product['manufacturer'] = self::getManufacturer($product['manufacturer'], $product['country']);
		}

		if (empty($product['price_type'])) $product['price_type'] = '';
		if (!is_numeric($product['price_type']))
		{
			$product['price_type'] = self::getCurrency($product['price_type']);
		}

		if (empty($product['product_unit'])) $product['product_unit'] = '';
		if (!is_numeric($product['product_unit']))
		{
			$product['product_unit'] = self::getUnit($product['product_unit']);
		}

		$categories = array();
		if (!empty($product['parent_id']))
		{
			$query = $db->getQuery(true);
			$query->select('id')
				->from('#__ksenmart_products_categories')
				->where('product_id=' . (int) $product['parent_id']);
			$db->setQuery($query);
			$categories = $db->loadColumn();
		}
		elseif (!empty($product['categories']))
		{
			$categories = self::addCategories($product['categories']);
		}

		if ($params['category'] == 'delete') self::deleteProductCategories($pid);
		if ($params['category'] == 'new' && !empty($categories)) self::deleteProductCategories($pid);

		foreach ($columns as $key => $column)
		{
			if (!isset($product[$key])) continue;
			$product[$key] = trim($product[$key]);

			switch ($column)
			{
				case 'int':
					$product[$key] = str_replace(' ', '', $product[$key]);
					$updates[]     = $db->qn($key) . '=' . (int) $product[$key];
					break;
				case 'float':
					$product[$key] = str_replace(' ', '', $product[$key]);
					$updates[]     = $db->qn($key) . '=' . (float) $product[$key];
					break;
				default:
					$updates[] = $db->qn($key) . '=' . $db->q($product[$key]);
			}
		}

		$query = $db->getQuery(true);
		$query->update('#__ksenmart_products')->set(implode(',', $updates))->where('id=' . (int) $pid);
		$db->setQuery($query);
		$db->execute();

		if ($params['photos'] == 'delete') self::deletePhotos($pid);
		if ($params['photos'] == 'new' && !empty($product['photos'])) self::deletePhotos($pid);

		if (!empty($categories)) self::addProductCategories($categories, $pid);
		if (!empty($product['tags'])) self::addTags($product['tags'], $pid);
		if (!empty($product['photos'])) self::addPhotos($product['photos'], $pid);
		if (!empty($product['properties'])) self::addProperties($product['properties'], $pid, $categories, $params);
		if (!empty($product['relatives'])) self::addRelatives($product['relatives'], $pid);

		return $pid;
	}

	/**
	 * Удаляет фотографии у товара
	 *
	 * @param int $pid
	 *
	 *
	 * @since version 4.1.6
	 */
	public static function deletePhotos($pid = 0)
	{
		if (empty($pid)) return;

		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')
			->from('#__ksenmart_files')
			->where(array(
				"media_type='image'",
				"owner_type='product'",
				"owner_id=" . (int) $pid));
		$db->setQuery($query);
		$images = $db->loadObjectList();
		foreach ($images as $image)
		{
			$files = scandir(JPATH_ROOT . DS . 'media' . DS . 'com_ksenmart' . DS . 'images' . DS . $image->folder);
			foreach ($files as $file)
			{
				if ($file != '.' && $file != '..' && is_dir(JPATH_ROOT . DS . 'media' . DS . 'com_ksenmart' . DS . 'images' . DS . $image->folder . DS . $file))
					if (file_exists(JPATH_ROOT . DS . 'media' . DS . 'com_ksenmart' . DS . 'images' . DS . $image->folder . DS . $file . DS . $image->filename))
						unlink(JPATH_ROOT . DS . 'media' . DS . 'com_ksenmart' . DS . 'images' . DS . $image->folder . DS . $file . DS . $image->filename);
			}

			$query = $db->getQuery(true);
			$query->delete('#__ksenmart_files')->where('id=' . (int) $image->id);
			$db->setQuery($query);
			$db->execute();
		}

		return;
	}

	/**
	 * Удаляет категории у товара
	 *
	 * @param int $pid
	 *
	 *
	 * @since version 4.1.6
	 */
	public static function deleteProductCategories($pid = 0)
	{
		if (empty($pid)) return;

		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->delete('#__ksenmart_products_categories')->where('id=' . (int) $pid);
		$db->setQuery($query);
		$db->execute();

		return;
	}

	/**
	 * Добавляет сопутствующие товары для товара
	 *
	 * @param       $relatives
	 * @param int   $pid
	 * @param array $params
	 *
	 *
	 * @since version 4.1.6
	 */
	public static function addRelatives($relatives, $pid = 0, $params = array())
	{
		if (empty($relatives) || empty($pid)) return;

		if (is_string($relatives)) $relatives = explode(';', $relatives);
		$db = JFactory::getDbo();
		foreach ($relatives as $relative)
		{
			$relative = trim($relative);
			if (empty($relative)) continue;

			if (!is_numeric($relative))
			{
				$query = $db->getQuery(true);
				$query->select('id')
					->from('#__ksenmart_products')
					->where($params['uniq'] . ' like ' . $db->quote($relative));
				$db->setQuery($query);
				$relative_id = $db->loadResult();
			}

			if (empty($relative_id)) continue;

			$query = $db->getQuery(true);
			$query->select('id')
				->from('#__ksenmart_products_relations')
				->where('product_id=' . (int) $pid)
				->where('relative_id=' . (int) $relative_id)
				->where('relation_type=' . $db->quote('relation'));
			$db->setQuery($query);
			$db_rel = $db->loadResult();
			if (empty($db_rel))
			{
				$qvalues = array(
					'product_id'    => (int) $pid,
					'relative_id'   => (int) $relative_id,
					'relation_type' => $db->quote('relation'));
				$query   = $db->getQuery(true);
				$query->insert('#__ksenmart_products_relations')
					->columns(implode(',', array_keys($qvalues)))
					->values(implode(',', $qvalues));
				$db->setQuery($query);
				$db->execute();
			}
		}
	}

	/**
	 * Добавляет свойства товарам
	 *
	 * @param       $product_properties
	 * @param int   $pid
	 * @param array $categories
	 *
	 *
	 * @since version 4.1.6
	 */
	public static function addProperties($product_properties, $pid = 0, $categories = array(), $params)
	{
		if (empty($pid) || empty($product_properties)) return;
		if (empty($params['properties'])) $params['properties'] = 'new';

		$db = JFactory::getDbo();

		$properties  = self::getProperties();
		$select_flag = false;
		foreach ($product_properties as $key => $property)
		{
			if (empty($property)) continue;
			if (!empty($property['title']))
			{
				$title = $property['title'];
			}
			else
			{
				$title = $key;
			}
			$title = trim($title);
			$type  = 'text';
			if (strpos($title, '_SELECT'))
			{
				$title = str_replace('_SELECT', '', $title);
				$type  = 'select';
			}
			if (empty($title) || !is_string($title)) continue;

			if (isset($property['values']))
			{
				$values = $property['values'];
			}
			else
			{
				$values = $property;
			}
			if (empty($values)) continue;

			if (empty($properties[$title]))
			{
				$alias   = KSFunctions::GenAlias($title);
				$qvalues = array(
					'title'     => $title,
					'alias'     => $alias,
					'type'      => $type,
					'published' => 1
				);
				JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR . DS . 'com_ksenmart' . DS . 'tables');
				$table = JTable::getInstance('properties', 'KsenmartTable');
				$table->bindCheckStore($qvalues);
				$property_id = $table->id;

				$new_property        = new stdClass();
				$new_property->id    = $property_id;
				$new_property->title = $title;
				$new_property->alias = $alias;
				$new_property->type  = $type;

				$properties[$title]        = $new_property;
				self::$_properties[$title] = $new_property;
			}

			$in = array();
			switch ($properties[$title]->type)
			{
				case 'text':
					if (!is_string($values) || (is_array($values) && count($values) > 1)) continue;
					if (is_array($values)) $values = current($values);
					$values = trim($values);
					$in[]   = self::setPropertyValue($pid, $properties[$title]->id, $values);
					break;
				default:
					if (is_string($values)) $values = explode(';', $values);

					$count_val = 0;
					if (!count($values)) continue;
					foreach ($values as $value)
					{
						$value = trim($value);
						if (empty($value)) continue;

						$val_parts = explode('=', $value);
						if (count($val_parts) == 2)
						{
							$value     = $val_parts[0];
							$val_price = $val_parts[1];
						}
						else
						{
							$val_price = '';
						}
						$in[] = self::setPropertyValue($pid, $properties[$title]->id, $value, 'update', $val_price);

						$count_val++;
					}
					if ($count_val > 1)
					{
						$select_flag = true;
					}
			}
			if ($params['properties'] == 'delete' || ($params['properties'] == 'new' && !empty($in)))
			{
				$query = $db->getQuery(true);
				$query->delete('#__ksenmart_product_properties_values')
					->where('product_id=' . (int) $pid)
					->where('property_id=' . (int) $properties[$title]->id);
				if (!empty($in)) $query->where('id NOT IN (' . implode(',', $in) . ')');
				$db->setQuery($query);
				$db->execute();
			}
			foreach ($categories as $category)
			{
				$query = $db->getQuery(true);
				$query->select('id')
					->from('#__ksenmart_product_categories_properties')
					->where('category_id=' . (int) $category)
					->where('property_id=' . (int) $properties[$title]->id);
				$db->setQuery($query);
				$res = $db->loadResult();
				if (empty($res))
				{
					$qvalues = array((int) $category, (int) $properties[$title]->id);
					$query   = $db->getQuery(true);
					$query->insert('#__ksenmart_product_categories_properties')
						->columns('category_id,property_id')
						->values(implode(',', $qvalues));
					$db->setQuery($query);
					$db->execute();
				}
			}
		}

		if ($select_flag)
		{
			$query = $db->getQuery(true);
			$query->update('#__ksenmart_products')
				->set('select_properties=1')
				->where('id=' . (int) $pid);
			$db->setQuery($query);
			$db->execute();
		}
	}

	private static function setPropertyValue($pid = 0, $property_id = 0, $title = '', $flag = 'new', $price = '')
	{
		if (empty($pid) || empty($property_id)) return 0;
		$db = JFactory::getDbo();
		if (empty($title))
		{
			if ($flag == 'delete')
			{
				$query = $db->getQuery(true);
				$query->delete('#__ksenmart_product_properties_values')
					->where('product_id=' . (int) $pid)
					->where('property_id=' . (int) $property_id);
				$db->setQuery($query);
				$db->execute();
			}

			return 0;
		}

		$query = $db->getQuery(true);
		$query->select('id')
			->from('#__ksenmart_property_values')
			->where('property_id=' . (int) $property_id)
			->where('title=' . $db->quote($title));
		$db->setQuery($query);
		$prop_value_id = $db->loadResult();

		if (empty($prop_value_id))
		{
			$alias   = KSFunctions::GenAlias($title);
			$qvalues = array(
				'property_id' => (int) $property_id,
				'title'       => $db->quote($title),
				'alias'       => $db->quote($alias));
			$query   = $db->getQuery(true);
			$query->insert('#__ksenmart_property_values')
				->columns(implode(',', array_keys($qvalues)))
				->values(implode(',', $qvalues));
			$db->setQuery($query);
			$db->execute();
			$prop_value_id = $db->insertid();
		}

		$query = $db->getQuery(true);
		$query->select('id')
			->from('#__ksenmart_product_properties_values')
			->where('product_id=' . (int) $pid)
			->where('property_id=' . (int) $property_id)
			->where('value_id=' . $prop_value_id);
		$db->setQuery($query);
		$prop_prod_value_id = $db->loadResult();

		if (empty($prop_prod_value_id))
		{
			$qvalues = array(
				'product_id'  => (int) $pid,
				'property_id' => (int) $property_id,
				'value_id'    => $prop_value_id,
				'text'        => $db->quote($title));
			$query   = $db->getQuery(true);
			$query->insert('#__ksenmart_product_properties_values')
				->columns(implode(',', array_keys($qvalues)))
				->values(implode(',', $qvalues));
			$db->setQuery($query);
			$db->execute();

			$prop_prod_value_id = $db->insertid();
		}
		elseif ($flag == 'update')
		{
			$query = $db->getQuery(true);
			$query->update('#__ksenmart_product_properties_values')
				->set('price=' . $db->quote($price))
				->where('id=' . (int) $prop_prod_value_id);
			$db->setQuery($query);
			$db->execute();
		}

		return $prop_prod_value_id;
	}

	/**
	 * Добавляет товару фотографии
	 *
	 * @param     $photos
	 * @param int $pid
	 *
	 *
	 * @since version 4.1.6
	 */
	public static function addPhotos($photos, $pid = 0)
	{
		if (empty($pid) || empty($photos)) return;

		if (is_string($photos)) $photos = explode(';', $photos);
		$db = JFactory::getDbo();
		$i  = 1;
		foreach ($photos as $photo)
		{
			$photo = trim($photo);
			if (empty($photo)) continue;

			$file      = basename($photo);
			$nameParts = explode('.', $file);
			$file      = microtime(true) . '.' . $nameParts[count($nameParts) - 1];
			$copied    = false;

			if (strpos($photo, 'http://') !== false)
			{
				if ($photo_content = @file_get_contents($photo))
				{
					if (file_put_contents(JPATH_ROOT . DS . 'media' . DS . 'com_ksenmart' . DS . 'images' . DS . 'products' . DS . 'original' . DS . $file, $photo_content))
					{
						$copied = true;
					}
				}
			}
			else
			{
				if (file_exists(JPATH_ROOT . DS . 'media' . DS . 'com_ksenmart' . DS . 'import' . DS . $photo))
				{
					if (copy(JPATH_ROOT . DS . 'media' . DS . 'com_ksenmart' . DS . 'import' . DS . $photo, JPATH_ROOT . DS . 'media' . DS . 'com_ksenmart' . DS . 'images' . DS . 'products' . DS . 'original' . DS . $file))
					{
						$copied = true;
					}
				}
			}
			if ($copied)
			{
				$mime    = mime_content_type(JPATH_ROOT . DS . 'media' . DS . 'com_ksenmart' . DS . 'images' . DS . 'products' . DS . 'original' . DS . $file);
				$qvalues = array(
					'owner_id'   => (int) $pid,
					'media_type' => $db->quote('image'),
					'owner_type' => $db->quote('product'),
					'folder'     => $db->quote('products'),
					'filename'   => $db->quote($file),
					'mime_type'  => $db->quote($mime),
					'title'      => $db->quote(''),
					'ordering'   => $i);
				$query   = $db->getQuery(true);
				$query->insert('#__ksenmart_files')
					->columns(implode(',', array_keys($qvalues)))
					->values(implode(',', $qvalues));
				$db->setQuery($query);
				$db->execute();
				$i++;
			}
		}
	}

	/**
	 * Добавляет товару тэги
	 *
	 * @param     $tags
	 * @param int $pid
	 *
	 *
	 * @since version 4.1.6
	 */
	public static function addTags($tags, $pid = 0)
	{
		if (empty($pid) || empty($tags)) return;

		if (is_string($tags)) $tags = explode(';', $tags);
		$db           = JFactory::getDbo();
		$product_tags = array();
		foreach ($tags as $key => $value)
		{
			$value = trim($value);
			if (empty($value)) continue;

			$query = $db->getQuery(true);
			$query->select('id')->from('#__tags')->where('title=' . $db->quote($value));
			$db->setQuery($query);
			$tag_id = $db->loadResult();
			if (!empty($tag_id))
				$product_tags[$key] = $tag_id;
			else
				$product_tags[$key] = '#new#' . $value;
		}

		$tableProducts = self::getTable('Products');
		JObserverMapper::attachAllObservers($tableProducts);
		JObserverMapper::addObserverClassToClass('JTableObserverTags', 'KsenmartTableProducts', array('typeAlias' => 'com_ksenmart.product'));
		$tableProducts->load($pid);
		$tagsObserver = $tableProducts->getObserverOfClass('JTableObserverTags');
		$tagsObserver->setNewTags($product_tags, true);

		return;
	}

	/**
	 * Добавляет товару привязку к категориям
	 *
	 * @param array $categories массив ID категорий
	 * @param int   $pid        ID товара
	 *
	 * @since version 4.1.6
	 */
	public static function addProductCategories($categories = array(), $pid = 0)
	{
		if (empty($pid) || empty($categories)) return;

		$is_default = true;
		$db         = JFactory::getDbo();
		foreach ($categories as $category)
		{
			$qvalues = array(
				'product_id'  => (int) $pid,
				'category_id' => (int) $category,
				'is_default'  => (int) $is_default);
			$query   = $db->getQuery(true);
			$query->insert('#__ksenmart_products_categories')
				->columns(implode(',', array_keys($qvalues)))
				->values(implode(',', $qvalues));
			$db->setQuery($query);
			$db->execute();
			$is_default = false;
		}

		return;
	}

	/**
	 * Добавляет категории в БД и возвращает массив ID категорий
	 *
	 * @param mixed $data Список категорий
	 *
	 * @return array
	 *
	 * @since version 4.1.6
	 */
	public static function addCategories($data)
	{
		if (empty($data)) return array();

		$categories = array();
		if (is_array($data))
		{
			$categories = self::addCategoriesArray($data);
		}
		if (is_string($data))
		{
			$categories = self::addCategoriesString($data);
		}
		if (is_object($data))
		{
			$categories = self::addCategoriesObject($data);
		}

		return $categories;
	}

	/**
	 * Добавляет категории из массива в БД и возвращает массив ID категорий
	 *
	 * @param array $data      Список категорий
	 * @param int   $parent_id ID родительской категории
	 *
	 * @return array
	 * @since version 4.1.6
	 */
	private static function addCategoriesArray($data = array(), $parent_id = 0)
	{
		if (empty($data)) return array();

		$categories = array();
		foreach ($data as $category)
		{
			if (empty($category['title'])) continue;

			$category['title'] = trim($category['title']);
			if (empty($category['parent_id'])) $category['parent_id'] = $parent_id;
			if (empty(self::$_categories[$category['parent_id']][$category['title']]))
			{
				$db    = JFactory::getDbo();
				$query = $db->getQuery(true);
				$query->select('id')
					->from('#__ksenmart_categories')
					->where('title=' . $db->quote($category['title']))
					->where('parent_id=' . (int) $category['parent_id']);
				$db->setQuery($query);
				self::$_categories[$category['parent_id']][$category['title']] = $db->loadResult();

				if (empty(self::$_categories[$category['parent_id']][$category['title']]))
				{
					$alias   = KSFunctions::GenAlias($category['title']);
					$qvalues = array(
						'title'     => $db->quote($category['title']),
						'alias'     => $db->quote($alias),
						'parent_id' => (int) $category['parent_id'],
						'published' => 1);

					$query = $db->getQuery(true);
					$query->insert('#__ksenmart_categories')
						->columns(implode(',', array_keys($qvalues)))
						->values(implode(',', $qvalues));
					$db->setQuery($query);
					$db->execute();
					self::$_categories[$category['parent_id']][$category['title']] = $db->insertid();
				}
			}

			$categories[] = self::$_categories[$category['parent_id']][$category['title']];

			if (!empty($category['childs']))
			{
				$childs     = self::addCategoriesArray($category['childs'], self::$_categories[$category['parent_id']][$category['title']]);
				$categories = array_merge($categories, $childs);
				$categories = array_unique($categories);
			}
		}

		return $categories;

	}

	/**
	 * Добавляет категории из строки в БД и возвращает массив ID категорий
	 *
	 * @param string $data Список категорий
	 *
	 * @return array
	 * @since version 4.1.6
	 */
	private static function addCategoriesString($data = '')
	{
		if (empty($data)) return array();

		$categories = explode(';', $data);
		$db         = JFactory::getDbo();
		$prd_cats   = array();
		foreach ($categories as $cats)
		{
			$cats = trim($cats);
			if (empty($cats)) continue;

			$parent_id = 0;
			$cats      = explode(':', $cats);
			foreach ($cats as $cat)
			{
				$cat = trim($cat);
				if (empty($cat)) continue;
				if (empty(self::$_categories[$parent_id][$cat]))
				{
					$query = $db->getQuery(true);
					$query->select('id')
						->from('#__ksenmart_categories')
						->where('title=' . $db->quote($cat))
						->where('parent_id=' . (int) $parent_id);
					$db->setQuery($query);
					self::$_categories[$parent_id][$cat] = $db->loadResult();

					if (empty(self::$_categories[$parent_id][$cat]))
					{
						$alias   = KSFunctions::GenAlias($cat);
						$qvalues = array(
							'title'     => $db->quote($cat),
							'alias'     => $db->quote($alias),
							'parent_id' => (int) $parent_id,
							'published' => 1);

						$query = $db->getQuery(true);
						$query->insert('#__ksenmart_categories')
							->columns(implode(',', array_keys($qvalues)))
							->values(implode(',', $qvalues));
						$db->setQuery($query);
						$db->execute();
						self::$_categories[$parent_id][$cat] = $db->insertid();
					}
				}

				$prd_cats[] = $parent_id = self::$_categories[$parent_id][$cat];
			}
		}

		return $prd_cats;
	}

	private static function addCategoriesObject($data)
	{
		if (empty($data)) return array();

		return array();
	}

	/**
	 * Задаёт родителя товару и дочерние группы
	 *
	 * @param $product
	 * @param $params
	 *
	 *
	 * @since version 4.1.6
	 */
	private static function setParent(&$product, $params)
	{
		if (empty($product['parent'])) return;

		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('id')
			->from('#__ksenmart_products')
			->where($db->qn($params['uniq']) . '=' . $db->quote($product['parent']))->where('parent_id=0');
		$db->setQuery($query);
		$parent_id = $db->loadResult();

		if (empty($parent_id))
		{
			$product['parent_id'] = 0;
		}
		else
		{
			$product['type']      = 'child';
			$product['parent_id'] = $parent_id;
			$query                = $db->getQuery(true);
			$query->update('#__ksenmart_products')->set('is_parent=1')->where('id=' . $parent_id);
			$db->setQuery($query);
			$db->execute();
		}

		if (!empty($product['childs_group']) && $product['parent_id'] != 0)
		{
			$query = $db->getQuery(true);
			$query->select('id')
				->from('#__ksenmart_products_child_groups')
				->where('title like ' . $db->quote($product['childs_group']))
				->where('product_id=' . $product['parent_id']);
			$db->setQuery($query);
			$childs_group_id = $db->loadResult();

			if (empty($childs_group_id))
			{
				$qvalues = array($db->quote($product['childs_group']), (int) $product['parent_id']);
				$query   = $db->getQuery(true);
				$query->insert('#__ksenmart_products_child_groups')->columns('title,product_id')->values(implode(',', $qvalues));
				$db->setQuery($query);
				$db->execute();
				$childs_group_id         = $db->insertid();
				$product['childs_group'] = $childs_group_id;
			}
			else
			{
				$product['childs_group'] = $childs_group_id;
			}
		}
	}

	/**
	 * Возвращает ID страны и добавляет если такой нет
	 *
	 * @param string $title
	 *
	 * @return bool|mixed
	 *
	 * @since version 4.1.6
	 */
	public static function getCountry($title = '')
	{
		if (empty($title)) return false;
		$title = trim($title);
		if (!empty(self::$_countries[$title])) return self::$_countries[$title];

		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('id')->from('#__ksenmart_countries')->where('title=' . $db->q($title));
		$db->setQuery($query);
		$id = $db->loadResult();

		if (empty($id))
		{
			$alias  = KSFunctions::GenAlias($title);
			$values = array(
				'title'     => $db->q($title),
				'alias'     => $db->q($alias),
				'published' => 1
			);
			$query  = $db->getQuery(true);
			$query->insert('#__ksenmart_countries')
				->columns(implode(',', array_keys($values)))
				->values(implode(',', $values));
			$db->setQuery($query);
			$db->execute();

			$id = $db->insertid();
		}
		self::$_countries[$title] = $id;

		return $id;
	}

	/**
	 * Возвращает ID производителя и добавляет нового если такого не существует
	 *
	 * @param string $title
	 * @param int    $country
	 *
	 * @return bool|mixed
	 *
	 * @since version 4.1.6
	 */
	public static function getManufacturer($title = '', $country = 0)
	{
		if (empty($title)) return false;
		$title = trim($title);
		if (!empty(self::$_manufacturers[$title])) return self::$_manufacturers[$title];

		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('id')->from('#__ksenmart_manufacturers')->where('title=' . $db->q($title));
		$db->setQuery($query);
		$id = $db->loadResult();

		if (empty($id))
		{
			$alias  = KSFunctions::GenAlias($title);
			$values = array(
				'title'     => $db->q($title),
				'alias'     => $db->q($alias),
				'country'   => (int) $country,
				'published' => 1
			);
			$query  = $db->getQuery(true);
			$query->insert('#__ksenmart_manufacturers')
				->columns(implode(',', array_keys($values)))
				->values(implode(',', $values));
			$db->setQuery($query);
			$db->execute();

			$id = $db->insertid();
		}
		self::$_manufacturers[$title] = $id;

		return $id;
	}

	/**
	 * Возвращает ID валюты по коду валюты
	 *
	 * @param string $code
	 *
	 * @return int
	 *
	 * @since version 4.1.6
	 */
	public static function getCurrency($code = '')
	{
		if (empty($code)) return self::getDefaultCurrency();

		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('id')->from('#__ksenmart_currencies')->where('code=' . $db->q($code));
		$db->setQuery($query);
		self::$_currencies[$code] = $db->loadResult();
		if (empty(self::$_currencies[$code])) self::$_currencies[$code] = self::getDefaultCurrency();

		return self::$_currencies[$code];
	}

	/**
	 * Возвращает ID валюты по умолчанию
	 *
	 * @return mixed
	 *
	 * @since version 4.1.6
	 */
	public static function getDefaultCurrency()
	{
		if (empty(self::$_currencies['default']))
		{
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('id')->from('#__ksenmart_currencies')->where('`default`=1');
			$db->setQuery($query);
			self::$_currencies['default'] = $db->loadResult();
		}

		return self::$_currencies['default'];
	}

	/**
	 * Возвращает ID типа юнита по названию
	 *
	 * @param string $code
	 *
	 * @return string
	 *
	 * @since version 4.1.6
	 */
	public static function getUnit($code = '')
	{
		if (empty($code)) return '';

		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('id')->from('#__ksenmart_product_units')->where('form1=' . $db->q($code));
		$db->setQuery($query);
		self::$_units[$code] = $db->loadResult();

		if (empty(self::$_units[$code]))
		{
			$qvalues = array(
				'form1' => $db->q($code),
				'form2' => $db->q($code),
				'form5' => $db->q($code));
			$query   = $db->getQuery(true);
			$query->insert('#__ksenmart_product_units')
				->columns(implode(',', array_keys($qvalues)))
				->values(implode(',', $qvalues));
			$db->setQuery($query);
			$db->execute();
			self::$_units[$code] = $db->insertid();
		}

		return self::$_units[$code];
	}

	/**
	 * Возвращает массив свойств товаров
	 *
	 * @return array
	 *
	 * @since version 4.1.6
	 */
	private static function getProperties()
	{
		if (!empty(self::$_properties)) return self::$_properties;

		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')->from('#__ksenmart_properties');
		$db->setQuery($query);

		self::$_properties = $db->loadObjectList('title');

		return self::$_properties;
	}

	/**
	 * Возвращает объект jTable указанной таблицы
	 *
	 * @param $table
	 *
	 * @return bool|JTable
	 *
	 * @since version 4.1.6
	 */
	public static function getTable($table)
	{
		JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_ksenmart/tables');
		$table = JTable::getInstance($table, 'KsenmartTable', array());

		return $table;
	}

	/**
	 * Возвращает ассоциативный массив полей товара из таблицы
	 *
	 * @return array
	 *
	 * @since version 4.1.6
	 */
	public static function getColumns()
	{
		if (empty(self::$_columns))
		{
			$db             = JFactory::getDbo();
			self::$_columns = $db->getTableColumns('#__ksenmart_products');
		}

		return self::$_columns;
	}

}
