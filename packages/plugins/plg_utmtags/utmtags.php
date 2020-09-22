<?php

defined('_JEXEC') or die('Restricted access');

if (!class_exists('KMPlugin')) {
	require(JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_ksenmart' . DS . 'classes' . DS . 'kmplugin.php');
}

class plgKMPluginsUtmtags extends KMPlugin {

	function __construct(&$subject, $config) {
		parent::__construct($subject, $config);
	}

	function onAfterDispatch() {
		$app             = JFactory::getApplication();
		$session         = JFactory::getSession();
		$jinput          = $app->input;
		$session_utmtags = $session->get('com_ksenmart.utmtags', null);
		$referer         = '';

		if (!empty($_SERVER['HTTP_REFERER'])) $referer = $_SERVER['HTTP_REFERER'];

		$utmtags       = array(
			'utm_source'    => $jinput->get('utm_source', null),
			'utm_medium'    => $jinput->get('utm_medium', null),
			'utm_campaign'  => $jinput->get('utm_campaign', null),
			'utm_content'   => $jinput->get('utm_content', null),
			'utm_term'      => $jinput->get('utm_term', null),
			'source_type'   => $jinput->get('source_type', null),
			'source'        => $jinput->get('source', null),
			'position_type' => $jinput->get('position_type', null),
			'position'      => $jinput->get('position', null),
			'keyword'       => $jinput->get('keyword', null),
		);
		$empty_utmtags = true;
		foreach ($utmtags as $utmtag) {
			if (!empty($utmtag)) {
				$empty_utmtags = false;
			}
		}
		$local_referer = strpos($referer, JURI::root());
		if ($empty_utmtags && $local_referer !== false)
			return false;
		$utmtags = json_encode($utmtags);

		if ($utmtags == $session_utmtags)
			return true;

		$session->set('com_ksenmart.utmtags', $utmtags);
		$session->set('com_ksenmart.referer', $referer);

		return true;
	}

	function onAfterExecuteKSMCartAddtocart($model, &$result) {
		$db       = JFactory::getDBO();
		$session  = JFactory::getSession();
		$order_id = $session->get('shop_order_id', null);
		$utmtags  = $session->get('com_ksenmart.utmtags', null);
		$referer  = $session->get('com_ksenmart.referer', null);
		if (empty($order_id))
			return false;
		if (empty($utmtags) && empty($referer))
			return false;

		$query = $db->getQuery(true);
		$query->update('#__ksenmart_orders')->set('utmtags=' . $db->quote($utmtags))->set('referer=' . $db->quote($referer))->where('id=' . $db->quote($order_id));
		$db->setQuery($query);
		$db->execute();

		return true;
	}

	function onAfterGetKSMFormInputOrderStatus_id($form, &$name, &$html) {
		$db       = JFactory::getDBO();
		$order_id = $form->getValue('id');
		if (empty($order_id))
			return false;

		$query = $db->getQuery(true);
		$query->select('utmtags, referer')->from('#__ksenmart_orders')->where('id=' . $db->quote($order_id));
		$db->setQuery($query);
		$order = $db->loadObject();
		if (empty($order->utmtags))
			return false;

		$utmtags = json_decode($order->utmtags, true);
		if (!is_array($utmtags) || count($utmtags) == 0)
			return false;

		$utmtags['referer'] = $order->referer;
		$view               = new stdClass();
		$view->utmtags      = $utmtags;
		$html .= KSSystem::loadPluginTemplate($this->_name, $this->_type, $view, 'order_tags');

		return true;
	}

}