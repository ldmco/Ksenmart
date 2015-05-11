<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<style type="text/css">
@media screen {input,.noprint {display: inline;height: auto;} .printable{display: none;}}
@media print {input,.noprint {display: none;} textarea {border:0;} .printable{display: inline;}}
body, td { font-family: 'Trebuchet MS', Arial, Helvetica, sans-serif;}
#ksenmart-map-input {display:none;}
</style>
<script type="text/javascript">
		
jQuery(document).ready(function(){
	Printform.init('inline_edit');
});

var lang_strings = {
	'edit_link':'Корректировка перед печатью',
	'field_title':'Двойной клик для редактирования',
	'save_link':'OK'}
var page_url = 'index.php?option=com_ksenmart&view=orders&layout=consignmentnote&tmpl=component';
var JText_user_address='<?php echo JText::_('user_address')?>';
</script>
<form action="" class="noprint">
	<input id="print_button" type="button" value="Печать" alt="Печать" title="Печать" onclick="window.print();return false;">
</form>

<table cellpadding="10" cellspacing="0" width="100%" border="0">
<tr>
	<td width="60%" style="border-bottom: 1px solid #ccc;">
		<?php if ($this->params->get('printforms_company_logo','')!=''):?>
		<h1><img width="200px" src="<?php echo JURI::root().$this->params->get('printforms_company_logo')?>"/></h1>
		<?php else:?>
		<h1><img width="200px" src="<?php echo JURI::root();?>media/ksenmart/images/ksenmart.png"/></h1>
		<?php endif;?>
	</td>
	<td width="40%" style="border-bottom: 1px solid #ccc;">
		<?php echo JText::_('ksm_orders_order_note_lbl')?>:
		<textarea style="width: 100%; height: 100px; font-size: 100%; font-weight: bold;"><?php echo $this->order->note?></textarea>
	</td>
</tr>
<tr>
	<td>
		<h2><?php echo JText::_('ksm_orders_order_number')?> <span class="inline_edit"><?php echo $this->order->id?></span></h2>
		<p>
		<strong style="font-size: 120%;" class="inline_edit"><?php echo KSMShipping::getShippingName($this->order->shipping_id)?></strong></p>
		<p><?php echo JText::_('ksm_orders_order_addressfields_lbl')?>:</p>
		<span class="inline_edit"><?php echo KSMOrders::getPrintformCustomerAddress($this->order->address_fields);?></span><br />
		<div id="ksenmart-map-layer" style="width:600px;height:400px;"></div>
	</td>
	<td valign="top">
		<h2 class="inline_edit"><?php echo $this->order->costs['total_cost_val'];?></h2>
		<p><?php echo JText::_('ksm_payments')?>: <strong class="inline_edit"><?php echo KSMPayments::getPaymentName($this->order->payment_id)?></strong></p>
		<p><?php echo JText::_('ksm_shippings')?>: <strong class="inline_edit"><?php echo KSMShipping::getShippingName($this->order->shipping_id)?></strong></p>
		<p><?php echo JText::_('ksm_orders_order_items')?>:</p>
		<p style="padding-left: 20px;">
		<?php foreach($this->order->items as $item):?>
			<?php echo $item->title?> (&times;<?php echo $item->count?>)<br />
		<?php endforeach;?>
		</p>
	</td>
</tr>
</table>
<input type="hidden" id="jform_shipping_coords" value="<?php echo $this->order->shipping_coords?>">