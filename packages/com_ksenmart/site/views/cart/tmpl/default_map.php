<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<div id="ksm-map">
	<div id="ksm-map-header">
		<button id="ksm-map-close">×</button>
		<h3><?php echo JText::_('KSM_CART_MAP_MARK_YOUR_ADDRESS'); ?></h3>
	</div>
	<div id="ksm-map-body">
		<div id="ksm-map-actions">
			<input type="text" id="ksm-map-to" />
			<div id="ksm-map-search"></div>
			<input type="button" id="ksm-map-to-center" value="<?php echo JText::_('KSM_CART_MAP_MOSCOW'); ?>">
			<input type="button" id="ksm-map-to-area" value="<?php echo JText::_('KSM_CART_MAP_REGION'); ?>">
			<input type="button" id="ksm-map-ok" class="ksm-btn-success" value="<?php echo JText::_('KSM_CART_MAP_DONE'); ?>">
			<input type="button" id="ksm-map-clear" value="<?php echo JText::_('KSM_CART_MAP_RESET'); ?>">
		</div>	
		<div id="ksm-map-layer"></div>
	</div>
</div>