<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<div class="top clearfix">
	<a class="adds km-modal" rel='{"x":"90%","y":"90%"}' href="<?php echo JRoute::_('index.php?option=com_ksenmart&view=orders&layout=order&tmpl=component');?>"><?php echo JText::_('ksm_orders_add_order')?></a>
	<a class="button delete-items"><?php echo JText::_('ksm_delete')?></a>
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