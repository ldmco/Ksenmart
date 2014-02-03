<?php defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

if (!class_exists('KsenmartHtmlHelper')) {
	require JPATH_ROOT.DS.'components'.DS.'com_ksenmart'.DS. 'helpers'.DS.'head.php';
	KsenmartHtmlHelper::AddHeadTags();
}

require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'helper.php');
KMHelper::loadHelpers('common');

$dispatcher	= JDispatcher::getInstance();
$results = $dispatcher->trigger('onBeforeStartComponent',array());
	
$document = JFactory::getDocument();
$document->addStyleSheet(JURI::base().'components/com_ksenmart/css/style.css');
$document->addScript(JURI::base().'components/com_ksenmart/js/style.js');

$controller = JController::getInstance('KsenMart');
$controller->execute(JRequest::getCmd('task'));
$controller->redirect();