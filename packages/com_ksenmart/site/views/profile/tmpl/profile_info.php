<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<script>
    var avatar_full = '<?php echo $this->user->logo_original; ?>';
</script>
<form method="post" class="form-horizontal profile_info" action="#tab2">
	<div class="control-group">
		<label class="control-label require" for="inputName"><?php echo JText::_('KSM_PROFILE_INFO_NAME'); ?></label>
		<div class="controls">
			<input type="text" name="form[first_name]" id="inputName" class="inputbox" value="<?php echo $this->user->first_name; ?>" required="true" />
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="inputName"><?php echo JText::_('KSM_PROFILE_INFO_LASTNAME'); ?></label>
		<div class="controls">
			<input type="text" name="form[last_name]" id="inputName" class="inputbox" value="<?php echo $this->user->last_name; ?>" />
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="inputName"><?php echo JText::_('KSM_PROFILE_INFO_MIDDLENAME'); ?></label>
		<div class="controls">
			<input type="text" name="form[middle_name]" id="inputName" class="inputbox" value="<?php echo $this->user->middle_name; ?>" />
		</div>
	</div>	
	<div class="control-group">
		<label class="control-label require" for="inputEmail"><?php echo JText::_('KSM_PROFILE_INFO_EMAIL'); ?></label>
		<div class="controls">
			<input type="text" name="form[email]" id="inputEmail" class="inputbox" value="<?php echo $this->user->email; ?>" required="true" />
		</div>
	</div>
	<div class="control-group">
		<label class="control-label require" for="inputPhone"><?php echo JText::_('KSM_PROFILE_INFO_PHONE'); ?></label>
		<div class="controls">
            <div class="input-append">
                <input type="text" id="customer_phone" name="form[phone]" value="<?php echo $this->user->phone; ?>" required="true" />
                <span class="add-on">
                    <input type="hidden" id="phone_mask" checked="true" />
                    <label id="descr" for="phone_mask"><?php echo JText::_('KSM_PROFILE_INFO_TYPE_PHONE'); ?></label>
                </span>
            </div>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label require"><?php echo JText::_('KSM_PROFILE_INFO_REGION'); ?></label>
		<div class="controls">
			<select class="sel" id="order_region" name="form[region]" style="width:180px;">
				<option value="0"><?php echo JText::_('KSM_PROFILE_INFO_CHOOSE_REGION'); ?></option>
				<?php foreach($this->regions as $region) { ?>
				<option value="<?php echo $region->id; ?>" <?php echo ($region->id == $this->user->region_id?'selected':''); ?>><?php echo $region->title; ?></option>
				<?php } ?>
			</select>
		</div>
	</div>	
	<?php foreach($this->fields as $key => $field){ ?>
	<div class="control-group">
		<label class="control-label require" for="inputEmail"><?php echo $field->title; ?></label>
		<div class="controls">
			<input type="text" name="field[<?php echo $field->id; ?>]" class="inputbox" value="<?php echo $this->user->{'field_'.$field->id}->value; ?>" placeholder="<?php echo $field->title; ?>" />
		</div>
	</div>		
	<?php } ?>
	<div class="control-group">
		<label class="control-label require"><?php echo JText::_('KSM_PROFILE_INFO_AVATAR'); ?></label>
		<div class="controls">
            <img src="<?php echo $this->user->logo_thumb; ?>" alt="<?php echo $this->user->name; ?>" class="border_ksen" />
            <a href="#avatar_edit" role="button" class="link_b_border" data-toggle="modal"><?php echo JText::_('KSM_PROFILE_INFO_CHANGE_AVATAR'); ?></a>
             
            <div id="avatar_edit" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 id="myModalLabel"><?php echo JText::_('KSM_PROFILE_INFO_CHANGING_AVATAR'); ?></h3>
                </div>
                <div class="modal-body">
                    <div class="avatar_preview">
                        <img src="<?php echo $this->user->logo_original; ?>" class="target" alt="<?php echo $this->user->name; ?>" />
                    </div>
                    <div class="preview-pane">
                        <div class="preview-container border_ksen">
                            <img src="<?php echo $this->user->logo_original; ?>" class="jcrop-preview" alt="<?php echo $this->user->name; ?>" />
                        </div>
                    </div>
                    
                    <input type="hidden" name="x1" value="0" />
                    <input type="hidden" name="y1" value="0" />
                    <input type="hidden" name="h" value="0" />
                    <input type="hidden" name="w" value="0" />
                    
                    <input type="hidden" name="boundx" value="0" />
                    <input type="hidden" name="boundy" value="0" />
                </div>
                <div class="modal-footer">
                    <input type="file" name="filename" class="inputbox pull-left" accept="image/jpeg,image/png,image/gif" />
                    <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><?php echo JText::_('KSM_PROFILE_INFO_AVATAR_CLOSE'); ?></button>
                    <button type="submit" class="btn btn-success avatar_edit"><?php echo JText::_('KSM_PROFILE_INFO_AVATAR_SAVE'); ?></button>
                </div>
            </div>
		</div>
	</div>	
	<div class="control-group">
		<div class="controls custom">
			<button type="submit" class="button btn btn-success"><?php echo JText::_('KSM_PROFILE_INFO_EDIT'); ?></button>
		</div>
	</div>		
	<input type="hidden" id="ymaphtml" name="ymaphtml" value="" />
	<input type="hidden" name="deliverycost" id="deliverycost" value="" />	
	<input type="hidden" name="form[id]" value="<?php echo $this->user->id; ?>" />
	<input type="hidden" name="task" value="profile.save_user" />
</form>