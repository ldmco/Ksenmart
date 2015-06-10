<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<div class="top">
	<div class="drag">
		<div class="drop">
			<?php echo JText::_('ksm_reports_from_date')?>&nbsp;&nbsp;
			<input type="text" id="from_date" size="20" value="<?php echo $this->state->get('from_date')?>" class="inputbox" readonly>&nbsp;&nbsp;
			<?php echo JText::_('ksm_reports_to_date')?>&nbsp;&nbsp;
			<input type="text" id="to_date" size="20" value="<?php echo $this->state->get('to_date')?>" class="inputbox" readonly>		
		</div>
		<a class="ok"><?php echo JText::_('ksm_ok')?></a>
	</div>
</div>	
<div class="total_info" style="margin-top:-50px;margin-bottom:10px;float:left">
	<span class="grey-span"><b><?php echo JText::_('ksm_reports_orders_count_orders')?></b></span> - <?php echo $this->total?>
	<br>
	<span class="grey-span"><b><?php echo JText::_('ksm_reports_orders_total_price')?></b></span> - <?php echo KSMPrice::showPriceWithoutTransform($this->total_cost)?>
	<br>
</div>
<table class="cat" width="100%" cellspacing="0">	
	<thead>
		<tr>
			<th class="order_number"><span class="sort_field" rel="id">№</span></th>
			<th class="order_name stretch" align="left"><span class="sort_field" rel="name"><?php echo JText::_('ksm_reports_order_user_name')?></span></th>
			<th class="order_cost"><span class="sort_field" rel="cost"><?php echo JText::_('ksm_reports_order_cost')?></span></th>
			<th class="order_date"><span class="sort_field" rel="date_add"><?php echo JText::_('ksm_reports_order_date')?></span></th>
			<th class="order_status"><?php echo JText::_('ksm_status')?></th>
		</tr>
	</thead>	
	<tbody>
	<?php if (count($this->items)>0):?>
		<?php foreach($this->items as $item):?>
			<?php $this->item=&$item;?>
			<?php echo $this->loadTemplate('item_form');?>
		<?php endforeach;?>
	<?php else:?>	
		<?php echo $this->loadTemplate('no_items');?>
	<?php endif;?>
	</tbody>
</table>
<div class="pagi">
</div>
<script>
	jQuery('#from_date').datepicker();
	jQuery('#to_date').datepicker();
	
	var OrdersList=new KMList({
		'view':'reports',
		'object':'OrdersList',
		'limit':<?php echo $this->state->get('list.limit');?>,
		'limitstart':<?php echo $this->state->get('list.start');?>,
		'total':<?php echo $this->total;?>,
		'order_type':'<?php echo $this->state->get('order_type');?>',
		'order_dir':'<?php echo $this->state->get('order_dir');?>',
		'table':'orders',
		'sortable':false
	});	
	
	jQuery('.top .ok').on('click',function(){
		var from_date=jQuery('#from_date').val();
		var to_date=jQuery('#to_date').val();
		jQuery.ajax({
			url:'index.php?option=com_ksenmart&view=reports&layout=default_ordersreport&report=ordersReport&from_date='+from_date+'&to_date='+to_date+'&tmpl=ksenmart',
			success:function(html){
				jQuery('#reports_content').html(html);
			}
		});	
	});	
</script>