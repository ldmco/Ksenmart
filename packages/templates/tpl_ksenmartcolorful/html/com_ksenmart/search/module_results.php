<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<?php if(!empty($this->products)){ ?>
    <div class="title_block"><?php echo JText::_('KSM_SEARCH_RESULTS_MODULE'); ?></div>
    <?php foreach($this->products as $product){ ?>
        <?php $link = JRoute::_('index.php?option=com_ksenmart&view=product&id='.$product->id.':'.$product->alias.'&Itemid='.$this->shop_id); ?>
            <div class="item">
                <div class="img">
                    <a href="<?php echo $link; ?>" title="<?php echo $product->title; ?>"><img src="<?php echo $product->small_img; ?>" alt="<?php echo $product->title; ?>" width="32px" height="32px" /></a>
                </div>
                <div class="title">
                    <a href="<?php echo $link; ?>" title="<?php echo $product->title; ?>"><?php echo $product->title; ?></a>
					<div class="type muted"><?php echo KSMPrice::showPriceWithTransform($product->price); ?></div>
                </div>
            </div>
     <?php }
}else{
    echo $this->loadTemplate('no_products', 'default');
} ?>