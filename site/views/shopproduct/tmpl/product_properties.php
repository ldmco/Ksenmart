<?php defined('_JEXEC') or die; ?>
<?php foreach($this->product->properties as $property) { ?>
    <?php if(!empty($property->values)){ ?>
        <?php if($property->type == 'text'){ ?>
			<div class="control-group">
				<label class="control-label"><?php echo $property->title ?>:</label>
				<div class="controls">
                    <?php foreach ($property->values as $value){ ?>
                        <span><?php echo $value->title; ?></span>
                    <?php } ?>
				</div>
			</div>
        <?php }elseif($property->type == 'select'){ ?>
            <?php if($property->view == 'select'){ ?>
    			<div class="control-group">
    				<label class="control-label"><?php echo $property->title ?>:</label>
    				<div class="controls">
    					<select class="sel" data-prop_id="<?php echo $property->property_id; ?>" name="property_<?php echo $this->product->id."_".$property->property_id; ?>" required="true">
    						<option value="">Выберите</option>
                            <?php foreach ($property->values as $value){ ?>
    						<option value="<?php echo $value->id; ?>"><?php echo $property->prefix; ?><?php echo $value->title; ?><?php echo $property->suffix; ?></option>
    						<?php } ?>
    					</select>
    				</div>
    			</div>
            <?php }elseif($property->view == 'radio' || $property->view == 'checkbox'){ ?>
    			<div class="control-group">
    				<label class="control-label"><?php echo $property->title ?>:</label>
    				<div class="controls">
                        <?php $i=0;foreach($property->values as $value){ ?>
                        <label class="<?php echo $property->view; ?>">
                            <input type="<?php echo $property->view; ?>" data-prop_id="<?php echo $property->property_id; ?>" name="property_<?php echo $this->product->id; ?>_<?php echo $property->property_id; ?>" value="<?php echo $value->id; ?>"<?php echo $i==0?' required="true"':''; ?> />
                            <?php echo $property->prefix; ?><?php echo $value->title; ?><?php echo $property->suffix; ?>
                        </label>
                        <?php $i++;} ?>
    				</div>
    			</div>                
            <?php } ?>
        <?php } ?>
    <?php } ?>
<?php } ?>