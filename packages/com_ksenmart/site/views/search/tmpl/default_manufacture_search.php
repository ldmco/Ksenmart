<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>

<?php if(!empty($this->manufacture_search)){ ?>
<div class="manufacture row-fluid">
    <?php foreach($this->manufacture_search as $item){ ?>
        <?php $link = JRoute::_('index.php?option=com_ksenmart&view=catalog&manufacturers[]='.$item->id.'&Itemid='.$this->shop_id); ?>
        <div class="item pull-left">
            <div class="img pull-left">
                <a href="<?php echo $link; ?>" title="<?php echo $item->title; ?>" data-type="manufacture"><img src="<?php echo JUri::root(); ?>/components/com_ksenmart/css/i/icon_manufacture.png" alt="<?php echo $item->title; ?>" width="32px" height="32px" /></a>
            </div>
            <div class="title pull-left">
                <div>
                    <a href="<?php echo $link; ?>" title="<?php echo $item->title; ?>" data-type="manufacture"><?php echo $item->title; ?></a>
                    <span class="product_total"><?php echo JText::sprintf('ksm_search_results_products', $item->product_total); ?></span>
                </div>
                <div class="type"><?php echo JText::_('ksm_search_manufacturer'); ?></div>
            </div>
        </div>
		<div class="clearfix"></div>
    <?php } ?>
</div>
<?php } ?>