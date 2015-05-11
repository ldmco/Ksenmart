<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<div id="minicart" class="well">
	<a href="<?php echo $link; ?>">
		<span class="muted"><strong>Корзина [<?php echo $cart->total_prds; ?>]</strong></span>
		<small class="muted">Перетащите сюда товары</small>
	</a>
</div>