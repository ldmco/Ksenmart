<?php
defined( '_JEXEC' ) or die;
?>
<tr class="list_item">
	<td class="name stretch">
		<div class="descr">	
			<a class="km-modal" rel='{"x":"90%","y":"90%"}' href="<?php echo JRoute::_('index.php?option=com_ksen&view=seo&layout=seotext&id='.$this->item->id.'&tmpl=component&extension='.$this->state->get('extension'));?>"><?php echo $this->item->text;?></a>
			<p>
				<a class="edit km-modal" rel='{"x":"90%","y":"90%"}' href="<?php echo JRoute::_('index.php?option=com_ksen&view=seo&layout=seotext&id='.$this->item->id.'&tmpl=component&extension='.$this->state->get('extension'));?>"><?php echo JText::_('ks_edit');?></a>
			</p>
		</div>		
	</td>
	<td class="seo_params">
		<?php echo $this->item->params;?>
	</td>
	<td class="del"><a></a></td>
	<input type="hidden" class="id" name="items[<?php echo $this->item->id;?>][id]" value="<?php echo $this->item->id;?>">
</tr>