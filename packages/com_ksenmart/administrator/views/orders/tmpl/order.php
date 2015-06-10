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
		<div class="save-close" style="width:auto;">
			<?php echo $this->loadTemplate('printforms');?>
			<input type="submit" value="<?php echo JText::_('KS_SAVE'); ?>" class="save">
			<input type="button" class="close" onclick="parent.closePopupWindow();">
		</div>
	</div>
	<div class="edit">
		<table width="100%">
			<tr>
				<td class="leftcol">
					<?php echo $this->loadTemplate('info');?>
					<br clear="both"><br>
					<div class="row">
						<h3><?php echo $this->form->getLabel('note'); ?></h3>
					</div>
					<div class="row">
						<?php echo $this->form->getInput('note'); ?>
					</div>
					<div class="row">
						<span class="linka" rel="adminnote">
							<a href="#"><?php echo JText::_('KSM_ORDERS_ORDER_ADD_ADMINNOTE'); ?></a>
						</span>
					</div>
					<div class="row adminnote" style="display: none;">
						<?php echo $this->form->getInput('admin_note'); ?>
					</div>
					<div class="row items">
						<?php echo $this->form->getInput('items'); ?>
					</div>
					<div class="row costs">
						<?php echo $this->form->getInput('costs'); ?>
					</div>
				</td>
				<td class="rightcol">
					<?php echo $this->form->getInput('user_id'); ?>
					<?php echo $this->form->getInput('customer_fields'); ?>
					<?php echo $this->form->getInput('address_fields'); ?>
					<div class="row">
						<div id="ksenmart-map-layer"></div>
					</div>
				</td>
			</tr>
		</table>
	</div>
	<input type="hidden" name="task" value="save_form_item">
	<?php echo $this->form->getInput('id'); ?>
	<?php echo $this->form->getInput('shipping_coords'); ?>
</form>