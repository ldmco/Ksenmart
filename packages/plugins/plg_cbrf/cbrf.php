<?php defined('_JEXEC') or die('Restricted access');

if (!class_exists('KMPlugin')) {
	require(JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_ksenmart' . DS . 'classes' . DS . 'kmplugin.php');
}

class plgKMPluginsCbrf extends KMPlugin {

	function __construct(&$subject, $config) {
		parent::__construct($subject, $config);
	}

	public function onBeforeStartComponent() {
		$db       = JFactory::getDBO();
		$cur_date = date('Y-m-d');

		$query = $db->getQuery(true);
		$query
			->select('count(id)')
			->from('#__ksenmart_currencies')
			->where('cbrf_update < ' . $db->quote($cur_date));
		$db->setQuery($query);
		$cache     = JFactory::getCache('com_ksenmart.cbrf', 'callback');
		$count_ids = $cache->get(array($db, 'loadResult'));

		if (!$count_ids) {
			return;
		}

		$cbr = new CBRAgent();
		if (!$cbr->load()) {
			return;
		}

		$query = $db->getQuery(true);
		$query
			->select('*')
			->from('#__ksenmart_currencies');
		$db->setQuery($query);
		$currencies = $db->loadObjectList();

		$def_cur = '';
		foreach ($currencies as $currency) {
			if ($currency->default) {
				$def_cur = $currency->code;
				break;
			}
		}

		if (empty($def_cur)) {
			return;
		}

		$def_cur = $def_cur == 'RUB' ? 1 : $cbr->get($def_cur);

		if (empty($def_cur)) {
			return;
		}

		foreach ($currencies as $currency) {
			$cur = $currency->code == 'RUB' ? 1 : $cbr->get($currency->code);
			$cur = $def_cur / $cur;

			$query = $db->getQuery(true);
			$query
				->update('#__ksenmart_currencies')
				->set('rate = ' . $cur)
				->set('cbrf_update = NOW()')
				->where('id = ' . $currency->id);
			$db->setQuery($query);
			$db->execute();
		}

		return;
	}

}

class CBRAgent {
	protected $list = array();

	public function load() {
		$xml = new DOMDocument();
		$url = 'http://www.cbr.ru/scripts/XML_daily.asp?date_req=' . date('d.m.Y');

		if (@$xml->load($url)) {
			$this->list = array();

			$root  = $xml->documentElement;
			$items = $root->getElementsByTagName('Valute');

			foreach ($items as $item) {
				$code              = $item->getElementsByTagName('CharCode')->item(0)->nodeValue;
				$curs              = $item->getElementsByTagName('Value')->item(0)->nodeValue;
				$this->list[$code] = floatval(str_replace(',', '.', $curs));
			}

			return true;
		} else
			return false;
	}

	public function get($cur) {
		return isset($this->list[$cur]) ? $this->list[$cur] : 0;
	}
}