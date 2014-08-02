<?php
defined( '_JEXEC' ) or die;
?>
<script>
	var JText_print_template_name='<?php echo JText::_('print_template_name')?>';
</script>
<form class="form" method="post">
	<div class="heading">
		<h3>
			<?php echo JText::_('template_editor')?>
		</h3>
		<div class="save-close">
			<input type="button" value="<?php echo JText::_('save')?>" class="save">
			<input type="button" class="close" onclick="parent.closePopupWindow();">
		</div>
	</div>
	<div class="edit no-content">
		<div class="row">
			<label class="inputname"><?php echo JText::_('template_name')?></label>
			<input type="text" class="inputbox" name="name" value="<?php echo $this->template->name?>" style="width: 250px;">
		</div>
		<div class="row">
			<label class="inputname"><?php echo JText::_('template_title')?></label>
			<input type="text" class="inputbox" name="title" value="<?php echo $this->template->title?>" style="width: 250px;">
		</div>			
		<br>	
		<span class="grey-span"><?php echo JText::_('template_text')?></span>
		<br>
		<?php echo $this->editor->display('text',$this->template->text,800,250,40,0,true);?>
	<div>
	<input type="hidden" name="option" value="com_ksenmart">
	<input type="hidden" name="tmpl" value="component">
	<input type="hidden" name="view" value="sendmails">
	<input type="hidden" name="task" value="sendmails.save_template">
	<input type="hidden" name="id" value="<?php echo $this->template->id?>">	
</form>