<?php
defined( '_JEXEC' ) or die;
JHtml::_('behavior.tooltip');
?>
<form class="form" method="post">
	<div class="heading">
		<h3><?php echo $this->title;?></h3>
		<div class="save-close">
			<input type="button" value="<?php echo JText::_('ksm_save')?>" class="save">
			<input type="button" class="close" onclick="parent.closePopupWindow();">
		</div>
	</div>
	<div class="edit" style="padding:30px 20px;">
		<div class="row">
			<label class="inputname"><?php echo JText::_('ksm_add')?></label>
			<select name="value" class="sel">
				<?php foreach($this->seotitlevalue->values as $value):?>
				<option value="<?php echo $value;?>"><?php echo JText::_('ksm_'.$value);?></option>
				<?php endforeach;?>
			</select>
		</div>	
		<div class="row user_value_row" style="display:none;">
			<label class="inputname"><?php echo JText::_('ksm_seo_seotitlevalue_name')?></label>
			<input name="user_value" class="inputbox" style="width:200px;">
		</div>	
		<div class="row property_row" style="display:none;">
			<label class="inputname"><?php echo JText::_('ksm_seo_seotitlevalue_property')?></label>
			<select name="property" class="sel" style="width:100px;">
				<?php foreach($this->seotitlevalue->properties as $property):?>
				<option value="<?php echo $property->id;?>::<?php echo $property->title;?>"><?php echo $property->title;?></option>
				<?php endforeach;?>
			</select>
		</div>		
	</div>	
	<input type="hidden" name="section" value="<?php echo $this->seotitlevalue->section?>">
</form>	