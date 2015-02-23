<?php
defined( '_JEXEC' ) or die;
?>
<div id="catalog">
	<h2>Просмотр отзыва</h2>
	<div id="review">
		<div class="top">
			<div class="name">
				<?php if (isset($this->comment->user->social)):?>
				<div class="socia"><img src="<?php echo JURI::root()?>components/com_ksenmart/css/i/<?php echo $this->comment->user->social_name?>.png" alt=""></div>
				<?php endif;?>
				<span><?php echo ($this->comment->name!=''?$this->comment->name:'Аноним')?></span>
			</div>
			<div class="date">
				<?php echo KsenmartHelper::formatCommentDate($this->comment->date_add)?>
			</div>	
			<div class="rate">
				<?php for($k=1;$k<6;$k++):?>
				<?php if (floor($this->comment->rate)>=$k):?>
				<img src="<?php echo JURI::base()?>components/com_ksenmart/images/stara.png" alt="" />
				<?php else:?>
				<img src="<?php echo JURI::base()?>components/com_ksenmart/images/star.png" alt="" />
				<?php endif;?>
				<?php endfor;?>
			</div>				
		</div>
		<div class="txt">
			<?php if ($this->comment->product!=0):?>
			<div class="product">
				<a href="<?php echo JRoute::_('index.php?option=com_ksenmart&view=product&id='.$this->comment->product.'&Itemid='.KsenmartHelper::getShopItemid())?>"><div style="<?php echo $this->comment->product_img_div_style;?>"><img style="<?php echo $this->comment->product_img_div_style;?>" src="<?php echo $this->comment->product_img;?>" alt=""></div></a>
			</div>	
			<?php endif;?>		
			<?php echo $this->comment->comment?>
		</div>
		<?php if (strip_tags($this->comment->good)!=''):?>
		<br clear="both">
		<br>
		<b>Достоинства</b>
		<div class="txt">
			<?php echo $this->comment->good?>
		</div>	
		<?php endif;?>	
		<?php if (strip_tags($this->comment->bad)!=''):?>
		<br clear="both">
		<br>
		<b>Недостатки</b>
		<div class="txt">
			<?php echo $this->comment->bad?>
		</div>			
		<?php endif;?>	
	</div>		
</div>