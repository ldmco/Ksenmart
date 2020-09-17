<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<div class="ksm-review ksm-block">
	<h2><?php echo $this->review->name; ?></h2>
	<div class="ksm-review-rating">
		<?php for($k=1;$k<6;$k++): ?>
			<?php if(floor($this->review->rate) >= $k): ?>
				<img src="<?php echo JURI::root()?>components/com_ksenmart/images/star-small.png" alt="" />
			<?php else: ?>
				<img src="<?php echo JURI::root()?>components/com_ksenmart/images/star2-small.png" alt="" />
			<?php endif; ?>
		<?php endfor; ?>
	</div>
	<div class="ksm-review-comment">
		<?php echo nl2br($this->review->comment); ?>
	</div>
</div>