<?php defined('_JEXEC') or die(); ?>
<table class="table table-hover">
	<thead>
		<tr>
			<th><?php echo JText::_('KSM_CART_TH_PHOTO'); ?></th>
			<th><?php echo JText::_('KSM_CART_TH_PRODUCT_INFO'); ?></th>
			<th><?php echo JText::_('KSM_CART_TH_COUNT'); ?></th>
			<th><?php echo JText::_('KSM_CART_TH_PRICE'); ?></th>
			<th><?php echo JText::_('KSM_CART_TH_PRODUCT_SUM'); ?></th>
			<th></th>
		</tr>
	</thead>
	<tbody class="items">
	<?php foreach($this->cart->items as $item){ ?>
        <?php echo $this->loadTemplate('item', null, array('item' => $item)); ?>        
	<?php } ?>
	</tbody>
</table>
<hr />
<div class="pull-right">
    <div class="total_cost_items lead">
    	<?php echo JText::_('KSM_CART_ITEMS_TOTAL_SUM_TEXT'); ?> <span><?php echo $this->cart->products_sum_val; ?></span>
    </div>
    <a href="javascript:void(0);" class="btn btn-success btn-large noTransition" id="order_info_show"><?php echo JText::_('KSM_CART_CHECKOUT_TEXT'); ?></a>
</div>
<div class="clearfix"></div>