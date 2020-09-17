<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>

<?php foreach($this->customer_fields as $customer_field): ?>
    <?php if ($customer_field->default) continue; ?>
    <div class="ksm-cart-order-step-row <?php echo $customer_field->class; ?>">
        <div class="ksm-cart-order-step-row-control">
			<?php if ($customer_field->type == 'select'): ?>
                <label class="ksm-cart-order-step-row-label"><?php echo JText::_(($customer_field->system == 1 ? 'ksm_cart_shipping_field_' : '') . $customer_field->title); ?><?php echo $customer_field->required ? ' *' : ''; ?></label>
                <select name="customer_fields[<?php echo $customer_field->id; ?>]"<?php echo $customer_field->required ? ' required="true"' : ''; ?>>
					<?php foreach ($customer_field->values as $value): ?>
                        <option value="<?php echo $value->id; ?>" <?php echo $customer_field->value == $value->id ? 'selected' : ''; ?>><?php echo $value->title; ?></option>
					<?php endforeach; ?>
                </select>
			<?php else: ?>
                <input type="text"
                       id="customer_<?php echo $customer_field->id; ?>"
                       class="customer_field"
					<?php echo($customer_field->system == 1 ? 'id="customer_' . $customer_field->title . '"' : ''); ?>
					<?php echo($customer_field->title == 'phone' ? 'minlength="11"' : ''); ?>
                       name="customer_fields[<?php echo $customer_field->system == 1 ? $customer_field->title : $customer_field->id; ?>]"
                       value="<?php echo $customer_field->value; ?>" <?php echo $customer_field->required ? ' required="true"' : ''; ?>
                       placeholder="<?php echo JText::_(($customer_field->system == 1 ? 'ksm_cart_shipping_field_' : '') . $customer_field->title); ?>"/>
                <label class="ksm-cart-order-step-row-error"></label>
			<?php endif; ?>
        </div>
    </div>
<?php endforeach; ?>