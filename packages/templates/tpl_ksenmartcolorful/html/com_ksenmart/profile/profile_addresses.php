<?php defined('_JEXEC') or die(); ?>
<div class="row-fluid">
	<p class="lead add_address pull-right"><span>+</span> <a href="javascript:void(0);" class="link_b_border"><?php echo JText::_('KSM_PROFILE_ADDRESSES_ADD_NEW_ADDRESS'); ?></a></p>
</div>
<div class="items row-fluid">
	<div class="new_address noTransition" style="display: none;">
		<form method="post" class="form-horizontal" action="#tab3">
			<div class="control-group">
				<label class="control-label require" for="city"><?php echo JText::_('KSM_PROFILE_ADDRESSES_CITY'); ?></label>
				<div class="controls">
					<input type="text" class="inputbox" name="city" id="city" placeholder="<?php echo JText::_('KSM_PROFILE_ADDRESSES_CITY'); ?>" required="true" />
				</div>
			</div>
			<div class="control-group">
				<label class="control-label require" for="street"><?php echo JText::_('KSM_PROFILE_ADDRESSES_STREET'); ?></label>
				<div class="controls">
					<input type="text" class="inputbox" name="street" id="street" placeholder="<?php echo JText::_('KSM_PROFILE_ADDRESSES_STREET'); ?>" required="true" />
				</div>
			</div>
			<div class="control-group">
				<label class="control-label require" for="house"><?php echo JText::_('KSM_PROFILE_ADDRESSES_HOUSE'); ?></label>
				<div class="controls">
					<input type="text" class="inputbox" name="house" id="house" placeholder="<?php echo JText::_('KSM_PROFILE_ADDRESSES_HOUSE'); ?>" required="true" />
				</div>
			</div>
			<div class="control-group">
				<label class="control-label require" for="floor"><?php echo JText::_('KSM_PROFILE_ADDRESSES_FLOOR'); ?></label>
				<div class="controls">
					<input type="text" class="inputbox" name="floor" placeholder="<?php echo JText::_('KSM_PROFILE_ADDRESSES_FLOOR'); ?>" id="floor" />
				</div>
			</div>
			<div class="control-group">
				<label class="control-label require" for="inputApartment"><?php echo JText::_('KSM_PROFILE_ADDRESSES_FLAT'); ?></label>
				<div class="controls">
					<input type="text" class="inputbox" name="flat" placeholder="<?php echo JText::_('KSM_PROFILE_ADDRESSES_FLAT'); ?>" id="inputApartment" />
				</div>
			</div>
			<div class="control-group">
				<div class="controls top">
					<label class="checkbox custom">
						<input type="checkbox" name="default" id="new_default" value="1" /> <?php echo JText::_('KSM_PROFILE_ADDRESSES_USE_AS_DEFAULT'); ?>
					</label>
					<button type="submit" class="button btn btn-success"><?php echo JText::_('KSM_PROFILE_ADDRESSES_ADD'); ?></button>
				</div>
			</div>
			<input type="hidden" name="coords" id="new_coords" value="" />
			<input type="hidden" name="task" value="profile.add_address" />
		</form>
        <hr />
	</div>
	<?php if(count($this->addresses) > 0){ ?>
	<table class="table table-hover adresses">
		<thead>
			<tr>
				<th></th>
				<th><?php echo JText::_('KSM_PROFILE_ADDRESSES_ADDRESS'); ?></th>
				<th><?php echo JText::_('KSM_PROFILE_ADDRESSES_DEFAULT'); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($this->addresses as $address){ ?>
			<tr class="expnd" data-tr="<?php echo $address->id; ?>">
				<td>
					<a href="<?php echo JRoute::_('index.php?option=com_ksenmart&task=profile.del_address&id='.$address->id.'#tab3'); ?>"><i class="icon-remove-circle"></i></a>
				</td>
				<td><?php echo KSSystem::formatAddress($address); ?></td>
				<td>
					<label class="radio address">
						<input type="radio" name="default_address" data-address_id="<?php echo $address->id; ?>" id="default_<?php echo $address->id; ?>" value="1"<?php echo ($address->default==1?' checked':'')?> />
						<?php echo JText::_('KSM_PROFILE_ADDRESSES_USE_AS_DEFAULT'); ?>
					</label>
					<input type="hidden" id="address_<?php echo $address->id; ?>" value="<?php echo KSSystem::formatAddress($address); ?>" />
					<input type="hidden" id="coords_<?php echo $address->id; ?>" value="<?php echo $address->coords; ?>" />
				</td>
			</tr>
            <tr style="display: none;" class="edit_address" data-exp-tr="<?php echo $address->id; ?>">
                <td colspan="3">
                	<div class="noTransition">
                		<form method="post" class="form-horizontal" action="#tab3">
                			<div class="control-group">
                				<label class="control-label require" for="city"><?php echo JText::_('KSM_PROFILE_ADDRESSES_CITY'); ?></label>
                				<div class="controls">
                					<input type="text" class="inputbox" name="city" id="city" placeholder="<?php echo JText::_('KSM_PROFILE_ADDRESSES_CITY'); ?>" required="true" value="<?php echo $address->city; ?>" />
                				</div>
                			</div>
                			<div class="control-group">
                				<label class="control-label require" for="street"><?php echo JText::_('KSM_PROFILE_ADDRESSES_STREET'); ?></label>
                				<div class="controls">
                					<input type="text" class="inputbox" name="street" id="street" placeholder="<?php echo JText::_('KSM_PROFILE_ADDRESSES_STREET'); ?>" required="true" value="<?php echo $address->street; ?>" />
                				</div>
                			</div>
                			<div class="control-group">
                				<label class="control-label require" for="house"><?php echo JText::_('KSM_PROFILE_ADDRESSES_HOUSE'); ?></label>
                				<div class="controls">
                					<input type="text" class="inputbox" name="house" id="house" placeholder="<?php echo JText::_('KSM_PROFILE_ADDRESSES_HOUSE'); ?>" required="true" value="<?php echo $address->house; ?>" />
                				</div>
                			</div>
                			<div class="control-group">
                				<label class="control-label require" for="floor"><?php echo JText::_('KSM_PROFILE_ADDRESSES_FLOOR'); ?></label>
                				<div class="controls">
                					<input type="text" class="inputbox" name="floor" placeholder="<?php echo JText::_('KSM_PROFILE_ADDRESSES_FLOOR'); ?>" id="floor" value="<?php echo $address->floor; ?>" />
                				</div>
                			</div>
                			<div class="control-group">
                				<label class="control-label require" for="inputApartment"><?php echo JText::_('KSM_PROFILE_ADDRESSES_FLAT'); ?></label>
                				<div class="controls">
                					<input type="text" class="inputbox" name="flat" placeholder="<?php echo JText::_('KSM_PROFILE_ADDRESSES_FLAT'); ?>" id="inputApartment" value="<?php echo $address->flat; ?>" />
                				</div>
                			</div>
                			<div class="control-group">
                				<div class="controls top">
                					<label class="checkbox custom">
                						<input type="checkbox" name="default" id="new_default" value="1"<?php echo ($address->default==1?' checked':'')?> /> <?php echo JText::_('KSM_PROFILE_ADDRESSES_USE_AS_DEFAULT'); ?>
                					</label>
                					<button type="submit" class="button btn btn-success"><?php echo JText::_('KSM_PROFILE_ADDRESSES_EDIT'); ?></button>
                				</div>
                			</div>
                            <input type="hidden" name="address_id" value="<?php echo $address->id; ?>" />
                			<input type="hidden" name="task" value="profile.edit_address" />
                		</form>
                	</div>
                </td>
            </tr>
			<? } ?>
		</tbody>
	</table>
	<?php }else{ ?>
	<h2 class="text-center"><?php echo JText::_('KSM_PROFILE_ADDRESSES_NO_ADDRESSES'); ?></h2>
	<?php } ?>
</div>