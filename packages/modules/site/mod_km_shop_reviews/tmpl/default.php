<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>

<?php if(!empty($reviews)): ?>
<div class="ksm-module-shopreviews ksm-block <?php echo $moduleclass_sfx?>">
    <?php foreach($reviews as $review): ?>
        <div class="ksm-module-shopreviews-item">
			<div class="ksm-module-shopreviews-item-name"><?php echo $review->name; ?></div>
			<div class="ksm-module-shopreviews-item-rating">
				<?php for($k = 1; $k < 6; $k++): ?>
					<?php if (floor($review->rate) >= $k): ?>
						<img src="<?php echo JURI::root()?>modules/mod_km_shop_reviews/images/star-small.png" alt="" />
					<?php else: ?>
						<img src="<?php echo JURI::root()?>modules/mod_km_shop_reviews/images/star2-small.png" alt="" />
					<?php endif; ?>
				<?php endfor; ?>
			</div>
			<div class="ksm-module-shopreviews-item-comment">
				<?php echo $review->comment; ?>
				<a href="<?php echo $review->link; ?>" class="ksm-module-shopreviews-item-more"><?php echo JText::_('MODULE_KM_SHOP_REVIEWS_MORE'); ?></a>
			</div>
        </div>
    <?php endforeach; ?>
    <a href="<?php echo JRoute::_('index.php?option=com_ksenmart&view=comments&layout=reviews&Itemid='.$Itemid); ?>" class="ksm-module-shopreviews-link"><?php echo JText::_('MODULE_KM_SHOP_REVIEWS_ALL_REVIEWS'); ?></a>
</div>
<?php endif; ?>