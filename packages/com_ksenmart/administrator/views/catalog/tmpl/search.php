<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

JHTML::_('behavior.modal');
?>
<div class="form">
	<div class="heading">
		<h3>
			<?php echo $this->title; ?>
		</h3>
		<div class="save-close">
			<input type="button" value="<?php echo JText::_('ksm_add')?>" class="save" ng-click="add_related()">
			<input type="button" class="close" onclick="parent.closePopupWindow();" ng-click="cancel()">
		</div>
	</div>
	<div class="edit">
		<div id="center">
			<table id="cat" class="add-relative" width="100%">
				<tr>
					<td width="250" class="left-column">
						<div id="tree">
							<form id="list-filters">
								<ul>
									<?php echo KSSystem::loadModules('km-list-left')?>
								</ul>
							</form>			
						</div>	
					</td>
					<td valign="top">
						<div id="content">
							<?php echo $this->loadTemplate('items_list_top');?>
							<?php echo $this->loadTemplate('items_list');?>
						</div>	
					</td>	
				</tr>	
			</table>	
		</div>
	</div>	
</div>	
<script>
var ProductsList = new KMList({
	'view':'catalog',
	'item_tpl':'search_item_form',
	'no_items_tpl':'search_no_items',
	'object':'ProductsList',
	'limit':<?php echo $this->state->get('list.limit');?>,
	'limitstart':<?php echo $this->state->get('list.start');?>,
	'total':<?php echo $this->total;?>,
	'order_type':'<?php echo $this->state->get('order_type');?>',
	'order_dir':'<?php echo $this->state->get('order_dir');?>',
	'table':'products',
	'sortable':false,
	'layout':'search'
});
</script>