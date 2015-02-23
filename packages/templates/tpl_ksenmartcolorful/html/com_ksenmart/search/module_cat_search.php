<?php defined( '_JEXEC' ) or die; ?>
<?php 
if($this->cat_search){
    foreach($this->cat_search as $item){ ?>
        <?php $link = JRoute::_('index.php?option=com_ksenmart&view=catalog&categories[]='.$item->cat_id.'&Itemid='.$this->shop_id); ?>
        <div class="item row-fluid">
            <div class="img span1">
                <a href="<?php echo $link; ?>" title="<?php echo $item->title; ?>"><img src="./modules/mod_km_simple_search/images/icon_cat.png" alt="<?php echo $item->title; ?>" width="32px" height="32px" /></a>
            </div>
            <div class="title span9">
                <div>
                    <a href="<?php echo $link; ?>" title="<?php echo $item->title; ?>" data-type="category"><?php echo $item->title; ?></a>
                </div>
                <div class="type">категория</div>
            </div>
            <div class="price span2 pull-right"><?php echo $item->product_total .' товаров'; ?></div>
        </div>
    <?php } ?>
<?php } ?>