<?php
defined( '_JEXEC' ) or die;
?>
<?php if (!empty($this->product->product_code)):?>
<div class="control-group">
	<label class="control-label"><?php echo JText::_('KSM_PRODUCT_ARTICLE'); ?></label>
	<div class="controls">
		<span class="article muted"><?php echo $this->product->product_code; ?></span>
	</div>
</div>
<?php endif; ?>
<?php if(!empty($this->product->introcontent)):?>
<div class="control-group">
	<label class="control-label"><?php echo JText::_('KSM_PRODUCT_MINIDESC'); ?></label>
	<div class="controls">
		<div class="minidesc"><?php echo html_entity_decode($this->product->introcontent); ?></div>
	</div>
</div>
<?php endif; ?>	