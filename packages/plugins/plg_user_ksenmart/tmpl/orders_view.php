<?php
defined('_JEXEC') or die;
?>
<div class="ksm-profile-orders">
	<table class="ksm-profile-orders-table">
		<thead>
			<tr>
				<th><?php echo JText::_('PLG_USER_KSENMART_ORDERS_HEAD_NUMBER_LBL'); ?></th>
				<th><?php echo JText::_('PLG_USER_KSENMART_ORDERS_HEAD_SHIPPING_LBL'); ?></th>
				<th><?php echo JText::_('PLG_USER_KSENMART_ORDERS_HEAD_STATUS_LBL'); ?></th>
				<th><?php echo JText::_('PLG_USER_KSENMART_ORDERS_HEAD_COST_LBL'); ?></th>
				<th><?php echo JText::_('PLG_USER_KSENMART_ORDERS_HEAD_DATE_LBL'); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($view->orders as $order): ?>
			<tr class="ksm-profile-orders-item" data-order_id="<?php echo $order->id; ?>">
				<td>
					<?php echo JText::sprintf('PLG_USER_KSENMART_ORDERS_NUMBER_LBL', $order->id); ?>
				</td>
				<td>
					<?php if (!empty($order->shipping_title)): ?>
						<?php echo $order->shipping_title; ?>
					<?php else: ?>
						<?php echo JText::_('PLG_USER_KSENMART_ORDERS_NOSHIPPING_LBL'); ?>
					<?php endif; ?>
				</td>
				<td><?php echo JText::_($order->status_name); ?></td>
				<td><?php echo $order->costs['total_cost_val']; ?></td>
				<td><?php echo KSSystem::formatCommentDate($order->date_add); ?></td>
			</tr>	
			<tr class="ksm-profile-orders-item-detail" data-order_id="<?php echo $order->id; ?>">
				<td colspan="5">
					<table class="ksm-profile-orders-item-detail-table">
						<thead>
							<tr>
								<th><?php echo JText::_('PLG_USER_KSENMART_ORDERS_DETAIL_HEAD_PHOTO_LBL'); ?></th>
								<th><?php echo JText::_('PLG_USER_KSENMART_ORDERS_DETAIL_HEAD_PRODUCT_LBL'); ?></th>
								<th><?php echo JText::_('PLG_USER_KSENMART_ORDERS_DETAIL_HEAD_QUANTITY_LBL'); ?></th>
								<th><?php echo JText::_('PLG_USER_KSENMART_ORDERS_DETAIL_HEAD_PRICE_LBL'); ?></th>
								<th><?php echo JText::_('PLG_USER_KSENMART_ORDERS_DETAIL_HEAD_TOTAL_LBL'); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($order->items as $item): ?>	
							<tr class="ksm-profile-orders-item-detail-product">
								<td class="ksm-profile-orders-item-detail-product-image">
									<a href="<?php echo $item->product->link; ?>">
										<img src="<?php echo $item->product->mini_small_img; ?>" alt="<?php echo $item->product->title; ?>">
									</a>
								</td>	
								<td class="ksm-profile-orders-item-detail-product-name">
									<p><a href="<?php echo $item->product->link; ?>"><?php echo $item->product->title; ?></a></p>
									<?php if (!empty($item->product->product_code)): ?>
									<p>
										<b><?php echo JText::_('KSM_PRODUCT_ARTICLE'); ?></b>
										<span><?php echo $item->product->product_code; ?></span>
									</p>
									<?php endif; ?>
									<?php foreach($item->properties as $item_property): ?>
										<?php if (!empty($item_property->value)): ?>
										<p>
											<b><?php echo $item_property->title; ?>:</b>
											<span><?php echo $item_property->value; ?></span>
										</p>
										<?php else: ?>
										<p>
											<b><?php echo $item_property->title; ?></b>
										</p>
										<?php endif; ?>
									<?php endforeach; ?>									
								</td>
								<td><?php echo $item->count; ?></td>
								<td><?php echo $item->product->val_price; ?></td>
								<td><?php echo KSMPrice::showPriceWithTransform($item->price * $item->count); ?></td>
							</tr>
							<?php endforeach; ?>
							<tr class="ksm-profile-orders-item-detail-status">
								<td colspan="5">
									<b><?php echo JText::_('PLG_USER_KSENMART_ORDERS_ADDRESS_LBL'); ?></b>
									<?php if (!empty($order->address_fields)): ?>
										<?php echo $order->address_fields; ?>		
									<?php else: ?>
										<?php echo JText::_('PLG_USER_KSENMART_NOINFO_LBL'); ?>
									<?php endif; ?>
								</td>
							</tr>
						</tbody>							
					</table>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>