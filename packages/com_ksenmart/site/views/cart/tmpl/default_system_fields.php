<?php
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>

<?php foreach ($this->system_fields as $key => $system_fields) : ?>
	<?php foreach ($system_fields as $field) : ?>
		<?php if ($key == 'address'): ?>
            <legend><?php echo JText::_('KSM_CART_HOW_DO_YOU'); ?></legend>
		<?php endif; ?>
        <div class="ksm-cart-order-step-row <?php echo $field->class; ?>">
            <div class="ksm-cart-order-step-row-control">
				<?php if ($field->type == 'select'): ?>
                    <label class="ksm-cart-order-step-row-label"><?php echo JText::_(($field->system == 1 ? 'ksm_cart_shipping_field_' : '') . $field->title); ?><?php echo $field->required ? ' *' : ''; ?></label>
                    <select name="<?php echo $key; ?>_fields[<?php echo $field->id; ?>]"<?php echo $field->required ? ' required="true"' : ''; ?>>
						<?php foreach ($field->values as $value): ?>
                            <option value="<?php echo $value->id; ?>" <?php echo $field->value == $value->id ? 'selected' : ''; ?>><?php echo $value->title; ?></option>
						<?php endforeach; ?>
                    </select>
				<?php else: ?>
                    <input type="text" <?php echo($field->system == 1 ? 'id="' . $key . '_' . $field->title . '"' : ''); ?>
						<?php echo($field->title == 'phone' ? 'minlength="11"' : ''); ?>
                           name="<?php echo $key; ?>_fields[<?php echo $field->system == 1 ? $field->title : $field->id; ?>]"
                           value="<?php echo $field->value; ?>" <?php echo $field->required ? ' required="true"' : ''; ?>
                           placeholder="<?php echo JText::_(($field->system == 1 ? 'ksm_cart_shipping_field_' : '') . $field->title); ?>"/>
                    <label class="ksm-cart-order-step-row-error"></label>
				<?php endif; ?>
            </div>
			<?php if ($key == 'address'): ?>
                <div class="ksm-cart-order-field-example">
                    <span><?php echo JText::_('KSM_CART_SHIPPING_FIELD_EXAMPLE'); ?>:</span>
					<?php echo JText::_('KSM_CART_SHIPPING_FIELD_ADDRESS_EXAMPLE'); ?>
                </div>
			<?php endif; ?>
        </div>
	<?php endforeach; ?>
<?php endforeach; ?>