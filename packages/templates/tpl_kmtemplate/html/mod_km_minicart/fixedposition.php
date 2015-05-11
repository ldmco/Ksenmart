<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<div id="minicart" class="static default-bg">
    <a href="<?php echo $link; ?>">
    	<span class="muted">Корзина [<?php echo $cart->total_prds; ?>]</span>
    	<small class="muted">Перетащите сюда товары</small>
    </a>
</div>