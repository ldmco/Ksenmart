<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<div <?php echo JMicrodata::htmlScope('product'); ?> class="ksm-product ksm-block">
	<?php echo $this->loadTemplate('edit'); ?>
	<div class="ksm-product-head">
		<div class="ksm-product-head-left">
			<?php echo $this->loadTemplate('title');?>		
		</div>
		<div class="ksm-product-head-right">
			<?php echo $this->loadTemplate('toplinks');?>
		</div>
	</div>
	<div class="ksm-product-body">
		<div class="ksm-product-body-left">
			<?php echo $this->loadTemplate('gallery'); ?>
		</div>
		<div class="ksm-product-body-right">
			<form action="<?php echo $this->product->add_link_cart; ?>" class="ksm-catalog-item-buy-form" method="post">
				<?php echo $this->loadTemplate('info');?>	
				<?php echo $this->loadTemplate('prices');?>	
    			<input type="hidden" name="id" value="<?php echo $this->product->id; ?>">	
    			<input type="hidden" name="price" value="<?php echo $this->product->price; ?>">	
    			<input type="hidden" name="product_packaging" class="product_packaging" value="<?php echo $this->product->product_packaging; ?>">
			</form>
		</div>
	</div>
	<div class="ksm-product-footer">
		<?php echo $this->loadTemplate('tabs'); ?>
		<?php echo $this->loadTemplate('sets'); ?>
		<?php echo $this->loadTemplate('related'); ?>
	</div>
</div>
