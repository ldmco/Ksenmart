<?php defined('_JEXEC') or die(); ?>
<?php
    $flag = true;
	foreach($this->product->properties as $prop){
       if($prop->type == 'select' && ($prop->view == 'select' || $prop->view == 'checkbox' || $prop->view == 'radio')){
           if(count($prop->values) > 1){
    	       $flag = false;
               break;
    	   }
       }
	}
?>
<li class="ajax_block_product span3 shop_box " data-id="<?php echo $this->product->id; ?>">
	<form method="post" action="<?php echo $this->product->add_link_cart; ?>" class="clearfix"> 
		<?php echo $this->loadTemplate('item_image'); ?>
		<div class="center_block">
			<div class="product_flags clearfix">
				<?php echo ($this->product->hot == 1?'<span class="new title_shop">ХИТ!</span>':'')?>
				<?php echo ($this->product->recommendation == 1?'<span class="availability title_shop">Рекомендуем!</span>	':''); ?>
				<?php echo ($this->product->new == 1?'<span class="online_only title_shop">Новинка!</span> ':''); ?>
				<?php echo ($this->product->promotion == 1?'<span class="on_sale title_shop">Акция!</span>':''); ?>
				
			</div>
            <div class="clear"></div>
            <h3><a class="product_link" href="<?php echo $this->product->link?>" title="<?php echo $this->product->title; ?>"><?php echo $this->product->title; ?></a></h3>
            <div class="product_desc">
				<?php if(!empty($this->product->product_code)){ ?>
					<div class="article muted">Артикул: <?php echo $this->product->product_code; ?></div>
				<?php }?>
				<?php if(!empty($this->product->introcontent)){ ?>
					<div class="introcontent muted">
						<div class="introcontent_wrapp">
							<?php echo $this->product->introcontent; ?>
						</div>
						<span>
							<a href="<?php echo $this->product->link; ?>" title="<?php echo JText::_('KSM_READ_MORE'); ?>"><?php echo JText::_('KSM_READ_MORE'); ?></a>
						</span>
					</div>
				<?php }?>
			</div> 
		</div>																				 
		<div class="right_block">
			<span class="price"><? echo $this->product->val_price; ?></span>
			<div class="clear noneclass"></div>
			<?php if(!$this->params->get('only_auth_buy', 0) && ($this->product->price != 0 && $this->product->is_parent == 0 && $flag)): ?>
			<span class="buy"><button type="submit" class="ajax_add_to_cart_button exclusive btn_add_cart"><span>Купить</span></button></span>
			<?php endif; ?>
			<a class="button" href="<?php echo $this->product->link; ?>" title="<?php echo JText::_('KSM_READ_MORE'); ?>"><?php echo JText::_('KSM_READ_MORE'); ?></a>          
		</div>
		<input type="hidden" name="product_packaging" value="<?php echo $this->product->product_packaging; ?>" />
		<input type="hidden" name="price" value="<?php echo $this->product->price; ?>" />
		<input type="hidden" name="id" value="<?php echo $this->product->id; ?>" />
	</form>
</li>