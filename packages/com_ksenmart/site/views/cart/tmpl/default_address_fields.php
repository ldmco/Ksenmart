<?php defined( '_JEXEC' ) or die;?>
<?php foreach($this->address_fields as $address_field){ ?>
<div class="control-group <?php echo $address_field->class; ?>">
	<label class="control-label"><?php echo JText::_(($address_field->system==1?'ksm_cart_shipping_field_':'').$address_field->title);?><?php echo $address_field->required?' *':''; ?></label>
	<div class="controls">
        <?php if ($address_field->type=='select'){ ?>
        <select class="selectbox" name="address_fields[<?php echo $address_field->id; ?>]"<?php echo $address_field->required?' required="true"':''; ?>>
			<?php foreach($address_field->values as $value){ ?>
			<option value="<?php echo $value->id;?>" <?php echo $address_field->value==$value->id?'selected':'';?>><?php echo $value->title;?></option>
			<?php } ?>
		</select>
		<?php }else{ ?>
		<input type="text" class="inputbox address_field" <?php echo ($address_field->system==1?'id="address_'.$address_field->title.'"':'');?> name="address_fields[<?php echo $address_field->system==1?$address_field->title:$address_field->id;?>]" value="<?php echo $address_field->value;?>"<?php echo $address_field->required?' required="true"':''; ?> />
		<?php } ?>
    </div>
</div>
<?php } ?>
<div class="control-group">
	<div class="controls">
        <a href="javascript:void(0);" class="link_b_border" id="mapselect"><?php echo JText::_('KSM_CART_ORDER_MAP_SELECT'); ?></a>
    </div>
</div>