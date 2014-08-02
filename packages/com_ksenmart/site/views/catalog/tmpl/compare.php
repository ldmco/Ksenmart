<?php defined('_JEXEC') or die; ?>
<div class="catalog js-compare-catalog" id="catalog">
    <h3><?php echo JText::_('KSM_COMPARE_TITLE'); ?></h3>
    <div class="row-fluid layout_<?php echo $this->layout_view; ?> layout_block" data-layout="<?php echo $this->layout_view; ?>">
        <?php if(!empty($this->rows)){ ?>
        <ul class="thumbnails items catalog-items js-compare-catalog-items">
        <?php foreach($this->rows as $product){ ?>
            <?php echo $this->loadTemplate('item', 'compare', array('product' => $product, 'params' => $this->params)); ?>
        <?php } ?>
        </ul>
        <?php }else{ ?>
        <?php echo $this->loadTemplate('noproducts', 'default'); ?>
        <?php } ?>
    </div>
    <?php echo $this->loadTemplate('pagination', 'default'); ?>
</div>