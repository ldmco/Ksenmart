<?php
defined( '_JEXEC' ) or die;
?>
<tr class="list_item">
	<td class="name stretch">
		<div class="descr">	
			<a class="km-modal" rel='{"x":"90%","y":"90%"}' href="<?php echo JRoute::_('index.php?option=com_ksenmart&view=seo&layout=seotext&id='.$this->item->id.'&tmpl=component');?>"><?php echo $this->item->text;?></a>
			<p>
				<a class="edit km-modal" rel='{"x":"90%","y":"90%"}' href="<?php echo JRoute::_('index.php?option=com_ksenmart&view=seo&layout=seotext&id='.$this->item->id.'&tmpl=component');?>"><?php echo JText::_('ksm_edit');?></a>
			</p>
		</div>		
	</td>
	<td class="seo_category">
		<?php foreach($this->item->categories as $category):?>
			<?php echo $category;?><br>
		<?php endforeach;?>
	</td>
	<td class="seo_manufacturer">
		<?php foreach($this->item->manufacturers as $manufacturer):?>
			<?php echo $manufacturer;?><br>
		<?php endforeach;?>	
	</td>
	<td class="seo_country">
		<?php foreach($this->item->countries as $country):?>
			<?php echo $country;?><br>
		<?php endforeach;?>	
	</td>
	<td class="seo_properties">
		<?php foreach($this->item->properties as $property):?>
			<?php echo $property;?><br>
		<?php endforeach;?>		
	</td>
	<td class="del"><a></a></td>
	<input type="hidden" class="id" name="items[<?php echo $this->item->id;?>][id]" value="<?php echo $this->item->id;?>">
</tr>