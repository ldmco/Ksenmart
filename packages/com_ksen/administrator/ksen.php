<?php defined('_JEXEC') or die;

$dispatcher = JDispatcher::getInstance();
JPluginHelper::importPlugin('system');
$result = $dispatcher->trigger('onLoadKsen', array('ksen.KS', array('admin', 'common'), array(), array('angularJS' => 0, 'admin' => true)));

KSSystem::loadJSLanguage();

require_once JPATH_COMPONENT.'/controller.php';

$controller = JControllerLegacy::getInstance('Ksen');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();