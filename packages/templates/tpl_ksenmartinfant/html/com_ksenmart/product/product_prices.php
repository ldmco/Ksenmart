<?php defined( '_JEXEC' ) or die; ?>
<div class="content_prices prices clearfix">
	<!-- prices -->
	<?php if ($this->product->val_old_price_wou != 0 || $this->product->new || $this->product->recommendation || $this->product->hot){ ?>
	<div class="row-2">
		<?php if ($this->product->val_old_price_wou != 0){ ?>
		<p id="old_price">
			<span class="price">
				<span id="old_price_display price"><?php echo $this->product->val_old_price?></span>
			</span>
		</p>
		<?php } ?>
		<span class="on_sale">
			 <?php if($this->product->new || $this->product->recommendation || $this->product->hot){ ?>
			<span class="status_block">
				<?php if($this->product->new){ ?>
				<span class="new_line label label-success">Новый</span>
				<?php } ?>
				<?php if($this->product->recommendation){ ?>
				<span class="recomedation_line label label-info">Рекомендованный</span>
				<?php } ?>
				<?php if($this->product->hot){ ?>
				<span class="hit_line label label-important">Хит</span>
				<?php } ?>
			</span>
			<?php } ?>
		</span>
	</div>
	<?php } ?>
	<?php if ($this->params->get('only_auth_buy',0) == 0 || ($this->params->get('only_auth_buy',0) != 0 && JFactory::getUser()->id != 0)){ ?>
		<input type="hidden" name="price" value="<?php echo $this->product->val_price_wou?>">	
		<input type="hidden" name="id" value="<?php echo $this->product->id?>">	
		<input type="hidden" name="product_packaging" class="product_packaging" value="<?php echo $this->product->product_packaging?>">
		<div class="row_1">
			<p class="our_price_display price">
				<?php echo $this->product->val_price?>
			</p>
			<p id="add_to_cart" class="buttons_bottom_block">
				<? if ($this->params->get('catalog_mode',0)==0){ ?>				
					<span class="buy">
						<button type="submit" id="add2cartbtn" class="btn exclusive button btn_add_cart"><b></b> <span>В корзину</span></button>
					</span>
				<? } ?>	
			</p>
			<!-- quantity wanted -->
			<p id="quantity_wanted_p">
				<input type="text" id="inputQuantity" class="inputbox text text-center" name="count" size="2" value="<?php echo $this->product->product_packaging?>" />
				<label>Количество: </label>
			</p>
		</div>
	<?php } ?>
	<div class="other-prices"></div>
</div>
<a href="javascript:void(0);" data-prd_id="<?php echo $this->product->id; ?>" class="spy_price" data-toggle="popover" data-placement="bottom" title="" data-original-title="Авторизация"><i class="icon-eye-open"></i> Следить за ценой</a>
