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
<li class="span3 item noTransition" data-id="<?php echo $this->product->id; ?>">
	<div class="thumbnail">
		<form method="post" action="<?php echo $this->product->add_link_cart; ?>" class="clearfix">     
			<div class="img">
				<?php echo $this->loadTemplate('item_image'); ?>
			</div>
			<div class="name">
                <div class="pos_relative">
    				<a href="<?php echo $this->product->link?>" title="<?php echo $this->product->title; ?>">
    					<?php echo $this->product->title; ?>
    				</a>
    				<?php if($this->product->tag != ''){?>
    				<div class="for"><?php echo $this->product->tag; ?></div>
    				<?php }?>
    				<?php if($this->product->manufacturer_title != ''){ ?>
    				<div class="for"><?php echo $this->product->manufacturer_title; ?></div>
    				<?php }?>
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
    				
    				<div class="muted row-fluid bottom_info">
                        <div class="span6 brand">
                            <?php if(!empty($this->product->manufacturer_title)){ ?>
                            Бренд: <a href="index.php?option=com_ksenmart&view=catalog&manufacturers[0]=<?php echo $this->product->manufacturer; ?>&Itemid=<?php echo KSSystem::getShopItemid(); ?>" title="<?php echo $this->product->manufacturer_title; ?>"><?php echo $this->product->manufacturer_title; ?></a>
                            <?php }?>
                        </div>
                        <div class="span6 rating">
                            <span class="title">Рейтинг: </span>
            				<?php for($k=1; $k<6; $k++){ ?>
                				<?php if(floor($this->product->rate->rate) >= $k){ ?>
                				<img src="<?php echo JURI::root()?>components/com_ksenmart/images/star2.png" alt="" />
                				<?php }else{ ?>
                				<img src="<?php echo JURI::root()?>components/com_ksenmart/images/star.png" alt="" />
                				<?php } ?>
            				<?php } ?>
                        </div>
                    </div>
                </div>
			</div>
			<div class="caption clearfix">
                <div class="bottom_wrapp span12">
					<div class="price row-fluid"<?php echo $this->product->price == 0?' style="visibility: hidden;"':''; ?>>
                        <div class="span6">
                            <span class="title">Цена: </span>
    						<?php if($this->product->old_price != 0){ ?>
    						<span class="old"><?php echo $this->product->val_old_price; ?></span>
    						<?php } ?>
    						<span class="normal"><? echo $this->product->val_price; ?></span>
                        </div>
                        <div class="span6 quant_wrapp">
                            <div class="input-prepend input-append quant">
                                <button type="button" class="btn minus">-</button>
                                <input type="text" id="inputQuantity" class="inputbox span4 text-center" name="count" value="<?php echo $this->product->product_packaging; ?>" />
                                <button type="button" class="btn plus">+</button>
                            </div>
                        </div>
					</div>
                    <div class="bottom span12">
    					<span class="delta">&diams;</span>
                        <?php if (($this->params->get('only_auth_buy',0) == 0 || ($this->params->get('only_auth_buy',0) != 0 && JFactory::getUser()->id != 0)) && ($this->product->price != 0 && $this->product->is_parent == 0 && $flag) && !$this->params->get('catalog_mode', 0)){ ?>	
        					<div class="buy">
                                <button type="submit" class="btn btn-success row-fluid"><b></b> <span>Купить</span></button>
                            </div>
                            <?php }else{ ?>
                            <div class="buy">
                                <a href="<?php echo $this->product->link; ?>" class="btn btn-info row-fluid"><b></b> <span><?php echo JText::_('KSM_READ_MORE'); ?></span></a>
                            </div>
                        <?php } ?>
                    </div>
				</div>
			</div>
            <input type="hidden" name="product_packaging" value="<?php echo $this->product->product_packaging; ?>" />
			<input type="hidden" name="price" value="<?php echo $this->product->price; ?>" />
			<input type="hidden" name="id" value="<?php echo $this->product->id; ?>" />
		</form>	
	</div>
</li>