<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<div class="default_shipping_methods">
    <?php if(!$this->shippings){ ?>	
    <div class="control-group">
    	<div class="controls no_shippings">
    		<label><?php echo JText::_('KSM_CART_NOSHIPING_TEXT'); ?></label>
    	</div>
    </div>
    <?php }else{ ?>
    <div class="control-group shippings">
        <div class="controls">
    	<?php foreach($this->shippings as $shipping){ ?>
    		<div class="shipping">
    			<label class="radio clearfix checkbox">
    				<?php if (!empty($shipping->icon)):?>
                    <span class="icon"><img src="<?php echo $shipping->icon; ?>" width="40px" /></span>
    				<?php endif;?>
    				<input type="radio" name="shipping_id" value="<?php echo $shipping->id;?>" required="true" onclick="KMCartChangeShipping(this);" <?php echo ($shipping->selected?'checked':''); ?> /> <?php echo JText::_($shipping->title); ?>
    			</label>
    		</div>
    	<?php } ?>
        </div>
    </div>
    <?php } ?>
</div>