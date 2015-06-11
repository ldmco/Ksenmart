<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);

define('KSC_PLUGIN', 'plg_system_ksencore');

define('KSC_ADMIN', 'ksencore');
define('KSC_ADMIN_PATH', JPATH_ROOT . '/plugins/system/' . KSC_ADMIN);
define('KSC_ADMIN_URL', JURI::root(true) . '/plugins/system/' . KSC_ADMIN);
define('KSC_ADMIN_REL', 'plugins/system/' . KSC_ADMIN);

define('KSC_ADMIN_PATH_CORE', KSC_ADMIN_PATH . '/core/');
define('KSC_ADMIN_PATH_CORE_HELPERS', KSC_ADMIN_PATH_CORE . 'helpers/');
define('KSC_ADMIN_PATH_CORE_TABLES', KSC_ADMIN_PATH_CORE . 'tables/');

define('KSC_ADMIN_URL_CORE_ASSETS', KSC_ADMIN_URL . '/core/assets/');
define('KSC_ADMIN_URL_CORE_ASSETS_JS', KSC_ADMIN_URL_CORE_ASSETS . 'js/');
define('KSC_ADMIN_URL_CORE_ASSETS_CSS', KSC_ADMIN_URL_CORE_ASSETS . 'css/');