<?php

/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

KSSystem::import('helpers.corehelper');
KSLoader::loadClass('product', 'com_ksenmart');

class KSMProducts extends KSCoreHelper
{

	private static $_products = [];
	private static $config = null;

	/**
	 * Возвращает внутренние настройки Ksenmart
	 *
	 * @return null|object
	 *
	 * @since version
	 */
	private static function getConfig()
	{
		if (empty(self::$config))
		{
			$path = JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_ksenmart' . DS . 'config.php';

			if (file_exists($path))
			{
				require_once($path);

				self::$config = new KMConfig();
			}
			else
			{
				self::$config = (object) [
					'catalogEagers' => [
						'files',
						'manufacturers',
						'units',
					],
				];
			}
		}

		return self::$config;
	}

	/**
	 * Возвращает объект товара
	 *
	 * @param $id
	 *
	 * @return mixed
	 *
	 * @since version
	 */
	public static function getProduct($id)
	{
		self::onExecuteBefore(array(&$id));

		if (isset(self::$_products[$id])) return self::$_products[$id];
		global $ext_name;
		$old_ext_name = $ext_name;
		$ext_name     = 'ksenmart';

		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query = KSMedia::setItemMainImageToQuery($query);
		$query
			->select('`p`.*, pc.category_id')
			->select($db->qn(array(
				'm.title',
				'u.form1',
			), array(
				'manufacturer_title',
				'unit',
			)))
			->from($db->qn('#__ksenmart_products', 'p'))
			->leftjoin($db->qn('#__ksenmart_products_categories', 'pc') . ' ON ' . $db->qn('p.id') . '=' . $db->qn('pc.product_id'))
			->leftjoin($db->qn('#__ksenmart_manufacturers', 'm') . ' ON ' . $db->qn('p.manufacturer') . '=' . $db->qn('m.id'))
			->leftjoin($db->qn('#__ksenmart_product_units', 'u') . ' ON ' . $db->qn('p.product_unit') . '=' . $db->qn('u.id'))
			->where($db->qn('pc.is_default') . '=' . $db->q('1'))
			->where($db->qn('p.id') . '=' . $db->q($id))
			->group($db->qn('p.id'));
		$db->setQuery($query);
		$row = $db->loadObject('KSMProduct');

		self::prepareProduct($row);
		$ext_name = $old_ext_name;

		self::onExecuteAfter(array(&$row));
		self::$_products[$id] = $row;

		return $row;
	}

	/**
	 * Обновляет объект товара
	 *
	 * @param $product
	 *
	 *
	 * @since version
	 */
	public static function setProduct($product)
	{
		if (!is_object($product) || !empty($product->id)) return;

		self::$_products[$product->id] = $product;
	}

	/**
	 * Возвращает коллекцию товаров
	 *
	 * @param array $products
	 *
	 * @return array
	 *
	 * @since version
	 */
	public static function getProducts($products = array())
	{
		self::onExecuteBefore(array(&$products));

		global $ext_name;
		$old_ext_name = $ext_name;
		$ext_name     = 'ksenmart';
		if (count($products) > 3)
		{
			$ids = $products;
			if (empty($products))
			{
				return $products;
			}
			$self_product = [];
			foreach ($products as $key => $id)
			{
				if (isset(self::$_products[$id]))
				{
					$self_product[$id] = self::$_products[$id];
					unset($products[$key]);
				}
			}

			if (!empty($products))
			{
				$db    = JFactory::getDbo();
				$query = $db->getQuery(true);
				$query->select('*')->from('#__ksenmart_products')->where('id IN (' . implode(',', $products) . ')');
				$db->setQuery($query);
				$rows = $db->loadObjectList('id', 'KSMProduct');

				$eagers = self::getEagers($rows, $products);
			}

			$products = [];
			foreach ($ids as $id)
			{
				JEventDispatcher::getInstance()->trigger('onBeforeExecuteHelperKSMProductsGetProduct', array(&$id));
				$product = null;
				if (isset($self_product[$id]))
				{
					$product = $self_product[$id];
				}
				else
				{
					if (empty($rows[$id]))
					{
						continue;
					}

					$product = $rows[$id];
					foreach ($eagers as $key => $eager)
					{
						switch ($key)
						{
							case 'files':
								if (isset($eager[$id]))
								{
									$product->filename = $eager[$id]->filename;
									$product->folder   = $eager[$id]->folder;
									$product->params   = $eager[$id]->params;
								}
								break;
							case 'manufacturers':
								$product->manufacturer_title = isset($eager[$product->manufacturer]) ? $eager[$product->manufacturer]->title : '';
								break;
							case 'units':
								$product->unit = isset($eager[$product->product_unit]->form1) ? $eager[$product->product_unit]->form1 : '';
								break;
							default:
								$product->{$key} = isset($eager[$id]) ? $eager[$id] : [];
						}
					}

					self::prepareProduct($product);
					JEventDispatcher::getInstance()->trigger('onAfterExecuteHelperKSMProductsGetProduct', array(&$product));
					self::$_products[$id] = $product;
				}
				$products[] = $product;
			}
		}
		else
		{
			foreach ($products as &$product)
			{
				if (is_object($product))
				{
					$product = self::getProduct($product->id);
				}
				else
				{
					$product = self::getProduct($product);
				}
			}
			unset($product);
		}
		$ext_name = $old_ext_name;

		self::onExecuteAfter(array(&$products));

		return $products;
	}

	/**
	 * Возвращает "жадные" данные
	 *
	 * @param $products
	 * @param $ids
	 *
	 * @return array
	 *
	 * @since version
	 */
	private static function getEagers($products, $ids)
	{
		if (empty($products))
		{
			return [];
		}

		$config = self::getConfig();
		$eagers = $manufacturers = $units = [];

		foreach ($products as $product)
		{
			$manufacturers[] = $product->manufacturer;
			$units[]         = $product->product_unit;
		}
		$manufacturers = array_unique($manufacturers);
		$units         = array_unique($units);

		foreach ($config->catalogEagers as $catalogEager)
		{
			switch ($catalogEager)
			{
				case 'files':
					$eagers['files'] = self::getFiles($ids);
					break;
				case 'manufacturers':
					$eagers['manufacturers'] = self::getManufacturers($manufacturers);
					break;
				case 'properties':
					$eagers['properties'] = self::getProductsProperties($ids);
					break;
				case 'units':
					$eagers['units'] = self::getUnits($units);
					break;
			}
		}

		return $eagers;
	}

	/**
	 * Подготавливает объект товара
	 *
	 * @param $product
	 *
	 * @return mixed
	 *
	 * @since version
	 */
	private static function prepareProduct(&$product)
	{
		if (empty($product)) return $product;
		$params               = JComponentHelper::getParams('com_ksenmart');
		$product->catalog_buy = ($params->get('only_auth_buy', 0) == 0 || ($params->get('only_auth_buy', 0) != 0 && JFactory::getUser()->id != 0)) && !empty($product->price) && $product->is_parent == 0 && !$params->get('catalog_mode', 0);
		if ($product->select_properties) $product->catalog_buy = false;

		if ($product->product_packaging == 0)
		{
			$product->product_packaging = 1;
		}
		$product->product_packaging = rtrim(rtrim($product->product_packaging, '0'), '.');

		if ($product->parent_id > 0)
		{
			$product->parent = self::getProduct($product->parent_id);
			if (empty($product->folder))
			{
				$product->folder = $product->parent->folder;
			}
			if (empty($product->filename))
			{
				$product->filename = $product->parent->filename;
			}
		}

		if (empty($product->folder))
		{
			$product->folder = 'products';
		}
		if (empty($product->filename))
		{
			$product->filename = 'no.jpg';
		}

		self::productPricesTransform($product);
		$product->link           = JRoute::_('index.php?option=com_ksenmart&view=product&id=' . $product->id . ':' . $product->alias . '&parent_id=' . $product->parent_id . '&Itemid=' . KSSystem::getShopItemid($product->category_id));
		$product->mini_small_img = KSMedia::resizeImage($product->filename, $product->folder, $params->get('mini_thumb_width'), $params->get('mini_thumb_height'), json_decode($product->params, true));
		$product->small_img      = KSMedia::resizeImage($product->filename, $product->folder, $params->get('thumb_width'), $params->get('thumb_height'), json_decode($product->params, true));
		$product->img            = KSMedia::resizeImage($product->filename, $product->folder, $params->get('middle_width'), $params->get('middle_height'), json_decode($product->params, true));
		$product->img_link       = KSMedia::resizeImage($product->filename, $product->folder, $params->get('full_width', 900), $params->get('full_height', 900), array('flex' => 1));
		$product->add_link_cart  = KSFunctions::getAddToCartLink();

		return $product;
	}

	public static function productPricesTransform(&$product)
	{
		$product->price              = KSMPrice::getPriceInCurrentCurrency($product->price, $product->price_type);
		$product->val_price          = KSMPrice::showPriceWithTransform($product->price);
		$product->old_price          = KSMPrice::getPriceInCurrentCurrency($product->old_price, $product->price_type);
		$product->val_old_price      = KSMPrice::showPriceWithTransform($product->old_price);
		$product->val_diff_price_wou = $product->old_price - $product->price;
		$product->val_diff_price     = KSMPrice::showPriceWithTransform($product->val_diff_price_wou);
		$product->currency_code      = KSMPrice::getCurrencyCode($product->price_type);

		return $product;
	}

	public static function getLinks($pid = 0, $parent_id = null)
	{
		if ($pid == 0) return false;
		$db = JFactory::getDbo();

		if ($parent_id === null)
		{
			$query = $db->getQuery(true);
			$query
				->select('parent_id')
				->from('#__ksenmart_products')
				->where('id=' . $db->q($pid));
			$db->setQuery($query);
			$parent_id = $db->loadResult();
		}

		if ($parent_id > 0)
		{
			$query = $db->getQuery(true);
			$query
				->select('id')
				->from('#__ksenmart_products')
				->where('parent_id=' . $db->q($parent_id));
			$db->setQuery($query);
			$ids = $db->loadColumn();
		}

		$cid = current(self::getProductCategory($pid));

		$query = $db->getQuery(true);
		$query
			->select('product_id')
			->from('#__ksenmart_products_categories')
			->where('product_id<' . $db->q($pid))
			->order('product_id DESC');
		if (!empty($ids)) $query->where('product_id in (' . implode(',', $ids) . ')');
		if (!empty($cid)) $query->where('category_id=' . $db->q($cid));

		$db->setQuery($query, 0, 1);
		$prev_id = $db->loadResult();

		if (empty($prev_id))
		{
			$query = $db->getQuery(true);
			$query
				->select('MAX(product_id)')
				->from('#__ksenmart_products_categories');
			if (!empty($ids)) $query->where('product_id in (' . implode(',', $ids) . ')');
			if (!empty($cid)) $query->where('category_id=' . $db->q($cid));
			$db->setQuery($query, 0, 1);
			$prev_id = $db->loadResult();
		}
		if (empty($prev_id)) $prev_id = $pid;

		$query = $db->getQuery(true);
		$query
			->select('product_id')
			->from('#__ksenmart_products_categories')
			->where('product_id>' . $db->q($pid))
			->order('product_id ASC');
		if (!empty($ids)) $query->where('product_id in (' . implode(',', $ids) . ')');
		if (!empty($cid)) $query->where('category_id=' . $db->q($cid));
		$db->setQuery($query, 0, 1);
		$next_id = $db->loadResult();

		if (empty($next_id))
		{
			$query = $db->getQuery(true);
			$query
				->select('MIN(product_id)')
				->from('#__ksenmart_products_categories');
			if (!empty($ids)) $query->where('product_id in (' . implode(',', $ids) . ')');
			if (!empty($cid)) $query->where('category_id=' . $db->q($cid));
			$db->setQuery($query, 0, 1);
			$next_id = $db->loadResult();
		}
		if (empty($next_id))
			$next_id = $pid;

		$prev_link = self::generateProductLink($prev_id);
		$next_link = self::generateProductLink($next_id);

		return array($prev_link, $next_link);
	}

	public static function generateProductLink($pid, $alias = '')
	{
		if (!empty($pid) && $pid > 0)
		{
			return JRoute::_('index.php?option=com_ksenmart&view=product&id=' . $pid . ':' . $alias . '&Itemid=' . KSSystem::getShopItemid());
		}

		return null;
	}

	public static function getProductPrices($pid)
	{
		if (!empty($pid) && $pid > 0)
		{
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true);

			$query->select('
                    p.id,
                    p.price,
                    p.old_price,
                    p.price_type
                ')->from('#__ksenmart_products AS p')->where('p.id=' . $db->escape($pid));

			$db->setQuery($query);

			return $db->loadObject();
		}

		return false;
	}

	public static function getProductPriceProperties($pid = 0, $selectedProperties = array(), $prd = null)
	{

		self::onExecuteBefore(array(&$pid, &$selectedProperties, &$prd));

		$productProperties = self::getProperties($pid);
		$prices            = self::getProductPrices($pid);
		$price             = $prices->price;
		$price_type        = $prices->price_type;

		foreach ($productProperties as $property)
		{
			if ($property->edit_price && isset($selectedProperties[$property->property_id]) && is_array($selectedProperties[$property->property_id]))
			{
				foreach ($property->values as $value)
				{
					$flag = false;
					if (isset($selectedProperties[$property->property_id]['value_id']) && $selectedProperties[$property->property_id]['value_id'] == $value->id) $flag = true;
					if (isset($selectedProperties[$property->property_id][$value->id]) &&
						(!isset($selectedProperties[$property->property_id][$value->id]['checked']) ||
							$selectedProperties[$property->property_id][$value->id]['checked'])
					) $flag = true;
					if ($flag)
					{
						$edit_priceC     = $value->price;
						$edit_price_symC = substr($edit_priceC, 0, 1);
						self::getCalcPriceAsProperties($edit_price_symC, $edit_priceC, $price);
					}
				}
			}
		}

		$price = KSMPrice::getPriceInCurrentCurrency($price, $price_type);

		if (!empty($prd))
		{
			$prd->price     = $price;
			$prd->val_price = KSMPrice::showPriceWithTransform($prd->price);
		}
		self::onExecuteAfter(array(&$price, &$prd));

		return $price;
	}

	public static function getProperties($pid = 0, $prid = 0, $val_id = 0, $by = 'ppv.product_id', $by_sort = 0)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('
                ppv.id,
                ppv.property_id,
                ppv.value_id,
                ppv.text,
                p.*
            ')->from('#__ksenmart_properties AS p')->leftJoin('#__ksenmart_product_properties_values AS ppv ON p.id=ppv.property_id');
		if ($pid)
		{
			$query->where('ppv.product_id=' . $pid);
		}

		if ($by_sort)
		{
			switch ($by)
			{
				case 'ppv.id':
					$query->where('ppv.id=' . $by_sort);
					break;
				default:
					$query->where('ppv.product_id=' . $pid);
					break;
			}
		}
		$query->where('p.published=1')->group('ppv.property_id');

		if ($prid)
		{
			$query->where('ppv.property_id=' . $prid);
		}

		$query->order('p.ordering');
		$db->setQuery($query);
		$properties = $db->loadObjectList('property_id');
		$properties = KSMProducts::getPropertiesChild($pid, $properties, $val_id);

		return $properties;
	}

	public static function getPropertiesChild($pid, $properties, $val_id)
	{
		if (!empty($properties))
		{
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true);

			$query->select('
                    pv.*,
                    ppv.property_id,
                    ppv.price,
                    ppv.text
                ')->from('#__ksenmart_property_values AS pv')->leftJoin('#__ksenmart_product_properties_values AS ppv ON ppv.value_id=pv.id');

			$query->order('pv.ordering');
			$query->group('pv.id');

			if ($pid)
			{
				$query->where('ppv.product_id=' . $pid);
			}

			if ($val_id)
			{
				$query->where('pv.id=' . $val_id);
			}

			$db->setQuery($query);
			$values = $db->loadObjectList();

			foreach ($values as $i => $value)
			{
				foreach ($properties as $j => &$property)
				{
					if ($value->property_id == $property->property_id)
					{
						$property->values[$value->id] = $value;
					}
				}
				unset ($property);
			}

			return $properties;
		}

		return $properties;
	}

	public static function getProductRate($id)
	{
		$db          = JFactory::getDbo();
		$rate        = new stdClass();
		$rate->rate  = 0;
		$rate->count = 0;
		$query       = $db->getQuery(true);
		$query->select('c.rate')->from('#__ksenmart_comments AS c')->where('c.product_id=' . $db->escape($id));
		$db->setQuery($query);
		$comments    = $db->loadObjectList();
		$rate->count = count($comments);
		if (!empty($comments))
		{
			foreach ($comments as $comment)
			{
				$rate->rate += $comment->rate;
			}
			$rate->rate = $rate->rate / $rate->count;
		}

		return $rate;
	}

	public static function getProductManufacturer($id)
	{
		$params = JComponentHelper::getParams('com_ksenmart');
		$db     = JFactory::getDbo();
		$query  = $db->getQuery(true);

		$query
			->select($db->qn(array(
				'm.id',
				'm.title',
				'm.alias',
				'm.content',
				'm.introcontent',
				'm.country',
				'm.ordering',
				'm.metatitle',
				'm.metadescription',
				'm.metakeywords',
				'f.filename',
				'f.folder',
				'f.params',
			)))
			->from($db->qn('#__ksenmart_manufacturers', 'm'))
			->leftJoin($db->qn('#__ksenmart_files', 'f') . ' ON ' . $db->qn('m.id') . '=' . $db->qn('f.owner_id') . 'AND' . $db->qn('f.owner_type') . '=' . $db->q('manufacturer'))
			->where($db->qn('m.id') . '=' . $db->q($id))
			->where($db->qn('m.published') . '=' . $db->q('1'));

		$db->setQuery($query);
		$manufacturer = $db->loadObject();

		if (count($manufacturer) > 0)
		{

			$manufacturer->img = KSMedia::resizeImage($manufacturer->filename, $manufacturer->folder, $params->get('manufacturer_width', 240), $params->get('manufacturer_height', 120), json_decode($manufacturer->params, true));

			unset($manufacturer->filename);
			unset($manufacturer->folder);
			unset($manufacturer->params);

			$query = $db->getQuery(true);
			$query
				->select('*')
				->from($db->qn('#__ksenmart_countries', 'c'))
				->where($db->qn('id') . '=' . $db->q($manufacturer->country));;
			$db->setQuery($query);
			$manufacturer->country = $db->loadObject();
		}

		return $manufacturer;
	}

	public static function incProductHit($id)
	{
		$db    = JFactory::getDbo();
		$query = "update #__ksenmart_products set hits=hits+1 where id='$id'";
		$db->setQuery($query);
		$db->execute();
	}

	public static function getPriceWithProperties($product_id, $properties = array(), $price = null)
	{
		$db = JFactory::getDbo();
		if (empty($price))
		{
			$product = KSSystem::loadDbItem($product_id, 'products');
			$price   = KSMPrice::getPriceInCurrentCurrency($product->price, $product->price_type);
		}
		foreach ($properties as $property_id => $values)
		{
			$query = $db->getQuery(true);
			$query->select('edit_price')->from('#__ksenmart_properties');
			$query->where('id=' . $property_id);
			$db->setQuery($query);
			$edit_price = $db->loadResult();
			if ($edit_price == 1)
			{
				foreach ($values as $value_id)
				{
					$query = $db->getQuery(true);
					$query->select('price')->from('#__ksenmart_product_properties_values');
					$query->where('property_id=' . $property_id)->where('value_id=' . $value_id)->where('product_id=' . $product_id);
					$db->setQuery($query);
					$under_price = $db->loadResult();
					if ($under_price && !empty($under_price))
					{
						$under_price_act = substr($under_price, 0, 1);
						switch ($under_price_act)
						{
							case '+':
								$price += substr($under_price, 1, strlen($under_price) - 1);
								break;
							case '-':
								$price -= substr($under_price, 1, strlen($under_price) - 1);
								break;
							case '/':
								$price = $price / substr($under_price, 1, strlen($under_price) - 1);
								break;
							case '*':
								$price = $price * substr($under_price, 1, strlen($under_price) - 1);
								break;
							default:
								$price += $under_price;
						}
					}
				}
			}
		}

		return $price;
	}

	public static function getSetRelated($pid, $info_generate = false)
	{
		$rows = new stdClass;
		if (!empty($pid) && $pid > 0)
		{
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('
                    pr.id,
                    pr.product_id,
                    pr.relative_id,
                    pr.relation_type
                ')->from('#__ksenmart_products_relations AS pr')->where('pr.relation_type="set"')->where('pr.product_id=' . $db->escape($pid));
			$db->setQuery($query);
			$rows = $db->loadObjectList();
			if (!empty($rows))
			{
				if ($info_generate)
				{
					foreach ($rows as & $row)
					{
						$row = KSMProducts::getProduct($row->relative_id);
					}
				}
			}
		}

		return $rows;
	}

	public static function getRelated($pid)
	{
		$rows = array();
		if (!empty($pid) && $pid > 0)
		{
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('pr.relative_id')
				->from('#__ksenmart_products_relations AS pr')
				->where('pr.relation_type="relation"')
				->where('pr.product_id=' . $db->escape($pid));

			$db->setQuery($query);
			$rows = $db->loadColumn();
			$rows = KSMProducts::getProducts($rows);
		}

		self::onExecuteAfter(array(&$rows));

		return $rows;
	}

	public static function getSetRelatedIds($pid)
	{
		$rows = array();
		if (!empty($pid) && $pid > 0)
		{
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('
                    pr.id
                ')->from('#__ksenmart_products_relations AS pr')->where('pr.relation_type="set"')->where('pr.product_id=' . $db->escape($pid));
			$db->setQuery($query);
			$rows = $db->loadColumn();
		}

		return $rows;
	}

	public static function getDefaultCategory($product_id)
	{
		$db  = JFactory::getDbo();
		$sql = $db->getQuery(true);
		$sql->select('category_id')->from('#__ksenmart_products_categories AS pc')->where('pc.product_id=' . $db->escape($product_id))->where('pc.is_default=1');
		$db->setQuery($sql);
		$category = $db->loadResult();

		return $category;
	}

	/**
	 * @param $product_id
	 *
	 * @return mixed
	 *
	 * @since version 2.0.0
	 */
	public static function getProductCategories($product_id)
	{
		$db  = JFactory::getDbo();
		$sql = $db->getQuery(true);
		$sql->select('pc.category_id')
			->from('#__ksenmart_products_categories AS pc')
			->where('pc.product_id=' . $db->escape($product_id))
			->order('is_default DESC');
		$db->setQuery($sql);
		$categories = $db->loadColumn();

		return $categories;
	}

	/**
	 * Возвращает массив id категорий товара
	 *
	 * @param $product_id
	 *
	 * @return array
	 *
	 * @since version 2.0.0
	 */
	public static function getProductCategory($product_id)
	{
		$final_categories   = array();
		$product_categories = self::getProductCategories($product_id);
		$default_category   = current($product_categories);

		foreach ($product_categories as $product_category)
		{
			if (!empty($default_category))
			{
				$id_default_way = false;
			}
			else
			{
				$id_default_way = true;
			}
			$categories = array();
			$parent     = $product_category;

			while (!empty($parent) && $parent != 0)
			{
				if ($parent == $default_category)
				{
					$id_default_way = true;
				}
				$category = KSSystem::getTableByIds(array($parent), 'categories', array('t.id', 't.parent_id'), true, false, true);
				if (empty($category) || !isset($category->id)) break;
				$categories[] = $category->id;
				$parent       = $category->parent_id;
			}
			if ($id_default_way && count($categories) > count($final_categories))
			{
				$final_categories = $categories;
			}
		}
		if (empty($final_categories)) $final_categories = array(0);

		return $final_categories;
	}

	public static function getCalcPriceAsProperties($edit_price_sym, $edit_price = 0, &$price = 0)
	{
		switch ($edit_price_sym)
		{
			case '+':
				$price += substr($edit_price, 1, strlen($edit_price) - 1);
				break;
			case '-':
				$price -= substr($edit_price, 1, strlen($edit_price) - 1);
				break;
			case '/':
				$price = $price / substr($edit_price, 1, strlen($edit_price) - 1);
				break;
			case '*':
				$price = $price * substr($edit_price, 1, strlen($edit_price) - 1);
				break;
			default:
				$price += (float) $edit_price;
		}

		return $price;
	}

	/**
	 * Возвращает коллекцию производителей
	 *
	 * @param $ids
	 *
	 * @return array|mixed
	 *
	 * @since version
	 */
	public static function getManufacturers($ids)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('id, title')->from('#__ksenmart_manufacturers');
		if (!empty($ids))
		{
			$query->where('id IN (' . implode(',', $ids) . ')');
		}
		$db->setQuery($query);
		$manufacturers = $db->loadObjectList('id');

		return $manufacturers ? $manufacturers : [];
	}

	/**
	 * Возвращает коллекцию юнитов
	 *
	 * @param $id
	 *
	 * @return array|mixed
	 *
	 * @since version
	 */
	public static function getUnits($ids)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('id, form1')->from('#__ksenmart_product_units');
		if (!empty($ids))
		{
			$query->where('id IN (' . implode(',', $ids) . ')');
		}
		$db->setQuery($query);
		$units = $db->loadObjectList('id');

		return $units ? $units : [];
	}

	/**
	 * Возвращает все файлы указанных товаров
	 *
	 * @param $ids
	 *
	 * @return array|mixed
	 *
	 * @since version
	 */
	public static function getFiles($ids)
	{
		if (empty($ids))
		{
			return [];
		}

		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query
			->select('owner_id, filename, folder, params')
			->from('#__ksenmart_files')
			->where('owner_id IN (' . implode(',', $ids) . ')')
			->where('owner_type="product"')
			->order('ordering')
			->group('owner_id');
		$db->setQuery($query);
		$files = $db->loadObjectList('owner_id');

		return $files ? $files : [];
	}

	/**
	 * Возвращает коллекцию свойств указанных товаров
	 *
	 * @param $ids
	 *
	 * @return array
	 *
	 * @since version
	 */
	public static function getProductsProperties($ids)
	{
		if (empty($ids))
		{
			return [];
		}

		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('
                ppv.id,
                ppv.property_id,
                ppv.product_id,
                ppv.value_id,
                ppv.text,
                p.*
            ')
			->from('#__ksenmart_properties AS p')
			->leftJoin('#__ksenmart_product_properties_values AS ppv ON p.id=ppv.property_id')
			->where('ppv.product_id IN (' . implode(',', $ids) . ')')
			->where('p.published=1')
			//->group('ppv.property_id')
			->order('p.ordering');

		$db->setQuery($query);
		$rows = $db->loadObjectList();

		if (empty($rows))
		{
			return [];
		}

		$properties = [];
		foreach ($rows as $property)
		{
			if (empty($properties[$property->product_id]))
			{
				$properties[$property->product_id] = [];
			}

			$properties[$property->product_id][$property->property_id] = $property;
		}

		$query = $db->getQuery(true);
		$query->select('
                    pv.*,
                    ppv.property_id,
                    ppv.product_id,
                    ppv.price,
                    ppv.text
                ')->from('#__ksenmart_property_values AS pv')
			->leftJoin('#__ksenmart_product_properties_values AS ppv ON ppv.value_id=pv.id')
			->where('ppv.product_id IN (' . implode(',', $ids) . ')')
			->order('pv.ordering');

		$db->setQuery($query);
		$values = $db->loadObjectList();

		foreach ($values as $value)
		{
			if (!isset($properties[$value->product_id][$value->property_id]))
			{
				continue;
			}

			if (empty($properties[$value->product_id][$value->property_id]->values))
			{
				$properties[$value->product_id][$value->property_id]->values = [];
			}

			$properties[$value->product_id][$value->property_id]->values[$value->id] = $value;
		}

		return $properties;
	}

}
