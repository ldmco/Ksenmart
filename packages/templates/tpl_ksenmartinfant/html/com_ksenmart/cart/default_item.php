<?php defined( '_JEXEC' ) or die( '=;)' ); ?>
<tr class="bordercolor cart_item item-cart">
			<form>
				<td class="cart_product">
					<a href="<?php echo $this->item->product->link; ?>"><img src="<?php echo $this->item->product->mini_small_img; ?>" alt="" /></a>
				</td>
				<td class="cart">
					<h5><a class="product_link" href="<?php echo $this->item->product->link; ?>" title="<?php echo $this->item->product->title; ?>"><?php echo $this->item->product->title; ?></a></h5>
					<?php if($this->item->product->product_code !='' ){ ?>
					<div class="clearfix insset-bottom">
						<span class="title-th">Артикул</span> <span><?php echo $this->item->product->product_code; ?></span>
					</div>
					<?php } ?>
					<?php if($this->item->product->manufacturer_title !='' ){ ?>
					<div class="clearfix insset-bottom">
						<span class="title-th">Производитель</span> <span><?php echo $this->item->product->manufacturer_title; ?></span>
					</div>
					<?php } ?>
					<?php foreach($this->item->properties as $item_property){
					if (!empty($item_property->value)){ ?>
						<div class="clearfix insset-bottom">
							<span class="title-th"><?php echo $item_property->title; ?></span> <span><?php echo $item_property->value; ?></span>
						</div>
					<? } else { ?>
						<div class="clearfix insset-bottom">
							<span class="title-th"><?php echo $item_property->title; ?></span>
						</div>
					<?
					} } ?>
					<div class="clearfix insset-bottom">
						<span class="title-th cart_price_title">Цена:</span>
						<span class="price"><? echo $this->item->product->val_price; ?></span>
					</div>
					<div class="clearfix insset-bottom">
						<span class="title-th cart_quantity_title">Кол-во:</span>
						<div class="cart_quantity">
							<div id="cart_quantity_button" class="cart_quantity_button" style="float:left;">
								<div class="quant quantt">
									<span class="minus cart_quantity_down"></span>
									<input type="text" 
										price="<?php echo $this->item->price?>" 
										product_id="<?php echo $this->item->product->id?>" 
										product_packaging="<?php echo $this->item->product->product_packaging?>" 
										count="<?php echo $this->item->count?>" 
										data-item_id="<?php echo $this->item->id?>" 
										class="cart_quantity_input inputbox" 
										value="<?php echo $this->item->count?>" />
									<span class="plus cart_quantity_up"></span>
								</div>
								<div class="div_cart_quantity_delete del">
									<a class="cart_quantity_delete" data-item_id="<?php echo $this->item->id?>" href="<?php echo $this->item->del_link; ?>"></a>
								</div>
							</div>
						</div>
					</div>
					<span class="title-th cart_price_title">Сумма:</span>
					<span class="price total-pr totall" id="total_product_price_1_0_0"><? echo KSMPrice::showPriceWithTransform($this->item->price*$this->item->count); ?></span>
					<input type="hidden" class="product_packaging" value="<?php echo $this->item->product->product_packaging; ?>" />
				</td>
			</form>	
</tr>