<?php
defined( '_JEXEC' ) or die;
?>
<div class="cont">
	<h3>Ваши данные</h3>
	<div class="row">
		<label>Ваше имя<span class="require"></span></label>
		<input type="text" name="name" class="inputbox" value="<?php echo $this->user->name?>" />
	</div>
	<div class="row">
		<label>Эл. почта<span class="require"></span></label>
		<input type="text" name="email" class="inputbox" value="<?php echo $this->user->email?>" />
	</div>
	<div class="row">
		<label>Телефон<span class="require"></span></label>
		<input type="text" name="phone_country" class="phone_country" value="<?php echo $this->user->phone_country?>" />
		<input type="text" name="phone_code" class="phone_code" value="<?php echo $this->user->phone_code?>" />
		<input type="text" name="phone" class="phone" value="<?php echo $this->user->phone?>" />
	</div>	
	<div class="row">
		<label>Ваш регион:</label>
		<select class="sel" id="order_region" name="region" style="width:190px;">
			<option value="0">Выбрать регион</option>
			<?php foreach($this->regions as $region):?>
			<option value="<?php echo $region->id?>" <?php echo ($region->id==$this->user->region?'selected':'')?>><?php echo $region->title?></option>
			<?php endforeach;?>
		</select>
	</div>		
	<div class="row">
		<label>Адрес доставки<span class="require"></span></label>
		<input type="text" name="address" class="inputbox" id="toid" value="<?php echo $this->user->address?>" /> <a id="mapselect" class="onmap" href="#">Указать на карте</a>
	</div>
	<?php foreach($this->fields as $field):?>
	<div class="row">
		<label><?php echo $field->title?></label>
		<input type="text" name="field_<?php echo $field->id?>" class="inputbox" value="<?php echo $this->user->{'field_'.$field->id}?>">
	</div>		
	<?php endforeach;?>
	<div class="row">
		<label>Комментарий</label>
		<textarea name="note" class="textarea"></textarea>
	</div>	
	<div class="row">
		<input type="checkbox" name="sendEmail" value="1"  <?php echo (in_array(1,$this->user->groups)?'checked':'')?> /> <span>Хочу получать информацию о скидках</span>
	</div>
</div>	