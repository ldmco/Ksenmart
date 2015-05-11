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
		<div class="price"><?php echo $this->item->val_price;?></div>
	</div>
	<div class="col2">
		<div class="name"><?php echo $this->item->title;?></div>
		<div class="product_code"><?php echo JText::_('ksm_catalog_product_code').':  '.$this->item->product_code;?></div>
	</div>
	<div class="col3">
		<div class="quants">
			<span class="minus"></span>
			<input name="jform[items][-<?php echo $this->item->id;?>][count]" class="inputbox pos-count" value="<?php echo $this->item->product_packaging;?>">
			<span class="plus"></span>
		</div>
		<div class="total"><?php echo JText::_('ksm_orders_order_item_total_price').KSMPrice::showPriceWithTransform($this->item->val_price_wou*$this->item->product_packaging);?></div>
	</div>
	<a class="del" href="#"></a>
	<input type="hidden" class="product_packaging" value="<?php echo $this->item->product_packaging;?>">
	<input type="hidden" name="jform[items][-<?php echo $this->item->id;?>][basic_price]" class="pos-prd-basic-price" value="<?php echo $this->item->val_price_wou;?>">
	<input type="hidden" name="jform[items][-<?php echo $this->item->id;?>][price]" class="pos-prd-price" value="<?php echo $this->item->val_price_wou;?>">
	<input type="hidden" name="jform[items][-<?php echo $this->item->id;?>][product_id]" class="pos-prd-id" value="<?php echo $this->item->id;?>">
	<input type="hidden" name="jform[items][-<?php echo $this->item->id;?>][id]" class="pos-id" value="-<?php echo $this->item->id;?>">
</div>