<?php defined('_JEXEC') or die; ?>
<div class="set">
	<h3 class="headname"><?php echo JText::_('KSM_PAYMENT_ALGORITHM'); ?></h3>
	<div class="row">
		<label class="inputname"><?php echo JText::_('KSM_PAYMENT_WALLETONE_ID'); ?></label>
		<input type="text" style="width:250px;" class="inputbox" name="jform[params][merchant_id]" value="<?php echo $view['merchant_id']; ?>" />
	</div>
	<div class="row">
		<label class="inputname"><?php echo JText::_('KSM_PAYMENT_WALLETONE_SECRET_KEY'); ?></label>
		<input type="text" style="width:250px;" class="inputbox" name="jform[params][secretKey]" value="<?php echo $view['secretKey']; ?>" />
	</div>
	<div class="row">
		<label class="inputname" style="float: none;"><?php echo JText::_('KSM_PAYMENT_WALLETONE_PAYMENT_TYPES'); ?></label>
		<?php foreach ($view['paymentTypesList'] as $paymentGroupName => $paymentTypes): ?>
			<div class="paymentGroup">
				<div class="paymentGroupName"><b><?php echo JText::_($paymentGroupName); ?></b></div>
				<div class="paymentTypes">
					<?php foreach ($paymentTypes as $paymentType => $paymentName): ?>
						<label class="paymentType">
							<input type="checkbox" name="jform[params][payment_types][]" id="jform_published" value="<?php echo $paymentType; ?>" <?php echo array_key_exists($paymentType, $view['payment_types']) ? 'checked' : ''; ?>>
							<span><?php echo JText::_($paymentName); ?></span>
						</label>
					<?php endforeach; ?>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</div>
<style type="text/css">
	.paymentGroup {
		float: left;
		width: 25%;
		padding: 5px;
		-webkit-box-sizing: border-box;
		-moz-box-sizing: border-box;
		-o-box-sizing: border-box;
		box-sizing: border-box;
	}
</style>