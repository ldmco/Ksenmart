<?php defined('_JEXEC') or die(); ?>
<div class="default_content">
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
			<?php if(isset($this->cart->discount_sum) && $this->cart->discount_sum > 0){ ?>
				<?php echo JText::_('KSM_CART_DISCOUNT_SUM_TEXT'); ?> <strong><?php echo $this->cart->discount_sum_val; ?></strong>
			<?php } ?>
			<br />
			<?php echo JText::_('KSM_CART_ITEMS_TOTAL_SUM_TEXT'); ?> <span><?php echo KSMPrice::showPriceWithTransform($this->cart->products_sum - $this->cart->discount_sum); ?></span>
		</div>
	</div>
	<div class="clearfix"></div>
</div>