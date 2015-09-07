<?php defined( '_JEXEC' ) or die; ?>
<?php
    $count_products = '';
?>
<?php 
foreach($this->relevant_search as $item){
    $type = 'категория'?>
    <?php //if($value != $item->title){
        //$count_products = $this->getCountRelevantsResult($item->title);
        $count_products = 5;
        $link = JRoute::_('index.php?option=com_ksenmart&view=search&Itemid='.$this->shop_id.'&value='.$item->title);
    ?>
        <div class="item clearfix">
        <div class="img pull-left">
        <a href="<?php echo $link; ?>" title="<?php echo $item->title; ?>"><img src="./modules/mod_ksenmart_simple_search/images/icon_search.png" alt="<?php echo $item->title; ?>" width="32px" height="32px" /></a>
        </div>
        <div class="title pull-left">
        <div><a href="<?php echo $link; ?>" title="<?php echo $item->title; ?>"><?php echo $item->title; ?></a></div>
        <div class="type">частый запрос</div>
        </div>
        <div class="price pull-right">
        <?php if(!empty($count_products)){
            echo $count_products .' товаров';
        } ?>
        </div>
		<div class="clearfix"></div>
    </div>
<?php } ?>