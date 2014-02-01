<?php	 		 		 	
defined( '_JEXEC' ) or die;
?>
<script>
	var JText_print_mail_subject='<?php echo JText::_('print_mail_subject')?>';
	var JText_choose_user_group='<?php echo JText::_('choose_user_group')?>';
</script>
<form method="post" class="form">
	<table class="cat" width="100%" cellspacing="0">	
		<thead>
			<tr>
				<th align="left" style="position:relative;">
					<?php echo JText::_('send_mail')?> - <?php echo JText::_($this->template->name)?>
					<input type="button" class="saves-green" value="<?php echo JText::_('send')?>">
				</th>	
			</tr>
		<thead>
		<tbody>
		<tr>
			<td style="background:#f9f9f9!important;">
				<div class="row">
					<label class="inputname"><?php echo JText::_('template_title')?></label>
					<input type="text" class="inputbox_205" name="title" value="<?php echo $this->template->title?>" style="width: 250px;">
				</div>	
				<?php echo KsenMartHelper::loadModules('','user_groups_slide',array('active'=>1,'show_add_link'=>0,'selected'=>array(1)))?>
				<br>	
				<span class="grey-span"><?php echo JText::_('template_text')?></span>
				<br>
				<?php echo $this->editor->display('text',$this->template->text,800,250,40,0,true);?>
			</td>
		</tr>
		</tbody>
	</table>
	<input type="hidden" name="groups" value="">
	<input type="hidden" name="option" value="com_ksenmart">
	<input type="hidden" name="tmpl" value="component">
	<input type="hidden" name="view" value="sendmails">
	<input type="hidden" name="task" value="sendmails.send_mail">	
</form>	