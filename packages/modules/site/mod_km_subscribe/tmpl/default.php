<?php defined('_JEXEC') or die; ?>
<?php $user = JFactory::getUser(); ?>
<div class="hidden-phone discount noTransition span12 text-center">
    <span class="delta">◆</span>
    <span>%</span>
    <p>Хотите получить скидку на все наши товары, а также получать спецпредложения и новости нашей компании?</p>
    <a href="javascript:void(0);">Подписаться</a>
    <form class="login-form form-horizontal text-left span6">
        <div class="control-group">
            <label class="control-label require" for="inputName">Эл. почта</label>
            <div class="controls">
                <input type="email" id="inputName" value="" name="login" class="inputbox" value="<?php echo !$user->guest?$user->email:''; ?>" placeholder="Ваш E-Mail" required="true">
            </div>  
        </div>  
        <div class="control-group">
            <div class="controls">
                <input type="submit" value="Подписаться" class="btn btn-success" />
            </div>
        </div>
    </form>
</div>