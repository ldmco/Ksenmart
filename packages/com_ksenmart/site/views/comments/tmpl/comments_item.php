<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<div class="ksm-comments-item">
	<?php if($this->review->product): ?>
	<div class="ksm-comments-item-image">
		<a href="<?php echo $this->review->product->link; ?>"><img src="<?php echo $this->review->product->mini_small_img; ?>" alt="" /></a>
	</div>
	<?php endif;?>
	<div class="ksm-comments-item-info">
		<div class="ksm-comments-item-name"><?php echo $this->review->name; ?></div>
		<div class="ksm-reviews-item-rating">
			<?php for($k = 1; $k < 6; $k++): ?>
				<?php if(floor($this->review->rate) >= $k): ?>
					<img src="<?php echo JURI::root()?>components/com_ksenmart/images/star-small.png" alt="" />
				<?php else: ?>
					<img src="<?php echo JURI::root()?>components/com_ksenmart/images/star2-small.png" alt="" />
				<?php endif; ?>
			<?php endfor; ?>
		</div>
		<div class="ksm-reviews-item-comment">
			<?php echo nl2br(mb_substr($this->review->comment, 0, $this->params->get('count_symbol', 400))); ?>
		</div>
		<div class="ksm-comments-item-more">
			<a href="<?php echo JRoute::_('index.php?option=com_ksenmart&view=comments&layout=comment&id='.$this->review->id); ?>" title="<?php echo JText::_('KSM_REVIEW_MORE'); ?>"><?php echo JText::_('KSM_REVIEW_MORE'); ?></a>
		</div>
	</div>
</div>