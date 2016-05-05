<?php
defined('_JEXEC') or die;
?>

<div class="ksm-profile-addresses">
	<?php foreach($view->addresses as $address): ?>
	<div class="ksm-profile-addresses-item">
		<span><?php echo $address->string; ?></span>
		<a class="ksm-profile-addresses-item-edit"></a>
		<a class="ksm-profile-addresses-item-del"></a>
		<div class="ksm-profile-addresses-item-form">
			<div class="ksm-profile-addresses-item-form-row">
				<label><?php echo JText::_('PLG_USER_KSENMART_ADDRESSES_ZIP_LBL'); ?></label>
				<input type="text" name="<?php echo $view->name; ?>[<?php echo $address->id; ?>][zip]" value="<?php echo $address->zip; ?>">			
			</div>		
			<div class="ksm-profile-addresses-item-form-row">
				<label><?php echo JText::_('PLG_USER_KSENMART_ADDRESSES_CITY_LBL'); ?></label>
				<input type="text" name="<?php echo $view->name; ?>[<?php echo $address->id; ?>][city]" value="<?php echo $address->city; ?>">			
			</div>		
			<div class="ksm-profile-addresses-item-form-row">
				<label><?php echo JText::_('PLG_USER_KSENMART_ADDRESSES_STREET_LBL'); ?></label>
				<input type="text" name="<?php echo $view->name; ?>[<?php echo $address->id; ?>][street]" value="<?php echo $address->street; ?>">		
			</div>
			<div class="ksm-profile-addresses-item-form-row">
				<label><?php echo JText::_('PLG_USER_KSENMART_ADDRESSES_HOUSE_LBL'); ?></label>
				<input type="text" name="<?php echo $view->name; ?>[<?php echo $address->id; ?>][house]" value="<?php echo $address->house; ?>">			
			</div>
			<div class="ksm-profile-addresses-item-form-row">
				<label><?php echo JText::_('PLG_USER_KSENMART_ADDRESSES_ENTRANCE_LBL'); ?></label>
				<input type="text" name="<?php echo $view->name; ?>[<?php echo $address->id; ?>][entrance]" value="<?php echo $address->entrance; ?>">		
			</div>
			<div class="ksm-profile-addresses-item-form-row">
				<label><?php echo JText::_('PLG_USER_KSENMART_ADDRESSES_FLOOR_LBL'); ?></label>
				<input type="text" name="<?php echo $view->name; ?>[<?php echo $address->id; ?>][floor]" value="<?php echo $address->floor; ?>">			
			</div>
			<div class="ksm-profile-addresses-item-form-row">
				<label><?php echo JText::_('PLG_USER_KSENMART_ADDRESSES_FLAT_LBL'); ?></label>
				<input type="text" name="<?php echo $view->name; ?>[<?php echo $address->id; ?>][flat]" value="<?php echo $address->flat; ?>">			
			</div>
			<div class="ksm-profile-addresses-item-form-row">
				<label><?php echo JText::_('PLG_USER_KSENMART_ADDRESSES_DEFAULT_LBL'); ?></label>
				<input type="checkbox" name="<?php echo $view->name; ?>[<?php echo $address->id; ?>][default]" value="1" <?php echo $address->default_checked; ?>>			
			</div>
		</div>
	</div>
	<?php endforeach; ?>
	<div class="ksm-profile-addresses-item ksm-profile-addresses-item-new">
		<span></span>
		<a class="ksm-profile-addresses-item-edit"></a>
		<a class="ksm-profile-addresses-item-del"></a>
		<div class="ksm-profile-addresses-item-form">
			<div class="ksm-profile-addresses-item-form-row">
				<label><?php echo JText::_('PLG_USER_KSENMART_ADDRESSES_ZIP_LBL'); ?></label>
				<input type="text" data-name="<?php echo $view->name; ?>[index][zip]" value="">			
			</div>		
			<div class="ksm-profile-addresses-item-form-row">
				<label><?php echo JText::_('PLG_USER_KSENMART_ADDRESSES_CITY_LBL'); ?></label>
				<input type="text" data-name="<?php echo $view->name; ?>[index][city]" value="">		
			</div>		
			<div class="ksm-profile-addresses-item-form-row">
				<label><?php echo JText::_('PLG_USER_KSENMART_ADDRESSES_STREET_LBL'); ?></label>
				<input type="text" data-name="<?php echo $view->name; ?>[index][street]" value="">			
			</div>
			<div class="ksm-profile-addresses-item-form-row">
				<label><?php echo JText::_('PLG_USER_KSENMART_ADDRESSES_HOUSE_LBL'); ?></label>
				<input type="text" data-name="<?php echo $view->name; ?>[index][house]" value="">			
			</div>
			<div class="ksm-profile-addresses-item-form-row">
				<label><?php echo JText::_('PLG_USER_KSENMART_ADDRESSES_ENTRANCE_LBL'); ?></label>
				<input type="text" data-name="<?php echo $view->name; ?>[index][entrance]" value="">		
			</div>
			<div class="ksm-profile-addresses-item-form-row">
				<label><?php echo JText::_('PLG_USER_KSENMART_ADDRESSES_FLOOR_LBL'); ?></label>
				<input type="text" data-name="<?php echo $view->name; ?>[index][floor]" value="">			
			</div>
			<div class="ksm-profile-addresses-item-form-row">
				<label><?php echo JText::_('PLG_USER_KSENMART_ADDRESSES_FLAT_LBL'); ?></label>
				<input type="text" data-name="<?php echo $view->name; ?>[index][flat]" value="">			
			</div>
			<div class="ksm-profile-addresses-item-form-row">
				<label><?php echo JText::_('PLG_USER_KSENMART_ADDRESSES_DEFAULT_LBL'); ?></label>
				<input type="checkbox" data-name="<?php echo $view->name; ?>[index][default]" value="1">		
			</div>
			<div class="ksm-profile-addresses-item-form-row">
				<button type="button"><?php echo JText::_('PLG_USER_KSENMART_ADDRESSES_ADD_LBL'); ?></button>			
			</div>		
		</div>
	</div>		
	<div class="ksm-profile-addresses-item-add">
		<button type="button"><?php echo JText::_('PLG_USER_KSENMART_ADDRESSES_ADD_NEW_LBL'); ?></button>
	</div>
</div>