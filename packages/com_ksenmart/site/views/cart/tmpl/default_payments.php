<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
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
    				<label class="radio clearfix checkbox">
						<?php if (!empty($payment->icon)):?>
                        <span class="icon"><img src="<?php echo $payment->icon; ?>" width="120px" /></span>
						<?php endif;?>
    					<input type="radio" name="payment_id" value="<?php echo $payment->id;?>" required="true" onclick="KMCartChangePayment(this);" <?php echo ($payment->selected?'checked':''); ?> /> <?php echo JText::_($payment->title); ?>
    				</label>
    			</div>
    		<?php } ?>
            </div>
        </div>		
    </div>
</div>