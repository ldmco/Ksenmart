<?php defined('_JEXEC') or die; ?>
<?php if($this->params->get('only_auth_buy',0) == 0 || ($this->params->get('only_auth_buy',0) != 0 && JFactory::getUser()->id != 0)){ ?>
<div class="prices-set form-horizontal">
	<div class="prices">
		<div class="control-group">
			<label class="control-label"><?php echo JText::_('KSM_PRODUCT_SET_PRICE_LABEL'); ?>: </label>
			<div class="controls">
				<span class="price com">
					<?php echo $this->product->val_price; ?>
				</span>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label"><?php echo JText::_('KSM_PRODUCT_SET_PRICE_ECONOMY'); ?>: </label>
			<div class="controls">
				<span class="price text-success"><?php echo $this->product->val_diff_price; ?></span>
			</div>
		</div>
	</div>
</div>
<?php } ?>
