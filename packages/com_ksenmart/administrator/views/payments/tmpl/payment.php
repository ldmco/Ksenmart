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
						<?php echo $this->form->getLabel('type'); ?>
						<?php echo $this->form->getInput('type'); ?>
					</div>	
					<div class="row">
						<?php echo $this->form->getLabel('published'); ?>
						<div class="checkb">
							<?php echo $this->form->getInput('published'); ?>
						</div>
					</div>	
					<div class="row alert">
						<p><b><?php echo JText::_('KSM_PAYMENTS_LINKS'); ?></b></p>
						<p><u><?php echo JText::_('KSM_PAYMENTS_LINKS_RESULT'); ?>:</u>&nbsp;&nbsp;<?php echo JURI::root().'index.php?option=com_ksenmart&task=cart.pay_order'; ?></p>
						<p><u><?php echo JText::_('KSM_PAYMENTS_LINKS_SUCCESS'); ?>:</u>&nbsp;&nbsp;<?php echo JURI::root().'index.php?option=com_ksenmart&view=cart&layout=pay_success'; ?></p>
						<p><u><?php echo JText::_('KSM_PAYMENTS_LINKS_ERROR'); ?>:</u>&nbsp;&nbsp;<?php echo JURI::root().'index.php?option=com_ksenmart&view=cart&layout=pay_error'; ?></p>
					</div>
					<div class="params-set">
						<?php echo $this->paramsform;?>
					</div>	
					<div class="row">
						<h3><?php echo $this->form->getLabel('description'); ?></h3>
					</div>
					<div class="row">
						<?php echo $this->form->getInput('description'); ?>
					</div>						
				</td>
				<td class="rightcol">	
					<?php echo $this->form->getInput('images'); ?>
					<?php echo $this->form->getInput('regions'); ?>
				</td>
			</tr>	
		</table>
	</div>	
	<input type="hidden" name="task" value="save_form_item">
	<?php echo $this->form->getInput('id'); ?>
</form>	