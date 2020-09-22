<?php
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class modKsenmartCategoriesHelper
{

	private static $_tree = array();
	private static $_active_id = array();

	public static function get_current_item()
	{
		$jinput = JFactory::getApplication()->input;
		$view   = $jinput->getCmd('view', '');
		if ($view == 'catalog')
		{
			$categories = $jinput->get('categories', array(), 'ARRAY');
			if (count($categories) == 1)
			{
				self::$_active_id = $categories[0];

				return self::$_active_id;
			}
			else
				return false;
		}
		elseif ($view == 'product')
		{
			$product_id       = $jinput->getInt('id', 0);
			self::$_active_id = KSMProducts::getProduct($product_id)->categories[0];
			if (!empty($active_id))
				return self::$_active_id;
			else
				return false;
		}
		else
			return false;
	}

	public static function get_path()
	{
		$path     = array();
		$get_path = false;
		$level    = false;

		for ($k = count(self::$_tree) - 1; $k >= 0; $k--)
		{
			if (self::$_tree[$k]->id == self::$_active_id) $get_path = true;
			if ($get_path && (self::$_tree[$k]->level < $level || !$level))
			{
				$path[] = self::$_tree[$k]->id;
				$level  = self::$_tree[$k]->level;
			}
			if ($level == 1) $get_path = false;
		}

		return $path;
	}

	public static function view_tree($mparams)
	{
		$categories  = $mparams->get('categories', array());
		$params      = array(
			'image_flag' => $mparams->get('show_images', 0),
			'img_width'  => $mparams->get('img_width', 200),
			'img_height' => $mparams->get('img_height', 200)
		);
		$columns     = array('kc.id', 'kc.title', 'kc.alias', 'kc.parent_id', 'kc.minprice');
		self::$_tree = KSMCatalog::getCategoriesTree(0, $categories, $params, $columns);

		return self::$_tree;
	}
}