<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

JHtml::_('behavior.modal');
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
				<div class="leftcol">
					<div class="row">
						<?php echo $this->form->getLabel('title');?>
						<?php echo $this->form->getInput('title');?>
						<span class="linka" rel="alias">
							<a><?php echo JText::_('ksm_alias')?></a>
						</span>						
						<span class="linka" rel="prefix">
							<a><?php echo JText::_('ksm_properties_property_prefix_lbl')?></a>
						</span>
						<span class="linka" rel="suffix">
							<a><?php echo JText::_('ksm_properties_property_suffix_lbl')?></a>
						</span>						
					</div>	
					<div class="row alias" style="display: none">
						<?php echo $this->form->getLabel('alias'); ?>
						<?php echo $this->form->getInput('alias'); ?>
					</div>	
					<div class="row prefix" style="display: none">
						<?php echo $this->form->getLabel('prefix'); ?>
						<?php echo $this->form->getInput('prefix'); ?>
					</div>
					<div class="row suffix" style="display: none">
						<?php echo $this->form->getLabel('suffix'); ?>
						<?php echo $this->form->getInput('suffix'); ?>
					</div>	
					<div class="row">
						<?php echo $this->form->getLabel('type'); ?>
						<?php echo $this->form->getInput('type');  ?>
					</div>					
					<div class="row default" <?php echo ($this->property->type!='text'?'style="display:none;"':'');?>>
						<?php echo $this->form->getLabel('default'); ?>
						<?php echo $this->form->getInput('default');  ?>
					</div>		
					<div class="row">
						<label class="inputname"><?php echo JText::_('KSM_PROPERTIES_PROPERTY_FLAG'); ?></label>
						<div class="checkb">
							<?php echo $this->form->getInput('published'); ?>
							<?php echo $this->form->getLabel('published'); ?>
						</div>
						<div class="checkb">
							<?php echo $this->form->getInput('edit_price'); ?>
							<?php echo $this->form->getLabel('edit_price'); ?>
						</div>
                        <div class="checkb">
							<?php echo $this->form->getInput('range'); ?>
							<?php echo $this->form->getLabel('range'); ?>
                        </div>
					</div>					
					<div class="row values" <?php echo ($this->property->type!='select'?'style="display:none;"':'');?>>
						<?php echo $this->form->getInput('values'); ?>
					</div>		
				</div>
				<div class="rightcol">
					<div class="rightcol-wra">
						<div <?php echo ($this->property->type!='select'?'style="display:none;"':'');?>>
							<?php echo $this->form->getInput('view'); ?>
						</div>
						<?php echo $this->form->getInput('categories'); ?>
					</div>
				</div>
	</div>	
	<input type="hidden" name="task" value="save_form_item">
	<?php echo $this->form->getInput('id'); ?>
</form>