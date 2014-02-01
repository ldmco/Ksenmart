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
	<div class="edit no-content">
		<div class="row">
			<?php echo $this->form->getLabel('title'); ?>
			<?php echo $this->form->getInput('title'); ?>
		</div>	
	</div>
	<input type="hidden" name="task" value="save_form_item">
	<?php echo $this->form->getInput('id');?>
</form>	