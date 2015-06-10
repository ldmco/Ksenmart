<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<div class="row">
	<?php echo $this->form->getLabel('status_id'); ?>
	<?php echo $this->form->getInput('status_id'); ?>
</div>	
<div class="row">
	<?php echo $this->form->getLabel('region_id'); ?>
	<?php echo $this->form->getInput('region_id'); ?>
</div>	
<div class="row">
	<?php echo $this->form->getLabel('shipping_id'); ?>
	<div class="shipping_id clearfix" style="width:100%;">
		<?php echo $this->form->getInput('shipping_id'); ?>
	</div>
</div>
<div class="row">
	<?php echo $this->form->getLabel('payment_id'); ?>
	<div class="payment_id">
		<?php echo $this->form->getInput('payment_id'); ?>
	</div>
</div>