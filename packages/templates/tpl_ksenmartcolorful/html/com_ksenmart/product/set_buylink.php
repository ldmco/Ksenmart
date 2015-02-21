<?php
defined('_JEXEC') or die;
?>
<?php if (($this->params->get('only_auth_buy',0)==0 || ($this->params->get('only_auth_buy',0)!=0 && JFactory::getUser()->id!=0)) && $this->params->get('catalog_mode',0) == 0):?>
<div class="to-order lead">
	<a href="javascript:void(0);" class="link_b_border lrg"><?php echo JText::_('KSM_PRODUCT_SET_BYLINK_TEXT'); ?></a>
</div>
<?php endif;?>