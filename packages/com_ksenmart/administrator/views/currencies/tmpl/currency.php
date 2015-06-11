<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

JHtml::_('behavior.tooltip');
?>
<form class="form" method="post">
	<div class="heading">
		<h3><?php echo $this->title;?></h3>
		<div class="save-close">
			<input type="submit" value="<?php echo JText::_('KS_SAVE'); ?>" class="save">
			<input type="button" class="close" onclick="parent.closePopupWindow();">
		</div>
	</div>
	<div class="edit">
		<table width="100%">
			<tr>
				<td class="leftcol">
					<div class="row">
						<?php echo $this->form->getLabel('title'); ?>
						<?php echo $this->form->getInput('title'); ?>
					</div>	
					<div class="row">
						<?php echo $this->form->getLabel('code'); ?>
						<?php echo $this->form->getInput('code'); ?>
					</div>	
					<div class="row">
						<?php echo $this->form->getLabel('rate'); ?>
						<?php echo $this->form->getInput('rate'); ?>
					</div>	
					<div class="row">
						<?php echo $this->form->getLabel('separator'); ?>
						<?php echo $this->form->getInput('separator'); ?>
					</div>	
					<div class="row">
						<?php echo $this->form->getLabel('template'); ?>
						<?php echo $this->form->getInput('template'); ?>
					</div>	
					<div class="row">
						<?php echo $this->form->getLabel('default'); ?>
						<div class="checkb">
							<?php echo $this->form->getInput('default'); ?>
						</div>		
					</div>					
					<div class="row">
						<?php echo $this->form->getLabel('fractional'); ?>
						<?php echo $this->form->getInput('fractional'); ?>
					</div>						
				</td>
			</tr>	
		</table>
	</div>	
	<input type="hidden" name="task" value="save_form_item">
	<?php echo $this->form->getInput('id'); ?>
</form>