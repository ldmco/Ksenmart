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
		<article class="row-fluid item" id="review_<?php echo $this->comment->id; ?>">
			<div class="span2 avatar">
				<a href="javascript:void(0)" title="<?php echo $this->comment->user->name; ?>">
					<img src="<?php echo $this->comment->user->medium_img; ?>" alt="<?php echo $this->comment->user->name; ?>" class="border_ksen" />
				</a>
				<div class="name"><?php echo $this->comment->user->name; ?></div>
			</div>
			<div class="span10 comment_wrapp row-fluid">
				<?php if($this->comment->product): ?>
				<div class="span2">
					<div class="photo">
						<a href="<?php echo $this->comment->product->link; ?>"><img class="border_ksen" src="<?php echo $this->comment->product->mini_small_img; ?>" alt="<?php echo $this->comment->product->title; ?>" /></a>
					</div>
					<div class="title"><a href="<?php echo $this->comment->product->link; ?>" title="<?php echo $this->comment->product->title; ?>"><?php echo $this->comment->product->title; ?></a></div>
				</div>
				<?php endif;?>
				<div class="span10">
					<div class="rating">
						<?php for($k=1;$k<6;$k++) {
							if(floor($this->comment->rate) >= $k){ ?>
						<img src="<?php echo JURI::root()?>components/com_ksenmart/images/star-small.png" alt="" />
						<?php }else{ ?>
						<img src="<?php echo JURI::root()?>components/com_ksenmart/images/star2-small.png" alt="" />
						<?php }
						} ?>
					</div>
					<div class="row-fluid comment">
						<?php echo nl2br($this->comment->comment); ?>
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
		</article>
	</div>
</div>