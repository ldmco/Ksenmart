<table class="cellpadding">
	<tr>
		<td colspan="2">Информация</td>
	</tr>
	<tr>
		<td>Имя:</td>
		<td><?php echo $this->order->customer_name; ?></td>
	</tr>							
	<tr>
		<td>E-mail:</td>
		<td><?php echo $this->order->customer_fields['email']; ?></td>
	</tr>
	<tr>
		<td>Адрес доставки:</td>
		<td><?php echo $this->order->address_fields; ?></td>
	</tr>
	<tr>
		<td>Номер телефона:</td>
		<td><?php echo $this->order->customer_fields['phone']; ?></td>
	</tr>
</table>	
<h2>Заказ</h2>
<table id="cart_content_tbl" cellspacing="0">
	<colgroup>
		<col width="50%" />
		<col width="15%" />
		<col width="20%" />
		<col width="5%" />
	</colgroup>
	<tr id="cart_content_header">
		<td><b>Продукт</b></td>
		<td align="center"><b>Кол-во</b></td>
		<td align="center"><b>Цена</b></td>
		<td align="center"><b>Стоимость</b></td>
	</tr>
    <?php foreach($this->order->items as $item) { ?>
    		<tr class="row_odd">
    			<td class="vid_produkt">
    				<a class="title_lookp" href="<?php echo JRoute::_(JURI::root() . 'index.php?option=com_ksenmart&view=product&id=' . $item->product_id . ":" . $item->alias . '&Itemid=' . KSSystem::getShopItemid()); ?>" ><?php echo $item->title; ?></a>
                    <?php if($item->product_code != '') { ?>
                        <i>Арт. <?php echo $item->product_code; ?></i>
                    <?php } ?>
                
                    <?php foreach($item->properties as $item_property) { ?>
                        <?php if(!empty($item_property->value)) { ?>
                            <br /><span><?php echo $item_property->title; ?>:</span> <?php echo $item_property->value; ?>
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
			Общая стоимость товаров:
		</td>
		<td align="center">
		</td>
		<td></td>
		<td id="cart_total" align="center"><?php echo $this->order->costs['cost_val']; ?></td>
	</tr>
	<tr>
		<td id="cart_total_label">Скидка:</td>
		<td align="center"></td>
		<td></td>
		<td id="cart_total" align="center"><?php echo $this->order->costs['discount_cost_val']; ?></td>
	</tr>	
	<tr>
		<td id="cart_total_label">Стоимость доставки:</td>
		<td align="center"></td>
		<td></td>
		<td id="cart_total" align="center"><?php echo $this->order->costs['shipping_cost_val']; ?></td>
	</tr>	
	<tr>
		<td id="cart_total_label">
			Итого
		</td>
		<td align="center"></td>
		<td></td>
		<td id="cart_total" align="center"><?php echo $this->order->costs['total_cost_val']; ?></td>
	</tr>
</table>
<?php $this->params = JComponentHelper::getParams('com_ksenmart'); ?>
<?php if($this->params->get('printforms_company_logo', null) && JURI::root() . $this->params->get('printforms_company_logo')): ?>
<a href="<?php echo JURI::root(); ?>" title="<?php echo $this->params->get('printforms_companyname'); ?>">
	<img src="<?php echo JURI::root() . $this->params->get('printforms_company_logo'); ?>" alt="<?php echo $this->params->get('printforms_companyname'); ?>" />
</a>
<?php endif; ?>
<?php if($this->params->get('printforms_companyname')): ?>
<p><b>Название компании: </b><?php echo $this->params->get('printforms_companyname'); ?></p>
<?php endif; ?>
<?php if($this->params->get('printforms_companyaddress')): ?>
<p><b>Адрес компании: </b><?php echo $this->params->get('printforms_companyaddress'); ?></p>
<?php endif; ?>
<?php if($this->params->get('printforms_companyphone')): ?>
<p><b>Телефон компании: </b><?php echo $this->params->get('printforms_companyphone'); ?></p>
<?php endif; ?>
<?php if($this->params->get('printforms_ceo_name')): ?>
<p><b>Директор компании: </b><?php echo $this->params->get('printforms_ceo_name'); ?></p>
<?php endif; ?>
<?php if($this->params->get('printforms_buh_name')): ?>
<p><b>Бухгалтер компании: </b><?php echo $this->params->get('printforms_buh_name'); ?></p>
<?php endif; ?>
<?php if($this->params->get('printforms_bank_account_number')): ?>
<p><b>Расчетный счет: </b><?php echo $this->params->get('printforms_bank_account_number'); ?></p>
<?php endif; ?>
<?php if($this->params->get('printforms_inn')): ?>
<p><b>ИНН: </b><?php echo $this->params->get('printforms_inn'); ?></p>
<?php endif; ?>
<?php if($this->params->get('printforms_kpp')): ?>
<p><b>КПП: </b><?php echo $this->params->get('printforms_kpp'); ?></p>
<?php endif; ?>
<?php if($this->params->get('printforms_bankname')): ?>
<p><b>Наименование банка: </b><?php echo $this->params->get('printforms_bankname'); ?></p>
<?php endif; ?>
<?php if($this->params->get('printforms_bank_kor_number')): ?>
<p><b>Корреспондентский счет: </b><?php echo $this->params->get('printforms_bank_kor_number'); ?></p>
<?php endif; ?>
<?php if($this->params->get('printforms_bik')): ?>
<p><b>БИК: </b><?php echo $this->params->get('printforms_bik'); ?></p>
<?php endif; ?>
<?php if($this->params->get('printforms_ip_name')): ?>
<p><b>Индивидуальный предприниматель: </b><?php echo $this->params->get('printforms_ip_name'); ?></p>
<?php endif; ?>
<?php if($this->params->get('printforms_ip_registration')): ?>
<p><b>Реквизиты: </b><?php echo $this->params->get('printforms_ip_registration'); ?></p>
<?php endif; ?>