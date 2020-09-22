<?php
defined('_JEXEC') or die;
?>
<div class="ksm-profile-reviews ksm-block">
	<?php if (!empty($view->shop_review)): ?>
	<div class="ksm-profile-reviews-block ksm-profile-reviews-shop">
		<h3><?php echo JText::_('PLG_USER_KSENMART_REVIEWS_SHOP_LBL'); ?></h3>
		<div class="ksm-profile-reviews-item">
			<div class="ksm-profile-reviews-item-img">
				<img src="<?php echo JURI::root().$view->shop_review->img; ?>">
			</div>		
			<div class="ksm-profile-reviews-item-content">
				<a class="ksm-profile-reviews-item-del"></a>
				<div class="ksm-profile-reviews-item-content-rate">
					<b><?php echo JText::_('PLG_USER_KSENMART_REVIEWS_RATE_LBL'); ?></b>
					<div>
						<?php for($k = 1; $k < 6; $k++): ?>
							<?php if ($view->shop_review->rate >= $k): ?>
								<img src="<?php echo JURI::root(); ?>plugins/user/ksenmart/assets/images/star-small.png" alt="" />
							<?php else: ?>
								<img src="<?php echo JURI::root(); ?>plugins/user/ksenmart/assets/images/star2-small.png" alt="" />
							<?php endif; ?>					
						<?php endfor; ?>
					</div>
				</div>	
				<div class="ksm-profile-reviews-item-content-info">
					<b><?php echo JText::_('PLG_USER_KSENMART_REVIEWS_COMMENT_LBL'); ?></b>
					<div><?php echo $view->shop_review->comment; ?></div>				
				</div>					
			</div>
			<input type="hidden" name="<?php echo $view->name; ?>[]" value="<?php echo $view->shop_review->id; ?>">
		</div>
	</div>	
	<?php endif; ?>
	<?php if (!empty($view->comments)): ?>	
	<div class="ksm-profile-reviews-block ksm-profile-reviews-product">
		<h3><?php echo JText::_('PLG_USER_KSENMART_REVIEWS_PRODUCT_LBL'); ?></h3>
		<?php foreach($view->comments as $comment): ?>
		<div class="ksm-profile-reviews-item">
			<div class="ksm-profile-reviews-item-img">
				<a href="<?php echo $comment->link; ?>">
					<img src="<?php echo $comment->img; ?>">
				</a>
			</div>		
			<div class="ksm-profile-reviews-item-content">
				<a class="ksm-profile-reviews-item-del"></a>
				<div class="ksm-profile-reviews-item-content-rate">
					<b><?php echo JText::_('PLG_USER_KSENMART_REVIEWS_RATE_LBL'); ?></b>
					<div>
						<?php for($k = 1; $k < 6; $k++): ?>
							<?php if ($comment->rate >= $k): ?>
								<img src="<?php echo JURI::root(); ?>plugins/user/ksenmart/assets/images/star-small.png" alt="" />
							<?php else: ?>
								<img src="<?php echo JURI::root(); ?>plugins/user/ksenmart/assets/images/star2-small.png" alt="" />
							<?php endif; ?>					
						<?php endfor; ?>
					</div>
				</div>	
				<div class="ksm-profile-reviews-item-content-info">
					<b><?php echo JText::_('PLG_USER_KSENMART_REVIEWS_COMMENT_LBL'); ?></b>
					<div><?php echo $comment->comment; ?></div>				
				</div>		
				<?php if (!empty($comment->good)): ?>
				<div class="ksm-profile-reviews-item-content-info">
					<b><?php echo JText::_('PLG_USER_KSENMART_REVIEWS_GOOD_LBL'); ?></b>
					<div><?php echo $comment->good; ?></div>				
				</div>	
				<?php endif; ?>	
				<?php if (!empty($comment->bad)): ?>
				<div class="ksm-profile-reviews-item-content-info">
					<b><?php echo JText::_('PLG_USER_KSENMART_REVIEWS_BAD_LBL'); ?></b>
					<div><?php echo $comment->bad; ?></div>				
				</div>	
				<?php endif; ?>					
			</div>
			<input type="hidden" name="<?php echo $view->name; ?>[]" value="<?php echo $comment->id; ?>">
		</div>
		<?php endforeach; ?>
	</div>	
	<?php endif; ?>
	<div class="ksm-profile-reviews-noinfo" <?php echo (!empty($view->comments) || !empty($view->shop_review) ? 'style="display:none;"' : ''); ?>>
		<?php echo JText::_('PLG_USER_KSENMART_NOINFO_LBL'); ?>
	</div>	
</div>