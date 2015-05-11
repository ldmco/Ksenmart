<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<div class="catalog">
	<h3><?php echo $this->manufacturer->title; ?></h3>
	<div class="catalog-description"><?php echo $this->manufacturer->content; ?></div>
	<?php echo $this->loadTemplate('sortlinks', 'default'); ?>
    <div class="row-fluid layout_<?php echo $this->layout_view; ?> layout_block" data-layout="<?php echo $this->layout_view; ?>">
        <?php if(!empty($this->rows)){ ?>
        <ul class="thumbnails items catalog-items">
    	<?php foreach($this->rows as $product){ ?>
            <?php echo $this->loadTemplate('item', null, array('product' => $product)); ?>
    	<?php } ?>
        </ul>
        <?php }else{ ?>
        <?php echo $this->loadTemplate('noproducts', 'default'); ?>
        <?php } ?>
    </div>
    <?php echo $this->loadTemplate('pagination', 'default'); ?>
</div>