<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

$dispatcher = JEventDispatcher::getInstance();
$dispatcher->trigger('onLoadKsen', array('ksenmart.KSM', array('common'), array(), array('angularJS' => 0)));
$dispatcher->trigger('onBeforeStartComponent',array());
KSSystem::loadJSLanguage(false);

if (!class_exists('KsenmartHtmlHelper')) {
	require JPATH_ROOT.DS.'components'.DS.'com_ksenmart'.DS. 'helpers'.DS.'head.php';
}
KsenmartHtmlHelper::AddHeadTags();

$controller = JControllerLegacy::getInstance('KsenMart');
$controller->execute(JRequest::getCmd('task'));
$controller->redirect();