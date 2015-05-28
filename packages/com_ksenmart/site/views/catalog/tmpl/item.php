<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

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
<li class="span3 item noTransition" data-id="<?php echo $product->id; ?>">
	<div class="thumbnail">
		<form method="post" action="<?php echo $product->add_link_cart; ?>" class="clearfix">        
			<div class="img">
				<a href="<?php echo $product->link; ?>" title="<?php echo $product->title; ?>">
					<img src="<?php echo $product->small_img; ?>" alt="<?php echo $product->title; ?>" class="span12" />
				</a>
				<?php echo ($product->hot == 1?'<span class="hit"></span>':'')?>
				<?php echo ($product->recommendation == 1?'<span class="super"></span>':''); ?>
				<?php echo ($product->new == 1?'<span class="new"></span>':''); ?>
				<?php echo ($product->promotion == 1?'<span class="act"></span>':''); ?>
			</div>
			<div class="name">
                <div class="pos_relative">
    				<a href="<?php echo $product->link?>" title="<?php echo $product->title; ?>">
    					<?php echo $product->title; ?>
    				</a>
    				<?php if(!empty($product->tag)){?>
    				<div class="for"><?php echo $product->tag; ?></div>
    				<?php }?>
    				<?php if(!empty($product->manufacturer_title)){ ?>
    				<div class="for_brand"><?php echo $product->manufacturer_title; ?></div>
    				<?php }?>
    				<?php if(!empty($product->product_code)){ ?>
    				<div class="article muted"><?php echo JText::_('KSM_PRODUCT_ARTICLE'); ?> <?php echo $product->product_code; ?></div>
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
    				
    				<div class="muted row-fluid bottom_info">
                        <div class="span6 brand">
                            <?php if(!empty($product->manufacturer_title)){ ?>
                            <?php echo JText::_('KSM_PRODUCT_MANUFACTURER'); ?> <a href="index.php?option=com_ksenmart&view=catalog&manufacturers[0]=<?php echo $product->manufacturer; ?>&Itemid=<?php echo KSSystem::getShopItemid(); ?>" title="<?php echo $product->manufacturer_title; ?>"><?php echo $product->manufacturer_title; ?></a>
                            <?php }?>
                        </div>
                        <div class="span6 rating">
                            <span class="title"><?php echo JText::_('KSM_PRODUCT_RATE'); ?> </span>
            				<?php for($k=1; $k<6; $k++){ ?>
                				<?php if(floor($product->rate->rate) >= $k){ ?>
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
					<div class="price row-fluid"<?php echo $product->val_price_wou == 0?' style="visibility: hidden;"':''; ?>>
                        <div class="span6">
                            <span class="title"><?php echo JText::_('KSM_PRODUCT_PRICE'); ?> </span>
    						<?php if($product->val_old_price_wou != 0){ ?>
    						<span class="old"><?php echo $product->val_old_price; ?></span>
    						<?php } ?>
    						<span class="normal"><? echo $product->val_price; ?></span>
                        </div>
                        <div class="span6 quant_wrapp">
                            <div class="input-prepend input-append quant">
                                <button type="button" class="btn minus">-</button>
                                <input type="text" id="inputQuantity" class="inputbox span4 text-center" name="count" value="<?php echo $product->product_packaging; ?>" />
                                <button type="button" class="btn plus">+</button>
                            </div>
                        </div>
					</div>
                    <div class="bottom span12">
    					<span class="delta">&diams;</span>
                        <?php if(!$params->get('only_auth_buy', 0) && ($product->val_price_wou != 0 && $product->is_parent == 0 && $flag)){ ?>	
        					<div class="buy">
                                <button type="submit" class="btn btn-success row-fluid"><?php echo JText::_('KSM_PRODUCT_ADDTOCART_BUTTON_TEXT'); ?></button>
                            </div>
                            <?php }else{ ?>
                            <div class="buy">
                                <a href="<?php echo $product->link; ?>" class="btn btn-info row-fluid"><?php echo JText::_('KSM_READ_MORE'); ?></a>
                            </div>
                        <?php } ?>
                    </div>
				</div>
			</div>
			<input type="hidden" name="product_packaging" value="<?php echo $product->product_packaging; ?>" />
			<input type="hidden" name="id" value="<?php echo $product->id; ?>" />
		</form>	
	</div>
</li>