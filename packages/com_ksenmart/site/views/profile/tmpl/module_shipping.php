<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<div class="row-fluid">
	<h5><?php echo JText::_('KSM_PROFILE_SHIPPING_MODULE_SHIPPINGS'); ?></h5>
	<?php if(count($this->shippings) > 0) { ?>
		<?php foreach($this->shippings as $ship) { ?>
		<p>
			<?php if (!empty($ship->icon)):?>
			<span class="icon"><img src="<?php echo $ship->icon; ?>" width="20px" /></span>
			<?php endif;?>			
			<?php echo $ship->title ?>
			— <b><?php echo $ship->sum_val ?></b>
		</p>
		<? } ?>
	<?php } else { ?>
		<p><?php echo JText::_('KSM_PROFILE_SHIPPING_MODULE_NO_SHIPPINGS'); ?></p>
	<?php } ?>
</div>
<div class="row-fluid">
	<h5><?php echo JText::_('KSM_PROFILE_SHIPPING_MODULE_PAYMENTS'); ?></h5>
	<?php if(count($this->payments) > 0) { ?>
		<?php foreach($this->payments as $pay) { ?>
		<p>
			<?php if (!empty($pay->icon)):?>
			<span class="icon"><img src="<?php echo $pay->icon; ?>" width="20px" /></span>
			<?php endif;?>			
			<?php echo $pay->title; ?>
		</p>
		<?php } ?>
	<?php } else { ?>
	<p><?php echo JText::_('KSM_PROFILE_SHIPPING_MODULE_NO_PAYMENTS'); ?></p>
	<?php } ?>
</div>