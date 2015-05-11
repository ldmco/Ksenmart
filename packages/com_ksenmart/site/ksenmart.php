<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

$dispatcher = JDispatcher::getInstance();
$dispatcher->trigger('onLoadKsen', array('ksenmart.KSM', array('common'), array(), array('angularJS' => 0)));
$dispatcher->trigger('onBeforeStartComponent',array());

if (!class_exists('KsenmartHtmlHelper')) {
	require JPATH_ROOT.DS.'components'.DS.'com_ksenmart'.DS. 'helpers'.DS.'head.php';
}
KsenmartHtmlHelper::AddHeadTags();

$document = JFactory::getDocument();
$document->addStyleSheet(JURI::base().'components/com_ksenmart/css/style.css');
$document->addScript(JURI::base().'components/com_ksenmart/js/style.js');

$controller = JControllerLegacy::getInstance('KsenMart');
$controller->execute(JRequest::getCmd('task'));
$controller->redirect();