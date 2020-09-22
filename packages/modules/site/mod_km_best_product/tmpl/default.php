<?php
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>
<div class="ksm-module-best-product ksm-block <?php echo $class_sfx ?>">
	<div class="container">
		<div class="col-md-6 ksm-module-best-product-left">
			<span class="ksm-module-best-product-label">Товар недели</span>
			<div class="ksm-module-best-product-info">
				<h2 class="ksm-module-best-product-title"><?php echo $product->title; ?></h2>
				<div class="ksm-module-best-product-description">
					<?php echo html_entity_decode($product->content); ?>
				</div>
			</div>
			<a class="sppb-btn sppb-btn-primary sppb-btn-rounded ksm-module-best-product-more" href="<?php echo $product->link; ?>">подробнее...</a>
		</div>
	</div>
	<div class="ksm-module-best-product-image-block" style="background: url(<?php echo $product->big_img; ?>) left center no-repeat;">
		<span class="ksm-module-best-product-price"><?php echo $product->val_price; ?></span>
	</div>
</div>