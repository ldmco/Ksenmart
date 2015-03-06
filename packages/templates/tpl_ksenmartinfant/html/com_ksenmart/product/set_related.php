<? defined( '_JEXEC' ) or die; ?>
<div class="thumbnail">
	<div class="img">
        <a href="<?php echo $product->link; ?>" title="<?php echo $product->title; ?>">
            <img src="<?php echo $product->small_img; ?>" alt="<?php echo $product->title; ?>" class="span12" />
        </a>
    </div>
	<div class="caption">
		<div class="name"><a href="<?php echo $product->link; ?>" title="<?php echo $product->title; ?>"><?php echo $product->title; ?></a></div>
		<div class="options">
			<?php require('product_properties.php'); ?>	
		</div>		
	</div>
</div>