<?php
    $count_products = '';
    $params = $this->params;
?>
<script>
	var total_items = <?php echo count($this->products);?>;
</script>
<div class="sort sortPagiBar shop_box_row shop_box_row clearfix">
	<ul class="product_view clearfix">
        <li id="product_view_grid" class="pull-right layout_icon<?php echo $this->layout_view == 'grid'?' active':''; ?>">
            <a href="javascript:void(0);" style="width:31px;height:29px;display:block;" class="layout_show" data-layout="grid">
                
            </a>
        </li>
        <li id="product_view_list" class="pull-right layout_icon<?php echo $this->layout_view == 'list'?' active':''; ?>">
            <a href="javascript:void(0);" style="width:31px;height:29px;display:block;" class="layout_show" data-layout="list">
                
            </a>
        </li>
	</ul>
</div>
<div class="row layout_<?php echo $this->layout_view; ?> layout_block" data-layout="<?php echo $this->layout_view; ?>">
        <ul id="product_list" class="row items catalog-items">
    	<?php if(!empty($this->products)){ ?>
            <?php 
            $counter = 0;
            foreach($this->products as $product){ 
    			require(JPATH_ROOT.'/templates/'.JFactory::getApplication()->getTemplate().'/html/com_ksenmart/catalog/item.php');
             }
        }else{
            echo $this->loadTemplate('no_products');
        } ?>
        </ul>
</div>
