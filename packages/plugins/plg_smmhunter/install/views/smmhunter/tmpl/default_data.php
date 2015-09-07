<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<h1><?php echo JText::_('ks_smmhunter_data'); ?></h1>
<a target="_blank" href="http://smm-hunter.ru" class="hunter-logo"></a>
<div class="hunter-data">
	<div class="row">
		<label><?php echo JText::_('ksm_plugin_smmhunter_login_lbl'); ?>:</label>
		<?php echo $this->plg_params->login; ?>
	</div>
	<div class="row">
		<label><?php echo JText::_('ksm_plugin_smmhunter_password_lbl'); ?>:</label>
		<?php echo $this->plg_params->password; ?>
	</div>
	<div class="row">
		<label><?php echo JText::_('ksm_plugin_smmhunter_smmhunter_code_lbl'); ?>:</label>
		<?php echo $this->plg_params->smmhunter_code; ?>
	</div>
	<div class="row">
		<a target="_blank" href="http://smm-hunter.ru" class="btn btn-success"><?php echo JText::_('ks_smmhunter_enter'); ?></a>
	</div>	
</div>