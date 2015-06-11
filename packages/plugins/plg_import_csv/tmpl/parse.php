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
					<label class="inputname" style="width:200px;"><?php echo JText::_('ksm_exportimport_import_csv_product_id')?></label>
					<select class="sel" id="id" name="id">
						<?php echo $view->options?>
					</select>
				</div>			
				<div class="row">
					<label class="inputname" style="width:200px;"><?php echo JText::_('ksm_exportimport_import_csv_product_name')?></label>
					<select class="sel" id="title" name="title">
						<?php echo $view->options?>
					</select>
				</div>
				<div class="row">
					<label class="inputname" style="width:200px;"><?php echo JText::_('ksm_exportimport_import_csv_product_parent')?></label>
					<select class="sel" id="parent" name="parent_id">
						<?php echo $view->options?>
					</select>
				</div>				
				<div class="row">
					<label class="inputname" style="width:200px;"><?php echo JText::_('ksm_exportimport_import_csv_product_category')?></label>
					<select class="sel" id="category" name="categories">
						<?php echo $view->options?>
					</select>
				</div>
				<div class="row">
					<label class="inputname" style="width:200px;"><?php echo JText::_('ksm_exportimport_import_csv_product_childs_group')?></label>
					<select class="sel" id="childs_group" name="childs_group">
						<?php echo $view->options?>
					</select>
				</div>				
				<div class="row">
					<label class="inputname" style="width:200px;"><?php echo JText::_('ksm_exportimport_import_csv_product_price')?></label>
					<select class="sel" id="price" name="price">
						<?php echo $view->options?>
					</select>
				</div>
				<div class="row">
					<label class="inputname" style="width:200px;"><?php echo JText::_('ksm_exportimport_import_csv_product_promotion_price')?></label>
					<select class="sel" id="promotion_price" name="promotion_price">
						<?php echo $view->options?>
					</select>
				</div>				
				<div class="row">
					<label class="inputname" style="width:200px;"><?php echo JText::_('ksm_exportimport_import_csv_product_currency')?></label>
					<select class="sel" id="price_type" name="price_type">
						<?php echo $view->options?>
					</select>
				</div>				
				<div class="row">
					<label class="inputname" style="width:200px;"><?php echo JText::_('ksm_exportimport_import_csv_product_code')?></label>
					<select class="sel" id="product_code" name="product_code">
						<?php echo $view->options?>
					</select>
				</div>
				<div class="row">
					<label class="inputname" style="width:200px;"><?php echo JText::_('ksm_exportimport_import_csv_product_unit')?></label>
					<select class="sel" id="product_unit" name="product_unit">
						<?php echo $view->options?>
					</select>
				</div>	
				<div class="row">
					<label class="inputname" style="width:200px;"><?php echo JText::_('ksm_exportimport_import_csv_product_packaging')?></label>
					<select class="sel" id="product_packaging" name="product_packaging">
						<?php echo $view->options?>
					</select>
				</div>		
				<div class="row">
					<label class="inputname" style="width:200px;"><?php echo JText::_('ksm_exportimport_import_csv_product_in_stock')?></label>
					<select class="sel" id="in_stock" name="in_stock">
						<?php echo $view->options?>
					</select>
				</div>
				<div class="row">
					<label class="inputname" style="width:200px;"><?php echo JText::_('ksm_exportimport_import_csv_product_promotion')?></label>
					<select class="sel" id="promotion" name="promotion">
						<?php echo $view->options?>
					</select>
				</div>		
				<div class="row">
					<label class="inputname" style="width:200px;"><?php echo JText::_('ksm_exportimport_import_csv_product_recommendation')?></label>
					<select class="sel" id="recommendation" name="recommendation">
						<?php echo $view->options?>
					</select>
				</div>
				<div class="row">
					<label class="inputname" style="width:200px;"><?php echo JText::_('ksm_exportimport_import_csv_product_hot')?></label>
					<select class="sel" id="hot" name="hot">
						<?php echo $view->options?>
					</select>
				</div>
				<div class="row">
					<label class="inputname" style="width:200px;"><?php echo JText::_('ksm_exportimport_import_csv_product_new')?></label>
					<select class="sel" id="new" name="new">
						<?php echo $view->options?>
					</select>
				</div>				
				<div class="row">
					<label class="inputname" style="width:200px;"><?php echo JText::_('ksm_exportimport_import_csv_product_manufacturer')?></label>
					<select class="sel" id="manufacturer" name="manufacturer">
						<?php echo $view->options?>
					</select>
				</div>
				<div class="row">
					<label class="inputname" style="width:200px;"><?php echo JText::_('ksm_exportimport_import_csv_product_country')?></label>
					<select class="sel" id="country" name="country">
						<?php echo $view->options?>
					</select>
				</div>			
				<div class="row">
					<label class="inputname" style="width:200px;"><?php echo JText::_('ksm_exportimport_import_csv_product_minidescription')?></label>
					<select class="sel" id="introcontent" name="introcontent">
						<?php echo $view->options?>
					</select>
				</div>					
				<div class="row">
					<label class="inputname" style="width:200px;"><?php echo JText::_('ksm_exportimport_import_csv_product_description')?></label>
					<select class="sel" id="content" name="content">
						<?php echo $view->options?>
					</select>
				</div>	
				<div class="row">
					<label class="inputname" style="width:200px;"><?php echo JText::_('ksm_exportimport_import_csv_product_relative')?></label>
					<select class="sel" id="relative" name="relative">
						<?php echo $view->options?>
					</select>
				</div>		
				<div class="row">
					<label class="inputname" style="width:200px;"><?php echo JText::_('ksm_exportimport_import_csv_product_set')?></label>
					<select class="sel" id="set" name="set">
						<?php echo $view->options?>
					</select>
				</div>					
				<div class="row">
					<label class="inputname" style="width:200px;"><?php echo JText::_('ksm_exportimport_import_csv_product_tags')?></label>
					<select class="sel" id="tags" name="tags">
						<?php echo $view->options?>
					</select>
				</div>				
				<?php foreach($view->properties as $property):?>
				<div class="row">
					<label class="inputname" style="width:200px;"><?php echo $property->title?></label>
					<select class="sel" id="property_<?php echo $property->id?>" name="property_<?php echo $property->id?>">
						<?php echo $view->options?>
					</select>
				</div>					
				<?php endforeach;?>
				<div class="row">
					<label class="inputname" style="width:200px;"><?php echo JText::_('ksm_exportimport_import_csv_product_photos')?></label>
					<select class="sel" id="photos" name="photos">
						<?php echo $view->options?>
					</select>
				</div>	
				<div class="row">
					<label class="inputname" style="width:200px;"><?php echo JText::_('ksm_exportimport_import_csv_product_photos_zip')?></label>
					<input type="file" name="photos_zip">
					<br clear="both">
					<small><?php echo JText::sprintf('ksm_exportimport_import_csv_product_photos_info', $view->max_filesize, $view->upload_dir)?></small>
				</div>					
				<div class="row">
					<label class="inputname" style="width:200px;"><?php echo JText::_('ksm_exportimport_import_csv_product_uniq_field')?></label>
					<select class="sel" id="unic" name="unic">
						<option value="product_code"><?php echo JText::_('ksm_exportimport_import_csv_product_code')?> </option>
						<option value="title"><?php echo JText::_('ksm_exportimport_import_csv_product_name')?> </option>
						<option value="id"><?php echo JText::_('ksm_exportimport_import_csv_product_id')?> </option>
					</select>
				</div>					
				<input type="hidden" name="encoding" value="<?php echo $view->encoding;?>">
			</td>
		</tr>
		</tbody>
	</table>
	<input type="hidden" name="type" value="import_csv" />
	<input type="hidden" name="step" value="import" />
</form>	
<script>
	jQuery('.cat .row').each(function(){
		var label=jQuery(this).find('label').text();
		jQuery(this).find('option').each(function(){
			if (jQuery(this).text()==label)
				jQuery(this).attr('selected','selected');
		});
	});
</script>