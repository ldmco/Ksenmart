<? defined('_JEXEC') or die; ?>
<section class="catalog addhomefeatured<?php echo $params->get('moduleclass_sfx'); ?>">
    <h4><span><? echo $module->title; ?></span></h4>
	<ul class="row">
    <?php
	$counter 	= 0;
	$countRows 	= count($products);
	foreach($products as $product) {
		require(JPATH_ROOT.'/templates/'.JFactory::getApplication()->getTemplate().'/html/com_ksenmart/catalog/item2.php');
	} ?>
    </ul>
</section>