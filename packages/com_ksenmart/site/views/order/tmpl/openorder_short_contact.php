<?php defined( '_JEXEC' ) or die; ?>
<div class="cont">
	<legend>Ваши данные для связи</legend>
	<div class="control-group">
		<label class="control-label require" for="city">Ваше имя</label>
		<div class="controls">
            <input type="text" name="name" class="inputbox" value="<?php echo $this->user->name; ?>" required="true" />
		</div>
	</div>
	<div class="control-group">
		<label class="control-label require" for="city">Эл. почта</label>
		<div class="controls">
            <input type="text" name="email" class="inputbox" value="<?php echo $this->user->email; ?>" required="true" />
		</div>
	</div>
	<div class="control-group">
		<label class="control-label require" for="inputPhone">Телефон</label>
		<div class="controls">
            <div class="input-append">
                <input type="text" name="phone" id="customer_phone" class="phone" value="<?php echo $this->user->phone; ?>" required="true" />
                <span class="add-on">
                    <input type="hidden" id="phone_mask" checked="true" />
                    <label id="descr" for="phone_mask">Введите номер</label>
                </span>
            </div>
		</div>
	</div>
</div>	