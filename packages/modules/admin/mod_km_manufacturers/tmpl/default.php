<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<li>
<div class="km-list-left-module km-manufacturers mod_km_manufacturers">
	<div class="km-list-left-module-title">
		<label><?php echo JText::_('mod_km_manufacturers_title')?></label>
		<a class="sh hides" href="#"></a>
		<a class="add km-modal" rel='{"x":"90%","y":"90%"}' href="<?php echo JRoute::_('index.php?option=com_ksenmart&view=catalog&layout=manufacturer&tmpl=component');?>"></a>
	</div>	
	<div class="km-list-left-module-content">
		<div class="lists">
			<div class="row-fluid">	
				<ul>
					<?php if (count($manufacturers)>0):?>
					<?php foreach($manufacturers as $manufacturer):?>
					<li class="<?php echo ($manufacturer->selected?'active':'');?>">
						<label>
							<?php echo $manufacturer->title?>
							<input type="checkbox" value="<?php echo $manufacturer->id?>" name="manufacturers[]" onclick="ManufacturersModule.setItem(this);" <?php echo ($manufacturer->selected?'checked':'')?>>
							<p class="actions">
								<a class="edit km-modal" rel='{"x":"90%","y":"90%"}' href="<?php echo JRoute::_('index.php?option=com_ksenmart&view=catalog&layout=manufacturer&id='.$manufacturer->id.'&tmpl=component');?>"><?php echo JText::_('ksm_edit')?></a>
								<a class="delete" href="<?php echo JRoute::_('index.php?option=com_ksenmart&task=delete_module_item&model=catalog&item=manufacturer&id='.$manufacturer->id.'&tmpl=ksenmart');?>"><?php echo JText::_('ksm_delete')?></a>
							</p>
						</label>
					</li>
					<?php endforeach;?>
					<?php else:?>
					<li>
						<label>
							<?php echo JText::_('mod_km_manufacturers_no_items')?>
						</label>
					</li>					
					<?php endif;?>
				</ul>
				<input type="hidden" name="manufacturers[]" value="">
			</div>
		</div>	
	</div>	
</div>
</li>				