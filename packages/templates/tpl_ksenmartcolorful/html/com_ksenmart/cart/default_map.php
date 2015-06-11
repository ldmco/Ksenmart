<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<div id="ksenmart-map" class="modal fade">
	<div id="ksenmart-map-header" class="modal-header">
		<button class="close" data-dismiss="modal">×</button>
		<h3><?php echo JText::_('KSM_CART_MAP_MARK_YOUR_ADDRESS'); ?></h3>
	</div>
	<div id="ksenmart-map-inner" class="modal-body">
		<div id="ksenmart-map-actions">
			<input type="text" id="ksenmart-map-to" />
			<div id="ksenmart-map-search"></div>
			<input type="button" id="ksenmart-map-to-center" class="btn" value="<?php echo JText::_('KSM_CART_MAP_MOSCOW'); ?>">
			<input type="button" id="ksenmart-map-to-area" class="btn" value="<?php echo JText::_('KSM_CART_MAP_REGION'); ?>">
			<input type="button" id="ksenmart-map-to-me" class="btn" value="<?php echo JText::_('KSM_CART_MAP_FIND_ME'); ?>">
			<input type="button" id="ksenmart-map-ok" class="btn btn-success" value="<?php echo JText::_('KSM_CART_MAP_DONE'); ?>">
			<input type="button" id="ksenmart-map-clear" class="btn btn-warning" value="<?php echo JText::_('KSM_CART_MAP_RESET'); ?>">
		</div>	
		<div id="ksenmart-map-layer"></div>
	</div>
</div>