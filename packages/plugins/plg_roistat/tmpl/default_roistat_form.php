<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<div class="alert alert-error hide js-RoiError">
	<p class="js-RoiError-Common">Упс, что-то пошло не так... Мы знаем о проблеме и уже работаем. Попробуйте обновить страницу или зайти попозже.</p>
	<p class="js-RoiError-Roi"></p>
</div>
<div class="control-group">
	<label class="control-label" for="inputEmail"><b>E-Mail</b></label>
	<div class="controls">
		<input type="email" name="login" id="inputEmail" placeholder="E-Mail" class="inputbox" required="" autofocus="true">
	</div>
</div>
<div class="control-group">
	<label class="control-label" for="inputPassword"><b>Пароль</b></label>
	<div class="controls">
		<input type="password" name="password" id="inputPassword" placeholder="Пароль" class="inputbox" required="">
	</div>
</div>
<div class="control-group">
	<div class="controls">
		<button type="submit" class="btn btn-connect btn-large">Войти</button>
		<a href="javascript:void(0);" class="btn btn-link hide js-RoiCreateLink">Пользователя не существует, создать?</a>
	</div>
</div>