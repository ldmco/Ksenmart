<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<div class="row-fluid">
	<h5>Доступные способы доставки:</h5>
	<?php if(count($this->shippings) > 0) { ?>
		<?php foreach($this->shippings as $ship) { ?>
		<p>
			<?php if (!empty($ship->icon)):?>
			<span class="icon"><img src="<?php echo $ship->icon; ?>" width="20px" /></span>
			<?php endif;?>			
			<?php echo $ship->title ?>
			— <b><?php echo $ship->sum_val ?></b>
		</p>
		<? } ?>
	<?php } else { ?>
		<p>Нет способов для этого региона</p>
	<?php } ?>
</div>
<div class="row-fluid">
	<h5>Доступные способы оплаты:</h5>
	<?php if(count($this->payments) > 0) { ?>
		<?php foreach($this->payments as $pay) { ?>
		<p>
			<?php if (!empty($pay->icon)):?>
			<span class="icon"><img src="<?php echo $pay->icon; ?>" width="20px" /></span>
			<?php endif;?>			
			<?php echo $pay->title; ?>
		</p>
		<?php } ?>
	<?php } else { ?>
	<p>Нет способов для этого региона</p>
	<?php } ?>
</div>