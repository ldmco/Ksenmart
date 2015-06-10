<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<li>
<div class="km-list-left-module km-user-commentrates mod_km_commentrates">
	<div class="km-list-left-module-title">
		<label><?php echo JText::_('mod_km_commentrates_title')?></label>
		<a class="sh hides" href="#"></a>
		<a class="add km-modal" rel='{"x":"500","y":"150"}' href="<?php echo JRoute::_('index.php?option=com_ksenmart&view=comments&layout=rate&tmpl=component');?>"></a>
	</div>	
	<div class="km-list-left-module-content">
		<div class="lists">
			<div class="row-fluid">
				<ul>
					<?php if (count($rates)>0):?>
					<?php foreach($rates as $rate):?>
					<li>
						<label>
							<?php echo $rate->title?>
							<input type="checkbox" value="<?php echo $rate->id?>" name="rates[]">
							<p class="actions">
								<a class="edit km-modal" rel='{"x":"500","y":"150"}' href="<?php echo JRoute::_('index.php?option=com_ksenmart&view=comments&layout=rate&id='.$rate->id.'&tmpl=component');?>"><?php echo JText::_('ksm_edit')?></a>
								<a class="delete" href="<?php echo JRoute::_('index.php?option=com_ksenmart&task=delete_module_item&model=comments&item=rate&id='.$rate->id.'&tmpl=ksenmart');?>"><?php echo JText::_('ksm_delete')?></a>
							</p>
						</label>
					</li>
					<?php endforeach;?>
					<?php else:?>
					<li>
						<label>
							<?php echo JText::_('mod_km_commentrates_no_items')?>
						</label>
					</li>					
					<?php endif;?>					
				</ul>
			</div>
		</div>	
	</div>	
</div>
</li>				