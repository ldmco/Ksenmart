<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<form method="post" class="form" enctype="multipart/form-data">
	<table class="cat" width="100%" cellspacing="0">	
		<thead>
			<tr>
				<th align="left">
					<?php echo JText::sprintf('ksm_exportimport_import_csv_step',3);?>
				</th>	
			</tr>
		</thead>
		<tbody>
		<tr>
			<td class="rightcol" style="background:#f9f9f9!important;padding-top:15px;">
				<div class="row">
					<label class="inputname" style="width:200px;"><?php echo JText::_('ksm_exportimport_import_csv_products_added');?></label>
					<?php echo (int)$view->info['insert']?>
				</div>
				<div class="row">
					<label class="inputname" style="width:200px;"><?php echo JText::_('ksm_exportimport_import_csv_products_updated');?></label>
					<?php echo (int)$view->info['update']?>
				</div>				
			</td>
		</tr>
		</tbody>
	</table>
</form>	
