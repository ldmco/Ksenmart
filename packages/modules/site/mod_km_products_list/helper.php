<?php
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class modKMProductsListHelper {

	public static function getList($params) {
		$db    = JFactory::getDbo();
		$categories = (array) $params->get('categories', array());
		$query = $db->getQuery(true);
		$query
			->select('p.id')
			->from('#__ksenmart_products AS p')
			->where('p.published = 1')
			->where('p.parent_id = 0')
			->order('p.ordering ASC')
			->group('p.id');

		if (!empty($categories)) {
			$query->leftJoin('#__ksenmart_products_categories as pc ON p.id=pc.product_id');
			$query->where('pc.category_id IN (' . implode(',', $categories) . ')');
		}
		$type = $params->get('type', 'recommendation');
		if ($type == 'hot') {
			$query->where('(p.hot = 1)');
		}
		if ($type == 'new') {
			$query->where('(p.new = 1)');
		}
		if ($type == 'recommendation') {
			$query->where('(p.recommendation = 1)');
		}
		if ($type == 'promotion') {
			$query->where('(p.promotion = 1)');
		}

		$db->setQuery($query, 0, $params->get('col', 10));
		$products = $db->loadColumn();

		$products = KSMProducts::getProducts($products);

		return $products;
	}

}