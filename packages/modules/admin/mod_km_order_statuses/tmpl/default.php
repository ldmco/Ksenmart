<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<li>
<div class="km-list-left-module km-order-statuses mod_km_order_statuses">
	<div class="km-list-left-module-title">
		<label><?php echo JText::_('mod_km_order_statuses_title')?></label>
		<a class="sh hides" href="#"></a>
		<a class="add km-modal" rel='{"x":"500","y":"150"}' href="<?php echo JRoute::_('index.php?option=com_ksenmart&view=orders&layout=orderstatus&tmpl=component');?>"></a>
	</div>	
	<div class="km-list-left-module-content">
		<div class="lists">
			<div class="row-fluid">	
				<ul>
					<?php foreach($statuses as $status):?>
					<li class="<?php echo ($status->selected?'active':'');?>">
						<label>
							<?php echo $status->title?>
							<input type="checkbox" value="<?php echo $status->id?>" name="statuses[]" onclick="OrderStatusesModule.setItem(this);" <?php echo ($status->selected?'checked':'')?>>
							<?php if (!$status->system):?>
							<p class="actions">
								<a class="edit km-modal" rel='{"x":"500","y":"150"}' href="<?php echo JRoute::_('index.php?option=com_ksenmart&view=orders&layout=orderstatus&id='.$status->id.'&tmpl=component');?>"><?php echo JText::_('ksm_edit')?></a>
								<a class="delete" href="<?php echo JRoute::_('index.php?option=com_ksenmart&task=delete_module_item&model=orders&item=orderstatus&id='.$status->id.'&tmpl=ksenmart');?>"><?php echo JText::_('ksm_delete')?></a>
							</p>
							<?php endif;?>
						</label>
					</li>
					<?php endforeach;?>
				</ul>
				<input type="hidden" name="statuses[]" value="">
			</div>
		</div>	
	</div>	
</div>
</li>				