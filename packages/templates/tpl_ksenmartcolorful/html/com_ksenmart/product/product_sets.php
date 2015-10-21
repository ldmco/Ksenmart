<?php defined('_JEXEC') or die(); ?>
<div class="sets row-fluid catalog related">
	<h3><?php echo JText::_('KSM_PRODUCT_SETS_TITLE'); ?>:</h3>
    <ul class="thumbnails items catalog-items">
		<?php foreach($this->product->sets as $set){ ?>		
		<li class="span3 item">
            <div class="thumbnail">
                <form action="<?php echo $this->product->add_link_cart; ?>" method="post">
                    <div class="img">
                        <a href="<?php echo $set->link; ?>" title="<?php echo $set->title; ?>">
                            <img src="<?php echo $set->mini_small_img; ?>" alt="<?php echo $set->title; ?>" class="span12" />
                        </a>
                        <span class="act"></span>
                    </div>
                    <div class="caption">
                    	<div class="name">
                    		<a href="<?php echo $set->link; ?>" title="<?php echo $set->title; ?>"><?php echo $set->title; ?></a>								
                    	</div>
                        <div class="bottom span12">
                    		<span class="delta">♦</span>
                            <div class="price">
                                <div><?php echo JText::_('KSM_PRODUCT_SETS_ECONOMY'); ?></div>
                    			<div class="normal"><?php echo $set->val_diff_price; ?></div>
                    		</div>	
                    		<div class="buy">
                                <button type="submit" class="btn btn-success row-fluid"><b></b> <span><?php echo JText::_('KSM_PRODUCT_ADDTOCART_BUTTON_TEXT'); ?></span></button>
                            </div>
                        </div>
                    </div>
					<input type="hidden" name="price" value="<?php echo $set->price; ?>">
					<input type="hidden" name="id" value="<?php echo $set->id; ?>">
					<input type="hidden" name="product_packaging" class="product_packaging" value="<?php echo $this->product->product_packaging; ?>">
					<input type="hidden" name="count" value="<?php echo $this->product->product_packaging; ?>">
                </form>
            </div>
		</li>
		<?php } ?>
	</ul>
</div>