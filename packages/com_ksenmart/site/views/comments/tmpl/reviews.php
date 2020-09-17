<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<div class="ksm-reviews ksm-block">
	<h2><?php echo JText::_('KSM_SHOP_REVIEWS_PATH_TITLE'); ?></h2>
	<?php if(!$this->show_shop_review && KSUsers::getUser()->id > 0): ?>
		<a href="javascript:void(0);" class="ksm-reviews-add"><?php echo JText::_('KSM_SHOP_REVIEW_ADD'); ?></a>
		<div class="ksm-reviews-add-form">
			<form method="post">
				<?php if(empty(KSUsers::getUser()->name)){ ?>
				<div class="ksm-reviews-add-form-row">
					<input type="text" name="name" placeholder="<?php echo JText::_('KSM_SHOP_REVIEW_ADD_NAME'); ?>" required="true" />
				</div>
				<?php } ?>
				<div class="ksm-reviews-add-form-row ksm-reviews-add-form-row-rate">
					<img data-rate="1" src="<?php echo JURI::root(); ?>components/com_ksenmart/images/star2-small.png" alt="">
					<img data-rate="2" src="<?php echo JURI::root(); ?>components/com_ksenmart/images/star2-small.png" alt="">
					<img data-rate="3" src="<?php echo JURI::root(); ?>components/com_ksenmart/images/star2-small.png" alt="">
					<img data-rate="4" src="<?php echo JURI::root(); ?>components/com_ksenmart/images/star2-small.png" alt="">
					<img data-rate="5" src="<?php echo JURI::root(); ?>components/com_ksenmart/images/star2-small.png" alt="">
				</div>
				<div class="ksm-reviews-add-form-row">
					<textarea name="review" placeholder="<?php echo JText::_('KSM_SHOP_REVIEW_ADD_COMMENT'); ?>" required="true"></textarea>
				</div>
				<div class="ksm-reviews-add-form-row">
					<input type="submit" value="<?php echo JText::_('KSM_SHOP_REVIEW_ADD_BUTTON'); ?>" />
				</div>
				<input type="hidden" name="task" value="comments.add_shop_review" />
				<input type="hidden" name="rate" value="0">
			</form>
			<hr />
		</div>
	<?php endif; ?>
	<?php if(!empty($this->reviews)): ?>
		<div class="ksm-reviews-items">
			<?php foreach($this->reviews as $review): ?>
				<div class="ksm-reviews-item">
					<div class="ksm-reviews-item-name"><?php echo $review->name; ?></div>
					<div class="ksm-reviews-item-rating">
						<?php for($k = 1; $k < 6; $k++): ?>
							<?php if(floor($review->rate) >= $k): ?>
								<img src="<?php echo JURI::root()?>components/com_ksenmart/images/star-small.png" alt="" />
							<?php else: ?>
								<img src="<?php echo JURI::root()?>components/com_ksenmart/images/star2-small.png" alt="" />
							<?php endif; ?>
						<?php endfor; ?>
					</div>
					<div class="ksm-reviews-item-comment">
						<?php echo nl2br(mb_substr($review->comment, 0, $this->params->get('count_symbol', 400))); ?>
					</div>
					<div class="ksm-reviews-item-more">
						<a href="<?php echo JRoute::_('index.php?option=com_ksenmart&view=comments&layout=review&id='.$review->id); ?>" title="<?php echo JText::_('KSM_REVIEW_MORE'); ?>"><?php echo JText::_('KSM_REVIEW_MORE'); ?></a>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</div>