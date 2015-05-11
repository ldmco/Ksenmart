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
				<th align="left" style="position:relative;">
					<?php echo JText::sprintf('ksm_exportimport_import_csv_step',1);?>
					<input type="submit" class="saves-green" value="<?php echo JText::_('ksm_upload')?>">
				</th>	
			</tr>
		<thead>
		<tbody>
		<tr>
			<td class="rightcol" style="background:#f9f9f9!important;padding-top:15px;">
				<div class="row">
					<label class="inputname" style="width:200px;"><?php echo JText::_('ksm_exportimport_import_csv_choosefile')?></label>
					<input type="file" name="csvfile">
				</div>
				<div class="row">
					<label class="inputname" style="width:200px;"><?php echo JText::_('ksm_exportimport_import_csv_choosecharset')?></label>
					<select class="sel" id="encoding" name="encoding" >
						<option value="cp1251">windows 1251</option>
						<option value="utf8">utf 8</option>
					</select>
				</div>	
				<div class="row">	
					<a href="<?php echo JURI::root(); ?>plugins/kmexportimport/import_csv/assets/example.csv" target="_blank"><?php echo JText::_('ksm_exportimport_import_csv_example')?></a>
				</div>				
			</td>
		</tr>
		</tbody>
	</table>
	<input type="hidden" name="type" value="import_csv" />
	<input type="hidden" name="step" value="parse" />
</form>