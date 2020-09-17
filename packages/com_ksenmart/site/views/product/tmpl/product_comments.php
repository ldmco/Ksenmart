<?php
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>
<div class="ksm-product-comments">
    <div itemprop="aggregateRating" itemscope="" itemtype="http://schema.org/AggregateRating">
        <meta itemprop="worstRating" content="1">
        <meta itemprop="ratingValue" content="<?php echo $this->product->rate->rate; ?>">
        <meta itemprop="bestRating" content="5">
        <meta itemprop="reviewCount" content="<?php echo $this->product->rate->count; ?>">
    </div>
	<?php if (count($this->product->comments) > 0) { ?>
		<?php $i = 0;
		foreach ($this->product->comments as $comment) {
			$i++; ?>
            <div class="ksm-product-comment">
                <div class="ksm-product-comment-name">
					<?php if (empty($comment->name)): ?>
						<?php echo $comment->comment_name; ?>
					<?php else: ?>
						<?php echo $comment->name; ?>
					<?php endif; ?>
                </div>
                <div class="ksm-product-comment-rating">
					<?php for ($k = 1; $k < 6; $k++): ?>
						<?php if (floor($comment->rate) >= $k): ?>
                            <img src="<?php echo JURI::root() ?>components/com_ksenmart/images/star-small.png" alt=""/>
						<?php else: ?>
                            <img src="<?php echo JURI::root() ?>components/com_ksenmart/images/star2-small.png" alt=""/>
						<?php endif; ?>
					<?php endfor; ?>
                </div>
                <div class="ksm-product-comment-text">
                    <label><?php echo JText::_('KSM_COMMENTS_LABEL_TEXT'); ?></label>
                    <div>
						<?php echo $comment->comment; ?>
                    </div>
                </div>
                <div class="ksm-product-comment-text">
                    <label><?php echo JText::_('KSM_COMMENTS_LABEL_PLUS'); ?></label>
                    <div>
						<?php echo $comment->good; ?>
                    </div>
                </div>
                <div class="ksm-product-comment-text">
                    <label><?php echo JText::_('KSM_COMMENTS_LABEL_MINUS'); ?></label>
                    <div>
						<?php echo $comment->bad; ?>
                    </div>
                </div>
            </div>
		<?php } ?>
	<?php } else { ?>
        <h3><?php echo JText::_('KSM_COMMENTS_LABEL_NOCOMMENT'); ?></h3>
	<?php } ?>
</div>