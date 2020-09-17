<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<div <?php echo JMicrodata::htmlScope('product'); ?> class="ksm-product ksm-product-parent-list ksm-block">
	<div class="ksm-product-head">
		<div class="ksm-product-head-left">
			<?php echo $this->loadTemplate('title', 'product');?>		
		</div>
		<div class="ksm-product-head-right">
			<?php echo $this->loadTemplate('toplinks', 'product');?>
		</div>
	</div>
	<div class="ksm-product-body">
		<div class="ksm-product-body-left">
			<?php echo $this->loadTemplate('gallery', 'product'); ?>
		</div>	
		<div class="ksm-product-body-right">
			<form action="<?php echo $this->product->add_link_cart; ?>" class="ksm-catalog-item-buy-form" method="post">
                <?php echo $this->loadTemplate('info','product');?>			
			</form>
		</div>
	</div>
	<div class="ksm-product-footer">
		<?php echo $this->loadTemplate('childs'); ?>
		<?php echo $this->loadTemplate('tabs','product');?>
		<?php echo $this->loadTemplate('related', 'product'); ?>
	</div>
</div>
