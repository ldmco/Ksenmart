<?php defined('_JEXEC') or die; ?>
<div class="catalog<?php echo $params->get('moduleclass_sfx'); ?>">
    <h3><?php echo $module->title; ?></h3>
	<div class="row-fluid">
        <ul class="thumbnails items catalog-items">
        <?php foreach($products as $product) { ?>
    		<?php echo KMSystem::loadTemplate(array('product' => $product, 'params' => $params)); ?>
    	<?php } ?>
        </ul>    
    </div>
</div>