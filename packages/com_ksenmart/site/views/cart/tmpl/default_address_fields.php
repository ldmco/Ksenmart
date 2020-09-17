<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>

<?php if (KSUsers::getUser()->id && $this->addresses): ?>
<div class="ksm-cart-order-step-row">
	<label class="ksm-cart-order-step-row-label"><?php echo JText::_('KSM_CART_ADDRESS_CHANGE_TITLE'); ?></label>
	<div class="ksm-cart-order-step-row-control">
		<select name="address_id">
			<option value="0" data-id="0" data-city="" data-zip="" data-street="" data-house="" data-entrance="" data-floor="" data-flat=""><?php echo JText::_('KSM_CART_ADDRESS_CHANGE_TITLE'); ?></option>
			<?php foreach($this->addresses as $address): ?>
				<option value="<?php echo $address->id; ?>" data-id="<?php echo $address->id; ?>" data-city="<?php echo $address->city; ?>" data-zip="<?php echo $address->zip; ?>" data-street="<?php echo $address->street; ?>" data-house="<?php echo $address->house; ?>" data-entrance="<?php echo $address->entrance; ?>" data-floor="<?php echo $address->floor; ?>" data-flat="<?php echo $address->flat; ?>" <?php echo $address->id==$this->selected_address ? 'selected' : '';?>><?php echo KSSystem::formatAddress($address); ?></option>
			<?php endforeach; ?>
		</select>
    </div>
</div>
<?php endif; ?>
<?php foreach($this->address_fields as $address_field): ?>
	<?php if ($address_field->default) continue; ?>
    <div class="ksm-cart-order-step-row <?php echo $address_field->class; ?>">
        <div class="ksm-cart-order-step-row-control">
            <?php if ($address_field->type=='select'): ?>
                <label class="ksm-cart-order-step-row-label"><?php echo JText::_(($address_field->system == 1 ? 'ksm_cart_shipping_field_' : '') . $address_field->title); ?><?php echo $address_field->required ? ' *' : ''; ?></label>
                <select name="address_fields[<?php echo $address_field->id; ?>]"<?php echo $address_field->required ? ' required="true"' : ''; ?>>
                    <?php foreach ($address_field->values as $value): ?>
                        <option value="<?php echo $value->id; ?>" <?php echo $address_field->value == $value->id ? 'selected' : ''; ?>><?php echo $value->title; ?></option>
                    <?php endforeach; ?>
                </select>
            <?php else: ?>
                <input type="text"
                       id="address_<?php echo $address_field->id; ?>"
                       class="address_field"
                    <?php echo($address_field->system == 1 ? 'id="customer_' . $address_field->title . '"' : ''); ?>
                    <?php echo($address_field->title == 'phone' ? 'minlength="11"' : ''); ?>
                       name="address_fields[<?php echo $address_field->system == 1 ? $address_field->title : $address_field->id; ?>]"
                       value="<?php echo $address_field->value; ?>" <?php echo $address_field->required ? ' required="true"' : ''; ?>
                       placeholder="<?php echo JText::_(($address_field->system == 1 ? 'ksm_cart_shipping_field_' : '') . $address_field->title); ?>"/>
                <label class="ksm-cart-order-step-row-error"></label>
            <?php endif; ?>
        </div>
    </div>
<?php endforeach; ?>