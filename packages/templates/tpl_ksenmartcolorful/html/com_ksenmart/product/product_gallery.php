<?php
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>
<div id="products_gallery">
    <div class="slides_container">
    <?php if(!empty($this->images)){ ?>
		<?php foreach($this->images as $image){ ?>
            <a href="<?php echo $image->img_link; ?>" class="highslide" onclick="return hs.expand(this)">
                <img src="<?php echo $image->img; ?>" alt="<?php echo htmlentities($this->product->title); ?>" />
            </a>
		<?php } ?>
    <?php }else{ ?>
        <a href="javascript:void(0);" title="<?php echo $this->product->title; ?>">
            <img src="<?php echo $this->product->img; ?>" alt="<?php echo htmlentities($this->product->title); ?>" />
        </a>
    <?php } ?>
    </div>
    <?php if(count($this->images) > 1){ ?>
    <div class="row-fluid">
        <ul class="pagination inline clearfix">
		<?php foreach($this->images as $image){ ?>
            <li class="thumb"><a href="javascript:void(0);"><img src="<?php echo $image->img_small; ?>" alt="<?php echo htmlentities($this->product->title); ?>" /></a></li>
		<?php } ?>
        </ul>
    </div>
    <?php } ?>
</div>
