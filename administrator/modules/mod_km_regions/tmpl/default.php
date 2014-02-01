<div class="ksenmart-payment_types">
	<div class="ksenmart-payment_types-title">
		<label><?php echo JText::_('KSM_ADD_PAYMENT'); ?></label>
		<a class="sh hides" href="javascript:void(0);" onclick="shModuleContent(this,'.ksenmart-payment_types-content');return false;"></a>
	</div>	
	<div class="ksenmart-payment_types-content">
		<ul>
			<?php foreach($payment_types as $payment_type){ ?>
			<li payment_type_name="<?php echo $payment_type->name; ?>" payment_type_id="<?php echo $payment_type->id; ?>">
				<label>
					<?php echo JText::_('KSM_'.$payment_type->name); ?>
					<a class="add_link"></a>				
				</label>
			</li>
			<?php } ?>
		</ul>
	</div>	
</div>	