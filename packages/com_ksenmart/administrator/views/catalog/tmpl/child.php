<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
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
							<a><?php echo JText::_('KSM_METADATA'); ?></a>
						</span>
						<span class="linka" rel="alias">
							<a><?php echo JText::_('KS_ALIAS'); ?></a>
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
						<span class="linka" rel="old_price"><a><?php echo JText::_('KSM_CATALOG_PRODUCT_OLD_PRICE_LBL'); ?></a></span>
						<span class="linka" rel="purchase_price"><a><?php echo JText::_('KSM_CATALOG_PRODUCT_PURCHASE_PRICE_LBL'); ?></a></span>
					</div>
					<div class="row old_price" style="display: none;">
						<?php echo $this->form->getLabel('old_price'); ?> 
						<?php echo $this->form->getInput('old_price'); ?> 
					</div>
					<div class="row purchase_price" style="display: none;">
						<?php echo $this->form->getLabel('purchase_price'); ?> 
						<?php echo $this->form->getInput('purchase_price'); ?> 
					</div>
					<div class="row">
						<div class="col">
							<?php echo $this->form->getLabel('in_stock'); ?> 
							<?php echo $this->form->getInput('in_stock'); ?>
						</div> 
						<div class="col">
							<?php echo $this->form->getInput('product_unit'); ?>
						</div>
						<span class="linka" rel="packaging"><a><?php echo JText::_('KSM_CATALOG_PRODUCT_PACKAGING_LBL'); ?></a></span>
					</div>
					<div class="row packaging"  style="display: none;">
						<?php echo $this->form->getLabel('product_packaging'); ?> 
						<?php echo $this->form->getInput('product_packaging'); ?>
					</div>
					<div class="row">
						<?php echo $this->form->getLabel('tags'); ?> 
						<?php echo $this->form->getInput('tags'); ?>
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
						<?php echo $this->form->getInput('properties');?>
					</div>
				</td>
				<td class="rightcol">
					<?php echo $this->form->getInput('images'); ?>
					<?php echo $this->form->getInput('categories'); ?>
					<?php echo $this->form->getInput('relative'); ?>
				</td>
			</tr>
		</table>
	<input type="hidden" name="task" value="save_form_item">
	<?php echo $this->form->getInput('id');?>
	<?php echo $this->form->getInput('parent_id');?>
	<?php echo $this->form->getInput('type');?>
	<?php echo $this->form->getInput('is_parent');?>
	</div>
</form>