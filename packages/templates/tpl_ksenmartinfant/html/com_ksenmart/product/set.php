<? defined( '_JEXEC' ) or die( '=;)' ); ?>
<?php echo $this->loadTemplate('toplinks', 'product');?>
<?php echo $this->loadTemplate('title', 'product');?>		
<div id="primary_block" class="clearfix">
		<!--ADD CUSTOM CLOUD ZOOM!!!-->
<!-- Call quick start function. -->
<!-- right infos-->
<div class="row">
<div id="pb-right-column" class="span4 setst">
		<?php echo $this->loadTemplate('gallery', 'product'); ?>
	</div>
	<!-- left infos-->
	<div id="pb-left-column" class="span5">
        <div id="short_description_block">
			<div id="short_description_content" class="rte align_justify">
				<? if ($this->product->product_code!=''){ ?>
				<div class="control-group">
					<label class="control-label">Артикул:</label>
					<div class="controls">
						<span class="article muted"><?php echo $this->product->product_code; ?></span>
					</div>
				</div>
				<? } ?>
				<?php if ($this->product->introcontent != '') {?>
				<div class="control-group">
					<label class="control-label">Коротко о главном:</label>
					<div class="controls">
						<div class="minidesc"><?php echo html_entity_decode($this->product->introcontent)?></div>
					</div>
				</div>
				<?php } ?>

			<?php if ($this->params->get('only_auth_buy',0)==0 || ($this->params->get('only_auth_buy',0)!=0 && JFactory::getUser()->id!=0)):?>
			<div class="content_prices clearfix">
				<!-- prices -->
				<div class="row-2">
					<p id="old_price">
						<span class="price">
							<label class="control-label">Ваша экономия:</label>
							<span id="old_price_display"><?php echo $this->product->val_diff_price; ?></span>
						</span>
                    </p>
                </div>
				<div class="row_1">
						<p class="our_price_display">
							<label class="control-label">Цена комплекта:</label>
							<?php echo $this->product->val_price; ?>
						</p>
						<p id="add_to_cart" class="buttons_bottom_block">
							<? if ($this->params->get('catalog_mode',0)==0){ ?>				
								<span class="to-order lead">
									<a href="javascript:void(0);" class="btn exclusive button btn_add_cart link_b_border lrg">Перейти к покупке</a>
								</span>
							<? } ?>	
						</p>
				</div>
                <div class="other-prices"></div>
            </div>
			<?php endif;?>	
		</div>
	</div>
	</div>
</div>
</div>
	<? if($this->set_related){ ?>
	<div class="sets catalog page_product_box set">
		<h3 class="toggle"><span>Сэкономь</span> купив комплектом</h3>
		<div class="row layout_grid layout_block">
		<form action="<?php echo $this->product->add_link_cart; ?>" method="post">
			<ul id="product_list" class="row items catalog-items">
			<?
			$k=0;
			foreach($this->set_related as $product){$k++; ?>
                <li class="ajax_block_product span3 shop_box">
					<a class="product_img_link" href="<?php echo $product->link; ?>" title="<?php echo $product->title; ?>">
						<img src="<?php echo $product->small_img; ?>" alt="<?php echo $product->title; ?>" />
					</a>
					<div class="center_block">
						<h3><a class="product_link" href="<?php echo $product->link; ?>" title="<?php echo $product->title; ?>"><?php echo $product->title; ?></a></h3>
						<div class="product_desc">
							<div class="options">
								<?php require('product_properties.php'); ?>	
							</div>
						</div> 
					</div>
				</li>
			<?php }	?>
			</ul>
			<?php if($this->params->get('only_auth_buy',0) == 0 || ($this->params->get('only_auth_buy',0) != 0 && JFactory::getUser()->id != 0)){ ?>
			<div class="bottom row-fluid" style="margin:20px 0 0;">
				<div style="float:left;">
					<div class="row-2">
						<p id="old_price">
							<span class="price">
								<label class="control-label">Ваша экономия:</label>
								<span id="old_price_display"><?php echo $this->product->val_diff_price; ?></span>
							</span>
						</p>
					</div>
					<div class="row_1">
						<p class="our_price_display">
							<label class="control-label">Цена комплекта:</label>
							<?php echo $this->product->val_price; ?>
						</p>
					</div>
				</div>
				<?php if ($this->params->get('catalog_mode',0) == 0){ ?>
					<span class="buy" style="float:left;margin:45px 0 0 50px;">
						<button type="submit" id="add2cartbtn" class="btn exclusive button btn_add_cart"><b></b> <span>В корзину</span></button>
					</span>
				<?php } ?>
			</div>
			<?php } ?>
			<input type="hidden" name="price" value="<?php echo $this->product->price; ?>" />
			<input type="hidden" name="id" value="<?php echo $this->product->id; ?>" />
			<input type="hidden" name="product_packaging" class="product_packaging" value="<?php echo $this->product->product_packaging; ?>" />
			<input type="hidden" name="count" value="<?php echo $this->product->product_packaging; ?>" />
		</form>
		</div>
	<div class="bg"></div>
	</div>
	<? } ?>
    <aside class="tabbable descs noTransition">
        <ul class="nav nav-tabs">
            <?php if(!empty($this->product->content)){ ?>
            <li class="active"><a href="#tab1" data-toggle="tab">Описание</a></li>
            <?php } ?>
            <li<?php echo empty($this->product->content)?' class="active"':''; ?>><a href="#tab3" data-toggle="tab">Отзывы</a></li>
            <li><a href="#tab4" data-toggle="tab">Оставить отзыв</a></li>
        </ul>
        <div class="tab-content">
            <?php if(!empty($this->product->content)){ ?>
            <div class="tab-pane active" id="tab1">
                <?php echo html_entity_decode($this->product->content); ?>
            </div>
            <?php } ?>
            <div class="tab-pane reviews<?php echo empty($this->product->content)?' active':''; ?>" id="tab3">
            	<aside>
            	<? if (count($this->product->comments)>0) { ?>
            		<? $i=0;foreach($this->product->comments as $comment) {
            		$i++;
            		$user = KSUsers::getUser($comment->user);
            		if ($i==3) echo '<div class="all-comments">'; ?>
            		<article class="item row-fluid reviews">
            			<div class="span2">
            				<div class="ava"><a href="javascript:void(0)"><img src="<?php echo $comment->logo_thumb; ?>" alt="" /></a></div>
            				<div class="info">
            					<div class="name"><?php echo $comment->name?></div>
            					<div class="rating">
            						<? for($k=1;$k<6;$k++) {
            							if (floor($comment->rate)>=$k) { ?>
            						<img src="<?php echo JURI::root()?>components/com_ksenmart/images/star-small.png" alt="" />
            						<? } else { ?>
            						<img src="<?php echo JURI::root()?>components/com_ksenmart/images/star2-small.png" alt="" />
            						<? }
            						} ?>
            					</div>
            				</div>
            			</div>
            			<div class="span10">
            				<dl class="dl-horizontal">
            				  <dt>Комментарий</dt>
            				  <dd><?php echo $comment->comment; ?></dd>
            				  <dt class="text-success">Плюсы</dt>
            				  <dd class="text-success"><?php echo $comment->good; ?></dd>
            				  <dt class="text-error">Минусы</dt>
            				  <dd class="text-error"><?php echo $comment->bad; ?></dd>
            				</dl>
            			</div>
            		</article>
            		<? } 
            		if($i > 2) echo '</div>';
            		if($i > 2) { ?>
            			<div class="show-all"><a href="javascript:void(0);">Показать все</a></div>
            		<? }
            		} else {
            		?>
            		<h4 class="text-center gray">Нет отзывов</h4>
            		<? } ?>
            	</aside>
            </div>
            <div class="tab-pane" id="tab4">
			<? if ($this->params->get('show_comment_form') == 1){
				require_once('product_comment_form.php');
            } ?>
            </div>
        </div>
    </aside>
</article>