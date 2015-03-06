<?php defined('_JEXEC') or die(); ?>
<? if (count($this->product->sets) > 0) { ?>
	<div class="sets row catalog page_product_box ">
	<h3 class="toggle"><span>Сэкономь</span> купив комплектом</h3>
	<div class="row layout_grid layout_block">
        <ul id="product_list" class="row items catalog-items">
			<? foreach($this->product->sets as $set){ ?>
				<li class="ajax_block_product span3 shop_box" data-id="<?php echo $product->id; ?>">
					<form method="post" action="<?php echo $this->product->add_link_cart; ?>" class="clearfix"> 
						<a class="product_img_link" href="<?php echo $set->link; ?>" title="<?php echo $set->title; ?>">
                            <img src="<?php echo $set->mini_small_img; ?>" alt="<?php echo $set->title; ?>" />
                        </a>
						<div class="center_block">
							<h3><a class="product_link" href="<?php echo $set->link; ?>" title="<?php echo $set->title; ?>"><?php echo $set->title; ?></a></h3>
						</div>																				 
						<div class="right_block buy">
							<span class="price">Экономия<br> <?php echo $set->val_diff_price; ?></span>
							<div class="clear noneclass"></div>
							<button type="submit" class="ajax_add_to_cart_button exclusive btn_add_cart"><span>Купить</span></button>
							<a class="button" href="<?php echo $set->link; ?>" title="<?php echo JText::_('KSM_READ_MORE'); ?>"><?php echo JText::_('KSM_READ_MORE'); ?></a>          
						</div>
						<input type="hidden" name="price" value="<?php echo $set->price?>">
						<input type="hidden" name="id" value="<?php echo $set->id?>">
						<input type="hidden" name="product_packaging" class="product_packaging" value="<?php echo $this->product->product_packaging?>">
						<input type="hidden" name="count" value="<?php echo $this->product->product_packaging?>">
					</form>
				</li>
			<? } ?>
        </ul>
	</div>
	</div>	
	<? } ?>