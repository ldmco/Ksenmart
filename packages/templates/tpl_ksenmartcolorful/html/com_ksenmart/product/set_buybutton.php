<?php
defined('_JEXEC') or die;
?>
<?php if(($this->params->get('only_auth_buy',0) == 0 || ($this->params->get('only_auth_buy',0) != 0 && JFactory::getUser()->id != 0)) && $this->params->get('catalog_mode',0) == 0){ ?>
<div class="buy">
	<button type="submit" class="btn green buyb"><b></b> <span><?php echo JText::_('KSM_PRODUCT_ADDTOCART_BUTTON_TEXT'); ?></span></button>
</div>
<?php } ?>
