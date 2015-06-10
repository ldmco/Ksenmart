<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

$dispatcher = JDispatcher::getInstance();
JPluginHelper::importPlugin('system');
$result = $dispatcher->trigger('onLoadKsen', array('ksenmart.KSM', array('admin', 'common'), array(), array('angularJS' => 0, 'admin' => true)));

KSLoader::loadLocalHelpers(array('common'));
KSSystem::loadJSLanguage();

require_once JPATH_COMPONENT.'/controller.php';

$controller = JControllerLegacy::getInstance('KsenMart');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();