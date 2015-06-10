<?php
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die('Restricted access');
?>

<div class="km-coupons default_shipping-plugin-renew">
	<div class="step">
		<legend><?php echo JText::_('ksm_discount_coupons_text'); ?></legend>
		<div class="controls">
		    <div class="control-group input-append">
			   <span><?php echo JText::_('ksm_discount_coupons_print_code_site'); ?></span>
			   <input type="text" class="inputbox span12" name="discount_code" value="" placeholder="<?php echo JText::_('ksm_discount_coupons_placeholder'); ?>" />
			   <input type="button" class="st_button btn" value="<?php echo JText::_('ksm_discount_coupons_recalculate'); ?>" />
		    </div>
        </div>
	</div>
</div>