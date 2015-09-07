<?php defined('_JEXEC') or die; ?>
<div class="catalog">
    <div class="items catalog-items">
        <div class="row-fluid">
            <ul class="thumbnails items catalog-items">
            	<?php if ((array)$this->watched) { ?>
            		<?php foreach($this->watched as $product) { ?>
                        <?php echo $this->loadOtherTemplate('item', 'default', 'catalog', array('product' => $product, 'params' => $this->params)); ?>
            		<?php } ?>
            	<?php }else{ ?>
            		<?php echo $this->loadOtherTemplate('noproducts', 'default', 'catalog'); ?>
            	<?php } ?>
            </ul>
        </div>
    </div>
</div>
<div class="pagination">
    <?php echo $this->pagination->getPagesLinks(); ?>
</div>