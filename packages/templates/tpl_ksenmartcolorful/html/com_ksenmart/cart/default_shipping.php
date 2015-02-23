<?php defined('_JEXEC') or die; ?>
<div class="kmcart-shipping default_shipping">
	<div class="step">
		<legend>Выберите способ доставки</legend>
        <?php echo $this->loadTemplate('regions'); ?>
        <?php echo $this->loadTemplate('shipping_methods'); ?>        
	</div>
	<?php if(count($this->customer_fields) > 0){ ?>
	<div class="step">
		<legend>Введите ваши данные</legend>
        <?php echo $this->loadTemplate('customer_fields'); ?>
	</div>	
	<?php } ?>
	<?php if($this->address_fields){ ?>
	<div class="step row-fluid address_fields_b">
        <div class="span6">
            <legend><?php echo JText::_('KSM_CART_ADDRESS_FIELDS_TITLE'); ?></legend>
            <?php echo $this->loadTemplate('address_fields'); ?>
        </div>
        <?php if(KSUsers::getUser()->id && $this->addresses){ ?>
        <div class="span6">
            <legend><?php echo JText::_('KSM_CART_ADDRESS_CHANGE_TITLE'); ?></legend>
            <?php echo $this->loadTemplate('change_addresses'); ?>
        </div>
        <?php } ?>
	</div>	
	<?php } ?>	
	<input type="hidden" id="shipping_coords" name="shipping_coords" value="<?php echo $this->state->get('shipping_coords'); ?>" />
</div>