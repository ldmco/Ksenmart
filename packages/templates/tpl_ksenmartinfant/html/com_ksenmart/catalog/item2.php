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
<li class="span3 item noTransition" data-id="<?php echo $product->id; ?>">
	<div class="featured_li">
		<form method="post" action="<?php echo $product->add_link_cart; ?>" class="clearfix"> 

                    <a class="product_image"  href="<?php echo $product->link; ?>" title="<?php echo $product->title; ?>">
						<img src="<?php echo $product->small_img; ?>" alt="" class="span12 main-img" /> 
					</a> 
                    <div>
                    	<span class="price"><? echo $product->val_price; ?></span>
						<h5><a class="product_link" href="<?php echo $product->link?>" title="<?php echo $product->title; ?>"><?php echo $product->title; ?></a></h5>
						<div class="product_desc">
							<!--<?php if(!empty($product->product_code)){ ?>
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
							<?php }?>--> 
						</div>
						<div class="clearfix"></div>	
        				<span class="buy"><button type="submit" class="btn btn-success exclusive ajax_add_to_cart_button btn_add_cart"><span>Купить</span></button></span>
                        <a class="button" href="<?php echo $product->link; ?>"><b></b> <span><?php echo JText::_('KSM_READ_MORE'); ?></span></a>
                    </div>
		
			<input type="hidden" name="product_packaging" value="<?php echo $product->product_packaging; ?>" />
			<input type="hidden" name="id" value="<?php echo $product->id; ?>" />
		</form>	
	</div>
</li>