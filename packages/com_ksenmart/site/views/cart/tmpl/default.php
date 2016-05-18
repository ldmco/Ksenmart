<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<div id="cart">
	<legend><?php echo JText::_('KSM_CART_YOUR_ORDER'); ?></legend>
    <?php echo $this->loadTemplate('map'); ?>
    <?php echo $this->loadTemplate('content'); ?>
	<div class="pull-right">
		<a href="javascript:void(0);" class="btn btn-success btn-large noTransition" id="order_info_show"><?php echo JText::_('KSM_CART_CHECKOUT_TEXT'); ?></a>
	</div>
	<div class="clearfix"></div>
    <div class="order_info_block hide noTransition">
		<form method="post" class="order_form form-horizontal" id="order">
			<h2><?php echo JText::_('KSM_CART_CART_TITLE'); ?></h2>
            <?php echo $this->loadTemplate('shipping'); ?>
            <?php echo $this->loadTemplate('payments'); ?>
            <?php echo $this->loadTemplate('note'); ?>
            <?php echo $this->loadTemplate('total'); ?>
		</form>	
    </div>
</div>	