<?php
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class modKMSimpleSearchHelper
{

	static function getProductsListAjax()
	{
		JEventDispatcher::getInstance()->trigger('onLoadKsen', array('ksenmart', array('common'), array(), array('angularJS' => 0)));
		KSLoader::loadLocalHelpers(array('common'));
		if (!class_exists('KsenmartHtmlHelper'))
		{
			require JPATH_ROOT . DS . 'components' . DS . 'com_ksenmart' . DS . 'helpers' . DS . 'head.php';
		}
		KsenmartHtmlHelper::AddHeadTags();
		$jinput = JFactory::getApplication()->input;
		$title  = $jinput->getString('module_title', '');
		$module = JModuleHelper::getModule('mod_km_simple_search', $title);
		$params = new Joomla\Registry\Registry($module->params);
		$value  = $jinput->getString('value', '');
		//$value = strtolower($value);
		//$morph_search = str_replace(' ', '* ', $value);

		$db = JFactory::getDbo();
		//$search = $db->quote($db->escape($value, true) . '%', false);
		$columns = $params->get('columns', array('title'));
		$where   = '';
		foreach ($columns as $key => $column)
		{
			if ($key != 0)
			{
				$where .= 'OR ';
			}
			$where .= 'MATCH
                    (' . $column . ') 
                AGAINST 
                    (' . $db->q('*' . $value . '*') . ')';
		}
		$query = $db->getQuery(true);
		$query
			->select('id')
			->from('#__ksenmart_products')
			->where('published=1')
			->where($where);
		$db->setQuery($query, 0, $params->get('count', 6));
		$ids = $db->loadColumn();

		if (empty($ids))
		{
			$columns = $params->get('detailcolumns', array('title'));
			$where   = '';
			foreach ($columns as $key => $column)
			{
				if ($key != 0)
				{
					$where .= 'OR ';
				}
				$where .= 'MATCH
                    (' . $column . ') 
                AGAINST 
                    (' . $db->q('*' . $value . '*') . ' IN BOOLEAN MODE)';
			}
			$query = $db->getQuery(true);
			$query
				->select('id')
				->from('#__ksenmart_products')
				->where('published=1')
				->where($where);
			$db->setQuery($query, 0, 6);
			$ids = $db->loadColumn();
		}

		$products = KSMProducts::getProducts($ids);

		ob_start();
		require JModuleHelper::getLayoutPath('mod_km_simple_search', 'products');
		$html = ob_get_contents();
		ob_end_clean();
		$return = array(
			'html'   => $html,
			'length' => count($products)
		);

		return $return;
	}
}