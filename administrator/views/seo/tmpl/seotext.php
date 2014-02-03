<?php
defined( '_JEXEC' ) or die;
JHtml::_('behavior.tooltip');
?>
<form class="form" method="post">
	<div class="heading">
		<h3><?php echo $this->title;?></h3>
		<div class="save-close">
			<input type="button" value="<?php echo JText::_('ksm_save')?>" class="save">
			<input type="button" class="close" onclick="parent.closePopupWindow();">
		</div>
	</div>
	<div class="edit">
		<table width="100%">
			<tr>
				<td class="leftcol">
					<div class="row">
						<?php echo $this->form->getLabel('metatitle'); ?>
						<?php echo $this->form->getInput('metatitle'); ?>
					</div>	
					<div class="row">
						<h3><?php echo $this->form->getLabel('metadescription'); ?></h3>
					</div>
					<div class="row">
						<?php echo $this->form->getInput('metadescription'); ?>
					</div>	
					<div class="row">
						<h3><?php echo $this->form->getLabel('metakeywords'); ?></h3>
					</div>
					<div class="row">
						<?php echo $this->form->getInput('metakeywords'); ?>
					</div>			
					<div class="row">
						<h3><?php echo $this->form->getLabel('text'); ?></h3>
					</div>
					<div class="row">
						<?php echo $this->form->getInput('text'); ?>
					</div>						
				</td>
				<td class="rightcol">	
					<?php echo $this->form->getInput('categories'); ?>
					<?php echo $this->form->getInput('manufacturers'); ?>
					<?php echo $this->form->getInput('countries'); ?>
					<?php echo $this->form->getInput('properties'); ?>
				</td>
			</tr>	
		</table>
	</div>	
	<input type="hidden" name="task" value="save_form_item">
	<?php echo $this->form->getInput('id'); ?>
</form>