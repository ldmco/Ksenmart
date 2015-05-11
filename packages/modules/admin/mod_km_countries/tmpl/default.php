<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<li>
<div class="km-list-left-module km-countries mod_km_countries">
	<div class="km-list-left-module-title">
		<label><?php echo JText::_('mod_km_countries_title')?></label>
		<a class="sh hides" href="#"></a>
		<a class="add km-modal" rel='{"x":"90%","y":"90%"}' href="<?php echo JRoute::_('index.php?option=com_ksenmart&view=countries&layout=country&tmpl=component');?>"></a>
	</div>	
	<div class="km-list-left-module-content">
		<div class="lists">
			<div class="row-fluid">	
				<ul>
					<?php if (count($countries)>0):?>
					<?php foreach($countries as $country):?>
					<li class="<?php echo ($country->selected?'active':'');?>">
						<label>
							<?php echo $country->title?>
							<input type="checkbox" value="<?php echo $country->id?>" name="countries[]" onclick="CountriesModule.setItem(this);" <?php echo ($country->selected?'checked':'')?>>
							<p class="actions">
								<a class="edit km-modal" rel='{"x":"90%","y":"90%"}' href="<?php echo JRoute::_('index.php?option=com_ksenmart&view=countries&layout=country&id='.$country->id.'&tmpl=component');?>"><?php echo JText::_('ksm_edit')?></a>
								<a class="delete" href="<?php echo JRoute::_('index.php?option=com_ksenmart&task=delete_module_item&model=countries&item=country&id='.$country->id.'&tmpl=ksenmart');?>"><?php echo JText::_('ksm_delete')?></a>
							</p>
						</label>
					</li>
					<?php endforeach;?>
					<?php else:?>
					<li>
						<label>
							<?php echo JText::_('mod_km_countries_no_items')?>
						</label>
					</li>					
					<?php endif;?>
				</ul>
				<input type="hidden" name="countries[]" value="">
			</div>
		</div>	
	</div>	
</div>
</li>				