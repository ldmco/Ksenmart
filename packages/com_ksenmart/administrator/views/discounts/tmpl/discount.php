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
		<h3>
			<?php echo $this->title;?>
		</h3>
		<div class="save-close">
			<input type="submit" value="<?php echo JText::_('KS_SAVE'); ?>" class="save">
			<input type="button" class="close" onclick="parent.closePopupWindow();">
		</div>
	</div>
	<div class="edit">
		<table width="100%">
			<tr>
				<td class="leftcol">
					<div class="set">
						<div class="row">
							<?php echo $this->form->getLabel('title'); ?>
							<?php echo $this->form->getInput('title'); ?>
						</div>	
						<div class="row">
							<?php echo $this->form->getLabel('type'); ?>
							<?php echo $this->form->getInput('type'); ?>
						</div>							
						<div class="row dates">
							<label class="inputname"><?php echo JText::_('ksm_discounts_discount_period')?></label>					
							<?php echo $this->form->getInput('from_date'); ?>
							<span>-</span>
							<?php echo $this->form->getInput('to_date'); ?>					
						</div>
						<div class="row">
							<?php echo $this->form->getLabel('sum'); ?>
							<div class="checkb">
								<?php echo $this->form->getInput('sum'); ?>
							</div>
						</div>						
						<div class="row">
							<?php echo $this->form->getLabel('enabled'); ?>
							<div class="checkb">
								<?php echo $this->form->getInput('enabled'); ?>
							</div>	
						</div>		
					</div>
					<div class="params-set">
						<?php echo $this->paramsform;?>
					</div>	
					<div class="set">
						<h3 class="headname"><?php echo JText::_('ksm_discount_usergroups_lbl')?></h3>
						<div class="lists">
							<div class="row">					
								<?php echo $this->form->getInput('user_groups');?>
							</div>	
						</div>	
						<br clear="both">
						<h3 class="headname"><?php echo JText::_('ksm_discount_useractions_lbl')?></h3>
						<div class="row">
							<?php echo $this->form->getLabel('actions_limit'); ?>
							<?php echo $this->form->getInput('actions_limit'); ?>
						</div>	
						<?php echo $this->form->getInput('user_actions');?>
					</div>	
					<div class="set">
						<h3 class="headname"><?php echo JText::_('ksm_discount_infomethods_lbl')?></h3>
						<div class="lists">
							<div class="row">						
								<?php echo $this->form->getInput('info_methods');?>
							</div>	
						</div>		
					</div>	
					<div class="row">
						<h3><?php echo $this->form->getLabel('content'); ?></h3>
					</div>
					<div class="row">
					<?php echo $this->form->getInput('content'); ?>
					</div>						
				</td>
				<td class="rightcol">	
					<?php echo $this->form->getInput('images'); ?>
					<?php echo $this->form->getInput('categories'); ?>
					<?php echo $this->form->getInput('manufacturers'); ?>
					<?php echo $this->form->getInput('regions'); ?>
				</td>
			</tr>	
		</table>
	</div>	
	<input type="hidden" name="task" value="save_form_item">
	<?php echo $this->form->getInput('id'); ?>
</form>	