<?php
    defined('_JEXEC' ) or die( '=;)');
//print_r($categories);
?>
<div class="item">
	<form method="post" action="<?php echo $product->add_link_cart; ?>" >
		<div class="img">
			<a href="<?php echo $product->link?>"><img src="<?php echo $product->small_img; ?>" alt="<?php echo $product->title; ?>" /></a>
			<?php echo ($product->hot==1?'':'')?>
			<?php echo ($product->recommendation==1?'':'')?>
			<?php echo ($product->new==1?'':'')?>
			<?php echo ($product->promotion==1?'<img class="discount" src="'.JURI::base().'templates/evrostor/css/i/discount.png">':'')?>
			<div class="price">
				<?php echo $product->val_price; ?>
			</div>
		</div>
		<?php if ($product->val_old_price_wou!=0){ ?>
        <div class="old-price"><?php echo $product->val_old_price; ?></div>
		<?php }else{ ?>
        <div class="old-price_empty clearfix">&nbsp;</div>
        <?php } ?>
		<div class="name">
			<a href="<?php echo $product->link; ?>" title="<?php echo $product->title; ?>"><?php echo $product->title; ?></a>		
		</div>
        <div class="weight-quant">
        	<div class="weight">
                <?php 
                $prop = array();
                $prop[] = $product->properties[3]->values;
                $prop[] = $product->properties[9]->values;
                foreach($prop as $parentArray){
                    foreach($parentArray as $value){
                        echo $value->title."&nbsp;";
                    }
                } ?>
            </div>
        	<div class="quant">
        		<span class="minus"></span>
        		<input type="text" class="inputbox" value="1">
        		<span class="plus"></span>
        	</div>
        </div>
		<?php if ($this->params->get('catalog_mode',0) == 0 && $product->val_price_wou != 0){ ?>
			<?php if ($this->params->get('only_auth_buy',0)==0 || ($this->params->get('only_auth_buy',0)!=0 && JFactory::getUser()->id!=0)){ ?>	
			<div class="buy"><a href="javascript:void(0);" class="button white">В корзину</a></div>			
			<?php }else{ ?>
			<div class="link"><a href="<?php echo $product->link; ?>">Посмотреть</a></div>
			<?php } ?>
		<?php } ?>
		<?php if ($product->product_code!='') {?>
		<div class="art">
			Артикул: <?php echo $product->product_code; ?>
		</div>
		<?php } ?>
        <div class="cat"><a href="<?php echo $product->cat_link; ?>" title="<?php echo $product->cat_title; ?>"><?php echo $product->cat_title; ?></a></div>
        <div class="clearfix"></div>
		<input type="hidden" name="count" value="<?php echo $product->product_packaging; ?>" />
		<input type="hidden" name="product_packaging" value="<?php echo $product->product_packaging; ?>" />	
		<input type="hidden" name="id" value="<?php echo $product->id; ?>" />			
	</form>	
</div>