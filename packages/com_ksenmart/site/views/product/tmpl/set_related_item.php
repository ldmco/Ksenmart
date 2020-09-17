<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<div class="ksm-set-buy-item">
	<div class="ksm-set-buy-item-img">
		<a href="<?php echo $this->related_product->link; ?>" title="<?php echo $this->related_product->title; ?>">
			<img src="<?php echo $this->related_product->small_img; ?>" alt="<?php echo $this->related_product->title; ?>" />
		</a>
	</div>
	<div class="ksm-set-buy-item-name">
		<a href="<?php echo $this->related_product->link; ?>" title="<?php echo $this->related_product->title; ?>"><?php echo $this->related_product->title; ?></a>								
	</div>
</div>
