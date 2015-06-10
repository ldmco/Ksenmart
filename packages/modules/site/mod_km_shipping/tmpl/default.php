<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<div class="deliv-info">
	<h3><?php echo $module->title; ?></h3>
	<div class="row-fluid">
		<h5><?php echo JText::_('MOD_KM_SHIPPING_REGION'); ?></h5>
		<select class="input-medium" id="shipping_region" style="width:180px;">
			<option value="0"><?php echo JText::_('MOD_KM_SHIPPING_CHOOSE_REGION'); ?></option>
			<?php foreach($regions as $region) { ?>
			     <option value="<?php echo $region->id; ?>" <?php echo ($region->id == $user_region ? 'selected' : ''); ?>><?php echo $region->title; ?></option>
			<?php } ?>
		</select>
	</div>
	<div class="deliv-payment-info">
		<div class="row-fluid">
			<h5><?php echo JText::_('MOD_KM_SHIPPING_SHIPPINGS'); ?></h5>
            <?php if(count($shippings) > 0) { ?>
                <?php foreach($shippings as $ship) {?>
            	<p>
					<?php if (!empty($ship->icon)):?>
						<span class="icon"><img src="<?php echo $ship->icon; ?>" width="20px" /></span>
					<?php endif;?>				
					<?php echo $ship->title ?>
					— <b><?php echo $ship->sum_val ?></b>
				</p>
            	<? } ?>
            <?php } else { ?>
            	<p><?php echo JText::_('MOD_KM_SHIPPING_NO_SHIPPINGS'); ?></p>
            <?php } ?>
		</div>
		<div class="row-fluid">
			<h5><?php echo JText::_('MOD_KM_SHIPPING_PAYMENTS'); ?></h5>
			<?php if(count($payments) > 0) { ?>
                <?php foreach($payments as $pay) { ?>
				<p>
					<?php if (!empty($pay->icon)):?>
					<span class="icon"><img src="<?php echo $pay->icon; ?>" width="20px" /></span>
					<?php endif;?>				
					<?php echo $pay->title; ?>
				</p>
				<?php } ?>
            <?php } else { ?>
			<p><?php echo JText::_('MOD_KM_SHIPPING_NO_PAYMENTS'); ?></p>
			<?php } ?>
		</div>
	</div>
</div>