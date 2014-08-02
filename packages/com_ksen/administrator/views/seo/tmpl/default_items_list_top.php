<?php	 		 		 	
defined( '_JEXEC' ) or die;
?>
<div class="top clearfix">
	<a class="adds km-modal" rel='{"x":"90%","y":"90%"}' href="<?php echo JRoute::_('index.php?option=com_ksen&view=seo&layout=seotext&tmpl=component&extension='.$this->state->get('extension'));?>"><?php echo JText::_('ks_seo_add_seotext')?></a>
	<a class="button delete-items"><?php echo JText::_('ks_delete')?></a>
</div>