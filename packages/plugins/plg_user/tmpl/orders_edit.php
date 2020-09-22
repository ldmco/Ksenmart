<?php
defined('_JEXEC') or die;
?>
<div class="ksm-profile-orders ksm-block">
	<?php if (!empty($view->orders)): ?>
        <div class="ksm-profile-orders-table">
            <div class="ksm-profile-orders-head">
                <div class="ksm-profile-order-number"><?php echo JText::_('PLG_USER_KSENMART_ORDERS_HEAD_NUMBER_LBL'); ?></div>
                <div class="ksm-profile-order-shipping"><?php echo JText::_('PLG_USER_KSENMART_ORDERS_HEAD_SHIPPING_LBL'); ?></div>
                <div class="ksm-profile-order-status"><?php echo JText::_('PLG_USER_KSENMART_ORDERS_HEAD_STATUS_LBL'); ?></div>
                <div class="ksm-profile-order-cost"><?php echo JText::_('PLG_USER_KSENMART_ORDERS_HEAD_COST_LBL'); ?></div>
                <div class="ksm-profile-order-date"><?php echo JText::_('PLG_USER_KSENMART_ORDERS_HEAD_DATE_LBL'); ?></div>
            </div>
			<?php foreach ($view->orders as $order): ?>
                <div class="ksm-profile-orders-item" data-order_id="<?php echo $order->id; ?>">
                    <div class="ksm-profile-order-number">
						<?php echo JText::sprintf('PLG_USER_KSENMART_ORDERS_NUMBER_LBL', $order->id); ?>
                    </div>
                    <div class="ksm-profile-order-shipping">
						<?php if (!empty($order->shipping_title)): ?>
							<?php echo $order->shipping_title; ?>
						<?php else: ?>
							<?php echo JText::_('PLG_USER_KSENMART_ORDERS_NOSHIPPING_LBL'); ?>
						<?php endif; ?>
                    </div>
                    <div class="ksm-profile-order-status"><?php echo JText::_($order->status_name); ?></div>
                    <div class="ksm-profile-order-cost">
						<?php echo $order->costs['total_cost_val']; ?>
						<?php echo (!empty($order->payment_content) ? '<br /><br />' . $order->payment_content : ''); ?>
                    </div>
                    <div class="ksm-profile-order-date"><?php echo KSSystem::formatCommentDate($order->date_add); ?></div>
                </div>
                <div class="ksm-profile-orders-item-detail" data-order_id="<?php echo $order->id; ?>">
                    <div>
                        <div class="ksm-profile-orders-item-detail-table">
                            <div class="ksm-profile-orders-item-detail-head">
                                <div class="ksm-profile-orders-item-detail-left">
                                    <div class="ksm-profile-orders-item-detail-img"><?php echo JText::_('PLG_USER_KSENMART_ORDERS_DETAIL_HEAD_PHOTO_LBL'); ?></div>
                                    <div class="ksm-profile-orders-item-detail-info"><?php echo JText::_('PLG_USER_KSENMART_ORDERS_DETAIL_HEAD_PRODUCT_LBL'); ?></div>
                                </div>
                                <div class="ksm-profile-orders-item-detail-right">
                                    <div class="ksm-profile-orders-item-detail-quant"><?php echo JText::_('PLG_USER_KSENMART_ORDERS_DETAIL_HEAD_QUANTITY_LBL'); ?></div>
                                    <div class="ksm-profile-orders-item-detail-prices"><?php echo JText::_('PLG_USER_KSENMART_ORDERS_DETAIL_HEAD_PRICE_LBL'); ?></div>
                                    <div class="ksm-profile-orders-item-detail-sum"><?php echo JText::_('PLG_USER_KSENMART_ORDERS_DETAIL_HEAD_TOTAL_LBL'); ?></div>
                                </div>
                            </div>
							<?php foreach ($order->items as $item): ?>
                                <div class="ksm-profile-orders-item-detail-product">
                                    <div class="ksm-profile-orders-item-detail-left">
                                        <div class="ksm-profile-orders-item-detail-img">
                                            <a href="<?php echo $item->product->link; ?>">
                                                <img src="<?php echo $item->product->mini_small_img; ?>"
                                                     alt="<?php echo $item->product->title; ?>">
                                            </a>
                                        </div>
                                        <div class="ksm-profile-orders-item-detail-info">
                                            <p>
                                                <a href="<?php echo $item->product->link; ?>"><?php echo $item->product->title; ?></a>
                                            </p>
											<?php if (!empty($item->product->product_code)): ?>
                                                <p>
                                                    <b><?php echo JText::_('KSM_PRODUCT_ARTICLE'); ?></b>
                                                    <span><?php echo $item->product->product_code; ?></span>
                                                </p>
											<?php endif; ?>
											<?php foreach ($item->properties as $item_property): ?>
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
                                        </div>
                                    </div>
                                    <div class="ksm-profile-orders-item-detail-right">
                                        <div class="ksm-profile-orders-item-detail-quant"><?php echo $item->count; ?></div>
                                        <div class="ksm-profile-orders-item-detail-prices"><?php echo $item->product->val_price; ?></div>
                                        <div class="ksm-profile-orders-item-detail-sum"><?php echo KSMPrice::showPriceWithTransform($item->price * $item->count); ?></div>
                                    </div>
                                </div>
							<?php endforeach; ?>
                            <div class="ksm-profile-orders-item-detail-status">
                                <div>
                                    <b><?php echo JText::_('PLG_USER_KSENMART_ORDERS_ADDRESS_LBL'); ?></b>
									<?php if (!empty($order->address_fields)): ?>
										<?php echo $order->address_fields; ?>
									<?php else: ?>
										<?php echo JText::_('PLG_USER_KSENMART_NOINFO_LBL'); ?>
									<?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
			<?php endforeach; ?>
        </div>
	<?php else: ?>
        <div class="ksm-profile-orders-noinfo">
			<?php echo JText::_('PLG_USER_KSENMART_NOINFO_LBL'); ?>
        </div>
	<?php endif; ?>
</div>