<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

JHtml::_('behavior.tooltip');
?>
<form class="form" method="post">
	<div class="heading">
		<h3><?php echo $this->title;?></h3>
		<div class="save-close">
			<input type="submit" value="<?php echo JText::_('KS_SAVE'); ?>" class="save">
			<input type="button" class="close" onclick="parent.closePopupWindow();">
		</div>
	</div>
	<div class="edit" style="padding:30px 20px;">
		<div class="row">
			<label class="inputname"><?php echo JText::_('ks_add')?></label>
			<select name="value" class="sel" style="width:185px;">
				<?php foreach($this->seourlvalue->values as $value):?>
				<option value="<?php echo $value;?>"><?php echo JText::_('ks_'.$value);?></option>
				<?php endforeach;?>
			</select>
		</div>	
		<div class="row user_value_row" style="display:none;">
			<label class="inputname"><?php echo JText::_('ks_seo_seourlvalue_name')?></label>
			<input name="user_value" class="inputbox" style="width:200px;">
		</div>	
	</div>	
	<input type="hidden" name="section" value="<?php echo $this->seourlvalue->section?>">
</form>	