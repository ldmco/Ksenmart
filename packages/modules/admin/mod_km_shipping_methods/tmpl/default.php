<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<li>
<div class="km-list-left-module km-shipping-methods mod_km_shipping_methods">
	<div class="km-list-left-module-title">
		<label><?php echo JText::_('mod_km_shipping_methods_title')?></label>
		<a class="sh hides" href="#"></a>
	</div>	
	<div class="km-list-left-module-content">
		<div class="lists">
			<div class="row-fluid">	
				<ul>
					<?php if (count($methods)>0):?>
					<?php foreach($methods as $method):?>
					<li class="<?php echo ($method->selected?'active':'');?>">
						<label>
							<?php echo JText::_($method->name)?>
							<input type="checkbox" value="<?php echo $method->element;?>" name="methods[]" onclick="ShippingMethodsModule.setItem(this);" <?php echo ($method->selected?'checked':'')?>>
							<a class="add km-modal" rel='{"x":"90%","y":"90%"}' href="<?php echo JRoute::_('index.php?option=com_ksenmart&view=shippings&layout=shipping&type='.$method->element.'&tmpl=component');?>"></a>
						</label>
					</li>
					<?php endforeach;?>
					<?php else:?>
					<li>
						<label>
							<?php echo JText::_('mod_km_shipping_methods_no_items')?>
						</label>
					</li>					
					<?php endif;?>
				</ul>
				<input type="hidden" name="methods[]" value="">
			</div>
		</div>	
	</div>	
</div>
</li>				