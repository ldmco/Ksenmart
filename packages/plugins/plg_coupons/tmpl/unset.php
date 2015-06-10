<?php
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die('Restricted access');
?>

<div class="km-coupons default_shipping-plugin-renew">
	<div class="step">
		<legend><?php echo JText::_('ksm_discount_coupons_text_1'); ?></legend>
		<div class="control-group">
			<span><?php echo JText::_('ksm_discount_coupons_text_2'); ?></span>&nbsp;<?php echo $view->code; ?>&nbsp;&nbsp;
			<input type="button" class="st_button btn" value="<?php echo JText::_('ksm_discount_coupons_unset_coupon'); ?>" />
		</div>
	</div>
</div>