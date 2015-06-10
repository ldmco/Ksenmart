<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>

<?php 
if($this->cat_search){
    foreach($this->cat_search as $item){ ?>
        <?php $link = JRoute::_('index.php?option=com_ksenmart&view=catalog&categories[]='.$item->cat_id.'&Itemid='.$this->shop_id); ?>
        <div class="item row-fluid">
            <div class="img span1">
                <a href="<?php echo $link; ?>" title="<?php echo $item->title; ?>"><img src="<?php echo JUri::root(); ?>/components/com_ksenmart/css/i/icon_cat.png" alt="<?php echo $item->title; ?>" width="32px" height="32px" /></a>
            </div>
            <div class="title span9">
                <div>
                    <a href="<?php echo $link; ?>" title="<?php echo $item->title; ?>" data-type="category"><?php echo $item->title; ?></a>
                </div>
                <div class="type"><?php echo JText::_('ksm_search_category'); ?></div>
            </div>
            <div class="price span2 pull-right"><?php echo JText::sprintf('ksm_search_results_products', $item->product_total); ?></div>
        </div>
    <?php } ?>
<?php } ?>