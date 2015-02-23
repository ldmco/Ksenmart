<?php defined('_JEXEC') or die; ?>
<form method="POST" action="index.php?option=com_ksenmart&task=shopajax.site_reg">
    <legend>Регистрация</legend>
	<div class="control-group">
		<div class="controls">
			<input type="text" class="inputbox" name="first_name" value="" placeholder="Ваше имя" required="true" />
		</div>
	</div>
	<div class="control-group">
		<div class="controls">
			<input type="email" class="inputbox" name="login" value="" placeholder="Эл. почта" required="true" />
		</div>
	</div>
	<div class="control-group">
		<div class="controls">
			<input type="password" class="inputbox" name="password" value="" placeholder="Пароль" required="true" />
		</div>
	</div>
	<div class="control-group">
		<div class="controls">
			<input type="password" class="inputbox" name="password1" value="" placeholder="Подтверждение пароля" required="true" />
		</div>
	</div>
	<div class="control-group">
		<div class="controls controls-row">
			<button type="submit" class="st_button btn btn-success">Регистрация</button>
		</div>
	</div>
	<div class="control-group">
		<div class="controls">
			<ul class="unstyled">
				<li><a href="<?php echo JRoute::_('index.php?option=com_users&view=reset'); ?>">Напомнить пароль</a></li>
			</ul>
		</div>
	</div>
</form>