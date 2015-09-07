<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<h1><?php echo JText::_('ks_smmhunter_register'); ?></h1>
<a target="_blank" href="http://smm-hunter.ru" class="hunter-logo"></a>
<p><?php echo JText::_('ks_smmhunter_register_text'); ?></p>
<form class="form-horizontal">
	<div class="row">
		<?php echo $this->form->getInput('name'); ?>
	</div>
	<div class="row">
		<?php echo $this->form->getInput('email'); ?>
	</div>	
	<div class="row">
		<?php echo $this->form->getInput('agree'); ?>
		<?php echo $this->form->getLabel('agree'); ?>
	</div>
	<div class="row">	
		<input type="submit" class="btn btn-success register" value="<?php echo JText::_('ks_smmhunter_register_button'); ?>">
		<input type="button" class="btn btn-success registering" value="<?php echo JText::_('ks_smmhunter_registering_button'); ?>">
	</div>
</form>