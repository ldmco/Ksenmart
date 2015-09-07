<div class="deliv-info">
	<h3>Доставка и оплата</h3>
	<div class="row-fluid">
		<h5>Вы находитесь в регионе:</h5>
		<select class="input-medium" id="shipping_region" style="width:180px;">
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
                <?php foreach($shippings as $ship) {?>
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
			<?php if(count($payments) > 0) { ?>
                <?php foreach($payments as $pay) { ?>
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
	</div>
</div>