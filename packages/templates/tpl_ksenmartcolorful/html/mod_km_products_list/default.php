<? defined('_JEXEC') or die; ?>
<div class="catalog<?php echo $params->get('moduleclass_sfx'); ?>">
    <h3><? echo $module->title; ?></h3>
	<div class="row-fluid">
		<ul class="thumbnails items catalog-items">
			<?php foreach($products as $product) { ?>
				<?php echo KSSystem::loadTemplate(array('product' => $product, 'params' => $com_params)); ?>
			<?php } ?>
		</ul>
    </div>
</div>