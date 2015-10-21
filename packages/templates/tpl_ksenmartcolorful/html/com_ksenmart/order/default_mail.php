<table class="cellpadding">
	<tr>
		<td colspan="2"><?php echo JText::_('KSM_ORDER_MAIL_INFORMATION'); ?></td>
	</tr>
	<tr>
		<td><?php echo JText::_('KSM_ORDER_MAIL_NAME'); ?></td>
		<td><?php echo $this->order->customer_name; ?></td>
	</tr>							
	<tr>
		<td><?php echo JText::_('KSM_ORDER_MAIL_EMAIL'); ?></td>
		<td><?php echo $this->order->customer_fields['email']; ?></td>
	</tr>
	<tr>
		<td><?php echo JText::_('KSM_ORDER_MAIL_ADDRESS'); ?></td>
		<td><?php echo $this->order->address_fields; ?></td>
	</tr>
	<tr>
		<td><?php echo JText::_('KSM_ORDER_MAIL_PHONE'); ?></td>
		<td><?php echo $this->order->phone; ?></td>
	</tr>	
</table>	
<h2><?php echo JText::_('KSM_ORDER_MAIL_ORDER'); ?></h2>
<table id="cart_content_tbl" cellspacing="0">
	<colgroup>
		<col width="50%" />
		<col width="15%" />
		<col width="20%" />
		<col width="5%" />
	</colgroup>
	<tr id="cart_content_header">
		<td><b><?php echo JText::_('KSM_ORDER_MAIL_PRODUCT'); ?></b></td>
		<td align="center"><b><?php echo JText::_('KSM_ORDER_MAIL_PRODUCT_QTY'); ?></b></td>
		<td align="center"><b><?php echo JText::_('KSM_ORDER_MAIL_PRODUCT_PRICE'); ?></b></td>
		<td align="center"><b><?php echo JText::_('KSM_ORDER_MAIL_PRODUCT_SUM'); ?></b></td>
	</tr>
    <?php foreach($this->order->items as $item) { ?>
    		<tr class="row_odd">
    			<td class="vid_produkt">
    				<a class="title_lookp" href="<?php echo JURI::root() . JRoute::_('index.php?option=com_ksenmart&view=product&id=' . $item->product_id . ':' . $item->product->alias . '&Itemid=' . KSSystem::getShopItemid()); ?>" ><?php echo $item->product->title; ?></a>
                    <?php if($item->product->product_code != '') { ?>
                        <i><?php echo JText::_('KSM_ORDER_MAIL_PRODUCT_SKU'); ?> <?php echo $item->product->product_code; ?></i>
                    <?php } ?>
                
                    <?php foreach($item->properties as $item_property) { ?>
                        <?php if(!empty($item_property->value)) { ?>
                            <br /><span><?php echo $item_property->title; ?>:</span> <?php echo $item_property->value; ?>
                        <?php } else { ?>
                            <br /><span><?php echo $item_property->title; ?></span>
                        <?php } ?>
                    <?php } ?>
    			</td>
			<td align="center"><?php echo $item->count; ?></td>
			<td align="center"><?php echo KSMPrice::showPriceWithTransform($item->price); ?></td>
			<td align="center" nowrap="nowrap">
				<?php echo KSMPrice::showPriceWithTransform($item->count * $item->price); ?>
			</td>
		</tr>
    <?php } ?>
	<tr>
		<td id="cart_total_label">
			<?php echo JText::_('KSM_ORDER_MAIL_PRODUCTS_SUM'); ?>
		</td>
		<td align="center">
		</td>
		<td></td>
		<td id="cart_total" align="center"><?php echo $this->order->costs['cost_val']; ?></td>
	</tr>
	<tr>
		<td id="cart_total_label"><?php echo JText::_('KSM_ORDER_MAIL_DISCOUNT'); ?></td>
		<td align="center"></td>
		<td></td>
		<td id="cart_total" align="center"><?php echo $this->order->costs['discount_cost_val']; ?></td>
	</tr>	
	<tr>
		<td id="cart_total_label"><?php echo JText::_('KSM_ORDER_MAIL_SHIPPING'); ?></td>
		<td align="center"></td>
		<td></td>
		<td id="cart_total" align="center"><?php echo $this->order->costs['shipping_cost_val']; ?></td>
	</tr>	
	<tr>
		<td id="cart_total_label">
			<?php echo JText::_('KSM_ORDER_MAIL_TOTAL'); ?>
		</td>
		<td align="center"></td>
		<td></td>
		<td id="cart_total" align="center"><?php echo $this->order->costs['total_cost_val']; ?></td>
	</tr>
</table>