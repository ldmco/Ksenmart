<?php defined( '_JEXEC' ) or die; ?>
<article class="unit row-fluid">
	<div class="top row-fluid">
		<?php echo $this->loadTemplate('title','product');?>	
		<?php echo $this->loadTemplate('toplinks','product');?>
	</div>
	<div class="row-fluid unit top_prd_block">
        <?php echo $this->loadTemplate('gallery', 'product'); ?>
		<div class="info" id="info">
			<form action="<?php echo $this->product->add_link_cart; ?>" method="post" class="form-horizontal">
                <?php echo $this->loadTemplate('info','product');?>			
                <?php echo $this->loadTemplate('prices','product');?>	
    			<input type="hidden" name="price" value="<?php echo $this->product->price; ?>" />
    			<input type="hidden" name="id" value="<?php echo $this->product->id?>" />
    			<input type="hidden" name="product_packaging" class="product_packaging" value="<?php echo $this->product->product_packaging?>" />
			</form>
		</div>
	</div>
	<?php echo $this->loadTemplate('social','product');?>
    <?php echo $this->loadTemplate('tabs','product');?>
	<?php foreach($this->childs_groups as $childs_group){ ?>
       <?php if (count($childs_group->products) > 0){ ?>
    	<div class="catalog">
    		<h3><?php echo $childs_group->title?></h3>
    		<ul class="thumbnails items catalog-items">
    			<?php foreach($childs_group->products as $product) { ?>
                    <?php echo $this->loadOtherTemplate('item', 'default', 'catalog', array('product' => $product, 'params' => $this->params)); ?>
    			<?php } ?>
    		</ul>	
    	</div>
    	<?php } ?>
	<?php } ?>	
</article>
<?php if (count($this->related) > 0){ ?>
    <?php echo $this->loadTemplate('related', 'product'); ?>
<?php } ?>