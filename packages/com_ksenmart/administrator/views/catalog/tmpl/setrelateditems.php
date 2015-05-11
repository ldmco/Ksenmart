<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<div class="position">
	<div class="col1">
		<div class="img"><img alt="" src="<?php echo $this->item->small_img;?>"></div>
		<div class="name"><?php echo $this->item->title;?></div>
		<div class="links">
			<a class="delete-pos" href="#"><?php echo JText::_('KSM_DELETE');?></a>
		</div>
	</div>
	<div class="col2">
		<?php echo $this->item->product_code;?>&nbsp;
	</div>
	<div class="col3">
		<?php echo $this->item->val_price;?>
	</div>
	<div class="col4">
		<?php echo $this->item->in_stock;?>
	</div>
	<input type="hidden" class="price" value="<?php echo $this->item->val_price_wou;?>">
	<input type="hidden" name="jform[relative][<?php echo $this->item->id;?>][relative_id]" value="<?php echo $this->item->id;?>">
</div>