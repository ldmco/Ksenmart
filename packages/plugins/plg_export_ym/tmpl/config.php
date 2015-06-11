<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<form method="post" class="form">
	<table class="cat" width="100%" cellspacing="0">	
		<thead>
			<tr>
				<th align="left" style="position:relative;">
					<?php echo JText::_('ksm_exportimport_export_ym_settings')?>
					<input type="submit" id="save_yandexmarket" class="saves-green" value="<?php echo JText::_('KS_SAVE'); ?>">
				</th>	
			</tr>
		<thead>
		<tbody>
		<tr>
			<td class="rightcol" style="background:#f9f9f9!important;padding:15px 10px;">
				<div class="row">
					<a href="<?php echo JURI::root().'index.php?option=com_ksenmart&view=catalog&exportym=1'?>" target="_blank">
						<?php echo JText::_('ksm_exportimport_export_ym_text')?>
					</a>	
				</div>
				<div class="row">
					<div align="right">
						<a class="check_all_cats"><?php echo JText::_('ksm_exportimport_export_ym_check_all')?></a> | <a class="discharge_cats"><?php echo JText::_('ksm_exportimport_export_ym_discharge')?></a>
					</div>		
					<br>
					<?php echo $view->form->getInput('categories');?>
				</div>	
				<div class="row">
					<?php echo $view->form->getLabel('shopname');?>
					<?php echo $view->form->getInput('shopname');?>
				</div>	
				<div class="row">
					<?php echo $view->form->getLabel('company');?>
					<?php echo $view->form->getInput('company');?>
				</div>				
			</td>
		</tr>
		</tbody>
	</table>
	<input type="hidden" name="type" value="export_ym" />
	<input type="hidden" name="step" value="saveconfig" />	
</form>	
<script>
jQuery('body').on('click','.check_all_cats',function(){
	jQuery('.ksm-slidemodule-ksmcategories li').addClass('active');
	jQuery('.ksm-slidemodule-ksmcategories input[type="checkbox"]').attr('checked','checked');
});

jQuery('body').on('click','.discharge_cats',function(){
	jQuery('.ksm-slidemodule-ksmcategories li').removeClass('active');
	jQuery('.ksm-slidemodule-ksmcategories input[type="checkbox"]').removeAttr('checked');
});	
</script>