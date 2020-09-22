<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

if (!class_exists('KMDiscountPlugin')) {
	require (JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_ksenmart' . DS . 'classes' . DS . 'kmdiscountplugin.php');
}

class plgKMDiscountactionsUtm extends KMDiscountPlugin {
	
	var $_params = array(
		'utm_source' => '',
		'utm_campaign' => '',
		'utm_content' => '',
		'keyword' => ''
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
		$html.= '		<label style="margin-bottom: 10px;font-size: 16px;" class="inputname">' . JText::_($plugin_name) . '</label>';
		$html.= '		<div class="row">';
		$html.= '			<label class="inputname">' . JText::_('KSM_DISCOUNTACTIONS_UTM_SOURCE') . '</label>';
		$html.= '			<input style="width: 300px;" type="text" name="jform[user_actions][utm][utm_source]" class="inputbox" value="' . $params['utm_source'] . '">';
		$html.= '		</div>';
		$html.= '		<div class="row">';
		$html.= '			<label class="inputname">' . JText::_('KSM_DISCOUNTACTIONS_UTM_CAMPAIGN') . '</label>';
		$html.= '			<input style="width: 300px;" type="text" name="jform[user_actions][utm][utm_campaign]" class="inputbox" value="' . $params['utm_campaign'] . '">';
		$html.= '		</div>';
		$html.= '		<div class="row">';
		$html.= '			<label class="inputname">' . JText::_('KSM_DISCOUNTACTIONS_UTM_CONTENT') . '</label>';
		$html.= '			<input style="width: 300px;" type="text" name="jform[user_actions][utm][utm_content]" class="inputbox" value="' . $params['utm_content'] . '">';
		$html.= '		</div>';
		$html.= '		<div class="row">';
		$html.= '			<label class="inputname">' . JText::_('KSM_DISCOUNTACTIONS_KEYWORD') . '</label>';
		$html.= '			<input style="width: 300px;" type="text" name="jform[user_actions][utm][keyword]" class="inputbox" value="' . $params['keyword'] . '">';
		$html.= '		</div>';
		$html.= '		<div style="clear:both;"></div>';
		$html.= '	</div>';
		$html.= '	<a href="#" onclick="removeDiscountAction(this);return false;"></a>';
		$html.= '</li>';
		
		return $html;
	}
	
	function onValidateAction($discount_id = null) {
		if (empty($discount_id)) return;

		$user_actions = KSMPrice::getDiscount($discount_id)->user_actions;
		if (empty($user_actions)) return;
		$user_actions = json_decode($user_actions, true);
		if (!isset($user_actions[$this->_name])) return;
		$session = JFactory::getSession();
		$utmtags = $session->get('com_ksenmart.utmtags', null);
		if(empty($utmtags)) return false;
		$utmtags = json_decode($utmtags);
		$utm_flag = true;
		foreach($user_actions[$this->_name] as $key => $utm){
			$utm = trim($utm);
			if(!empty($utm) && $utmtags->{$key} != $utm) $utm_flag = false;
		}
		
		return $utm_flag;
	}
	
	function onAfterDispatch(){
		$app = JFactory::getApplication();
		$session = JFactory::getSession();
		$jinput = $app->input;
		$session_utmtags = $session->get('com_ksenmart.utmtags', null);
		$utmtags = array(
			'utm_source' => $jinput->get('utm_source', null),
			'utm_campaign' => $jinput->get('utm_campaign', null),
			'utm_content' => $jinput->get('utm_content', null),
			'keyword' => $jinput->get('keyword', null),
		);	
		$empty_utmtags = true;
		foreach($utmtags as $utmtag){
			if (!empty($utmtag)){
				$empty_utmtags = false;
			}
		}
		if ($empty_utmtags)
			return false;		
		$utmtags = json_encode($utmtags);
		
		if ($utmtags == $session_utmtags)
			return true;
			
		$session->set('com_ksenmart.utmtags', $utmtags);
		
		return true;
	}
	
}
