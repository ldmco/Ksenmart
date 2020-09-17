<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>

<?php if ($this->params->get('only_auth_buy', 0) == 0 || ($this->params->get('only_auth_buy', 0) != 0 && JFactory::getUser()->id != 0)):?>
<div class="ksm-product-prices">
	<div class="ksm-product-info-row">
		<label class="ksm-product-info-row-label"><?php echo JText::_('KSM_PRODUCT_SET_PRICE_LABEL'); ?></label>
		<div <?php echo JMicrodata::htmlProperty('offers'); ?> <?php echo JMicrodata::htmlScope('offer'); ?> class="ksm-product-info-row-control">
			<span class="price">
				<span class="ksm-product-price"><?php echo $this->product->val_price; ?></span>
				<span class="ksm-product-old-price"><?php echo $this->product->val_old_price; ?></span>
				<?php echo JMicrodata::htmlMeta($this->product->price, 'price'); ?>
				<?php echo JMicrodata::htmlMeta($this->product->currency_code, 'priceCurrency'); ?>
			</span>
		</div>
	</div>
	<div class="ksm-product-info-row">
		<label class="ksm-product-info-row-label"><?php echo JText::_('KSM_PRODUCT_SET_PRICE_ECONOMY'); ?></label>
		<div class="ksm-product-info-row-control">
			<span class="ksm-product-price"><?php echo $this->product->val_diff_price; ?></span>
		</div>
	</div>
</div>
<?php endif;?>