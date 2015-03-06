<?php defined('_JEXEC') or die; ?>
		<div id="minicart" class="shopping_cart">
			<a href="<?php echo $link; ?>" rel="nofollow">
          <strong class="opancart"></strong>
                <span class="shopping_cart_title"></span>
                <span class="ajax_cart_quantity hidden" style="display: none;"><?php echo $cart->total_prds; ?></span>
                <span class="ajax_cart_no_product"><?php echo $cart->total_prds; ?></span>
            </a>
		</div>