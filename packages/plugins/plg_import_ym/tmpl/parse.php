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
				<?php echo JText::sprintf('ksm_exportimport_import_csv_step',2);?>
				<input type="submit" class="saves-green" value="<?php echo JText::_('ksm_upload')?>">
			</th>
		</tr>
		<thead>
		<tbody>
		<tr>
			<td class="rightcol" style="background:#f9f9f9!important;padding-top:15px;">
				<div class="row">
					<label class="inputname" style="width:200px;"><?php echo JText::_('ksm_exportimport_import_ym_chooseproperties')?></label>
					<select class="sel" id="properties_type" name="properties_type" >
						<option value="no">Текстом в описание</option>
						<option value="yes">Отдельными свойствами</option>
					</select>
				</div>
				<div class="row">
					<label class="inputname" style="width:200px;">Выберите уникальное поле</label>
					<select class="sel" id="uniq" name="uniq" >
						<option value="0">Стандартно</option>
						<option value="1">Артикул(id YM)</option>
					</select>
				</div>
				<input type="hidden" name="encoding" value="<?php echo $view->encoding;?>">
				<div style="display:none;" id="progressupload">
					<h2>Процесс загрузки:</h2>
					<div class="mesegges"></div>
				</div>
			</td>
		</tr>
		</tbody>
	</table>
	<input type="hidden" name="type" value="import_csv" />
	<input type="hidden" name="step" value="import" />
</form>
<script>
	var step_import = 1;
	var start = 0;

	jQuery(document).ready(function(){

		jQuery('form .saves-green').on('click', function(e){
			e.preventDefault();
			jQuery('#progressupload').show();
			uploadYm();
		});

	});

	function uploadYm(){
		var data = {};
		data['view'] = 'exportimport';
		data['task'] = 'pluginAction';
		data['action'] = 'uploadYm';
		data['plugin'] = 'Import_ym';
		data['format'] = 'json';
		data['tmpl'] = 'ksenmart';
		data['step'] = 'import';
		data['step_import'] = step_import;
		data['start'] = start;
		data['encoding'] = jQuery('input[name="encoding"]').val();
		data['properties_type'] = jQuery('#properties_type').val();
		console.log(data);
		jQuery.ajax({
			url:'/index.php?option=com_ksenmart',
			data:data,
			dataType:'json',
			async:false,
			success:function(response){
				if (response.status){
					if(step_import == 1){
						step_import++;
						jQuery('#progressupload .mesegges').append('<div class="row">Выгрузка категорий завершена</div>');
					} else {
						start = start + 100;
						jQuery('#progressupload .mesegges').append('<div class="row">Товаров выгружено: '+start+'</div>');
					}
					uploadYm();
				} else {
					jQuery('#progressupload .mesegges').append('<div class="row">Выгрузка завершена.</div>');
				}
			}
		});
	}
</script>