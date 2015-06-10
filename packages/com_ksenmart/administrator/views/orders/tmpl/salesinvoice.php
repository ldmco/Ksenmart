<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<script type="text/javascript">

jQuery(document).ready(function(){
	Printform.init('inline_edit');
});

var lang_strings = {
	'edit_link':'Корректировка перед печатью',
	'field_title':'Двойной клик для редактирования',
	'save_link':'OK'}
var page_url = 'index.php?option=com_ksenmart&view=orders&layout=salesinvoice&tmpl=component';

</script>
<form action="" class="noprint">
	<input id="print_button" type="button" value="Печать" alt="Печать" title="Печать" onclick="window.print();return false;">
</form>
<div align="right">
<table cellpadding="0" cellspacing="0" border="0" style="width: 100mm">
	<tr>
		<td class=reportSmallFont align=right>Приложение № 1<br>
		к постановлению правительства РФ<br>
		от 26 декабря 2011 г. № 1137</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
</table>
</div>

<table cellpadding="0" cellspacing="0" border="0" class="mainTable_normal" width="100%">
	<tr>
		<td colspan=2><b>СЧЁТ-ФАКТУРА № <span class="inline_edit underlined"><?php echo $this->order->id?></span> от <span class="inline_edit underlined"><?php echo KSMOrders::getPrintformDate($this->order->date_add)?> г.</span></b></td>
		<td>(1)</td>
	</tr>
	<tr>
		<td colspan=2><b>ИСПРАВЛЕНИЕ № <span class="inline_edit underlined"><?php echo $this->order->id?></span> от <span class="inline_edit underlined"><?php echo KSMOrders::getPrintformDate($this->order->date_add)?> г.</span></b></td>
		<td>(1а)</td>
	</tr>
	<tr>
		<td colspan=3>&nbsp;</td>
	</tr>
	<tr>
		<td class=leftAlign>Продавец:</td>
		<td class="leftAlign underlined"><b><?php echo $this->params->get('printforms_companyname','Продавец')?></b></td>
		<td>(2)</td>
	</tr>
	<tr>
		<td class=leftAlign>Адрес:</td>
		<td class="leftAlign underlined"><b>
		<?php echo $this->params->get('printforms_companyname','Продавец')?>,&nbsp;
		(тел.:<?php echo $this->params->get('printforms_companyphone','+7 495 1234567')?>)
		</b></td>
		<td>(2а)</td>
	</tr>
	<tr>
		<td class=leftAlign>ИНН/КПП продавца:</td>
		<td class="leftAlign underlined"><b><?php echo $this->params->get('printforms_inn')?>/<?php echo $this->params->get('printforms_kpp')?></b></td>
		<td>(2б)</td>
	</tr>
	<tr>
		<td class=leftAlign>Грузоотправитель и его адрес:</td>
		<td class="leftAlign underlined"><b>
		<?php echo $this->params->get('printforms_companyname','Продавец')?>,&nbsp;
		<?php echo $this->params->get('printforms_address','адрес')?>,&nbsp;
		(тел.:<?php echo $this->params->get('printforms_companyphone','+7 495 1234567')?>)&nbsp;
		р/счет №<?php echo $this->params->get('printforms_bank_account_number')?> в <?php echo $this->params->get('printforms_bank_name')?>, кор/счет <?php echo $this->params->get('printforms_kor_number')?>, БИК <?php echo $this->params->get('printforms_bik')?>
		</b></td>
		<td>(3)</td>
	</tr>
	<tr>
		<td class=leftAlign>Грузополучатель и его адрес:</td>
		<td class="leftAlign underlined"><b class="inline_edit">
			<?php echo KSMOrders::getPrintformCustomerName($this->order->customer_fields);?>&nbsp;
			<?php echo KSMOrders::getPrintformCustomerAddress($this->order->address_fields);?>&nbsp;
			<?php echo (isset($this->order->customer_fields['phone']) && $this->order->customer_fields['phone']!=''?'тел.: '.$this->order->customer_fields['phone']:'')?>
		</b></td>
		<td>(4)</td>
	</tr>
	<tr>
		<td colspan=3>&nbsp;</td>
	</tr>
	<tr>
		<td class=leftAlign>К платежно-расчетному документу:</td>
		<td class="leftAlign">№<div class="underlined inline_edit" style="width:60mm; display: inline-block;">&nbsp;</div> 
		от <div class="underlined inline_edit" style="width:50mm;display: inline-block;">&nbsp;</div>
		</td>
		<td>(5)</td>
	</tr>
	<tr>
		<td colspan="3">&nbsp;</td>
	</tr>
	<tr>
		<td class=leftAlign>Покупатель:</td>
		<td class="leftAlign underlined"><b class="inline_edit"><?php echo KSMOrders::getPrintformCustomerName($this->order->customer_fields);?></b></td>
		<td>(6)</td>
	</tr>
	<tr>
		<td class=leftAlign>Адрес:</td>
		<td class="leftAlign underlined"><b class="inline_edit">
			<?php echo KSMOrders::getPrintformCustomerAddress($this->order->address_fields);?>&nbsp;
			<?php echo (isset($this->order->customer_fields['phone']) && $this->order->customer_fields['phone']!=''?'тел.: '.$this->order->customer_fields['phone']:'')?>
		</b></td>
		<td>(6а)</td>
	</tr>
	<tr>
		<td class=leftAlign>ИНН/КПП покупателя:</td>
		<td class="leftAlign underlined">
		<table style="display:inline;"><tr>
		<td style="width:50mm;font-weight: bold;" class="inline_edit"></td>
		<td style="font-weight: bold;">/</td>
		<td style="width:50mm;font-weight: bold;" class="inline_edit"></td>
		</tr></table>
		</td>
		<td>(6б)</td>
	</tr>
	<tr>
		<td class=leftAlign>Валюта: наименование, код</td>
		<td class="leftAlign underlined"><b class="inline_edit"><?php echo KSMPrice::getCurrencyName()?></b>, <b class="inline_edit"><?php echo KSMPrice::getCurrencyCode()?></b></td>
		<td>(7)</td>
	</tr>
	<tr>
		<td colspan=3>&nbsp;</td>
	</tr>
	<tr>
		<td colspan=3>&nbsp;</td>
	</tr>
</table>

<table width="100%" border="0" cellpadding=0 cellspacing=0
	class="mainTable_normal">
	<tr>
		<td class="b_left b_top" rowspan="2">
			Наименование товара<br>(описание выполненных работ,<br>оказанных услуг),<br/>имущественного права 
		</td>
		<td class="b_left b_top" colspan="2">
			Единица<br>измерения 
		</td>
		<td class="b_left b_top" rowspan="2">
			Коли-<br>чество<br>(объем)
		</td>
		<td class="b_left b_top" rowspan="2">Цена (тариф)<br>за единицу<br>измерения 
		</td>
		<td class="b_left b_top" rowspan="2">
			Стоимость товаров<br>(работ, услуг),<br>имущественных прав,<br>без налога — всего 
		</td>
		<td class="b_left b_top" rowspan="2">В том числе<br>сумма<br>акциза 
		</td>
		<td class="b_left b_top" rowspan="2">Налоговая<br>ставка 
		</td>
		<td class="b_left b_top" rowspan="2">Сумма<br>налога,<br>предъявляемая<br>покупателю
		</td>
		<td class="b_left b_top" rowspan="2">
			Стоимость това-<br>ров (работ, ус-<br>луг), имуществен-<br>ных прав с нало-<br>гом — всего 
		</td>
		<td class="b_left b_top" colspan="2">Страна происхож-<br>дения товара</td>
		<td class="b_left b_top b_right" rowspan="2">Номер<br>таможен<br>ной<br>декла-<br>рации</td>
	</tr>
	<tr>
		<td class="b_left b_top" title="Код ОКЕИ">к<br>о<br>д</td>
		<td class="b_left b_top" title="национальное условное обозначение ОКЕИ">условное<br>обозначение<br>(национальное)</td>
		<td class="b_left b_top">циф-<br>ровой<br>код</td>
		<td class="b_left b_top">краткое<br>наимено-<br>вание</td>
		
	</tr>
	<tr>
		<td class="b_left b_top">1</td>
		<td class="b_left b_top" title="Код ОКЕИ">2</td>
		<td class="b_left b_top" title="национальное условное обозначение ОКЕИ">2а</td>
		<td class="b_left b_top">3</td>
		<td class="b_left b_top">4</td>
		<td class="b_left b_top">5</td>
		<td class="b_left b_top">6</td>
		<td class="b_left b_top">7</td>
		<td class="b_left b_top">8</td>
		<td class="b_left b_top">9</td>
		<td class="b_left b_top">10</td>
		<td class="b_left b_top">10а</td>
		<td class="b_left b_top b_right">11</td>
	</tr>
	<?php $k=1;?>
	<?php $total_count=0;?>
	<?php foreach($this->order->items as $item):?>
	<tr>
		<td class="b_left b_top leftAlign inline_edit"><?php echo $item->title?></td>
		<td class="b_left b_top inline_edit">796</td>
		<td class="b_left b_top inline_edit">шт</td>
		<td class="b_left b_top"><?php echo $item->count?></td>
		<td class="b_left b_top rightAlign nobr"><?php echo KSMPrice::showPriceWithoutTransform($item->price)?></td>
		<td class="b_left b_top rightAlign nobr"><?php echo KSMPrice::showPriceWithoutTransform($item->price*$item->count*(100-$this->params->get('printforms_nds'))/100)?></td>
		<td class="b_left b_top rightAlign">0,00</td>
		<td class="b_left b_top rightAlign"><?php echo $this->params->get('printforms_nds')?>%</td>
		<td class="b_left b_top rightAlign"><?php echo KSMPrice::showPriceWithoutTransform($item->price*$item->count*$this->params->get('printforms_nds')/100)?></td>
		<td class="b_left b_top rightAlign nobr"><?php echo KSMPrice::showPriceWithoutTransform($item->price*$item->count)?></td>
		<td class="b_left b_top inline_edit">&nbsp;</td>
		<td class="b_left b_top inline_edit">&nbsp;</td>
		<td class="b_top b_left b_right inline_edit">&nbsp;</td>
	</tr>
	<?php $total_count+=$item->count;?>
	<?php $k++;?>
	<?php endforeach;?>
	<?php if ($this->order->costs['shipping_cost']!=0):?>
	<tr>
		<td class="b_left b_top leftAlign">доставка <?php echo KSMShipping::getShippingName($this->order->shipping_id)?></td>
		<td class="b_left b_top">796</td>
		<td class="b_left b_top">шт</td>
		<td class="b_left b_top">1</td>
		<td class="b_left b_top rightAlign nobr"><?php echo $this->order->costs['shipping_cost_val'];?></td>
		<td class="b_left b_top rightAlign nobr"><?php echo $this->order->costs['shipping_cost_val'];?></td>
		<td class="b_left b_top rightAlign">0,00</td>
		<td class="b_left b_top rightAlign">0%</td>
		<td class="b_left b_top rightAlign">0,00</td>
		<td class="b_left b_top rightAlign nobr"><?php echo $this->order->costs['shipping_cost_val'];?></td>
		<td class="b_left b_top">&nbsp;</td>
		<td class="b_left b_top">&nbsp;</td>
		<td class="b_top b_left b_right">&nbsp;</td>
	</tr>
	<?php endif;?>
	<tr class=totals>
		<td colspan=6 class="b_left b_top b_bottom">
		<table class=mainTable_normal border="0" cellpadding=0 cellspacing=0 width="100%">
			<tr>
				<td class="leftAlign nobr"><b>Всего к оплате</b></td>
				<td class="rightAlign nobr"><b><?php echo KSFunctions::stringView($this->order->cost)?>, <?php echo ($this->params->get('printforms_nds')!=0?'в т.ч. НДС':'без налога (НДС)')?></b></td>
			</tr>
		</table>
		</td>
		<td class="b_left b_top b_bottom" colspan="2">&times;</td>
		<td class="b_left b_top b_bottom rightAlign"><?php echo KSFunctions::stringView($this->order->cost*$this->params->get('printforms_nds')/100)?></td>
		<td class="b_left b_top b_bottom rightAlign nobr"><?php echo KSFunctions::stringView($this->order->cost*(100-$this->params->get('printforms_nds'))/100)?></td>
		<td class="b_left b_top">&nbsp;</td>
		<td class="b_top">&nbsp;</td>
		<td class="b_top">&nbsp;</td>
	</tr>
</table>

<table cellpadding="0" cellspacing="0" border="0"
	class="mainTable_normal" width="100%">
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
</table>

<table cellpadding="0" cellspacing="0" border="0"
	class="mainTable_normal" width="100%">
	<tr>
		<td style="width: 45mm" class="nobr leftAlign"><b>Руководитель
		организации<br>или иное уполномоченное лицо</b></td>
		<td class=underlined>&nbsp;</td>
		<td style="width: 5mm">&nbsp;</td>
		<td class="underlined nobr" style="width: 45mm"><b><?php echo $this->params->get('printforms_ceo_name')?></b></td>
		<td style="width: 15mm">&nbsp;</td>
		<td style="width: 55mm" class="nobr leftAlign"><b>Главный бухгалтер<br>или иное уполномоченное лицо</b></td>
		<td class=underlined>&nbsp;</td>
		<td style="width: 5mm">&nbsp;</td>
		<td class="underlined nobr" style="width: 45mm"><b><?php echo $this->params->get('printforms_buh_name')?></b></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td class=smallFont>(подпись)</td>
		<td>&nbsp;</td>
		<td class=smallFont>(ф.и.о.)</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td class=smallFont>(подпись)</td>
		<td>&nbsp;</td>
		<td class=smallFont>(ф.и.о.)</td>
	</tr>
</table>

<table cellpadding="0" cellspacing="0" border="0"
	class="mainTable_normal" width="100%">
	<tr>
		<td class="nobr leftAlign" valign="bottom" style="width: 55mm"><b>Индивидуальный предприниматель</b></td>
		<td class="underlined" style="width: 45mm">&nbsp;</td>
		<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td class="underlined nobr" valign="bottom"><b><?php echo $this->params->get('printforms_ip_name')?></b></td>
		<td style="width: 5mm">&nbsp;</td>
		<td class="underlined nobr" valign="bottom"><b><?php echo $this->params->get('printforms_ip_registration')?></b></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td class=smallFont>(подпись)</td>
		<td>&nbsp;</td>
		<td class=smallFont>(ф.и.о.)</td>
		<td>&nbsp;</td>
		<td class=smallFont>(реквизиты свидетельства о государственной регистрации индивидуального предпринимателя)</td>
	</tr>
</table>