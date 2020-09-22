<?php
defined('_JEXEC') or die;
?>
<div class="ksm-profile-products ksm-block">
	<?php foreach($view->products as $product): ?>
	<div class="ksm-profile-products-item">
		<div class="ksm-profile-products-item-img">
			<img src="<?php echo $product->small_img; ?>">
			<div class="ksm-profile-products-item-flags">
				<?php if ($product->hot == 1): ?>
				<span class="ksm-profile-products-item-flag-hot"></span>
				<?php endif; ?>
				<?php if ($product->recommendation == 1): ?>
				<span class="ksm-profile-products-item-flag-recommendation"></span>
				<?php endif; ?>
				<?php if ($product->new == 1): ?>
				<span class="ksm-profile-products-item-flag-new"></span>
				<?php endif; ?>
				<?php if ($product->promotion == 1): ?>
				<span class="ksm-profile-products-item-flag-promotion"></span>
				<?php endif; ?>				
			</div>			
		</div>
		<div class="ksm-profile-products-item-name">
			<a href="<?php echo $product->link; ?>"><?php echo $product->title; ?></a>
			<?php if (!empty($product->manufacturer_title)): ?>
			<span><?php echo $product->manufacturer_title; ?></span>
			<?php endif; ?>
		</div>	
		<div class="ksm-profile-products-item-price">
			<?php if (!empty($product->old_price)): ?>
			<span class="ksm-profile-products-item-price-old"><?php echo $product->val_old_price; ?></span>
			<?php endif; ?>
			<span class="ksm-profile-products-item-price-normal"><?php echo $product->val_price; ?></span>
		</div>	
		<div class="ksm-profile-products-item-button">
			<button type="button" class="ksm-profile-products-item-button-remove ksm-btn"><?php echo JText::_('PLG_USER_KSENMART_PRODUCTS_REMOVE_LBL'); ?></button>
		</div>	
		<input type="hidden" name="<?php echo $view->name; ?>[]" value="<?php echo $product->id; ?>">
	</div>
	<?php endforeach; ?>
	<div class="ksm-profile-products-noinfo" <?php echo (count($view->products) ? 'style="display:none;"' : ''); ?>>
		<?php echo JText::_('PLG_USER_KSENMART_NOINFO_LBL'); ?>
	</div>
</div>