<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<div class="ksm-module-shipping ksm-block <?php echo $class_sfx?>">
	<div class="ksm-module-shipping-region-row">
		<h5><?php echo JText::_('MOD_KM_SHIPPING_REGION'); ?></h5>
		<select id="ksm-module-shipping-region-select">
			<option value="0"><?php echo JText::_('MOD_KM_SHIPPING_CHOOSE_REGION'); ?></option>
			<?php foreach($regions as $region): ?>
			     <option value="<?php echo $region->id; ?>" <?php echo ($region->id == $user_region ? 'selected' : ''); ?>><?php echo $region->title; ?></option>
			<?php endforeach; ?>
		</select>
	</div>
	<?php require JModuleHelper::getLayoutPath('mod_km_shipping', 'default_shipping_info'); ?>
</div>