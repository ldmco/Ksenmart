<?php
defined( '_JEXEC' ) or die;
?>
<div class="top clearfix">
	<a class="adds km-modal" rel='{"x":"90%","y":"90%"}' href="<?php echo JRoute::_('index.php?option=com_ksenmart&view=catalog&layout=product&tmpl=component');?>"><?php echo JText::_('ksm_catalog_add_product')?></a>
	<a class="button copy-items"><?php echo JText::_('ksm_copy')?></a>
	<a class="button delete-items"><?php echo JText::_('ksm_delete')?></a>						
	<div class="drag">
		<form id="add-set-form">
			<div class="drop"><?php echo JText::_('ksm_catalog_add_set_string')?></div>
			<a class="ok"><?php echo JText::_('ksm_ok')?></a>
		</form>		
	</div>
</div>