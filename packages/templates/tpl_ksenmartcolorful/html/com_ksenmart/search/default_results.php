<?php defined( '_JEXEC' ) or die; ?>
<div class="sort">
	<ul class="breadcrumb noTransition clearfix">
        <li class="pull-right layout_icon<?php echo $this->layout_view == 'grid'?' active':''; ?>">
            <a href="javascript:void(0);" class="layout_show" data-layout="grid">
                <i class="icon-th-large"></i>
            </a>
        </li>
        <li class="pull-right layout_icon<?php echo $this->layout_view == 'list_ext'?' active':''; ?>">
            <a href="javascript:void(0);" class="layout_show" data-layout="list_ext">
                <i class="icon-th-list"></i>
            </a>
        </li>
        <li class="pull-right layout_icon<?php echo $this->layout_view == 'list'?' active':''; ?>">
            <a href="javascript:void(0);" class="layout_show" data-layout="list">
                <i class="icon-list"></i>
            </a>
        </li>
	</ul>
</div>
<div class="catalog">
    <div class="row-fluid layout_<?php echo $this->layout_view; ?> layout_block" data-layout="<?php echo $this->layout_view; ?>">
        <ul class="thumbnails items catalog-items">
        <?php if(!empty($this->products)){ ?>
        	<?php foreach($this->products as $product){ ?>
                <?php echo $this->loadOtherTemplate('item', 'default', 'catalog', array('product' => $product, 'params' => $this->params)); ?>
        	<?php } ?>
        <?php }else{ ?>
            <?php echo $this->loadTemplate('no_products'); ?>
        <?php } ?>
        </ul>
    </div>
</div>