<?php
defined('_JEXEC') or die;
?>
<?php if (count($view->products) > 0){ ?>
<div class="ksm-module-products-list ksm-block ksm-block">
	<h3><?php echo JText::sprintf('KSM_PLUGIN_SAMEPRODUCTS_CATEGORY_MODULE_LBL', $view->category_title); ?></h3>
	<div class="ksm-module-products-list-items">
		<?php foreach($view->products as $product){ ?>
			<?php echo KSSystem::loadTemplate(array('product' => $product, 'params' => $view->params)); ?>
		<?php } ?>
	</div>
</div>
<?php } ?>