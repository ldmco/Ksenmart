<?php defined('_JEXEC') or die; ?>
<div id="minicart" class="well">
	<a href="<?php echo $link; ?>">
		<span class="muted"><strong><?php echo JText::_('MOD_KM_MINICART_LABEL'); ?> [<?php echo $cart->total_prds; ?>]</strong></span>
		<small class="muted"><?php echo JText::_('MOD_KM_MINICART_TEXT'); ?></small>
	</a>
</div>