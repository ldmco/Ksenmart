<?php
defined('_JEXEC') or die('Restricted access');

if (!class_exists('KMPlugin')) {
	require(JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_ksenmart' . DS . 'classes' . DS . 'kmplugin.php');
}

class plgKMPluginsSameProducts extends KMPlugin {

	function __construct(&$subject, $config) {
		parent::__construct($subject, $config);
	}

	public function onAfterDisplayKSMProductParent_product_list_related(&$view, &$tpl, &$html) {
		global $ext_name, $ext_name_com;
		$old_ext_name     = $ext_name;
		$old_ext_name_com = $ext_name_com;
		$ext_name         = 'ksenmart';
		$ext_name_com     = 'com_ksenmart';
		$html             = $this->getHtml($view->product->id) . $html;

		$ext_name     = $old_ext_name;
		$ext_name_com = $old_ext_name_com;

		return true;
	}

	public function onDisplayModKMSameProduct($id, &$html, $params = array()) {
		global $ext_name, $ext_name_com;
		$old_ext_name     = $ext_name;
		$old_ext_name_com = $ext_name_com;
		$ext_name         = 'ksenmart';
		$ext_name_com     = 'com_ksenmart';
		foreach ($params as $key => $param) {
			$this->params->set($key, $param);
		}
		$html = $this->getHtml($id, 'module') . $html;

		$ext_name     = $old_ext_name;
		$ext_name_com = $old_ext_name_com;

		return true;
	}

	public function onAfterDisplayKSMProductProduct_related(&$view, &$tpl, &$html) {
		global $ext_name, $ext_name_com;
		$old_ext_name     = $ext_name;
		$old_ext_name_com = $ext_name_com;
		$ext_name         = 'ksenmart';
		$ext_name_com     = 'com_ksenmart';
		$html             = $this->getHtml($view->product->id) . $html;

		$ext_name     = $old_ext_name;
		$ext_name_com = $old_ext_name_com;

		return true;
	}

	private function getHtml($product_id, $tmpl = '') {
		$db             = JFactory::getDBO();
		$properties     = $this->params->get('properties', array());
		$all_categories = $this->params->get('all_categories', 0);
		$this_category  = $this->params->get('this_category', 0);
		$count          = $this->params->get('count', 10);
		$com_params     = JComponentHelper::getParams('com_ksenmart');
		$html           = '';
		$pids           = array();
		if (empty($all_categories) && empty($this_category)) {
			return $html;
		}

		if (count($properties)) {
			foreach ($properties as $property_id) {
				$query = $db->getQuery(true);
				$query
					->select('value_id')
					->from('#__ksenmart_product_properties_values')
					->where('product_id = ' . $product_id)
					->where('property_id = ' . $property_id);
				$db->setQuery($query);
				$value_ids = $db->loadColumn();

				if (empty($value_ids)) {
					continue;
				}

				$query = $db->getQuery(true);
				$query
					->select('product_id')
					->from('#__ksenmart_product_properties_values')
					->where('product_id != ' . $product_id)
					->where('value_id in (' . implode(',', $value_ids) . ')');

				if (count($pids)) {
					$query->where('product_id in (' . implode(',', $pids) . ')');
				}
				$db->setQuery($query);
				$pids = $db->loadColumn();

				if (!count($pids)) {
					return $html;
				}
			}
		}

		if (!empty($this_category)) {
			$category_id = KSMProducts::getProduct($product_id)->categories[0];

			if (empty($category_id)) {
				return $html;
			}

			$query = $db->getQuery(true);
			$query
				->select('title')
				->from('#__ksenmart_categories')
				->where('id = ' . $category_id);
			$db->setQuery($query);
			$category_title = $db->loadResult();

			$query = $db->getQuery(true);
			$query
				->select('p.id')
				->from('#__ksenmart_products as p')
				->innerjoin('#__ksenmart_products_categories as pc on pc.product_id = p.id')
				->where('pc.category_id = ' . $category_id)
				->where('p.published = 1')
				->where('p.parent_id = 0')
				->where('p.id != ' . $product_id)
				->order('RAND()');
			if (count($pids)) {
				$query->where('p.id in (' . implode(',', $pids) . ')');
			}
			$db->setQuery($query, 0, $count);
			$cur_pids = $db->loadColumn();

			$products             = KSMProducts::getProducts($cur_pids);
			$view                 = new stdClass();
			$view->params         = $com_params;
			$view->products       = $products;
			$view->category_title = $category_title;
			$html .= KSSystem::loadPluginTemplate($this->_name, $this->_type, $view, 'this_category' . $tmpl);
		}

		if (!empty($all_categories)) {
			$category_id = KSMProducts::getProduct($product_id)->categories[0];

			$query = $db->getQuery(true);
			$query
				->select('p.id')
				->from('#__ksenmart_products as p')
				->innerjoin('#__ksenmart_products_categories as pc on pc.product_id = p.id')
				->where('pc.category_id != ' . $category_id)
				->where('p.published = 1')
				->where('p.parent_id = 0')
				->where('p.id != ' . $product_id)
				->order('RAND()');
			if (count($pids)) {
				$query->where('p.id in (' . implode(',', $pids) . ')');
			}

			$db->setQuery($query, 0, $count);
			$cur_pids = $db->loadColumn();

			$products       = KSMProducts::getProducts($cur_pids);
			$view           = new stdClass();
			$view->params   = $com_params;
			$view->products = $products;
			$html .= KSSystem::loadPluginTemplate($this->_name, $this->_type, $view, 'all_categories');
		}

		return $html;
	}

}