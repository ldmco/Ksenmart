<?php defined('_JEXEC') or die(); ?>
<script>
	var total_items=<?php echo (int)$this->total; ?>;
</script>
<div class="catalog">
	<h1><span><?php echo $this->category->title; ?></span></h1>
	<div class="row_category row-fluid clearfix cat_desc">
		<div class="span3"><img style="width:100%;" src="<?php echo $this->category->image; ?>" alt="" /></div>
		<div class="span9"><?php echo $this->category->content; ?></div>
	</div>
	<?php if(!empty($this->seo_text)){ ?>
	<div class="row_category clearfix cat_desc"><?php echo $this->seo_text; ?></div>
	<?php } ?>
	<?php require_once('sort_links.php'); ?>
	<div class="row layout_<?php echo $this->layout_view; ?> layout_block" data-layout="<?php echo $this->layout_view; ?>">
        <?php if(!empty($this->rows)){ ?>
        <ul id="product_list" class="row items catalog-items">
		<?php foreach($this->rows as $product){ ?>
            <?php echo $this->loadTemplate('item', 'default', array('product' => $product, 'params' => $this->params)); ?>
    	<?php } ?>
        </ul>
        <?php }else{ ?>
        <?php require_once('no_products.php'); ?>
        <?php } ?>
    </div>
	<? if($this->params->get('site_use_pagination',1 )== 1){
		require('pagination.php');
	}else{ ?>
	<div class="more">
		<a href="javascript:void(0);"><span>Еще товары</span></a>
	</div>
	<? } ?>
</div>