<?php
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
JHtml::_('script', 'jui/chosen.jquery.min.js', false, true, false, false);
JHtml::_('stylesheet', 'jui/chosen.css', false, true);
?>
<div class="ksm-cart ksm-block">
    <?php echo $this->loadTemplate('map'); ?>
    <?php echo $this->loadTemplate('content'); ?>
	<div class="ksm-clear"></div>
    <div class="ksm-cart-order">
		<form method="post" class="ksm-cart-order-form">
			<h2><?php echo JText::_('KSM_CART_CART_TITLE'); ?></h2>
			<?php echo $this->loadTemplate('steps'); ?>
            <?php //echo $this->loadTemplate('total'); ?>
		</form>
    </div>
</div>	