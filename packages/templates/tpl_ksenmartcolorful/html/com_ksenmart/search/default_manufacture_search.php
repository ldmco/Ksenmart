<?php defined( '_JEXEC' ) or die; ?>
<?php if(!empty($this->manufacture_search)){ ?>
<div class="manufacture clearfix">
    <?php foreach($this->manufacture_search as $item){ ?>
        <?php $link = JRoute::_('index.php?option=com_ksenmart&view=catalog&manufacturers[]='.$item->id.'&Itemid='.$this->shop_id); ?>
        <div class="item pull-left">
            <div class="img pull-left">
                <a href="<?php echo $link; ?>" title="<?php echo $item->title; ?>" data-type="manufacture"><img src="./modules/mod_ksenmart_simple_search/images/icon_manufacture.png" alt="<?php echo $item->title; ?>" width="32px" height="32px" /></a>
            </div>
            <div class="title pull-left">
                <div>
                    <a href="<?php echo $link; ?>" title="<?php echo $item->title; ?>" data-type="manufacture"><?php echo $item->title; ?></a>
                    <span class="product_total"><?php echo $item->product_total; ?> товаров</span>
                </div>
                <div class="type">производитель</div>
            </div>
        </div>
		<div class="clearfix"></div>
    <?php } ?>
</div>
<?php } ?>