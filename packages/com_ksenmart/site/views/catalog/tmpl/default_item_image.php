<?php
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>
<a href="<?php echo $this->product->link; ?>" title="<?php echo $this->product->title; ?>">
    <img src="<?php echo $this->product->small_img; ?>">
</a>
<div class="ksm-catalog-item-flags">
	<?php if ($this->product->promotion == 1 || ($this->product->old_price > 0 && $this->product->old_price > $this->product->price)): ?>
        <span class="ksm-catalog-item-flag-promotion"></span>
	<?php endif; ?>
	<?php if ($this->product->hot == 1): ?>
        <span class="ksm-catalog-item-flag-hot"></span>
	<?php endif; ?>
	<?php if ($this->product->recommendation == 1): ?>
        <span class="ksm-catalog-item-flag-recommendation"></span>
	<?php endif; ?>
	<?php if ($this->product->new == 1): ?>
        <span class="ksm-catalog-item-flag-new"></span>
	<?php endif; ?>
</div>	
