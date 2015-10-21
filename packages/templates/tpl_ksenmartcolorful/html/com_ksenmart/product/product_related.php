<?php defined('_JEXEC') or die(); ?>
<div class="catalog related">
	<h3><span><?php echo JText::_('KSM_RELATED_PRODUCT_TITLE'); ?>:</span></h3>
	<ul class="thumbnails items catalog-items">
		<?php foreach($this->related as $product){ ?>
            <?php echo $this->loadOtherTemplate('item', 'default', 'catalog', array('product' => $product, 'params' => $this->params)); ?>
		<?php } ?>
	</ul>	
</div>