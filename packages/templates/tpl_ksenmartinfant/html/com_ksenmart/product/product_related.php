<? defined('_JEXEC') or die();

?>
<section class="page_product_box toggle_frame more_info_inner4">
	<h3 class="toggle"><span><?php echo JText::_('KSM_RELATED_PRODUCT_TITLE'); ?></span></h3>
	<div class="catalog related">
		<div class="row layout_grid layout_block">
		<ul id="product_list" class="row items catalog-items">
			<?php foreach($this->related as $product){ ?>
				<?php echo $this->loadOtherTemplate('item', 'default', 'catalog', array('product' => $product, 'params' => $this->params)); ?>
			<?php } ?>
		</ul>
		</div>
	</div>
</section>
<? } ?>