<?php defined('_JEXEC') or die; ?>
<?php $user = JFactory::getUser(); ?>
<div class="discount noTransition text-center">
	<p>Хотите получить скидку на все наши товары, а также получать спецпредложения и новости нашей компании?</p>
    <div class="row-fluid">
        <i class="icons icon-arrow-down"></i>
        <a href="#">Подписаться</a>
        <i class="icons icon-arrow-down"></i>
    </div>
	<form class="login-form form-horizontal text-left span6">
        <div class="control-group">
        	<label class="control-label require" for="inputName">Эл. почта</label>
        	<div class="controls">
        		<input type="email" name="login" id="inputName" class="inputbox" value="<?php echo !$user->guest?$user->email:''; ?>" placeholder="Ваш E-Mail" required="true" />
        	</div>
        </div>
        <div class="control-group">
        	<div class="controls">
        		<input type="submit" value="Подписаться" class="btn btn-success" />
        	</div>
        </div>
	</form>
</div>