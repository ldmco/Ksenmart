<?php
defined( '_JEXEC' ) or die;
?>
<form method="post" class="form">
	<table class="cat" width="100%" cellspacing="0">	
		<thead>
			<tr>
				<th align="left" style="position:relative;">
					<?php echo JText::_('KSM_EXPORTIMPORT_EXPORT_TO_CSV')?>
					<input type="submit" class="saves-green" value="<?php echo JText::_('KSM_EXPORTIMPORT_EXPORTING'); ?>">
				</th>	
			</tr>
		<thead>
		<tbody>
		<tr>
			<td class="rightcol" style="background:#f9f9f9!important;padding:15px 10px;">
				<div class="row">
					<div align="right">
						<a class="check_all_cats"><?php echo JText::_('ksm_exportimport_check_all')?></a> | <a class="discharge_cats"><?php echo JText::_('ksm_exportimport_discharge')?></a>
					</div>		
					<br>
					<?php echo $this->form->getInput('categories');?>
				</div>	
			</td>
		</tr>
		</tbody>
	</table>
	<input type="hidden" name="categories" value="" />
	<input type="hidden" name="task" value="exportimport.export_csv" />
	<input type="hidden" name="option" value="com_ksenmart" />
	<input type="hidden" name="view" value="exportimport" />	
</form>	
