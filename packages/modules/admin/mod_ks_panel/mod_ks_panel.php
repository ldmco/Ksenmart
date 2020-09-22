<?php
/**
 *
 * $Id: mod_ks_panel.php 1.0.0 2012-11-27 09:24:43 Alexander Polyakov $
 * @package	    Joomla! 
 * @subpackage	Ksenmart Cpanel
 * @version     1.0.0
 * @description 
 * @copyright	  Copyright © 2012 - All rights reserved.
 * @license		  GNU General Public License v2.0
 * @author		  Alexander Polyakov
 * @author mail	alx.polyakov@gmail.com
 * @website		  http://alx-polyakov.ru/
 *
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
 
// include the helper file
require_once(dirname(__FILE__).'/helper.php');


$doc = JFactory::getDocument();
$doc->addScript('/administrator/modules/mod_ks_panel/js/jquery-1.7.1.min.js');
$doc->addScript('/administrator/modules/mod_ks_panel/js/jquery.custom.min.js');
$doc->addScript('/administrator/modules/mod_ks_panel/js/js.js');

$doc->addStyleSheet('/administrator/modules/mod_ks_panel/css/default.css');
$doc->addStyleSheet('http://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css');
 
// include the template for display


$items = ModKsenmartcpanelHelper::getItems();

require(JModuleHelper::getLayoutPath('mod_ks_panel'));
?>