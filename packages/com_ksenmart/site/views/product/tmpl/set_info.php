<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>

<div class="ksm-product-info">
	<?php if (!empty($this->product->introcontent)): ?>
		<div class="ksm-product-info-row">
			<?php echo html_entity_decode($this->product->introcontent); ?>
		</div>
	<?php endif; ?>
	<?php if (!empty($this->product->product_code)): ?>
		<div class="ksm-product-info-row">
			<label class="ksm-product-info-row-label"><?php echo JText::_('KSM_PRODUCT_ARTICLE'); ?></label>
			<div class="ksm-product-info-row-control">
				<?php echo $this->product->product_code; ?>
			</div>
		</div>
	<?php endif; ?>
</div>