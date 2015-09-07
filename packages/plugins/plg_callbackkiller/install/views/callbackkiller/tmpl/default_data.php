<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<h1><?php echo JText::_('ks_callbackkiller_data'); ?></h1>
<a target="_blank" href="http://callbackkiller.ru" class="killer-logo"></a>
<div class="killer-data">
	<div class="row">
		<label><?php echo JText::_('ksm_plugin_callbackkiller_login_lbl'); ?>:</label>
		<?php echo $this->plg_params->login; ?>
	</div>
	<div class="row">
		<label><?php echo JText::_('ksm_plugin_callbackkiller_password_lbl'); ?>:</label>
		<?php echo $this->plg_params->password; ?>
	</div>
	<div class="row">
		<label><?php echo JText::_('ksm_plugin_callbackkiller_callbackkiller_code_lbl'); ?>:</label>
		<?php echo $this->plg_params->callbackkiller_code; ?>
	</div>
	<div class="row">
		<a target="_blank" href="https://callbackkiller.ru/user/killers/<?php echo $this->plg_params->callbackkiller_code; ?>/edit/?loginhash=<?php echo $this->plg_params->loginhash; ?>" class="btn btn-success"><?php echo JText::_('ks_callbackkiller_enter'); ?></a>
	</div>	
	<div class="row">
		<a href="#" class="btn btn-primary update"><?php echo JText::_('ks_callbackkiller_update'); ?></a>
		<a href="javascript:void(0);" class="btn btn-primary updating"><?php echo JText::_('ks_callbackkiller_updating'); ?></a>
	</div>		
</div>