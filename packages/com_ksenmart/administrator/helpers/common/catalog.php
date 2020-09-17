<?php
/**
 * @copyright   Copyright (C) 2016. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
KSSystem::import('helpers.corehelper');

/**
 * Class KSMCatalog
 *
 * @since version 4.1.0
 */
class KSMCatalog extends KSCoreHelper
{

	private static $_menu = array();
	private static $_tree = array();

	/**
	 * Построение дерева категорий
	 *
	 * @param int   $parent     Родительская категория
	 * @param array $categories Массив категорий, если пуст, то выводит все категории
	 * @param array $params     Настройки вывода древа категорий, выводить ли изображения, размер изображений и т.д.
	 * @param array $columns    Список возвращаемых колонок из БД, если пусть то выводит id, title, alias, parent_id
	 *
	 * @return array            Возвращает дерево категорий в виде многомерного массива
	 *
	 * @since version 4.1.0
	 */
	public static function getCategoriesTree($parent = 0, $categories = array(), $params = array(), $columns = array())
	{
		self::onExecuteBefore(array($parent, $categories, $params));

		if (!empty(self::$_tree)) return self::$_tree;
		if (!isset($params['image_flag'])) $params['image_flag'] = false;
		if (!isset($params['img_width'])) $params['img_width'] = 200;
		if (!isset($params['img_height'])) $params['img_height'] = 200;

		$db    = JFactory::getDBO();
		$query = $db->getQuery(true);
		if (empty($columns))
		{
			$query->select('kc.id,kc.title,kc.alias,kc.parent_id');
		}
		else
		{
			$query->select(implode(',', $db->quoteName($columns)));
		}
		$query->from('#__ksenmart_categories as kc')->where('kc.published=1')->order('kc.ordering');
		if ($categories)
		{
			$query->where('kc.id IN(' . implode(', ', $categories) . ') OR kc.parent_id IN(' . implode(', ', $categories) . ')');
		}
		if ($params['image_flag']) KSMedia::setItemMainImageToQuery($query, 'category', 'kc.');

		$db->setQuery($query);
		$rows       = $db->loadObjectList('id');
		$top_parent = (object) array(
			'id'       => $parent,
			'children' => array(),
		);
		$menu       = array(
			0 => $top_parent
		);
		foreach ($rows as $k => $v)
		{
			if (!empty($v->folder))
			{
				$v->img = KSMedia::resizeImage($v->filename, $v->folder, $params['img_width'], $params['img_height'], json_decode($v->params, true));
			}
			$v->children = (isset($menu[$k])) ? $menu[$k]->children : array();
			$menu[$k]    = $v;
			if (!isset($menu[$v->parent_id]))
			{
				$menu[$v->parent_id]           = new stdClass();
				$menu[$v->parent_id]->children = array();
			}
			$menu[$v->parent_id]->children[$v->id] = $v;
		}
		unset($rows);
		self::$_menu = $menu;
		if (self::$_menu) self::makeCategoriesTree(self::$_menu[0]);
		self::$_menu = array();
		self::onExecuteAfter(array(&self::$_tree));

		return self::$_tree;
	}

	private static function makeCategoriesTree($category, $level = 1)
	{
		if (isset($category->children) && !empty($category->children))
		{
			foreach ($category->children as $child)
			{
				$child->level      = $level;
				$child->deeper     = false;
				$child->shallower  = false;
				$child->level_diff = 0;
				$child->link       = JRoute::_('index.php?option=com_ksenmart&view=catalog&categories[0]=' . $child->id . ':' . $child->alias . '&Itemid=' . KSSystem::getShopItemid($child->id));
				//$child->class      = $this->value && in_array($child->id, $this->value) ? ' active' : '';
				if (isset(self::$_tree[count(self::$_tree) - 1]))
				{
					self::$_tree[count(self::$_tree) - 1]->deeper     = ($child->level > self::$_tree[count(self::$_tree) - 1]->level);
					self::$_tree[count(self::$_tree) - 1]->shallower  = ($child->level < self::$_tree[count(self::$_tree) - 1]->level);
					self::$_tree[count(self::$_tree) - 1]->level_diff = (self::$_tree[count(self::$_tree) - 1]->level - $child->level);
				}
				self::$_tree[] = $child;
				if (isset(self::$_tree[count(self::$_tree) - 1]))
				{
					self::$_tree[count(self::$_tree) - 1]->deeper     = (1 > self::$_tree[count(self::$_tree) - 1]->level);
					self::$_tree[count(self::$_tree) - 1]->shallower  = (1 < self::$_tree[count(self::$_tree) - 1]->level);
					self::$_tree[count(self::$_tree) - 1]->level_diff = (self::$_tree[count(self::$_tree) - 1]->level - 1);
				}
				self::makeCategoriesTree(self::$_menu[$child->id], $level + 1);
			}
		}
	}

	/**
	 * Получение всех дочерних категорий
	 *
	 * @param int $catid ID категории
	 *
	 * @return array     Возвращает массив ID дочерних категорий
	 *
	 * @since version 4.1.0
	 */
	public static function getChildCats($catid)
	{
		self::onExecuteBefore(array($catid));

		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query
			->select('c.id')
			->from('#__ksenmart_categories AS c')
			->order('c.ordering');

		if (is_array($catid))
		{
			\Joomla\Utilities\ArrayHelper::toInteger($catid);
			$return = $catid;
			$query->where('c.parent_id IN (' . implode(',', $catid) . ')');
		}
		else
		{
			$return = array($catid);
			$query->where('c.parent_id=' . (int) $catid);
		}

		$db->setQuery($query);
		$cats = $db->loadColumn();

		if (count($cats) > 0)
		{
			$return1 = self::getChildCats($cats);
			$return  = array_merge($return, $return1);
		}

		self::onExecuteAfter(array(&$return));

		return $return;
	}

}