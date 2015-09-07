<?php defined('_JEXEC') or die; ?>
<article class="row-fluid item" id="review_<?php echo $this->review->id; ?>">
	<div class="span2 avatar">
		<a href="javascript:void(0)" title="<?php echo $this->review->user->name; ?>">
			<img src="<?php echo $this->review->user->medium_img; ?>" alt="<?php echo $this->review->user->name; ?>" class="border_ksen" />
		</a>
		<div class="name"><?php echo $this->review->user->name; ?></div>
	</div>
	<div class="span10 comment_wrapp row-fluid">
		<?php if($this->review->product): ?>
		<div class="span2">
			<div class="photo">
				<a href="<?php echo $this->review->product->link; ?>"><img class="border_ksen" src="<?php echo $this->review->product->mini_small_img; ?>" alt="" /></a>
			</div>
		</div>
		<?php endif;?>
		<div class="span10">
			<div class="rating">
				<?php for($k=1;$k<6;$k++) {
					if(floor($this->review->rate) >= $k){ ?>
				<img src="<?php echo JURI::root()?>components/com_ksenmart/images/star-small.png" alt="" />
				<?php }else{ ?>
				<img src="<?php echo JURI::root()?>components/com_ksenmart/images/star2-small.png" alt="" />
				<?php }
				} ?>
			</div>
			<div class="row-fluid comment">
				<?php echo nl2br(mb_substr($this->review->comment, 0, $this->params->get('count_symbol', 400))); ?>
			</div>
			<div class="read_more">
				<a href="<?php echo JRoute::_('index.php?option=com_ksenmart&view=comments&layout=comment&id='.$this->review->id); ?>" title="Подробнее">Подробнее</a>
			</div>
		</div>
	</div>
</article>