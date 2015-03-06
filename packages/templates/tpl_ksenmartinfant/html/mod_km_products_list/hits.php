<? defined('_JEXEC') or die; ?>
<section class="block products_block column_box <?php echo $params->get('moduleclass_sfx'); ?>">
    <h3><span><? echo $module->title; ?></span></h3>
	<ul>
    <?php
	$counter 	= 0;
	$countRows 	= count($products);
	foreach($products as $product) {

    $flag = true;
	foreach($product->properties as $prop){
       if($prop->type != 'text'){
           if(count($prop->values) > 1){
    	       $flag = false;
               break;
    	   }
       }
	}
?>
<li class="clearfix shop_box">
	<a class="products_block_img"  href="<?php echo $product->link; ?>" title="<?php echo $product->title; ?>">
		<img src="<?php echo $product->small_img; ?>" alt="">
	</a>
	<div class="product_info">
		<h5><a class="product_link" href="<?php echo $product->link?>" title="<?php echo $product->title; ?>"><?php echo $product->title; ?></a></h5>
		<?php echo $product->introcontent; ?>
		<span class="price"><? echo $product->val_price; ?></span>
		<p><a class="lnk_more" href="<?php echo $product->link; ?>"><?php echo JText::_('KSM_READ_MORE'); ?></a></p>
	</div>
</li>
	<?php } ?>
    </ul>
</section>