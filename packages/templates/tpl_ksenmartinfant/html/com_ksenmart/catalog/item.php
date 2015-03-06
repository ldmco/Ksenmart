<?php defined('_JEXEC') or die(); ?>
<?php
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
<li class="ajax_block_product span3 shop_box " data-id="<?php echo $product->id; ?>">
	<form method="post" action="<?php echo $product->add_link_cart; ?>" class="clearfix"> 
		<a class="product_img_link" href="<?php echo $product->link; ?>" title="<?php echo $product->title; ?>">
			<img src="<?php echo $product->small_img; ?>" alt="" /> 
		</a>
		<div class="center_block">
			<div class="product_flags clearfix">
				<?php echo ($product->hot == 1?'<span class="new title_shop">ХИТ!</span>':'')?>
				<?php echo ($product->recommendation == 1?'<span class="availability title_shop">Рекомендуем!</span>	':''); ?>
				<?php echo ($product->new == 1?'<span class="online_only title_shop">Новинка!</span> ':''); ?>
				<?php echo ($product->promotion == 1?'<span class="on_sale title_shop">Акция!</span>':''); ?>
				
			</div>
            <div class="clear"></div>
            <h3><a class="product_link" href="<?php echo $product->link?>" title="<?php echo $product->title; ?>"><?php echo $product->title; ?></a></h3>
            <div class="product_desc">
				<?php if(!empty($product->product_code)){ ?>
					<div class="article muted">Артикул: <?php echo $product->product_code; ?></div>
				<?php }?>
				<?php if(!empty($product->introcontent)){ ?>
					<div class="introcontent muted">
						<div class="introcontent_wrapp">
							<?php echo $product->introcontent; ?>
						</div>
						<span>
							<a href="<?php echo $product->link; ?>" title="<?php echo JText::_('KSM_READ_MORE'); ?>"><?php echo JText::_('KSM_READ_MORE'); ?></a>
						</span>
					</div>
				<?php }?>
			</div> 
		</div>																				 
		<div class="right_block">
			<span class="price"><? echo $product->val_price; ?></span>
			<div class="clear noneclass"></div>
			<span class="buy"><button type="submit" class="ajax_add_to_cart_button exclusive btn_add_cart"><span>Купить</span></button></span>
			<a class="button" href="<?php echo $product->link; ?>" title="<?php echo JText::_('KSM_READ_MORE'); ?>"><?php echo JText::_('KSM_READ_MORE'); ?></a>          
		</div>
		<input type="hidden" name="product_packaging" value="<?php echo $product->product_packaging; ?>" />
		<input type="hidden" name="price" value="<?php echo $product->price; ?>" />
		<input type="hidden" name="id" value="<?php echo $product->id; ?>" />
	</form>
</li>