<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<p class="lead add_address"><span>+</span> <a href="javascript:void(0);" class="link_b_border"><?php echo JText::_('KSM_PROFILE_ADDRESSES_ADD_NEW_ADDRESS'); ?></a></p>
<div class="items">
	<div class="new_address noTransition" style="display: none;">
		<form method="post" class="form-horizontal" action="#tab3">
			<div class="control-group">
				<label class="control-label require" for="city"><?php echo JText::_('KSM_PROFILE_ADDRESSES_CITY'); ?></label>
				<div class="controls">
					<input type="text" class="inputbox" name="city" id="city" required="true" />
				</div>
			</div>
			<div class="control-group">
				<label class="control-label require" for="zip"><?php echo JText::_('KSM_PROFILE_ADDRESSES_ZIP'); ?></label>
				<div class="controls">
					<input type="text" class="inputbox" name="zip" id="zip" />
				</div>
			</div>			
			<div class="control-group">
				<label class="control-label require" for="street"><?php echo JText::_('KSM_PROFILE_ADDRESSES_STREET'); ?></label>
				<div class="controls">
					<input type="text" class="inputbox" name="street" id="street" required="true" />
				</div>
			</div>
			<div class="control-group">
				<label class="control-label require" for="house"><?php echo JText::_('KSM_PROFILE_ADDRESSES_HOUSE'); ?></label>
				<div class="controls">
					<input type="text" class="inputbox" name="house" id="house" required="true" />
				</div>
			</div>
			<div class="control-group">
				<label class="control-label require" for="entrance"><?php echo JText::_('KSM_PROFILE_ADDRESSES_ENTRANCE'); ?></label>
				<div class="controls">
					<input type="text" class="inputbox" name="entrance" id="entrance" />
				</div>
			</div>			
			<div class="control-group">
				<label class="control-label require" for="floor"><?php echo JText::_('KSM_PROFILE_ADDRESSES_FLOOR'); ?></label>
				<div class="controls">
					<input type="text" class="inputbox" name="floor" id="floor" />
				</div>
			</div>
			<div class="control-group">
				<label class="control-label require" for="inputApartment"><?php echo JText::_('KSM_PROFILE_ADDRESSES_FLAT'); ?></label>
				<div class="controls">
					<input type="text" class="inputbox" name="flat" id="inputApartment" />
				</div>
			</div>
			<div class="control-group">
				<div class="controls top">
					<label class="checkbox custom">
						<input type="checkbox" name="default" id="new_default" value="1" /> <?php echo JText::_('KSM_PROFILE_ADDRESSES_USE_AS_DEFAULT'); ?>
					</label>
					<input type="submit" class="btn btn-success" value="<?php echo JText::_('KSM_PROFILE_ADDRESSES_ADD'); ?>" />
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
                					<input type="text" class="inputbox" name="city" id="city" required="true" value="<?php echo $address->city; ?>" />
                				</div>
                			</div>
                			<div class="control-group">
                				<label class="control-label require" for="zip"><?php echo JText::_('KSM_PROFILE_ADDRESSES_ZIP'); ?></label>
                				<div class="controls">
                					<input type="text" class="inputbox" name="zip" id="zip" value="<?php echo $address->zip; ?>" />
                				</div>
                			</div>							
                			<div class="control-group">
                				<label class="control-label require" for="street"><?php echo JText::_('KSM_PROFILE_ADDRESSES_STREET'); ?></label>
                				<div class="controls">
                					<input type="text" class="inputbox" name="street" id="street" required="true" value="<?php echo $address->street; ?>" />
                				</div>
                			</div>
                			<div class="control-group">
                				<label class="control-label require" for="house"><?php echo JText::_('KSM_PROFILE_ADDRESSES_HOUSE'); ?></label>
                				<div class="controls">
                					<input type="text" class="inputbox" name="house" id="house" required="true" value="<?php echo $address->house; ?>" />
                				</div>
                			</div>
                			<div class="control-group">
                				<label class="control-label require" for="entrance"><?php echo JText::_('KSM_PROFILE_ADDRESSES_ENTRANCE'); ?></label>
                				<div class="controls">
                					<input type="text" class="inputbox" name="entrance" id="entrance" value="<?php echo $address->entrance; ?>" />
                				</div>
                			</div>							
                			<div class="control-group">
                				<label class="control-label require" for="floor"><?php echo JText::_('KSM_PROFILE_ADDRESSES_FLOOR'); ?></label>
                				<div class="controls">
                					<input type="text" class="inputbox" name="floor" id="floor" value="<?php echo $address->floor; ?>" />
                				</div>
                			</div>
                			<div class="control-group">
                				<label class="control-label require" for="inputApartment"><?php echo JText::_('KSM_PROFILE_ADDRESSES_FLAT'); ?></label>
                				<div class="controls">
                					<input type="text" class="inputbox" name="flat" id="inputApartment" value="<?php echo $address->flat; ?>" />
                				</div>
                			</div>
                			<div class="control-group">
                				<div class="controls top">
                					<label class="checkbox custom">
                						<input type="checkbox" name="default" id="new_default" value="1"<?php echo ($address->default==1?' checked':'')?> /> <?php echo JText::_('KSM_PROFILE_ADDRESSES_USE_AS_DEFAULT'); ?>
                					</label>
                					<input type="submit" class="btn btn-success" value="<?php echo JText::_('KSM_PROFILE_ADDRESSES_EDIT'); ?>" />
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