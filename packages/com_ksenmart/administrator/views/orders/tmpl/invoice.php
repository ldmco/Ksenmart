<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<style type="text/css">
@media screen {input,.noprint {display: inline;height: auto;} .printable{display: none;}}
@media print {input,.noprint {display: none;} .printable{display: inline;}}
body, td { font-family:Arial, Helvetica, sans-serif;}
.printList { width:90%;  margin:30px auto;}
.printList td { vertical-align:top;}
h1 { margin:10px 0 0 0;}
h4 { border-bottom:1px solid #ddd;margin:0; padding-bottom:10px; color:#666; font-size:11pt;}
p { margin:0 0 0px 0;}
.urllinks span { display:block;padding:0 5px 0 0;}
.strongtext {  font-weight:bold;}
.middeltext { font-size:10pt;}
.grey { background:#F7F7f7; padding:10px; margin:0 0 0 0; font-size:11pt;}
.even { background:#F7F7f7;}
.w1 { padding:0 10px 0 30px; text-align:right; width:1%; white-space:nowrap;}
.w2 { text-align:right;padding:0 10px 0 0;width:83%}
.invoiceHeader td { font-size:11pt;}
.invoiceHeader td.invNum, .totalPrice { font-size:15pt; font-weight:bold; color:#000; padding:0 0 10px 0; line-height:15pt;}
.invoiceDecsr { border-top:solid 1px #ddd; margin:15px 0 0 0;}
.invoiceDecsr td { padding:5px; border-collapse:collapse; vertical-align:top; text-align:center;} 
.invoiceDecsr th { background:#f0f0f0; padding:5px;  font-weight:normal; font-size:11pt; }
.invoiceDecsr td.lefttext, .invoiceDecsr th.lefttext { text-align:left;}
.invoiceDecsr tr.total td { border-top:solid 1px #999; border-right:none;}
.invoiceDecsr tr.total td.totalPrice { border:1px solid #999; background:#f0f0f0;width:20% }
.centrtext {  text-align:center;}
.greytext { color:#555; font-weight:bold;}
.Footer { width:50%; margin:0 0 0 auto;}
</style>
<script type="text/javascript">

jQuery(document).ready(function(){
	Printform.init('inline_edit');
});

var lang_strings = {
	'edit_link':'Корректировка перед печатью',
	'field_title':'Двойной клик для редактирования',
	'save_link':'OK'}
var page_url = 'index.php?option=com_ksenmart&view=orders&layout=invoice&tmpl=component';

</script>
<form action="" class="noprint">
	<input id="print_button" type="button" value="Печать" alt="Печать" title="Печать" onclick="window.print();return false;">
</form>
<table cellpadding="20" cellspacing="0" width="90%" border="0"
	class="printList">
	<tr>
		<td class="">
		<div class="grey">
		<p class="strongtext"><?php echo $this->params->get('shop_name')?></p>
		<?php echo ($this->params->get('printforms_companyname')!='<p class="strongtext">'.$this->params->get('printforms_companyname').'</p>'?'':'')?>
		<?php echo ($this->params->get('printforms_companyaddress')!='<p>'.$this->params->get('printforms_companyaddress').'</p>'?'':'')?>
		<?php echo ($this->params->get('printforms_companyphone')!='<p>Тел.: '.$this->params->get('printforms_companyphone').'</p>'?'':'')?>
		<br/>
		<p class="urllinks"><span><a href="<?php echo JURI::root()?>"><?php echo JURI::root()?></a><br />
		<a href="mailto:<?php echo $this->params->get('shop_email')?>"><?php echo $this->params->get('shop_email')?></a></span></p>
		</div>
		<br clear="both">
		<br clear="both">		
		</td>
	</tr>
	<tr>
		<td>
		<table width="100%" border="0" cellpadding="0" cellspacing="0"
			class="invoiceHeader">
			<tr>
				<td class="w1">Инвойс:</td>
				<td class="invNum inline_edit"><?php echo $this->order->id?></td>
				<td class="w1 strongtext inline_edit"><?php echo KSMOrders::getPrintformDate($this->order->date_add)?></td>
			</tr>
			<tr>
				<td class="w1">Плательщик:</td>
				<td class="middeltext greytext">
				<p class="inline_edit"><?php echo KSMOrders::getPrintformCustomerName($this->order->customer_fields);?></p>
				<p class="inline_edit"><?php echo KSMOrders::getPrintformCustomerAddress($this->order->address_fields);?></p>
				</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td class="w1">Получатель:</td>
				<td class="middeltext greytext">
				<p class="inline_edit"><?php echo KSMOrders::getPrintformCustomerName($this->order->customer_fields);?></p>
				<p class="inline_edit"><?php echo KSMOrders::getPrintformCustomerAddress($this->order->address_fields);?></p>
				</td>
				<td>&nbsp;</td>
			</tr>

		</table>

		<table width="100%" border="0" cellpadding="0" cellspacing="0"
			class="invoiceDecsr">
			<tr>
				<th>&nbsp;</th>
				<th class="lefttext">Описание</th>
				<th>Кол-во</th>
				<th>Цена</th>
			</tr>
			<?php $k=1;?>
			<?php foreach($this->order->items as $item):?>
			<tr class="odd">
				<td><?php echo $k?></td>
				<td class="lefttext inline_edit"><?php echo $item->title?></td>
				<td><?php echo $item->count?></td>
				<td class="nobr"><?php echo KSMPrice::showPriceWithoutTransform($item->price*$item->count)?></td>
			</tr>
			<?php $k++;?>
			<?php endforeach;?>
			<?php if ($this->order->costs['discount_cost']!=0):?>
			<tr>
				<td>&nbsp;</td>
				<td class="lefttext">Скидка</td>
				<td>&nbsp;</td>
				<td class="nobr"><?php echo $this->order->costs['discount_cost_val'];?></td>
			</tr>
			<?php endif;?>			
			<?php if ($this->order->costs['shipping_cost']!=0):?>
			<tr>
				<td>&nbsp;</td>
				<td class="lefttext">Доставка</td>
				<td>&nbsp;</td>
				<td class="nobr"><?php echo $this->order->costs['shipping_cost_val'];?></td>
			</tr>
			<?php endif;?>
			<tr class="total">
				<td>&nbsp;</td>
				<td class="strongtext lefttext">Итого</td>
				<td>&nbsp;</td>
				<td class="totalPrice"><?php echo $this->order->costs['total_cost_val'];?></td>
			</tr>
		</table>
		</td>
	</tr>
</table>