<?php defined('_JEXEC') or die; ?>
<div class="ksm-manufacturers">
	<ul>
		<?php foreach($manufacturers as $manufacturer): ?>
		<li class="<?php echo ($manufacturer->selected?'active':''); ?>">
			<span>
			<?php if (property_exists($manufacturer,'small_img')):?>
			<img src="<?php echo $manufacturer->small_img; ?>" alt="<?php echo $manufacturer->title; ?>">
			<?php endif;?>
			</span>
			<a href="<?php echo $manufacturer->link; ?>">
				<?php echo $manufacturer->title; ?>
			</a>
		</li>
		<?php endforeach;?>
	</ul>
</div>