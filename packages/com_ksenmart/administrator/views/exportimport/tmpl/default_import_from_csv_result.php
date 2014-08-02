<?php	 		 		 	
defined( '_JEXEC' ) or die;
?>
<form method="post" class="form" enctype="multipart/form-data">
	<table class="cat" width="100%" cellspacing="0">	
		<thead>
			<tr>
				<th align="left">
					<?php echo JText::sprintf('ksm_exportimport_import_from_csv_step',3);?>
				</th>	
			</tr>
		</thead>
		<tbody>
		<tr>
			<td class="rightcol" style="background:#f9f9f9!important;padding-top:15px;">
				<div class="row">
					<label class="inputname" style="width:200px;"><?php echo JText::_('ksm_exportimport_import_from_csv_products_added');?></label>
					<?php echo (int)$this->info['insert']?>
				</div>
				<div class="row">
					<label class="inputname" style="width:200px;"><?php echo JText::_('ksm_exportimport_import_from_csv_products_updated');?></label>
					<?php echo (int)$this->info['update']?>
				</div>				
			</td>
		</tr>
		</tbody>
	</table>
</form>	
