<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<li class="span3 item">
    <div class="thumbnail">
    	<div class="img">
            <a href="<?php echo $this->related_product->link; ?>" title="<?php echo $this->related_product->title; ?>">
                <img src="<?php echo $this->related_product->small_img; ?>" alt="<?php echo $this->related_product->title; ?>" class="span12" />
            </a>
        </div>
    	<div class="caption">
    		<div class="name"><a href="<?php echo $this->related_product->link; ?>" title="<?php echo $this->related_product->title; ?>"><?php echo $this->related_product->title; ?></a></div>
    	</div>
    </div>
</li>