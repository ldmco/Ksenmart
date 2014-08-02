	<div class="row-fluid">
		<h5>Доступные способы доставки:</h5>
        <?php if(count($this->shippings) > 0) { ?>
            <?php foreach($this->shippings as $ship) {
                $cost           = 0;
                //include (JPATH_ROOT . '/administrator/components/com_ksenmart/helpers/shipping/' . $ship->type . '.php');
            ?>
        	<p><?php echo $ship->title ?><?php echo ($cost != 0 ? ' — ' . KSMPrice::showPriceWithoutTransform($cost) : ''); ?> (доставка не позднее <?php echo KSMShipping::getShippingDate($ship->id); ?>)</p>
        	<? } ?>
        <?php } else { ?>
        	<p>Нет способов для этого региона</p>
        <?php } ?>
	</div>
	<div class="row-fluid">
		<h5>Доступные способы оплаты:</h5>
		<?php if(count($this->payments) > 0) { ?>
            <?php foreach($this->payments as $pay) { ?>
			<p><?php echo $pay->title; ?></p>
			<?php } ?>
        <?php } else { ?>
		<p>Нет способов для этого региона</p>
		<?php } ?>
	</div>