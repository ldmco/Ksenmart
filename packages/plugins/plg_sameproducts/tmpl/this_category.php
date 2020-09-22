<?php
defined('_JEXEC') or die;
?>
<?php if (count($view->products) > 0){ ?>
<div class="ksm-product-related ksm-catalog ksm-block">
	<h2><?php echo JText::sprintf('KSM_PLUGIN_SAMEPRODUCTS_CATEGORY_LBL', $view->category_title); ?></h2>
	<div class="ksm-catalog-items ksm-catalog-items-grid">
		<?php foreach($view->products as $product){ ?>
			<?php echo KSSystem::loadTemplate(array('product' => $product, 'params' => $view->params)); ?>
		<?php } ?>
	</div>
</div>
<?php } ?>