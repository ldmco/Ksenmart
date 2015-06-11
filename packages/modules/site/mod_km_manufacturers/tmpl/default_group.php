<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>

<?php foreach($manufacturers as $country => $manufacturer): ?>
	<ul class="nav nav-list">
		<li class="nav-header"><?php echo $country; ?></li>
		<?php foreach($manufacturer as $brand): ?>
			<li class="<?php echo ($brand->selected?'active':''); ?>">
				<span>
					<?php if (property_exists($brand, 'small_img')):?>
					<img src="<?php echo $brand->small_img; ?>" alt="<?php echo $brand->title; ?>">
					<?php endif;?>
					</span>
				<a href="<?php echo $brand->link; ?>" title="<?php echo $brand->title; ?>">
					<?php echo $brand->title; ?>
				</a>
			</li>
		<?php endforeach;?>
	</ul>
<?php endforeach;?>