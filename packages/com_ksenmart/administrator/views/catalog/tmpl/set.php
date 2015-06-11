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
			<?php echo $this->title; ?>
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
					<div class="row">
						<?php echo $this->form->getLabel('title'); ?>
						<?php echo $this->form->getInput('title'); ?>
						<span class="linka" rel="meta-data">
							<a><?php echo JText::_('ksm_metadata')?></a>
						</span>
						<span class="linka" rel="alias">
							<a><?php echo JText::_('ksm_alias')?></a>
						</span>
					</div>
					<div class="row meta-data" style="display: none">
						<?php echo $this->form->getLabel('metatitle'); ?>
						<?php echo $this->form->getInput('metatitle'); ?>
					</div>
					<div class="row meta-data" style="display: none">
						<?php echo $this->form->getLabel('metadescription'); ?>
						<?php echo $this->form->getInput('metadescription'); ?>
					</div>
					<div class="row meta-data" style="display: none">
						<?php echo $this->form->getLabel('metakeywords'); ?>
						<?php echo $this->form->getInput('metakeywords'); ?>
					</div>
					<div class="row alias" style="display: none">
						<?php echo $this->form->getLabel('alias'); ?>
						<?php echo $this->form->getInput('alias'); ?>
					</div>		
					<div class="row">
						<?php echo $this->form->getLabel('product_code'); ?> 
						<?php echo $this->form->getInput('product_code'); ?> 
					</div>
					<div class="row">
						<div class="col">
							<?php echo $this->form->getLabel('price'); ?> 
							<?php echo $this->form->getInput('price'); ?>
						</div> 
						<div class="col">
							<?php echo $this->form->getInput('price_type'); ?>
						</div>
					</div>
					<div class="row">
						<?php echo $this->form->getLabel('old_price'); ?> 
						<?php echo $this->form->getInput('old_price'); ?>
					</div> 					
					<div class="row">
						<?php echo $this->form->getLabel('tag'); ?> 
						<?php echo $this->form->getInput('tag'); ?>
					</div>					
					<div class="row">
						<label class="inputname"><?php echo JText::_('KSM_CATALOG_PRODUCT_FLAG'); ?></label>
						<div class="checkb">
							<?php echo $this->form->getInput('new'); ?>
							<?php echo $this->form->getLabel('new'); ?>
						</div>
						<div class="checkb">
							<?php echo $this->form->getInput('promotion'); ?>
							<?php echo $this->form->getLabel('promotion'); ?>
						</div>
						<div class="checkb">
							<?php echo $this->form->getInput('recommendation'); ?>
							<?php echo $this->form->getLabel('recommendation'); ?>
						</div>
						<div class="checkb">
							<?php echo $this->form->getInput('hot'); ?>
							<?php echo $this->form->getLabel('hot'); ?>
						</div>						
					</div>
					<div class="row">
						<h3><?php echo $this->form->getLabel('content'); ?></h3>
					</div>
					<div class="row">
						<?php echo $this->form->getInput('content'); ?>
					</div>
					<div class="row">
						<span class="linka" rel="minidesc">
							<a href="#"><?php echo JText::_('KSM_ADD_MINIDESC'); ?></a>
						</span>
					</div>
					<div class="row minidesc" style="display: none;">
						<?php echo $this->form->getInput('introcontent'); ?>
					</div>
					<div class="row">
						<h3><?php echo $this->form->getLabel('relative'); ?></h3>
					</div>						
					<div class="row">
						<?php echo $this->form->getInput('relative'); ?>
					</div>						
				</td>	
				<td class="rightcol">
					<?php echo $this->form->getInput('images'); ?>
					<?php echo $this->form->getInput('categories'); ?>
				</td>
			</tr>	
		</table>
	<input type="hidden" name="task" value="save_form_item">
	<input type="hidden" name="close" value="1">
	<?php echo $this->form->getInput('id');?>
	<?php echo $this->form->getInput('type');?>
	</div>
</form>