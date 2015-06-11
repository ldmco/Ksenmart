<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<div id="catalog">
	<h2><?php echo JText::_('KSM_REVIEW_TITLE'); ?></h2>
	<div id="review">
		<div class="top">
			<div class="name">
				<?php if (isset($this->comment->user->social)):?>
				<div class="socia"><img src="<?php echo JURI::root()?>components/com_ksenmart/css/i/<?php echo $this->comment->user->social_name?>.png" alt=""></div>
				<?php endif;?>
				<span><?php echo ($this->comment->user->name!='' ? $this->comment->user->name : JText::_('KSM_USERS_ANONYM'))?></span>
			</div>
			<div class="date">
				<?php echo KSSystem::formatCommentDate($this->comment->date_add)?>
			</div>	
			<div class="rate">
				<?php for($k=1;$k<6;$k++):?>
				<?php if (floor($this->comment->rate)>=$k):?>
				<img src="<?php echo JURI::base()?>components/com_ksenmart/images/star-small.png" alt="" />
				<?php else:?>
				<img src="<?php echo JURI::base()?>components/com_ksenmart/images/star2-small.png" alt="" />
				<?php endif;?>
				<?php endfor;?>
			</div>				
		</div>
		<div class="txt">
			<?php if ($this->comment->product!=0):?>
			<div class="product">
				<a href="<?php echo JRoute::_('index.php?option=com_ksenmart&view=product&id='.$this->comment->product.'&Itemid='.KSSystem::getShopItemid())?>"><div style="<?php echo $this->comment->product_img_div_style;?>"><img style="<?php echo $this->comment->product_img_div_style;?>" src="<?php echo $this->comment->product_img;?>" alt=""></div></a>
			</div>	
			<?php endif;?>		
			<?php echo $this->comment->comment?>
		</div>
		<?php if (strip_tags($this->comment->good)!=''):?>
		<br clear="both">
		<br>
		<b><?php echo JText::_('KSM_REVIEW_GOOD'); ?></b>
		<div class="txt">
			<?php echo $this->comment->good?>
		</div>	
		<?php endif;?>	
		<?php if (strip_tags($this->comment->bad)!=''):?>
		<br clear="both">
		<br>
		<b><?php echo JText::_('KSM_REVIEW_BAD'); ?></b>
		<div class="txt">
			<?php echo $this->comment->bad?>
		</div>			
		<?php endif;?>	
	</div>		
</div>