<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<div class="set">
	<h3 class="headname"><?php echo JText::_('KSM_PAYMENT_ALGORITHM'); ?></h3>
	<div class="row alert">
		<?php echo JText::_('KSM_PAYMENT_WALLETONE_PROMO_LINK'); ?><a target="_blank" href="http://www.walletone.com/merchant/?promo=PZUUSgfesY5HqJ">http://www.walletone.com/merchant/?promo=PZUUSgfesY5HqJ</a>
	</div>
	<div class="row">
		<label class="inputname"><?php echo JText::_('KSM_PAYMENT_WALLETONE_ID'); ?></label>
		<input type="text" style="width:250px;" class="inputbox" name="jform[params][merchant_id]" value="<?php echo $view['merchant_id']; ?>" />
	</div>
	<div class="row">
		<label class="inputname"><?php echo JText::_('KSM_PAYMENT_WALLETONE_SECRET_KEY'); ?></label>
		<input type="text" style="width:250px;" class="inputbox" name="jform[params][secretKey]" value="<?php echo $view['secretKey']; ?>" />
	</div>
	<div class="row">
		<div class="ksm-slidemodule-payment-types slide_module active">
			<div class="module-head">
				<label><?php echo JText::_('KSM_PAYMENT_WALLETONE_PAYMENT_TYPES'); ?></label>
				<a class="show_module_content" onclick="shSlideModuleContent(this);return false;"></a>
			</div>
			<div class="module-content no-favorites">
				<div class="lists">
					<div class="row">
						<div class="row payment-types">
							<ul>
								<?php foreach ($view['paymentTypesList'] as $paymentGroupName => $paymentTypes): ?>
									<?php foreach ($paymentTypes as $paymentType => $paymentName): ?>
										<?php if (array_key_exists($paymentType, $view['payment_types'])): ?>
										<li data-paymenttype="<?php echo $paymentType; ?>">
											<span><?php echo JText::_($paymentName); ?></span>
											<i></i>
											<input type="hidden" name="jform[params][payment_types][]" value="<?php echo $paymentType; ?>">
										</li>
										<?php endif; ?>
									<?php endforeach; ?>	
								<?php endforeach; ?>								
								<li class="no-payment-types" style="<?php echo (count($view['payment_types']) ? 'display:none;' : ''); ?>"><span><?php echo JText::_('KSM_PAYMENT_WALLETONE_NO_PAYMENT_TYPES'); ?></span></li>	
							</ul>
						</div>
						<div class="row">
							<a href="#" id="add-payment-type" class="add"><?php echo JText::_('KS_ADD'); ?></a>
						</div>
						<div id="popup-window5" class="popup-window" style="display: none;">
							<div style="width: 460px;height: 260px;margin-left: -230px;margin-top: -130px;">
								<div class="popup-window-inner">
									<div class="heading">
										<h3><?php echo JText::_('KSM_PAYMENT_WALLETONE_PAYMENT_TYPES'); ?></h3>
										<div class="save-close"><button class="close" onclick="return false;"></button></div>
									</div>
									<div class="contents">	
										<div class="contents-inner">	
											<div class="slide_module">
												<?php foreach ($view['paymentTypesList'] as $paymentGroupName => $paymentTypes): ?>
												<div class="row">
													<h3><?php echo JText::_($paymentGroupName); ?></h3>
													<ul>
														<?php foreach ($paymentTypes as $paymentType => $paymentName): ?>
														<li data-paymenttype="<?php echo $paymentType; ?>" class="<?php echo array_key_exists($paymentType, $view['payment_types']) ? 'active' : ''; ?>"><span><?php echo JText::_($paymentName); ?></span></li>
														<?php endforeach; ?>
													</ul>	
												</div>	
												<?php endforeach; ?>
											</div>	
										</div>	
									</div>	
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>	
	</div>
</div>
<style type="text/css">
.ksm-slidemodule-payment-types ul li i {
	position: absolute;
	top: 50%;
	margin: -20px 0 0;
	right: -4px;
	width: 40px;
	height: 40px;
	background: url(<?php echo JURI::root(); ?>plugins/system/ksencore/core/assets/css/i/close.png) center center no-repeat;
	cursor: pointer;
}
#popup-window5 ul li {
	cursor:pointer;
}
</style>
<script>
jQuery('#add-payment-type').on('click', function() {
	jQuery('#popup-window5').fadeIn(400);
});

jQuery('#popup-window5 li').click(function(){
	var paymentType = jQuery(this).data().paymenttype;
	
	if (jQuery(this).is('.active'))
	{
		removePaymentType(paymentType);
	}
	else
	{
		addPaymentType(paymentType);
	}
});

jQuery('body').on('click', '.ksm-slidemodule-payment-types .payment-types li i', function(){
	var paymentType = jQuery(this).parents('li').data().paymenttype;
	removePaymentType(paymentType);
});

function addPaymentType(paymentType)
{
	var html='';
	var title=jQuery('#popup-window5 li[data-paymenttype='+paymentType+'] span').text();
	
	html += '<li data-paymenttype='+paymentType+'>';
	html += '		<span>'+title+'</span>';
	html += '		<i></i>';
	html += '		<input type="hidden" name="jform[params][payment_types][]" value="'+paymentType+'">';
	html += '</li>';
	jQuery('#popup-window5 li[data-paymenttype='+paymentType+']').addClass('active');
	jQuery('.ksm-slidemodule-payment-types .no-payment-types').hide();
	jQuery('.ksm-slidemodule-payment-types .payment-types ul').append(html);
}

function removePaymentType(paymentType)
{
	jQuery('.ksm-slidemodule-payment-types .payment-types li[data-paymenttype='+paymentType+']').remove();
	if (jQuery('.ksm-slidemodule-payment-types .payment-types li').length==1)
	{
		jQuery('.ksm-slidemodule-payment-types .no-payment-types').show();
	}
	jQuery('#popup-window5 li[data-paymenttype='+paymentType+']').removeClass('active');
}
</script>