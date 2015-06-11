<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<?php if ($this->order->id>0):?>
<input type="button" value="<?php echo JText::_('ksm_orders_order_print')?>" class="print"> 
<div id="popup-window2" class="popup-window">
	<div style="width: 460px;height: 175px;margin-left: -230px;margin-top: -137px;">
		<div class="popup-window-inner">
			<div class="heading">
				<h3><?php echo JText::_('ksm_orders_order_print_forms')?></h3>
				<div class="save-close">
					<button class="print-button"><?php echo JText::_('ksm_orders_order_print')?></button>
					<button class="close" onclick="return false;"></button>
				</div>
			</div>
			<div class="contents">
				<div class="contents-inner">
					<div class="slide_module">
						<div class="row">
							<ul>
								<li><span><input type="checkbox" value="salesinvoice">&nbsp;<?php echo JText::_('ksm_orders_order_salesinvoice')?></span></li>
								<li><span><input type="checkbox" value="shippingsummary">&nbsp;<?php echo JText::_('ksm_orders_order_shippingsummary')?></span></li>
								<li><span><input type="checkbox" value="invoice">&nbsp;<?php echo JText::_('ksm_orders_order_invoice')?></span></li>
								<li><span><input type="checkbox" value="consignmentnote">&nbsp;<?php echo JText::_('ksm_orders_order_consignmentnote')?></span></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>			
<?php endif;?>