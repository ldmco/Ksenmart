<?php defined( '_JEXEC' ) or die; ?>
<script>
	var total_items=<?php echo (int)$this->total;?>;
</script>
<div class="catalog">
	<h1><?php echo JText::_('KSM_CATALOG_TITLE');?></h1>
	<?php if (!empty($this->seo_text)):?>
	<div class="catalog-description"><?php echo $this->seo_text;?></div>
	<?php endif;?>	
	<?php require_once('sort_links.php');?>
	<div class="row layout_<?php echo $this->layout_view; ?> layout_block" data-layout="<?php echo $this->layout_view; ?>">
        <?php if(!empty($this->rows)){ ?>
        <ul id="product_list" class="row thumbnails items catalog-items">
		<?php foreach($this->rows as $product){ ?>
            <?php echo $this->loadTemplate('item', 'default', array('product' => $product, 'params' => $this->params)); ?>
    	<?php } ?>
        </ul>
        <?php }else{ ?>
        <?php require_once('no_products.php'); ?>
        <?php } ?>
    </div>
	<?php if ($this->params->get('site_use_pagination',1)==1):?>
	<?php require('pagination.php');?>
	<?php else:?>
	<div class="more">
		<a href="javascript:void(0);"><span>Еще товары</span></a>
	</div>	
	<?php endif;?>
</div>