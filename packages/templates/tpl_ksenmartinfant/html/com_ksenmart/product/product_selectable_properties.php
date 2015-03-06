<? defined('_JEXEC') or die();

foreach($this->product->properties as $property) {
    switch($property->type) {
        case 'checkbox':
            if(isset($property->values[0]) && $property->values[0]->title == 1) { ?>
			<p>
				<span><?php echo $property->title ?>:</span><input type="checkbox" name="property_<?php echo $this->product->id ?>_<?php echo $property->id ?>" value="1" />
			</p>	
			<? }
            break;
        case 'select':
            if(count($property->values) > 1) { ?>
				<div class="control-group">
					<label class="control-label"><?php echo $property->title ?>:</label>
					<div class="controls">
						<select class="sel" id="property_<?php echo $this->product->id."_".$property->id; ?>" name="property_<?php echo $this->product->id."_".$property->id; ?>" required="true">
							<? foreach ($property->values as $value){ ?>
							<option value="<?php echo $value->value_id; ?>"><?php echo $value->title ?></option>
							<?php } ?>
						</select>
					</div>
				</div>
				<?php echo $property->finishing; ?>									
			<? }
            break;
        case 'radio':
            if(count($property->values) > 1){ ?>
			<p class="row">
				<span><?php echo $property->title; ?>:<span class="err"><?php echo JText::_('KSM_PRODUCT_SELECTABLE_FETURES_SELECT_VALUE'); ?></span></span>
				<? foreach ($property->values as $value) { ?>
				<div class="custom">
                    <input type="radio" name="property_<?php echo $this->product->id; ?>_<?php echo $property->id; ?>" value="<?php echo $value->value_id; ?>" />
                    <label><?php echo $value->title; ?><?php echo $property->finishing; ?></label>
                </div>
				<? } ?>
			</p>								
			<? }
            break;
    }
}