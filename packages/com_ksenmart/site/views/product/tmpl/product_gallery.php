<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<figure class="span6" id="products_gallery">
    <div class="slides_container">
    <?php if(!empty($this->images)){ ?>
		<?php foreach($this->images as $image){ ?>
            <a href="<?php echo $image->img_link; ?>" class="highslide" onclick="return hs.expand(this)">
                <img src="<?php echo $image->img; ?>" alt="<?php echo $this->product->title; ?>" />
            </a>
		<? } ?>
    <? }else{ ?>
        <a href="javascript:void(0);" title="<?php echo $this->product->title; ?>">
            <img src="<?php echo $this->product->img; ?>" alt="<?php echo $this->product->title; ?>" />
        </a>
    <? } ?>
    </div>
    <?php if(count($this->images) > 1){ ?>
    <figcaption class="row-fluid">
        <ul class="pagination inline clearfix">
		<? foreach($this->images as $image){ ?>
            <li class="thumb span2"><a href="javascript:void(0);"><img src="<?php echo $image->img_small; ?>" alt="<?php echo $this->product->title; ?>" /></a></li>
		<? } ?>
        </ul>
    </figcaption>
    <? } ?>
</figure>