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
var page_url = 'index.php?option=com_ksenmart&view=orders&layout=consignmentnote&tmpl=component';

</script>
<form action="" class="noprint">
	<input id="print_button" type="button" value="Печать" alt="Печать" title="Печать" onclick="window.print();return false;">
</form>
<table cellpadding="0" cellspacing="0" border="0" width="100%">
	<tr>
		<td class=reportSmallFont align=right>Унифицированная форма №
		Торг-12<br>Утверждена Постановлением Госкомстата России
		<br>от 25.12.1998 г. за №132
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
</table>
<table cellpadding="0" cellspacing="0" border="0" width="100%">
	<tr>
		<td valign=top width="90%">
		<table cellpadding="0" cellspacing="0" border="0" width="100%"	class="mainTable">
			<tr>
				<td class=underlined align=center>
					<b>
					<?php echo $this->params->get('printforms_companyname','Продавец')?>,&nbsp;
					<?php echo ($this->params->get('printforms_companyname')!=''?$this->params->get('printforms_companyname').',&nbsp;':'')?>
					<?php echo $this->params->get('printforms_companyaddress','город, улица, дом')?>,&nbsp;
					(тел.:<?php echo $this->params->get('printforms_companyphone','+7 495 1234567')?>)
					</b>
				</td>
			</tr>
			<tr>
				<td class=underlined align=center><b>
				р/счет №<?php echo $this->params->get('printforms_bank_account_number')?> в <?php echo $this->params->get('printforms_bank_name')?>, кор/счет <?php echo $this->params->get('printforms_kor_number')?>, БИК <?php echo $this->params->get('printforms_bik')?>
				</b></td>
			</tr>
			<tr>
				<td class="reportSmallFont underlined cellComment" align="center"	style="padding-top: 1mm; padding-bottom: 5mm">
					грузоотправитель, адрес, номер телефона, банковские реквизиты</td>
			</tr>
			<tr>
				<td class="reportSmallFont cellComment" align="center" style="padding-top: 1mm; padding-bottom: 2mm">
					структурное	подразделение
				</td>
			</tr>
		</table>

		<table cellpadding="0" cellspacing="0" border="0" width="100%">
			<tr>
				<td class="reportSmallFont name_cell">Грузополучатель</td>
				<td width="100%" class="reportSmallFont underlined"><b class="inline_edit">
					<?php echo KSMOrders::getPrintformCustomerName($this->order->customer_fields);?>&nbsp;
					<?php echo KSMOrders::getPrintformCustomerAddress($this->order->address_fields);?>&nbsp;
					<?php echo (isset($this->order->customer_fields['phone']) && $this->order->customer_fields['phone']!=''?'тел.: '.$this->order->customer_fields['phone']:'')?>
				</b></td>
			</tr>
			<tr>
				<td class="reportSmallFont name_cell">Поставщик</td>
				<td width="100%" class="reportSmallFont underlined"><b>
					<?php echo $this->params->get('printforms_companyname','Продавец')?>,&nbsp;
					<?php echo ($this->params->get('printforms_companyname')!=''?$this->params->get('printforms_companyname').',&nbsp;':'')?>
					<?php echo $this->params->get('printforms_companyaddress','город, улица, дом')?>,&nbsp;
					(тел.:<?php echo $this->params->get('printforms_companyphone','+7 495 1234567')?>),
					р/счет №<?php echo $this->params->get('printforms_bank_account_number')?> в <?php echo $this->params->get('printforms_bank_name')?>, кор/счет <?php echo $this->params->get('printforms_kor_number')?>, БИК <?php echo $this->params->get('printforms_bik')?>
				</b></td>
			</tr>
			<tr>
				<td class="reportSmallFont name_cell">Плательщик</td>
				<td width="100%" class="reportSmallFont underlined">
				<b class="inline_edit">
					<?php echo KSMOrders::getPrintformCustomerName($this->order->customer_fields);?>&nbsp;
					<?php echo KSMOrders::getPrintformCustomerAddress($this->order->address_fields);?>&nbsp;
					<?php echo (isset($this->order->customer_fields['phone']) && $this->order->customer_fields['phone']!=''?'тел.: '.$this->order->customer_fields['phone']:'')?>
				</b></td>
			</tr>
			<tr>
				<td class="reportSmallFont name_cell">Основание</td>
				<td width="100%" class="reportSmallFont underlined"><b class="inline_edit">По заказу № <?php echo $this->order->id?> от <?php echo KSMOrders::getPrintformDate($this->order->date_add)?> г.</b></td>
			</tr>
			<tr>
				<td colspan=2>&nbsp;</td>
		</table>

		<table cellpadding="0" cellspacing="0" border="0" width="100%">
			<tr>
				<td align=center>

				<table cellpadding="0" cellspacing="0" border="0">
					<tr>
						<td rowspan=2 class="reportSmallFont docNameLabels" valign=bottom>
						<b>ТОВАРНАЯ	НАКЛАДНАЯ&nbsp;</b>
						</td>
						<td class="reportSmallFont docNameLabels b_top b_left b_right" align="center">
							Номер<br>документа
						</td>
						<td class="reportSmallFont docNameLabels b_top b_right"	align="center">
							Дата<br>составления
						</td>
					</tr>

					<tr>
						<td
							class="reportSmallFont docNameLabels b_top b_left b_bottom b_right docNameValues"
							align=center>
							<b class="inline_edit"><?php echo $this->order->id?></b>
						</td>
						<td
							class="reportSmallFont docNameLabels b_top b_right b_bottom docNameValues"
							align=center>
							<b class="inline_edit"><?php echo date('d.m.Y')?></b>
						</td>
					</tr>

					<tr>
						<td colspan=3 class=separatorCell>&nbsp;</td>
					</tr>
				</table>

				</td>
			</tr>
		</table>
		</td>

		<td valign=top align=right>

		<table cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td colspan=2 class=reportSmallFont>&nbsp;</td>
				<td style="width: 20mm"
					class="item_cell b_left b_bottom b_top b_right reportSmallFont"
					align=center>Код</td>
			</tr>
			<tr>
				<td class="reportSmallFont name_cell nobr" colspan=2 align=right>Форма
				по ОКУД</td>
				<td style="width: 20mm"
					class="item_cell b_left b_bottom b_right reportSmallFont"
					align=center>0330212</td>
			</tr>
			<tr>
				<td class="reportSmallFont name_cell nobr" colspan=2 align=right>по
				ОКПО</td>
				<td style="width: 20mm"
					class="item_cell b_left b_bottom b_right reportSmallFont"
					align=center>&nbsp;</td>
			</tr>
			<tr>
				<td class="reportSmallFont name_cell nobr" colspan=2 align=right>Вид
				деятельности по ОКДП</td>
				<td style="width: 20mm"
					class="item_cell b_left b_bottom b_right reportSmallFont"
					align=center>&nbsp;</td>
			</tr>
			<tr>
				<td class="reportSmallFont name_cell nobr" colspan=2 align=right>по
				ОКПО</td>
				<td style="width: 20mm"
					class="item_cell b_left b_bottom b_right reportSmallFont"
					align=center>&nbsp;</td>
			</tr>
			<tr>
				<td class="reportSmallFont name_cell nobr" colspan=2 align=right>по
				ОКПО</td>
				<td style="width: 20mm"
					class="item_cell b_left b_bottom b_right reportSmallFont"
					align=center>&nbsp;</td>
			</tr>
			<tr>
				<td class="reportSmallFont">&nbsp;</td>
				<td class="reportSmallFont name_cell b_bottom nobr" align=right>по
				ОКПО</td>
				<td style="width: 20mm"
					class="item_cell b_left b_bottom b_right reportSmallFont"
					align=center>&nbsp;</td>
			</tr>
			<tr>
				<td rowspan="2">&nbsp;</td>
				<td style="width: 20mm"
					class="name_cell item_cell b_left b_bottom reportSmallFont"
					align=right>номер</td>
				<td style="width: 20mm"
					class="item_cell b_left b_bottom b_right reportSmallFont inline_edit"
					align=center><?php echo $this->order->id?></td>
			</tr>
			<tr>
				<td style="width: 20mm"
					class="name_cell item_cell b_left b_bottom reportSmallFont"
					align=right>дата</td>
				<td style="width: 20mm"
					class="item_cell b_left b_bottom b_right reportSmallFont inline_edit"
					align=center></td>
			</tr>
			<tr>
				<td class="reportSmallFont name_cell nobr" rowspan="2" valign="top">Транспортная
				накладная</td>
				<td style="width: 20mm"
					class="name_cell item_cell b_left b_bottom reportSmallFont"
					align=right>номер</td>
				<td style="width: 20mm"
					class="item_cell b_left b_bottom b_right reportSmallFont"
					align=center>&nbsp;</td>
			</tr>
			<tr>
				<td style="width: 20mm"
					class="name_cell item_cell b_left b_bottom reportSmallFont"
					align=right>дата</td>
				<td style="width: 20mm"
					class="item_cell b_left b_bottom b_right reportSmallFont"
					align=center>&nbsp;</td>
			</tr>
			<tr>
				<td class="reportSmallFont name_cell nobr" colspan=2 align=right>Вид
				операции</td>
				<td style="width: 20mm"
					class="item_cell b_left b_bottom b_right reportSmallFont"
					align=center>&nbsp;</td>
			</tr>
		</table>

		</td>
	</tr>
</table>

<table width="100%" border="0" cellpadding=0 cellspacing=0
	class="mainTable">
	<tr>
		<td rowspan="2" class="b_top b_left"><b>№<br>
		п/п</b></td>
		<td colspan="2" class="b_top b_left b_bottom"><b>Товар</b></td>
		<td colspan="2" class="b_top b_left b_bottom"><b>Ед. изм.</b></td>
		<td rowspan="2" class="b_top b_left"><b>Вид<br>упа-<br>ков-<br>ки</b></td>
		<td colspan="2" class="b_top b_left b_bottom"><b>Количество</b></td>
		<td rowspan="2" class="b_top b_left"><b>Масса<br>брутто</b></td>
		<td rowspan="2" class="b_top b_left"><b>Количество<br>(масса<br>нетто)</b></td>
		<td rowspan="2" class="b_top b_left"><b>Цена, руб.<br>коп.</b></td>
		<td rowspan="2" class="b_top b_left"><b>Сумма без<br>учета НДС<br>руб. коп.</b></td>
		<td colspan="2" class="b_top b_left b_bottom"><b>НДС</b></td>
		<td rowspan="2" class="b_top b_left b_right"><b>Сумма с<br>учетом НДС<br>руб. коп.</b></td>
	</tr>
	<tr>
		<td class="b_left">наименование, характеристика,<br>сорт, артикул товара</td>
		<td class="b_left">Код</td>
		<td class="b_left">Наиме-<br>нование</td>
		<td class="b_left">код<br>по<br>ОКЕИ</td>
		<td class="b_left">в од-<br>ном<br>месте</td>
		<td class="b_left">мест,<br>штук</td>
		<td class="b_left">ставка, %</td>
		<td class="b_left">сумма руб.<br>коп.</td>
	</tr>
	<tr class=boldborders>
		<td class="b_left b_top b_bottom">1</td>
		<td class="b_left b_top b_bottom">2</td>
		<td class="b_left b_top b_bottom">3</td>
		<td class="b_left b_top b_bottom">4</td>
		<td class="b_left b_top b_bottom">5</td>
		<td class="b_left b_top b_bottom">6</td>
		<td class="b_left b_top b_bottom">7</td>
		<td class="b_left b_top b_bottom">8</td>
		<td class="b_left b_top b_bottom">9</td>
		<td class="b_left b_top b_bottom">10</td>
		<td class="b_left b_top b_bottom">11</td>
		<td class="b_left b_top b_bottom">12</td>
		<td class="b_left b_top b_bottom">13</td>
		<td class="b_left b_top b_bottom">14</td>
		<td class="b_left b_top b_bottom b_right">15</td>
	</tr>
	<?php $k=1;?>
	<?php $total_count=0;?>
	<?php foreach($this->order->items as $item):?>
	<tr>
		<td class="b_left b_bottom"><?php echo $k?></td>
		<td class="b_left b_bottom leftAlign inline_edit"><?php echo $item->title?></td>
		<td class="b_left b_bottom">&nbsp;</td>
		<td class="b_left b_bottom">шт.</td>
		<td class="b_left b_bottom">шт.</td>
		<td class="b_left b_bottom">&nbsp;</td>
		<td class="b_left b_bottom">&nbsp;</td>
		<td class="b_left b_bottom rightAlign">&nbsp;</td>
		<td class="b_left b_bottom">&nbsp;</td>
		<td class="b_left b_bottom rightAlign"><?php echo $item->count?></td>
		<td class="b_left b_bottom rightAlign nobr"><?php echo KSMPrice::showPriceWithoutTransform($item->price)?></td>
		<td class="b_left b_bottom rightAlign nobr"><?php echo KSMPrice::showPriceWithoutTransform($item->price*$item->count*(100-$this->params->get('printforms_nds'))/100)?></td>
		<td class="b_left b_bottom"><?php echo $this->params->get('printforms_nds')?></td>
		<td class="b_left b_bottom rightAlign"><?php echo KSMPrice::showPriceWithoutTransform($item->price*$item->count*$this->params->get('printforms_nds')/100)?></td>
		<td class="b_left b_bottom b_right rightAlign nobr"><?php echo KSMPrice::showPriceWithoutTransform($item->price*$item->count)?></td>
	</tr>
	<?php $total_count+=$item->count;?>
	<?php $k++;?>
	<?php endforeach;?>
	<tr>
		<td colspan="7" align="right" class="rightAlign">Итого</td>
		<td class="b_left b_bottom">X</td>
		<td class="b_left b_bottom">X</td>
		<td class="b_left b_bottom rightAlign nobr"><?php echo $total_count?></td>
		<td class="b_left b_bottom">X</td>
		<td class="b_left b_bottom rightAlign nobr"><?php echo KSMPrice::showPriceWithoutTransform($this->order->cost*(100-$this->params->get('printforms_nds'))/100)?></td>
		<td class="b_left b_bottom">X</td>
		<td class="b_left b_bottom rightAlign nobr"><?php echo KSMPrice::showPriceWithoutTransform($this->order->cost*$this->params->get('printforms_nds')/100)?></td>
		<td class="b_left b_bottom b_right rightAlign nobr"><?php echo KSMPrice::showPriceWithoutTransform($this->order->cost)?></td>
	</tr>
	<tr class=totals>
		<td colspan="7" align="right" class="rightAlign normalFont ">Всего
		по накладной</td>
		<td class="b_left b_bottom">&nbsp;</td>
		<td class="b_left b_bottom">&nbsp;</td>
		<td class="b_left b_bottom rightAlign"><?php echo $total_count?></td>
		<td class="b_left b_bottom normalFont">X</td>
		<td class="b_left b_bottom rightAlign nobr"><?php echo KSMPrice::showPriceWithoutTransform($this->order->cost*(100-$this->params->get('printforms_nds'))/100)?></td>
		<td class="b_left b_bottom normalFont">X</td>
		<td class="b_left b_bottom rightAlign"><?php echo KSMPrice::showPriceWithoutTransform($this->order->cost*$this->params->get('printforms_nds')/100)?></td>
		<td class="b_left b_bottom b_right rightAlign nobr"><?php echo KSMPrice::showPriceWithoutTransform($this->order->cost)?></td>
	</tr>
</table>

<table width="100%" border="0" cellpadding=0 cellspacing=0
	class="mainTable">
	<tr>
		<td class=separatorCell>&nbsp;</td>
	</tr>
</table>

<table width="100%" border="0" cellpadding=0 cellspacing=0
	class="mainTable">
	<tr>
		<td class="nobr">Товарная накладная имеет приложение на</td>
		<td style="width: 40%" class="underlined">&nbsp;</td>
		<td class="nobr">и содержит</td>
		<td style="width: 40%" class=underlined><b><?php echo KSFunctions::number2string($total_count,1)?></b></td>
		<td class="nobr">порядковых номеров записей</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td class="reportSmallFont cellComment">прописью</td>
		<td>&nbsp;</td>
	</tr>
</table>

<table width="100%" border="0" cellpadding=0 cellspacing=0
	class="mainTable">
	<tr>
		<td class=separatorCell>&nbsp;</td>
	</tr>
</table>

<table width="100%" border="0" cellpadding=0 cellspacing=0
	class="mainTable">
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td class="rightAlign">Масса груза (нетто)</td>
		<td class=underlined><b>&nbsp;</b></td>
		<td class="b_top b_left b_bottom b_right" style="width: 30mm">&nbsp;</td>
		<td class="leftAlign" style="width: 20mm">кг</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td class=cellComment>прописью</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td style="width: 20mm" class="leftAlign nobr">Всего мест</td>
		<td style="width: 50%" class=underlined><b>&nbsp;</b></td>
		<td class="rightAlign">Масса груза (брутто)</td>
		<td class=underlined><b>&nbsp;</b></td>
		<td class="b_top b_left b_bottom b_right" style="width: 30mm">&nbsp;</td>
		<td class="leftAlign" style="width: 20mm">кг</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td class=cellComment>прописью</td>
		<td>&nbsp;</td>
		<td class=cellComment>прописью</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
</table>

<table width="100%" border="0" cellpadding=0 cellspacing=0
	class="mainTable">
	<tr>
		<td class=separatorCell>&nbsp;</td>
	</tr>
</table>

<table border=0 cellpadding=0 cellspacing=0 width="100%">
	<tr>

		<td width="50%">

		<table width="100%" border="0" cellpadding=0 cellspacing=0
			class="mainTable">
			<tr>
				<td class="nobr">Приложение (паспорта, сертификаты, и т.п.)</td>
				<td width="80%" class=underlined>&nbsp;</td>
				<td>листах</td>
			</tr>
		</table>

		<table width="100%" border="0" cellpadding=0 cellspacing=0
			class="mainTable">
			<tr>
				<td class=leftAlign>Всего отпущено на сумму</td>
			</tr>
			<tr>
				<td class="underlined leftAlign"><b><?php echo KSFunctions::stringView($this->order->cost)?>, <?php echo ($this->params->get('printforms_nds')!=0?'в т.ч. НДС':'без налога (НДС)')?></b></td>
			</tr>
			<tr>
				<td class=separatorCell>&nbsp;</td>
			</tr>
		</table>

		<table width="100%" border="0" cellpadding=0 cellspacing=0
			class="mainTable">
			<tr>
				<td class="leftAlign nobr">Отпуск разрешил</td>
				<td class=underlined style="width: 30%">Директор</td>
				<td>&nbsp;</td>
				<td class=underlined style="width: 30%">&nbsp;</td>
				<td>&nbsp;</td>
				<td class=underlined><b><?php echo $this->params->get('printforms_ceo_name')?></b></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td class=cellComment>должность</td>
				<td>&nbsp;</td>
				<td class=cellComment>подпись</td>
				<td>&nbsp;</td>
				<td class="cellComment nobr">расшифровка подписи</td>
			</tr>
			<tr>
				<td class=leftAlign>&nbsp;</td>
				<td class=underlined>Гл. Бухгалтер</td>
				<td>&nbsp;</td>
				<td class=underlined>&nbsp;</td>
				<td>&nbsp;</td>
				<td class=underlined><b><?php echo $this->params->get('printforms_buh_name')?></b></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td class=cellComment>должность</td>
				<td>&nbsp;</td>
				<td class=cellComment>подпись</td>
				<td>&nbsp;</td>
				<td class="cellComment nobr">расшифровка подписи</td>
			</tr>
		</table>

		<table border="0" cellpadding=0 cellspacing=0
			class="mainTable">
			<tr>
				<td width="90">М.П.</td>
				<td style="padding-left:100px;" class="inline_edit"><?php echo date('d.m.Y')?></td>
			</tr>
		</table>

		</td>

		<td style="padding-left: 5px">&nbsp;</td>

		<td width="50%" valign=top>

		<table width="100%" border="0" cellpadding=0 cellspacing=0
			class="mainTable">
			<tr>
				<td class="leftAlign nobr">По доверенности №</td>
				<td class=underlined style="width: 85%">&nbsp;</td>
			</tr>
			<tr>
				<td class=separatorCell>&nbsp;</td>
				<td class=separatorCell>&nbsp;</td>
			</tr>
		</table>

		<table width="100%" border="0" cellpadding=0 cellspacing=0
			class="mainTable">
			<tr>
				<td class=leftAlign>Выданной</td>
				<td class=underlined style="width: 90%">&nbsp;</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td class="cellComment nobr">кем, кому (организация,
				должность, фамилия, и.о.)</td>
			</tr>
		</table>

		<table width="100%" border="0" cellpadding=0 cellspacing=0
			class="mainTable">
			<tr>
				<td class="leftAlign nobr">Груз принял</td>
				<td class=underlined style="width: 90%">&nbsp;</td>
			</tr>
		</table>

		<table width="100%" border="0" cellpadding=0 cellspacing=0
			class="mainTable">
			<tr>
				<td class=separatorCell>&nbsp;</td>
				<td class=separatorCell>&nbsp;</td>
			</tr>
			<tr>
				<td align=left class="nobr">Груз получил грузополучатель</td>
				<td class=underlined style="width: 90%">&nbsp;</td>
			</tr>
		</table>

		<table border="0" cellpadding=0 cellspacing=0
			class="mainTable">
			<tr>
				<td colspan="2">&nbsp;</td>
			</tr>
			<tr>
				<td width="90">М.П.</td>
				<td style="padding-left:100px;" class="inline_edit"><?php echo date('d.m.Y')?></td>
			</tr>
		</table>

		</td>

	</tr>
</table>
