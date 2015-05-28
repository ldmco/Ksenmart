<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>

<?php if($this->cat_search){ ?>
<div class="categories row-fluid">
    <?php  foreach($this->cat_search as $item){ ?>
        <?php $link = JRoute::_('index.php?option=com_ksenmart&view=catalog&categories[]='.$item->cat_id.'&Itemid='.$this->shop_id); ?>
        <div class="item span4">
            <div class="img span2">
                <a href="<?php echo $link; ?>" title="<?php echo $item->title; ?>"><img src="<?php echo JUri::root(); ?>/components/com_ksenmart/css/i/icon_cat.png" alt="<?php echo $item->title; ?>" width="32px" height="32px" /></a>
            </div>
            <div class="title span10">
                <div>
                    <a href="<?php echo $link; ?>" title="<?php echo $item->title; ?>" data-type="category"><?php echo $item->title; ?></a>
                    <small class="product_total muted"><?php echo JText::sprintf('ksm_search_results_products', $item->product_total); ?></small>
                </div>
                <div class="type muted"><?php echo JText::_('ksm_search_category'); ?></div>
            </div>
			<div class="clearfix"></div>
        </div>
    <?php } ?>
</div>
<?php } ?>