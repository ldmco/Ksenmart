<?php
defined('_JEXEC') or die;

$document = JFactory::getDocument();
$document->addStyleSheet(JURI::base().'components/com_ksenmart/css/style.css');
$document->addStyleSheet(JURI::base().'components/com_ksenmart/css/prog-style.css');
$document->addStyleSheet(JURI::base().'components/com_ksenmart/css/nprogress.css');

//$document->addScript(JURI::base().'components/com_ksenmart/js/jquery-1.7.1.min.js');
$document->addScript('http://ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js');
$document->addScript(JURI::base().'components/com_ksenmart/js/common.js');
$document->addScript(JURI::base().'components/com_ksenmart/js/style.js');
$document->addScript(JURI::base().'components/com_ksenmart/js/nprogress.js');

require_once JPATH_COMPONENT.'/helpers/helper.php';
KMHelper::loadHelpers('admin');
KMHelper::loadHelpers('common');
KMSystem::loadPlugins();
KMSystem::loadJSLanguage();

require_once JPATH_COMPONENT.'/controller.php';
$controller = JControllerLegacy::getInstance('KsenMart');
$controller->execute(JRequest::getCmd('task'));
$controller->redirect();