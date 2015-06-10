<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

require_once (dirname(__FILE__) . '/helper.php');
$events = MODKSMNewsHelper::getEvents($params);
$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));
require (JModuleHelper::getLayoutPath('mod_ksm_news', $params->get('layout', 'default')));
