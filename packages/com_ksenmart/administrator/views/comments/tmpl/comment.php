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
						<?php echo $this->form->getLabel('name'); ?>
						<?php echo $this->form->getInput('name'); ?>
					</div>		
					<div class="row">
						<?php echo $this->form->getLabel('rate'); ?>
						<?php echo $this->form->getInput('rate'); ?>
					</div>		
					<div class="row">
						<?php echo $this->form->getInput('rates'); ?>
					</div>					
					<div class="row">
						<h3><?php echo $this->form->getLabel('comment'); ?></h3>
					</div>				
					<div class="row">
						<?php echo $this->form->getInput('comment'); ?>
					</div>		
					<div class="row">
						<h3><?php echo $this->form->getLabel('good'); ?></h3>
					</div>				
					<div class="row">
						<?php echo $this->form->getInput('good'); ?>
					</div>
					<div class="row">
						<h3><?php echo $this->form->getLabel('bad'); ?></h3>
					</div>				
					<div class="row">
						<?php echo $this->form->getInput('bad'); ?>
					</div>					
				</td>
				<td class="rightcol">	
					<?php echo $this->form->getInput('user_id'); ?>
					<?php echo $this->form->getInput('product_id'); ?>
				</td>
			</tr>	
		</table>	
	</div>	
	<input type="hidden" name="task" value="save_form_item">
	<?php echo $this->form->getInput('id');?>
</form>