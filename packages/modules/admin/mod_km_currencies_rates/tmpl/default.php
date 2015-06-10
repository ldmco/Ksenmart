<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<li>
<div class="km-list-left-module km-currencies-rates mod_km_currencies_rates">
	<div class="km-list-left-module-title">
		<label><?php echo JText::_('mod_km_currencies_rates_title')?></label>
		<a class="save"></a>
		<a class="sh hides" href="#"></a>
	</div>	
	<div class="km-list-left-module-content">
		<div class="lists">
			<div class="row-fluid">	
				<ul>
					<?php if (count($currencies)>0):?>
					<?php foreach($currencies as $currency):?>
					<li>
						<label>
							<?php echo KSMPrice::showPriceWithoutTransform(1);?> = <?php echo KSMPrice::showPriceWithoutTransform('<input type="text" name="items['.$currency->id.'][rate]" class="inputbox" value="'.$currency->rate.'">',$currency->id)?>
						</label>
						<input type="hidden" name="items[<?php echo $currency->id;?>][id]" value="<?php echo $currency->id;?>">
					</li>
					<?php endforeach;?>
					<?php else:?>
					<li>
						<label>
							<?php echo JText::_('mod_km_currencies_rates_no_items')?>
						</label>
					</li>					
					<?php endif;?>					
				</ul>
			</div>
		</div>	
	</div>	
</div>
</li>				