<?php defined('_JEXEC') or die; ?>
<div class="km-discounts">
	<?php foreach($discounts as $discount):?>
	<div class="km-discount">
		<?php if (isset($discount->img)):?>
		<div class="km-discount-image" style="<?php echo $discount->img_div_style; ?>">
			<img style="<?php echo $discount->img_img_style;?>" src="<?php echo $discount->img; ?>">
		</div>	
		<?php endif;?>
		<div class="km-discount-title">
			<?php echo $discount->title;?>
		</div>
		<div class="km-discount-content">
			<?php echo $discount->content;?>
		</div>		
	</div>
	<?php endforeach;?>
</div>	