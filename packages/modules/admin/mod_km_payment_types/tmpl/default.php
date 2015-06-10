<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<li>
<div class="km-list-left-module km-payment-types mod_km_payment_types">
	<div class="km-list-left-module-title">
		<label><?php echo JText::_('mod_km_payment_types_title')?></label>
		<a class="sh hides" href="#"></a>
	</div>	
	<div class="km-list-left-module-content">
		<div class="lists">
			<div class="row-fluid">	
				<ul>
					<?php if (count($payment_types)>0):?>
					<?php foreach($payment_types as $payment_type):?>
					<li class="<?php echo ($payment_type->selected?'active':'');?>">
						<label>
							<?php echo JText::_($payment_type->name)?>
							<input type="checkbox" value="<?php echo $payment_type->element;?>" name="types[]" onclick="PaymentTypesModule.setItem(this);" <?php echo ($payment_type->selected?'checked':'')?>>
							<a class="add km-modal" rel='{"x":"90%","y":"90%"}' href="<?php echo JRoute::_('index.php?option=com_ksenmart&view=payments&layout=payment&type='.$payment_type->element.'&tmpl=component');?>"></a>
						</label>
					</li>
					<?php endforeach;?>
					<?php else:?>
					<li>
						<label>
							<?php echo JText::_('mod_km_payment_types_no_items')?>
						</label>
					</li>					
					<?php endif;?>
				</ul>
				<input type="hidden" name="types[]" value="">
			</div>
		</div>	
	</div>	
</div>
</li>				