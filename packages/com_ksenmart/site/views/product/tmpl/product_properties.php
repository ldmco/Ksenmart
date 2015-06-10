<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>

<?php foreach($this->product->properties as $property) { ?>
    <?php if(!empty($property->values) && $property->type != 'none'){ ?>
        <?php if($property->type == 'text'){ ?>
			<div class="control-group row">
				<label class="control-label"><?php echo $property->title ?>:</label>
				<div class="controls">
                    <?php foreach ($property->values as $value){ ?>
                        <span><?php echo $value->title; ?></span>
                    <?php } ?>
				</div>
			</div>
        <?php }elseif($property->type == 'select'){ ?>
            <?php if($property->view == 'select'){ ?>
    			<div class="control-group row">
    				<label class="control-label"><?php echo $property->title ?>:</label>
    				<div class="controls">
    					<select class="sel" data-prop_id="<?php echo $property->property_id; ?>" name="property_<?php echo $this->product->id."_".$property->property_id; ?>" required="true">
    						<option value=""><?php echo JText::_('KSM_PRODUCT_PROPERTY_CHOOSE'); ?></option>
                            <?php foreach ($property->values as $value){ ?>
    						<option value="<?php echo $value->id; ?>"><?php echo $property->prefix; ?><?php echo $value->title; ?><?php echo $property->suffix; ?></option>
    						<?php } ?>
    					</select>
    				</div>
    			</div>
            <?php }elseif($property->view == 'radio' || $property->view == 'checkbox'){ ?>
    			<div class="control-group row">
    				<label class="control-label"><?php echo $property->title ?>:</label>
    				<div class="controls">
                        <?php $i=0;foreach($property->values as $value){ ?>
                        <label class="<?php echo $property->view; ?>">
                            <input type="<?php echo $property->view; ?>" data-prop_id="<?php echo $property->property_id; ?>" name="property_<?php echo $this->product->id; ?>_<?php echo $property->property_id; ?>" value="<?php echo $value->id; ?>" />
                            <a href="<?php echo JRoute::_('index.php?option=com_ksenmart&view=catalog&properties[]='.$value->id); ?>"><?php echo $property->prefix; ?><?php echo $value->title; ?><?php echo $property->suffix; ?></a>
                        </label>
                        <?php $i++;} ?>
    				</div>
    			</div>          
            <?php }elseif($property->view == 'text_inline'){ ?>
    			<div class="control-group row">
    				<label class="control-label"><?php echo $property->title ?>:</label>
    				<div class="controls">
						<?php $texts = array(); ?>
                        <?php foreach($property->values as $value){ ?>
							<?php $texts[] = '<a href="'.JRoute::_('index.php?option=com_ksenmart&view=catalog&properties[]='.$value->id).'">'.$value->title.'</a>'; ?>
						<?php } ?>
                        <span>
                            <?php echo $property->prefix; ?><?php echo implode(',', $texts); ?><?php echo $property->suffix; ?>
                        </span>
    				</div>
    			</div>  	
            <?php }elseif($property->view == 'text_row'){ ?>
    			<div class="control-group row">
    				<label class="control-label"><?php echo $property->title ?>:</label>
    				<div class="controls">
						<ul style="margin-top:5px;">
                        <?php foreach($property->values as $value){ ?>
                        <li>
                           <a href="<?php echo JRoute::_('index.php?option=com_ksenmart&view=catalog&properties[]='.$value->id); ?>"><?php echo $property->prefix; ?><?php echo $value->title; ?><?php echo $property->suffix; ?></a>
                        </li>		
						<?php } ?>
						</ul>
					</div>
    			</div>  				
            <?php } ?>
        <?php } ?>
    <?php } ?>
<?php } ?>