<?php defined( '_JEXEC' ) or die; ?>
<?php if ($this->set_related): ?>
<div class="set catalog related">
	<h3><span><?php echo JText::_('KSM_PRODUCT_RELATED_TITLE'); ?></span>
		
		<?php echo $this->loadTemplate('social','product');?>
	</h3>
	
	<form action="<?php echo $this->product->add_link_cart; ?>" method="post">
		<ul class="thumbnails items catalog-items">
		<?php foreach($this->set_related as $product): ?>
			<?php echo $this->loadTemplate('related_item', null, array('related_product' => $product));?>
		<?php endforeach;?>
		</ul>
		<div class="bottom row-fluid">
			<?php echo $this->loadTemplate('related_prices');?>
			<?php echo $this->loadTemplate('buybutton');?>
		</div>
		<input type="hidden" name="price" value="<?php echo $this->product->price; ?>" />
		<input type="hidden" name="id" value="<?php echo $this->product->id; ?>" />
		<input type="hidden" name="product_packaging" class="product_packaging" value="<?php echo $this->product->product_packaging; ?>" />
		<input type="hidden" name="count" value="<?php echo $this->product->product_packaging; ?>" />
	</form>
</div>
<?php endif;?>