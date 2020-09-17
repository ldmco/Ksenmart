<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
?>
<form class="form clearfix" method="POST">
	<div class="heading">
		<h3>
			<?php echo $this->title; ?>
		</h3>
		<div class="save-close">
			<input type="submit" value="<?php echo JText::_('KS_SAVE'); ?>" class="btn btn-save">
			<input type="button" class="close" onclick="parent.closePopupWindow();">
		</div>
	</div>
	<div class="edit">
				<div class="leftcol">
					<div class="row">
						<?php echo $this->form->getLabel('title'); ?>
						<?php echo $this->form->getInput('title'); ?>
					</div>
					<div class="row">
						<?php echo $this->form->getLabel('ratio'); ?>
						<?php echo $this->form->getInput('ratio'); ?>
					</div>
				</div>
				<div class="rightcol">
					<div class="rightcol-wra">
						<?php echo $this->form->getInput('images'); ?>
					</div>
				</div>
	<input type="hidden" name="task" value="save_form_item">
	<input type="hidden" name="close" value="1">
	<?php echo $this->form->getInput('id');?>
	</div>
</form>