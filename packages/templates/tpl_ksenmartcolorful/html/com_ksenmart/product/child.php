<?php defined('_JEXEC') or die; ?>
<article class="unit row-fluid">
	<div class="top row-fluid">
		<?php echo $this->loadTemplate('title', 'product'); ?>
		<?php echo $this->loadTemplate('toplinks', 'product'); ?>
	</div>
	<div class="row-fluid top_prd_block">
		<?php echo $this->loadTemplate('gallery', 'product'); ?>
		<div class="info span6">
			<form action="<?php echo $this->product->add_link_cart; ?>" method="post" class="form-horizontal">
                <?php echo $this->loadTemplate('info', 'product'); ?>			
                <?php echo $this->loadTemplate('prices', 'product'); ?>		
                <input type="hidden" name="price" value="<?php echo $this->product->price; ?>"/>	
    			<input type="hidden" name="id" value="<?php echo $this->product->id; ?>"/>	
    			<input type="hidden" name="product_packaging" class="product_packaging" value="<?php echo $this->product->product_packaging; ?>"/>
			</form>
		</div>
	</div>
	
    <?php echo $this->loadTemplate('social', 'product'); ?>
    
    <?php echo $this->loadTemplate('tabs', 'product'); ?>
    
    <?php if (count($this->product->sets) > 0) { ?>
	   <?php echo $this->loadTemplate('sets', 'product'); ?>
	<?php } ?>
</article>
<?php if (count($this->related) > 0){ ?>
    <?php echo $this->loadTemplate('related', 'product'); ?>
<?php } ?>