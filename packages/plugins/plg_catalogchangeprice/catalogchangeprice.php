<?php
defined('_JEXEC') or die('Restricted access');

if (!class_exists('KMPlugin')) {
	require(JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_ksenmart' . DS . 'classes' . DS . 'kmplugin.php');
}

class plgKMPluginsCatalogchangeprice extends KMPlugin {

	function __construct(&$subject, $config) {
		parent::__construct($subject, $config);
	}

	public function onAfterDisplayAdminKSMcatalogdefault_items_list_top($view, $name = '', &$html = '') {
		$html     = str_replace('<a class="button delete-items">Удалить</a>', '<a class="button delete-items">Удалить</a><a class="buttons calculate_price">Пересчитать цену</a>', $html);
		$document = JFactory::getDocument();
		$document->addScript('../plugins/' . $this->_type . '/' . $this->_name . '/assets/js/spektradmin.js');
		$style = '
			.buttons {
				float: left;
				padding: 11px;
				margin-left: 15px;
				font-size: 14px;
				background: #525252;
				text-decoration: none;
				color: white;
				height: 18px;
				cursor: pointer;
			}
			.buttons:hover {
				text-decoration: none;
				color: #fff;
			}
			#calculator .buttons {
				margin-left: 0;
			}
		';
		$document->addStyleDeclaration($style);
	}

	public function onAjaxSpektrxsetPrices() {
		$jinput         = JFactory::getApplication()->input;
		$sign_exchange  = $jinput->get('sign_exchange', 0);
		$exchange_price = $jinput->get('exchange_price', 0);
		if (empty($exchange_price)) return false;
		$db            = JFactory::getDBO();
		$unit_exchange = $jinput->get('unit_exchange', 0);
		$categories    = $jinput->get('categories', array());
		\Joomla\Utilities\ArrayHelper::toInteger($categories);
		$categories    = array_filter($categories, 'KSFunctions::filterArray');
		$manufacturers = $jinput->get('manufacturers', array());
		\Joomla\Utilities\ArrayHelper::toInteger($manufacturers);
		$manufacturers = array_filter($manufacturers, 'KSFunctions::filterArray');
		$searchword    = $jinput->get('searchword', '');

		$query = $db->getQuery(true);
		$query->select('p.id,p.price')->from('#__ksenmart_products as p');
		//$query->update('#__ksenmart_products as p');
		if (!empty($searchword)) $query->where('p.title like ' . $db->quote('%' . $searchword . '%') . ' or p.product_code like ' . $db->quote('%' . $searchword . '%'));
		if (count($manufacturers) > 0) $query->where('p.manufacturer in (' . implode(',', $manufacturers) . ')');
		if (count($categories) > 0) {
			$query->innerjoin('#__ksenmart_products_categories as pc on pc.product_id=p.id');
			$query->where('pc.category_id in (' . implode(', ', $categories) . ')');
		}
		$query->group('p.id');
		$db->setQuery($query);
		$items = $db->loadObjectList();
		foreach ($items as $item) {
			if ($unit_exchange == 1) {
				if ($sign_exchange == 1) {
					$newprice = $item->price - $exchange_price;
				} else {
					$newprice = $item->price + $exchange_price;
				}
			} else {
				if ($sign_exchange == 1) {
					$newprice = $item->price * (100 - $exchange_price) / 100;
				} else {
					$newprice = $item->price * (100 + $exchange_price) / 100;
				}
			}
			if ($newprice < 0) continue;
			$newprice = round($newprice);
			$query    = $db->getQuery(true);
			$query->update('#__ksenmart_products')->set('price=' . (int) $newprice)->where('id=' . (int) $item->id);
			$db->setQuery($query);
			$db->execute();
		}

		return true;
	}
}