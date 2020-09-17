<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<?php if (count($this->product->sets) > 0) { ?>
<div class="ksm-product-sets">
	<h3><?php echo JText::_('KSM_PRODUCT_SETS_TITLE'); ?></h3>
    <div class="ksm-product-sets-items">
		<?php foreach($this->product->sets as $set){ ?>
		<div class="ksm-product-sets-item">
			<div class="ksm-product-sets-item-img">
				<a href="<?php echo $set->link; ?>" title="<?php echo $set->title; ?>">
					<img src="<?php echo $set->small_img; ?>" alt="<?php echo $set->title; ?>" />
				</a>
			</div>
			<div class="ksm-product-sets-item-name">
				<a href="<?php echo $set->link; ?>" title="<?php echo $set->title; ?>"><?php echo $set->title; ?></a>								
			</div>
			<div class="ksm-product-sets-item-prices">
				<p><?php echo JText::_('KSM_PRODUCT_SETS_ECONOMY'); ?></p>
				<p class="ksm-product-sets-item-price"><?php echo $set->val_diff_price; ?></p>
			</div>	
			<div class="ksm-product-sets-item-button">
				<form action="<?php echo $this->product->add_link_cart; ?>" method="post">
					<button type="submit" class="ksm-btn-success"><?php echo JText::_('KSM_PRODUCT_ADDTOCART_BUTTON_TEXT'); ?></button>
					<input type="hidden" name="price" value="<?php echo $set->price; ?>">
					<input type="hidden" name="id" value="<?php echo $set->id; ?>">
					<input type="hidden" name="product_packaging" class="product_packaging" value="<?php echo $this->product->product_packaging; ?>">
					<input type="hidden" name="count" value="<?php echo $this->product->product_packaging; ?>">
				</form>						
			</div>
		</div>
		<?php } ?>
	</ul>
</div>
<?php } ?>