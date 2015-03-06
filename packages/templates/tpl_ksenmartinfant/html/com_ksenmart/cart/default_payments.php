<div class="kmcart-payments default_payments">
    <div class="step">
    	<legend><?php echo JText::_('KM_CART_CHANGE_PAYMENT_METHOD'); ?></legend>
        <?php if(count($this->payments) == 0){ ?>	
		<div class="control-group">
			<div class="controls no_payments">
				<label><?php echo JText::_('KSM_CART_NOPAYMENT_TEXT'); ?></label>
			</div>
		</div>
        <?php } ?>
        <div class="control-group payments">
            <div class="controls">		
			<?php foreach($this->payments as $payment){ ?>
			<div class="payment">
				<label class="radio clearfix">
					<input type="radio" name="payment_id" regions="<?php echo $payment->regions; ?>" value="<?php echo $payment->id; ?>" <?php echo (count($payment->id) == 1?'checked':''); ?> required="true" />
					<?php echo JText::_($payment->title); ?>
				</label>
			</div>
			<?php } ?>		
			</div>
		</div>
    </div>
</div>