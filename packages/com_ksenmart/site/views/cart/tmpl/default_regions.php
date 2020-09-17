<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<div class="ksm-cart-order-shipping-regions ksm-cart-order-step-row">
	<div class="ksm-cart-order-step-row-control">
		<select id="ksm-cart-order-region_id" name="region_id" onchange="KMCartChangeRegion(this);" required="true">
			<option value=""><?php echo JText::_('KSM_CART_YOUR_CHOOSE_REGION'); ?></option>
			<?php foreach($this->regions as $region):?>
			<option value="<?php echo $region->id; ?>" <?php echo ($region->selected?'selected':''); ?>><?php echo $region->title; ?></option>
			<?php endforeach; ?>
		</select>
	</div>
</div>