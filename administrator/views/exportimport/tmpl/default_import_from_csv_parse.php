<?php	 		 		 	
defined( '_JEXEC' ) or die;
?>
<form method="post" class="form" enctype="multipart/form-data">
	<table class="cat" width="100%" cellspacing="0">	
		<thead>
			<tr>
				<th align="left" style="position:relative;">
					<?php echo JText::sprintf('ksm_exportimport_import_from_csv_step',2);?>
					<input type="submit" class="saves-green" value="<?php echo JText::_('ksm_upload')?>">
				</th>	
			</tr>
		<thead>
		<tbody>
		<tr>
			<td class="rightcol" style="background:#f9f9f9!important;padding-top:15px;">
				<div class="row">
					<label class="inputname" style="width:200px;"><?php echo JText::_('ksm_exportimport_product_name')?></label>
					<select class="sel" id="title" name="title">
						<?php echo $this->options?>
					</select>
				</div>
				<div class="row">
					<label class="inputname" style="width:200px;"><?php echo JText::_('ksm_exportimport_product_parent')?></label>
					<select class="sel" id="parent" name="parent">
						<?php echo $this->options?>
					</select>
				</div>				
				<div class="row">
					<label class="inputname" style="width:200px;"><?php echo JText::_('ksm_exportimport_product_category')?></label>
					<select class="sel" id="category" name="categories">
						<?php echo $this->options?>
					</select>
				</div>
				<div class="row">
					<label class="inputname" style="width:200px;"><?php echo JText::_('ksm_exportimport_product_childs_group')?></label>
					<select class="sel" id="childs_group" name="childs_group">
						<?php echo $this->options?>
					</select>
				</div>				
				<div class="row">
					<label class="inputname" style="width:200px;"><?php echo JText::_('ksm_exportimport_product_price')?></label>
					<select class="sel" id="price" name="price">
						<?php echo $this->options?>
					</select>
				</div>
				<div class="row">
					<label class="inputname" style="width:200px;"><?php echo JText::_('ksm_exportimport_product_promotion_price')?></label>
					<select class="sel" id="promotion_price" name="promotion_price">
						<?php echo $this->options?>
					</select>
				</div>				
				<div class="row">
					<label class="inputname" style="width:200px;"><?php echo JText::_('ksm_exportimport_product_currency')?></label>
					<select class="sel" id="price_type" name="price_type">
						<?php echo $this->options?>
					</select>
				</div>				
				<div class="row">
					<label class="inputname" style="width:200px;"><?php echo JText::_('ksm_exportimport_product_code')?></label>
					<select class="sel" id="product_code" name="product_code">
						<?php echo $this->options?>
					</select>
				</div>
				<div class="row">
					<label class="inputname" style="width:200px;"><?php echo JText::_('ksm_exportimport_product_unit')?></label>
					<select class="sel" id="product_unit" name="product_unit">
						<?php echo $this->options?>
					</select>
				</div>	
				<div class="row">
					<label class="inputname" style="width:200px;"><?php echo JText::_('ksm_exportimport_product_packaging')?></label>
					<select class="sel" id="product_packaging" name="product_packaging">
						<?php echo $this->options?>
					</select>
				</div>		
				<div class="row">
					<label class="inputname" style="width:200px;"><?php echo JText::_('ksm_exportimport_product_in_stock')?></label>
					<select class="sel" id="in_stock" name="in_stock">
						<?php echo $this->options?>
					</select>
				</div>
				<div class="row">
					<label class="inputname" style="width:200px;"><?php echo JText::_('ksm_exportimport_product_promotion')?></label>
					<select class="sel" id="promotion" name="promotion">
						<?php echo $this->options?>
					</select>
				</div>				
				<div class="row">
					<label class="inputname" style="width:200px;"><?php echo JText::_('ksm_exportimport_product_manufacturer')?></label>
					<select class="sel" id="manufacturer" name="manufacturer">
						<?php echo $this->options?>
					</select>
				</div>
				<div class="row">
					<label class="inputname" style="width:200px;"><?php echo JText::_('ksm_exportimport_product_country')?></label>
					<select class="sel" id="country" name="country">
						<?php echo $this->options?>
					</select>
				</div>				
				<div class="row">
					<label class="inputname" style="width:200px;"><?php echo JText::_('ksm_exportimport_product_description')?></label>
					<select class="sel" id="content" name="content">
						<?php echo $this->options?>
					</select>
				</div>	
				<div class="row">
					<label class="inputname" style="width:200px;"><?php echo JText::_('ksm_exportimport_product_relative')?></label>
					<select class="sel" id="relative" name="relative">
						<?php echo $this->options?>
					</select>
				</div>					
				<?php foreach($this->properties as $property):?>
				<div class="row">
					<label class="inputname" style="width:200px;"><?php echo $property->title?></label>
					<select class="sel" id="property_<?php echo $property->id?>" name="property_<?php echo $property->id?>">
						<?php echo $this->options?>
					</select>
				</div>					
				<?php endforeach;?>
				<div class="row">
					<label class="inputname" style="width:200px;"><?php echo JText::_('ksm_exportimport_product_photos')?></label>
					<select class="sel" id="photos" name="photos">
						<?php echo $this->options?>
					</select>
				</div>	
				<div class="row">
					<label class="inputname" style="width:200px;"><?php echo JText::_('ksm_exportimport_product_photos_zip')?></label>
					<input type="file" name="photos_zip">
				</div>					
				<div class="row">
					<label class="inputname" style="width:200px;"><?php echo JText::_('ksm_exportimport_product_uniq_field')?></label>
					<select class="sel" id="unic" name="unic">
						<option value="title"><?php echo JText::_('ksm_exportimport_product_name')?> </option>
						<option value="product_code"><?php echo JText::_('ksm_exportimport_product_code')?> </option>
					</select>
				</div>					
				<input type="hidden" name="encoding" value="<?php echo $this->state->get('encoding');?>">
			</td>
		</tr>
		</tbody>
	</table>
	<input type="hidden" name="layout" value="import_from_csv_result" />
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