<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<?php if ($this->params->get('only_auth_buy',0) == 0 || ($this->params->get('only_auth_buy',0) != 0 && JFactory::getUser()->id != 0)){ ?>
	<div class="control-group">
		<label class="control-label"><?php echo JText::_('KSM_PRODUCT_PRICE'); ?></label>
		<div class="controls prices">
			<?php if ($this->product->old_price != 0){ ?>
			<span class="old-price muted"><?php echo $this->product->val_old_price; ?></span>
			<?php } ?>
			<span class="price"><?php echo $this->product->val_price; ?></span>
			<a href="javascript:void(0);" data-prd_id="<?php echo $this->product->id; ?>" class="spy_price" data-toggle="popover" data-placement="bottom" title="" data-original-title="Авторизация"><?php echo JText::_('KSM_PRODUCT_SPY_PRICE'); ?></a>
		</div>
	</div>
<?php } ?>
<?php if ($this->params->get('catalog_mode',0)==0){ ?>	
	<?php if ($this->product->in_stock==0 && $this->params->get('use_stock',1)==1):?>
		<h3><?php echo JText::_('KSM_PRODUCT_OUT_OF_STOCK'); ?></h3>
	<?php else:?>			
	<div class="control-group">
		<label class="control-label"><?php echo JText::_('KSM_PRODUCT_PRODCOL'); ?></label>
		<div class="controls">
			<div class="input-prepend input-append span2 quant">
				<button type="button" class="btn minus">-</button>
				<input type="text" id="inputQuantity" class="inputbox span12 text-center" name="count" value="<?php echo $this->product->product_packaging?>" />
				<button type="button" class="btn plus">+</button>
			</div>
		</div>
	</div>
	<div class="buy">
		<button type="submit" class="btn btn-success btn-large"><?php echo JText::_('KSM_PRODUCT_ADD_TO_CART_LABEL'); ?></button>
	</div>
	<?php endif;?>
<?php } ?>