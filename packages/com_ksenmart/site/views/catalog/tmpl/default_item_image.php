<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<a href="<?php echo $this->product->link; ?>" title="<?php echo $this->product->title; ?>">
	<img src="<?php echo $this->product->small_img; ?>" alt="" class="span12" />
</a>
<?php echo ($this->product->hot == 1?'<span class="hit"></span>':'')?>
<?php echo ($this->product->recommendation == 1?'<span class="super"></span>':''); ?>
<?php echo ($this->product->new == 1?'<span class="new"></span>':''); ?>
<?php echo ($this->product->promotion == 1?'<span class="act"></span>':''); ?>