<?php
defined('_JEXEC') or die;
?>
<div class="ksm-profile-products">
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
			<?php if ($product->catalog_buy): ?> 
				<form class="ksm-catalog-item-buy-form">
					<button type="submit" class="ksm-profile-products-item-button-buy"><?php echo JText::_('KSM_PRODUCT_ADDTOCART_BUTTON_TEXT'); ?></button>
					<input type="hidden" name="count" value="<?php echo $product->product_packaging; ?>" />
					<input type="hidden" name="product_packaging" value="<?php echo $product->product_packaging; ?>" />
					<input type="hidden" name="price" value="<?php echo $product->price; ?>" />
					<input type="hidden" name="id" value="<?php echo $product->id; ?>" />				
				</form>
			<?php else: ?>
				<a href="<?php echo $product->link; ?>" class="ksm-profile-products-item-button-view"><?php echo JText::_('KSM_READ_MORE'); ?></a>
			<?php endif; ?>
		</div>		
	</div>
	<?php endforeach; ?>
</div>