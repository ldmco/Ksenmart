<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>

<div class="km-discounts">
	<?php foreach($discounts as $discount):?>
	<div class="km-discount">
		<?php if (in_array('title', $params->get('show', array('title')))):?>
		<div class="km-discount-title">
			<h2><?php echo $discount->title;?></h2>
		</div>
		<?php endif;?>
		<?php if (in_array('image', $params->get('show', array('title'))) && !empty($discount->image)):?>
		<div class="km-discount-image" style="<?php echo $discount->img_div_style; ?>">
			<img src="<?php echo $discount->image; ?>">
		</div>	
		<?php endif;?>
		<?php if (in_array('content', $params->get('show', array('title')))):?>
		<div class="km-discount-content">
			<?php echo $discount->content;?>
		</div>		
		<?php endif;?>
	</div>
	<?php endforeach;?>
</div>	