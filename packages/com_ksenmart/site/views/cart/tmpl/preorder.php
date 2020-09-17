<?php
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>
<div class="ksm-preorder ksm-block">
	<h2><?php echo JText::_('KSM_ORDER_FORM_YOUR_DATA'); ?></h2>
	<form method="post">
		<div class="ksm-preorder-message">
			<?php echo $this->message; ?>
		</div>
		<?php foreach ($this->customer_fields as $customer_field): ?>
			<div class="ksm-preorder-info-row <?php echo $customer_field->class; ?>">
				<label
					class="ksm-preorder-info-row-label"><?php echo JText::_(($customer_field->system == 1 ? 'ksm_cart_shipping_field_' : '') . $customer_field->title); ?><?php echo $customer_field->required ? ' *' : ''; ?></label>
				<div class="ksm-preorder-info-row-control">
					<?php if ($customer_field->type == 'select'): ?>
						<select
							name="customer_fields[<?php echo $customer_field->id; ?>]"<?php echo $customer_field->required ? ' required="true"' : ''; ?>>
							<?php foreach ($customer_field->values as $value): ?>
								<option
									value="<?php echo $value->id; ?>" <?php echo $customer_field->value == $value->id ? 'selected' : ''; ?>><?php echo $value->title; ?></option>
							<?php endforeach; ?>
						</select>
					<?php else: ?>
						<input
							type="text" <?php echo($customer_field->system == 1 ? 'id="customer_' . $customer_field->title . '"' : ''); ?>
							name="customer_fields[<?php echo $customer_field->system == 1 ? $customer_field->title : $customer_field->id; ?>]"
							value="<?php echo $customer_field->value; ?>"<?php echo $customer_field->required ? ' required="true"' : ''; ?> />
					<?php endif; ?>
				</div>
			</div>
		<?php endforeach; ?>
		<div class="ksm-preorder-info-row ksm-preorder-buttons">
			<button type="submit"><?php echo JText::_('KSM_ORDER_FORM_BUY'); ?></button>
			<a class="ksm-preorder-to-catalog"><?php echo JText::_('KSM_ORDER_FORM_TO_CATALOG'); ?></a>
		</div>
		<input type="hidden" name="id" value="<?php echo $this->product_id; ?>"/>
		<input type="hidden" name="count" value="<?php echo $this->product_count; ?>"/>
		<?php foreach ($this->product_properties as $key => $val): ?>
			<input type="hidden" name="<?php echo $key; ?>" value="<?php echo $val; ?>"/>
		<?php endforeach; ?>
		<input type="hidden" name="task" value="cart.create_order"/>
	</form>
</div>	