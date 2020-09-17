<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<div class="ksm-comment ksm-block">
	<h2><?php echo JText::_('KSM_REVIEW_TITLE'); ?></h2>
	<div class="ksm-comment-image">
		<a href="<?php echo $this->comment->product->link; ?>"><img class="border_ksen" src="<?php echo $this->comment->product->mini_small_img; ?>" alt="<?php echo $this->comment->product->title; ?>" /></a>
	</div>
	<div class="ksm-comment-info">
		<div class="ksm-comment-product">
			<a href="<?php echo $this->comment->product->link; ?>" title="<?php echo $this->comment->product->title; ?>"><?php echo $this->comment->product->title; ?></a>
		</div>
		<div class="ksm-comment-name">
			<?php echo $this->comment->name; ?>
		</div>		
		<div class="ksm-comment-rating">
			<?php for($k=1;$k<6;$k++): ?>
				<?php if(floor($this->comment->rate) >= $k): ?>
					<img src="<?php echo JURI::root()?>components/com_ksenmart/images/star-small.png" alt="" />
				<?php else: ?>
					<img src="<?php echo JURI::root()?>components/com_ksenmart/images/star2-small.png" alt="" />
				<?php endif; ?>
			<?php endfor; ?>
		</div>		
	</div>
	<div class="ksm-comment-txts">
		<div class="ksm-comment-txt">
			<?php echo nl2br($this->comment->comment); ?>
		</div>
		<?php if (strip_tags($this->comment->good) != ''): ?>
			<div class="ksm-comment-txt">
				<b><?php echo JText::_('KSM_REVIEW_GOOD'); ?></b>
				<?php echo $this->comment->good; ?>
			</div>
		<?php endif;?>
		<?php if (strip_tags($this->comment->bad) != ''): ?>
			<div class="ksm-comment-txt">
				<b><?php echo JText::_('KSM_REVIEW_BAD'); ?></b>
				<?php echo $this->comment->bad; ?>
			</div>
		<?php endif;?>
	</div>
</div>
