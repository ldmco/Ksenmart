<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<tr class="item-cart">
	<td style="width: 16%;">
		<form>
			<div class="photo">
				<a href="<?php echo $this->item->product->link; ?>"><img src="<?php echo $this->item->product->mini_small_img; ?>" alt="" /></a>
			</div>
	</td>
	<td style="width: 45%;">
		<dl class="dl-horizontal">
			<dt><?php echo JText::_('KSM_PRODUCT_COLUMN_TITLE'); ?></dt>
			<dd><a href="<?php echo $this->item->product->link; ?>" title="<?php echo $this->item->product->title; ?>"><?php echo $this->item->product->title; ?></a></dd>
			<?php if(!empty($this->item->product->product_code)){ ?>
			<dt><?php echo JText::_('KSM_PRODUCT_ARTICLE'); ?></dt>
			<dd><?php echo $this->item->product->product_code; ?></dd>
			<?php } ?>
			<?php if(!empty($this->item->product->manufacturer_title)){ ?>
			<dt><?php echo JText::_('KSM_PRODUCT_MANUFACTURER'); ?></dt>
			<dd><?php echo $this->item->product->manufacturer_title; ?></dd>
			<?php } ?>
            <?php foreach($this->item->properties as $item_property){ ?>
    			<?php if (!empty($item_property->value)){ ?>
    				<dt><?php echo $item_property->title; ?></dt>
    				<dd><?php echo $item_property->value; ?></dd>
    			<?php } else { ?>
    				<dt><?php echo $item_property->title; ?></dt>
    				<dd></dd>
    			<?php } ?>
            <?php } ?>
		</dl>
	</td>
	<td style="width: 12%;">
        <div class="input-prepend input-append quant quantt">
            <button type="button" class="btn minus">-</button>
            <input type="text" price="<?php echo $this->item->price; ?>" product_id="<?php echo $this->item->product->id; ?>" product_packaging="<?php echo $this->item->product->product_packaging; ?>" count="<?php echo $this->item->count; ?>" data-item_id="<?php echo $this->item->id; ?>" class="inputbox span1 text-center" value="<?php echo $this->item->count; ?>" />
            <button type="button" class="btn plus">+</button>
        </div>
	</td>
	<td>
		<div class="pricee">
			<?php echo $this->item->product->val_price; ?>
		</div>	
	</td>
	<td>
		<div class="totall">
			<?php echo KSMPrice::showPriceWithTransform($this->item->price*$this->item->count); ?>
		</div>
	</td>
	<td>
		<div class="del">
			<a data-item_id="<?php echo $this->item->id?>" href="<?php echo $this->item->del_link; ?>">&#215;</a>	
		</div>
		<input type="hidden" class="product_packaging" value="<?php echo $this->item->product->product_packaging; ?>" />
	</form>	
	</td>
</tr>