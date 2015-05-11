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
					<?php echo JText::_('KSM_EXPORTIMPORT_EXPORT_CSV')?>
					<input type="submit" class="saves-green" value="<?php echo JText::_('KSM_EXPORTIMPORT_EXPORT_CSV_EXPORTING'); ?>">
				</th>	
			</tr>
		<thead>
		<tbody>
		<tr>
			<td class="rightcol" style="background:#f9f9f9!important;padding:15px 10px;">
				<div class="row">
					<div align="right">
						<a class="check_all_cats"><?php echo JText::_('ksm_exportimport_export_csv_check_all')?></a> | <a class="discharge_cats"><?php echo JText::_('ksm_exportimport_export_csv_discharge')?></a>
					</div>		
					<br>
					<?php echo $view->form->getInput('categories');?>
				</div>	
				<div class="row">
					<?php echo $view->form->getLabel('unic');?>
					<?php echo $view->form->getInput('unic');?>
				</div>	
			</td>
		</tr>
		</tbody>
	</table>
	<input type="hidden" name="type" value="export_csv" />
	<input type="hidden" name="step" value="export" />
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