<?php defined( '_JEXEC' ) or die; ?>
<?php if ($this->params->get('only_auth_buy',0) == 0 || ($this->params->get('only_auth_buy',0) != 0 && JFactory::getUser()->id != 0)){ ?>
    <div class="control-group">
        <label class="control-label">Цена:</label>
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
    <div class="control-group">
        <label class="control-label"><?php echo JText::_('KSM_PRODUCT_PRODCOL'); ?></label>
        <div class="controls">
            <div class="quant">
                <span class="minus">-</span>
                <input type="text" id="inputQuantity" class="inputbox text-center" name="count" value="<?php echo $this->product->product_packaging?>" />
                <span class="plus">+</span>
            </div>
        </div>
    </div>
	<div class="buy">
		<button type="submit" class="btn green buyb"><?php echo JText::_('KSM_PRODUCT_ADD_TO_CART_LABEL'); ?></button>
	</div>
<? } ?>
