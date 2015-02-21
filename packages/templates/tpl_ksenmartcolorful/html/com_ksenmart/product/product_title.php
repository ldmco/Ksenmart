<?php
defined('_JEXEC') or die;
?>
<div class="span7">
	<h1>
	<?php echo $this->product->title;?>
	<?php if ($this->product->new || $this->product->recommendation || $this->product->hot):?>
	<span class="status_block">
		<?php if($this->product->new): ?>
		<span class="new_line label label-success"><?php echo JText::_('KSM_PRODUCT_LABEL_NEW'); ?></span>
		<?php endif;?>
		<?php if($this->product->recommendation): ?>
		<span class="recomedation_line label label-info"><?php echo JText::_('KSM_PRODUCT_LABEL_RECOMENDATION'); ?></span>
		<?php endif;?>
		<?php if($this->product->hot): ?>
		<span class="hit_line label label-important"><?php echo JText::_('KSM_PRODUCT_LABEL_HIT'); ?></span>
		<?php endif; ?>
	</span>
	<?php endif; ?>
	</h1>
</div>
