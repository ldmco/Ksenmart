<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

$km_params = JComponentHelper::getParams('com_ksenmart');
if($km_params->get('modules_styles', true)){
    $document = JFactory::getDocument();
    $document->addStyleSheet(JURI::base().'modules/mod_km_contacts/css/mod_km_contacts.css');
}

$params = JComponentHelper::getParams('com_ksenmart');
$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));

require (JModuleHelper::getLayoutPath('mod_km_contacts', $params->get('layout', 'default')));