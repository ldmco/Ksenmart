<?php defined( '_JEXEC' ) or die(); ?>
<td class="image">
	<a href="<?php echo $this->item->product->link; ?>"><img src="<?php echo $this->item->product->mini_small_img; ?>" alt="<?php echo $this->item->product->title; ?>" class="km_img sm" /></a>
</td>
<td class="names">
	<div class="name">
		<a href="<?php echo $this->item->product->link; ?>"><?php echo $this->item->product->title; ?></a>
	</div>
	<div class="info">
		<dl class="dl-horizontal">
		  <dt>Артикул:</dt>
		  <dd><?php echo $this->item->product->product_code; ?>&nbsp;</dd>
		<?php foreach($this->item->properties as $item_property) {
			if (!empty($item_property->value)) { ?>
			  <dt><?php echo $item_property->title; ?>:</dt>
			  <dd><?php echo $item_property->value; ?></dd>
			<? } else { ?>
			  <dt><?php echo $item_property->title; ?></dt>
			  <dd></dd>			
			<? }
		}
		if ($order->status_id == 5) {
			foreach($this->item->product->files as $file) { ?>
			<div class="">
				<label><a target="_blank" href="<?php echo JURI::root()?>administrator/components/com_ksenmart/files/<?php echo $file->file; ?>"><?php echo $file->title; ?></a></label>
			</div>				
			<?
			}
		} ?>
		</dl>
	</div>
</td>	
<td class="quantt">
	<?php echo $this->item->count; ?>
</td>
<td class="pricee">
	<?php echo KSMPrice::showPriceWithTransform($this->item->price); ?>
</td>	
<td class="totall">
	<?php echo KSMPrice::showPriceWithTransform($this->item->price*$this->item->count); ?>
</td>