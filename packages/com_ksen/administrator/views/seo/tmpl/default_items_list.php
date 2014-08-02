<?php	 		 		 	
defined( '_JEXEC' ) or die;
?>
<table class="cat" width="100%" cellspacing="0">	
	<thead>
		<tr>
			<th class="name stretch" align="left"><?php echo JText::_('ks_seo_seotext_name')?></th>
			<th class="seo_params"><?php echo JText::_('ks_seo_seotext_params')?></th>
			<th class="del"><span></span></th>
		</tr>
	</thead>	
	<tbody>
	<?php if (count($this->items)>0):?>
		<?php foreach($this->items as $item):?>
			<?php $this->item=&$item;?>
			<?php echo $this->loadTemplate('item_form');?>
		<?php endforeach;?>
	<?php else:?>
		<?php echo $this->loadTemplate('no_items');?>
	<?php endif;?>
	</tbody>
</table>
<div class="pagi">
</div>	