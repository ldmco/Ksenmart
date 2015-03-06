<?
defined( '_JEXEC' ) or die( '=;)' );
?>
<div class="unit">
	<div class="top">
		<div class="left"><h1><?=$this->product->title?></h1></div>	
		<div class="right">
			<div class="to-fav"><a prd_id="<?=$this->product->id?>" href="#"><?php echo JText::_('KSM_PRODUCT_ADD_TO_FAVORITES_TITLE'); ?></a></div>
			<a href="<?=$this->links[0]?>" class="prev"><span></span></a>
			<a href="<?=$this->links[1]?>" class="next"><span></span></a>
		</div>
	</div>	
	<div class="itself">
		<div class="images">
			<div class="big">
				<a href="<?=$this->product->img_link?>" onclick="return hs.expand(this)"><img src="<?=$this->product->img?>" alt="" /></a>
			</div>
			<div class="thumbs">
				<?
				foreach($this->images as $image)
				{
				?>
				<div class="thumb"><a href="<?=$image->img_link?>" onclick="return hs.expand(this)"><img src="<?=$image->img_small?>" alt="" /></a></div>
				<?
				}
				?>
			</div>
		</div>	
		<div class="info">
			<?
			if ($this->product->product_code!='')
			{
			?>
			<div class="article">
				<?php echo  JText::_('KSM_PRODUCT_SKU'); ?>: <?=$this->product->product_code?>
			</div>
			<?
			}
			?>	
			<div class="rating">
				<?
				for($k=1;$k<6;$k++)
				{
				if (floor($this->product->rate->rate)>=$k)
				{
				?>
				<img src="<?=JURI::root()?>components/com_ksenmart/images/star2.png" alt="" />
				<?
				}
				else
				{
				?>
				<img src="<?=JURI::root()?>components/com_ksenmart/images/star.png" alt="" />
				<?
				}
				}
				?>
				&nbsp;<span><?=$this->product->rate->count?> <?php echo JText::_('KSM_PRODUCT_RATES_AMOUNT'); ?></span>
			</div>
			<?
			if ($this->product->introcontent!='')
			{
			?>
			<div class="minidesc"><?=html_entity_decode($this->product->introcontent)?></div>
			<?
			}			
			?>	
			<form action="<?=$this->product->add_link_cart?>" method="post">
				<div class="options">
					<div class="row">
						<div class="prop-name"><?php echo $this->childs_title?>:<span class="err"><?php echo JText::_('KSM_PRODUCT_SELECTABLE_FETURES_SELECT_VALUE');?></span></div>
						<div class="prop">
							<select class="sel" id="property_childs" name="property_childs">
							<option value="0">Выбрать</option>
							<?
							foreach($this->childs as $child)
							{
							?>
							<option value="<?php echo JRoute::_('index.php?option=com_ksenmart&view=product&id='.$child->id.':'.$child->alias)?>" <?php echo ($child->id==$this->product->id?'selected':'')?>><?=$child->title?></option>
							<?
							}
							?>
							</select>	
						</div>
					</div>				
					<?require ('product_selectable_properties.php');?>	
				</div>
				<div class="prices">
					<div class="price"><strong><?=$this->product->val_price?></strong></div>
					<a href="#" prd_id="<?=$this->product->id?>"><?php echo JText::_('KSM_PRODUCT_WATCH_PRICE_TITLE'); ?></a>
				</div>
				<?
				if (KsenmartHelper::getSettingsValue('catalog_mode')==0)
				{
				?>				
				<div class="buy">
					<button class="button"><p><?php echo JText::_('KSM_PRODUCT_ADDTOCART_BUTTON_TEXT'); ?></p><span></span></button>
				</div>
				<?
				}
				?>				
				<input type="hidden" name="price" value="<?=$this->product->val_price_wou?>">	
				<input type="hidden" name="id" value="<?=$this->product->id?>">	
				<input type="hidden" name="count" value="1">					
			</form>
			<div class="cond">
				<h4><?php echo JText::_('KSM_PRODUCT_CUSTOM_FEATURES_TITLE')?></h4>
				<?require ('product_fixed_properties.php');?>	
			</div>	
			<div class="social">
				<h4><?php echo JText::_('KSM_PRODUCT_SOCIAL_LINKS_TITLE'); ?></h4>
				<script type="text/javascript" src="//yandex.st/share/share.js" charset="utf-8"></script>
				<div class="yashare-auto-init" data-yashareL10n="ru" data-yashareType="none" data-yashareQuickServices="vkontakte,facebook,twitter,odnoklassniki,moimir,gplus"></div> 
			</div>				
		</div>
	</div>
	<?
	if ($this->product->content!='')
	{
	?>	
	<div class="desc">
		<h4><?php echo JText::_('KSM_PRODUCT_DESCRIPTION_TITLE')?></h4>
		<?=html_entity_decode($this->product->content)?>
	</div>
	<?
	}
	?>
	<div class="reviews">
		<div class="head">
			<h4><?php echo JText::_('KSM_PRODUCT_REVIES_TITLE')?></h4>
			<?
			if (KsenMartHelper::getSettingsValue('show_comment_form')==1)
				require_once('product_comment_form.php');
			?>			
		</div>
		<?
		if (count($this->product->comments)>0)
		{
		?>
		<div class="items">
			<?
			$i=0;
			foreach($this->product->comments as $comment)
			{
			$i++;
			$user=KsenmartHelper::getUser($comment->user);
			if ($i==3) echo '<div class="all-comments">';
			?>
			<div class="item">
				<div class="w100">
					<div class="ava"><a href="javascript:void(0)"><img src="<?=JURI::root().$user->logo?>" alt="" /></a></div>
					<div class="info">
						<div class="name"><?=$comment->name?></div>
						<div class="rating">
							<?
							for($k=1;$k<6;$k++)
							{
							if (floor($comment->rate)>=$k)
							{
							?>
							<img src="<?=JURI::root()?>components/com_ksenmart/images/star-small.png" alt="" />
							<?
							}
							else
							{
							?>
							<img src="<?=JURI::root()?>components/com_ksenmart/images/star2-small.png" alt="" />
							<?
							}
							}
							?>
						</div>
					</div>
				</div>
				<div class="txt">
					<?=$comment->comment?>
				</div>
			</div>
			<?
			}
			if ($i>2) echo '</div>';
			?>
		</div>
		<?
		if ($i>2)
		{
		?>
		<div class="show-all"><a href="#"><?php echo JText::_('KSM_SHOW_ALL')?></a></div>
		<?
		}
		}
		else
		{
		?>
		<h4><?php echo JText::_('KSM_PRODUCT_NO_REVIEWS_TITLE')?></h4>
		<?
		}
		?>
	</div>	
	<?
	if (count($this->product->sets)>0)
	{
	?>
	<div class="sets">
		<h3><?php echo JText::_('KSM_PRODUCT_SETS_TITLE'); ?></h3>
		<div class="items">
			<?
			foreach($this->product->sets as $set)
			{
			?>		
			<div class="item">
				<div class="photo"><a href="<?=$set->link?>"><img src="<?=$set->small_img?>" alt=""></a></div>
				<div class="desc">
					<div class="name"><a href="<?=$set->link?>"><?=$set->title?></a></div>
					<div class="price">
						<p><?php echo JText::_('KSM_PRODUCT_ECONOMY_TITLE')?></p>
						<?=$set->val_diff_price?></span>
					</div>
					<?
					if (KsenmartHelper::getSettingsValue('catalog_mode')==0)
					{
					?>					
					<form action="<?=$this->product->add_link_cart?>" method="post">				
						<div class="buy"><button class="button"><?php echo JText::_('KSM_BUY_BUTTON_TEXT'); ?></button></div>
						<input type="hidden" name="price" value="<?=$this->product->price?>">	
						<input type="hidden" name="id" value="<?=$set->id?>">	
						<input type="hidden" name="count" value="1">						
					</form>	
					<?
					}
					?>					
				</div>
			</div>
			<?
			}
			?>
		</div>
	</div>	
	<?
	}
	?>
</div>
<?require ('product_related.php');?>