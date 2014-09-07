<div class="deliv-info">
	<h3>Доставка и оплата</h3>
	<div class="row-fluid">
		<h5>Вы находитесь в регионе:</h5>
		<select class="input-medium" id="region" style="width:180px;">
			<option value="0">Выбрать регион</option>
			<?php foreach($regions as $region) { ?>
			     <option value="<?php echo $region->id; ?>" <?php echo ($region->id == $user_region ? 'selected' : ''); ?>><?php echo $region->title; ?></option>
			<?php } ?>
		</select>
	</div>
	<div class="deliv-payment-info">
		<div class="row-fluid">
			<h5>Доступные способы доставки:</h5>
            <?php if(count($shippings) > 0) { ?>
                <?php foreach($shippings as $ship) {
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
			<?php if(count($payments) > 0) { ?>
                <?php foreach($payments as $pay) { ?>
				<p><?php echo $pay->title; ?></p>
				<?php } ?>
            <?php } else { ?>
			<p>Нет способов для этого региона</p>
			<?php } ?>
		</div>
	</div>
</div>