<?php defined('_JEXEC') or die(); ?>
<form method="post" class="order_form form-horizontal" id="order">
	<h2>Оформление заказа</h2>
	<div class="step">
		<legend>Доступный способ доставки</legend>
		<div class="control-group">
			<span>Ваш регион</span>
			<select id="region_id" name="region_id">
				<option value="0">Выбрать регион</option>
				<?php foreach($this->regions as $region):?>
				<option value="<?php echo $region->id; ?>" <?php echo ($region->selected?'selected':''); ?>><?php echo $region->title; ?></option>
				<?php endforeach; ?>
			</select>
		</div>		
		<div class="control-group shippings">
			<?php if (count($this->shippings) == 0): ?>		
			<div class="no_shippings">
				<label>Нет способов доставки для выбранного региона</label>
			</div>
			<?php endif;?>
			<?php foreach($this->shippings as $shipping): ?>
			<div class="shipping">
				<label class="radio clearfix">
                    <span class="icon"><?php echo $shipping->icon; ?></span>
					<input type="radio" name="shipping_type" <?php echo count($this->shippings) == 1?'checked="checked"':''; ?> value="<?php echo $shipping->id; ?>" /> <?php echo JText::_($shipping->title); ?>
				</label>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
	<div class="step">
		<legend>Примечание к заказу</legend>	
		<div class="control-group">
			<textarea name="note" id="inputComment" class="textarea" placeholder="Комментарий"></textarea>
		</div>	
	</div>
	<!--div class="cart_contact_info">
		<legend>Заполните ваши данные</legend>
		<div class="control-group">
			<label class="control-label require" for="inputName">Ваше имя</label>
			<div class="controls">
				<input type="text" id="inputName" name="name" class="inputbox" value="<?php echo $this->order_info->name; ?>" placeholder="Ваше имя" />
			</div>
		</div>
		<div class="control-group">
			<label class="control-label require" for="inputEmail">Эл. почта</label>
			<div class="controls">
				<input type="email" id="inputEmail" name="email" class="inputbox" value="<?php echo $this->order_info->email; ?>" placeholder="Эл. почта" />
			</div>
		</div>
		<div class="control-group">
			<label class="control-label require" for="inputPhone">Телефон</label>
			<div class="controls">
                <div class="input-append">
                    <input type="text" id="customer_phone" name="phone" value="<?php echo $this->order_info->phone; ?>" required="true" />
                    <span class="add-on">
                        <input type="hidden" id="phone_mask" checked="true" />
                        <label id="descr" for="phone_mask">Введите номер</label>
                    </span>
                </div>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label require" for="inputAddress">Адрес доставки</label>
			<div class="controls">
				<input type="text" name="address" class="inputbox" id="toid" value="<?php echo $this->order_info->address; ?>" placeholder="Адрес доставки" />
				<a id="mapselect" class="onmap" href="#">Указать на карте</a>
			</div>
		</div>	
		<div class="control-group">
			<label class="control-label" for="inputComment">Комментарий</label>
			<div class="controls">
				<textarea name="note" id="inputComment" class="textarea" placeholder="Комментарий"><?php echo $this->order_info->note; ?></textarea>
			</div>
		</div>
		<div class="control-group">
			<div class="controls">
				<label class="checkbox">
					<input type="checkbox" id="inputInfoCheck" name="sendEmail" value="1"  <?php echo ($this->order_info->sendEmail==1?'checked':''); ?> /> Хочу получать информацию о скидках
				</label>
			</div>
		</div>
	</div-->	
	<div class="step">
		<legend>Выберите способ оплаты</legend>
		<div class="no_payments hide">
			<span class="grey-span">Нет способов оплаты для выбранного региона</span>
		</div>
		<?php foreach($this->payments as $payment){ ?>
		<div class="payment">
			<label class="radio clearfix">
				<input type="radio" name="payment_type" regions="<?php echo $payment->regions; ?>" value="<?php echo $payment->id; ?>" <?php echo ($payment->id==$this->order_info->payment_type?'checked':''); ?> />
				<?php echo JText::_($payment->title); ?>
			</label>
		</div>
		<?php } ?>		
	</div>	
	<input type="hidden" id="ymaphtml" name="ymaphtml" value="<?php echo $this->order_info->ymaphtml; ?>" />
	<input type="hidden" name="deliverycost" id="deliverycost" value="<?php echo $this->order_info->delivery_cost; ?>" />
	<input type="hidden" name="discount" id="discount" value="<?php echo isset($this->cart->discount_sum)?$this->cart->discount_sum:0; ?>" />
	<input type="hidden" name="task" value="shopopencart.close_order" />
</form>