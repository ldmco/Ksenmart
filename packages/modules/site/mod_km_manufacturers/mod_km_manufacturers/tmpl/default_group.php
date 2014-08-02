<?php defined('_JEXEC') or die; ?>
<?php foreach($manufacturers as $country => $manufacturer): ?>
	<div>
	<h4><?php echo $country; ?></h4>
	<?php foreach($manufacturer as $brand): ?>
	<ul>
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
	</ul>
	<?php endforeach;?>
	</div>
<?php endforeach;?>