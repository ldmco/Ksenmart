<?php defined('_JEXEC') or die; ?>
<form method="POST" action="index.php?option=com_ksenmart&task=shopajax.site_reg">
    <legend><?php echo JText::_('KSM_PROFILE_REGISTRATION'); ?></legend>
	<div class="control-group">
		<div class="controls">
			<input type="text" class="inputbox" name="first_name" value="" placeholder="<?php echo JText::_('KSM_PROFILE_REGISTRATION_FIRSTNAME'); ?>" required="true" />
		</div>
	</div>
	<div class="control-group">
		<div class="controls">
			<input type="email" class="inputbox" name="login" value="" placeholder="<?php echo JText::_('KSM_PROFILE_REGISTRATION_EMAIL'); ?>" required="true" />
		</div>
	</div>
	<div class="control-group">
		<div class="controls">
			<input type="password" class="inputbox" name="password" value="" placeholder="<?php echo JText::_('KSM_PROFILE_REGISTRATION_PASSWORD'); ?>" required="true" />
		</div>
	</div>
	<div class="control-group">
		<div class="controls">
			<input type="password" class="inputbox" name="password1" value="" placeholder="<?php echo JText::_('KSM_PROFILE_REGISTRATION_PASSWORD2'); ?>" required="true" />
		</div>
	</div>
	<div class="control-group">
		<div class="controls controls-row">
			<button type="submit" class="st_button btn btn-success"><?php echo JText::_('KSM_PROFILE_REGISTRATION'); ?></button>
		</div>
	</div>
	<div class="control-group">
		<div class="controls">
			<ul class="unstyled">
				<li><a href="<?php echo JRoute::_('index.php?option=com_users&view=reset'); ?>"><?php echo JText::_('KSM_PROFILE_REGISTRATION_RESET_PASSWORD'); ?></a></li>
			</ul>
		</div>
	</div>
</form>