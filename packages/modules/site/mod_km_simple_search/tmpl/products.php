<?php
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>
<div class="ksm-module-search-items">
	<?php foreach ($products as $product) { ?>
        <div class="ksm-module-search-item">
            <a class="ksm-module-search-item-link" href="<?php echo $product->link; ?>">
                <span class="ksm-module-search-item-left"><img class="ksm-module-search-item-img" src="<?php echo $product->mini_small_img; ?>"></span>
                <span class="ksm-module-search-item-price"><?php echo $product->val_price; ?></span>
                <span class="ksm-module-search-item-title"><?php echo $product->title; ?></span>
            </a>
        </div>
	<?php } ?>
</div>