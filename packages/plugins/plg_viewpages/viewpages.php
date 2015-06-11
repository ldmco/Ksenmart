<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

if (!class_exists('KMPlugin')) {
	require (JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_ksenmart' . DS . 'classes' . DS . 'kmplugin.php');
}

class plgKMDiscountactionsViewpages extends KMPlugin {
	
	var $_params = array(
		'value' => 0
	);
	
	function __construct(&$subject, $config) {
		parent::__construct($subject, $config);
	}
	
	function onDisplayParamsForm($name = '', $params = null) {
		if ($name != $this->_name) 
		return;
		if (empty($params)) $params = $this->_params;
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('name')->from('#__extensions')->where('element=' . $db->quote($this->_name))->where('folder="kmdiscountactions"');
		$db->setQuery($query);
		$plugin_name = $db->loadResult();
		$html = '';
		$html.= '<li>';
		$html.= '	<div class="line">';
		$html.= '		<label class="inputname">' . JText::_($plugin_name) . '</label>';
		$html.= '		<input type="text" name="jform[user_actions][viewpages][value]" class="inputbox" value="' . $params['value'] . '">';
		$html.= '		<p>' . JText::_('ksm_discountactions_viewpages_pieces') . '</p>';
		$html.= '	</div>';
		$html.= '	<a href="#" onclick="removeDiscountAction(this);return false;"></a>';
		$html.= '</li>';
		
		return $html;
	}
	
	function onBeforeStartComponent() {
		$session = JFactory::getSession();
		$user_viewpages = $session->get('com_ksenmart.user_viewpages', 0);
		$tmpl = JRequest::getVar('tmpl', 'index');
		if ($tmpl == 'index') $user_viewpages++;
		$session->set('com_ksenmart.user_viewpages', $user_viewpages);
	}
	
	function onValidateAction($discount_id = null) {
		if (empty($discount_id)) 
		return;
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('user_actions')->from('#__ksenmart_discounts')->where('id=' . $discount_id);
		$db->setQuery($query);
		$user_actions = $db->loadResult();
		if (empty($user_actions)) 
		return;
		$user_actions = json_decode($user_actions, true);
		if (!isset($user_actions[$this->_name])) 
		return;
		$session = JFactory::getSession();
		$user_viewpages = $session->get('com_ksenmart.user_viewpages', 0);
		if ($user_viewpages < $user_actions[$this->_name]['value']) 
		return false;
		
		return true;
	}
}
