<? defined('_JEXEC') or die(); ?>
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
	<div id="productsSortForm">
		<label for="selectProductSort"><?php echo JText::_('KSM_SORT_BY'); ?></label>
		<?php echo $this->sort_links['price']['link']?> <span class="divider">/</span>
		<?php echo $this->sort_links['hits']['link']?> <span class="divider">/</span>
	</div>
</div>
