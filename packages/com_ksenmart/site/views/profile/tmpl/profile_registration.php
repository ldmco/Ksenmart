<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<form method="POST" action="index.php?option=com_ksenmart&task=shopajax.site_reg">
    <legend><?php echo JText::_('KSM_PROFILE_REGISTRATION'); ?></legend>
	<div class="control-group">
		<div class="controls">
			<input type="text" class="inputbox" name="first_name" value="" placeholder="<?php echo JText::_('KSM_PROFILE_REGISTRATION_FIRSTNAME'); ?>" required="true" />
		</div>
	</div>
	<div class="control-group">
		<div class="controls">
			<input type="text" class="inputbox" name="last_name" value="" placeholder="<?php echo JText::_('KSM_PROFILE_REGISTRATION_LASTNAME'); ?>" />
		</div>
	</div>
	<div class="control-group">
		<div class="controls">
			<input type="text" class="inputbox" name="middle_name" value="" placeholder="<?php echo JText::_('KSM_PROFILE_REGISTRATION_MIDDLENAME'); ?>" />
		</div>
	</div>	
	<?php foreach($this->fields as $field): ?>
	<div class="control-group">
		<div class="controls">
			<input type="text" class="inputbox" name="fields[<?php echo $field->id; ?>]" value="" placeholder="<?php echo $field->title; ?>" />
		</div>
	</div>		
	<?php endforeach; ?>	
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