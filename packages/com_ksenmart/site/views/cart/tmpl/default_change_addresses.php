<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<table class="table table-hover select_address">
	<tbody>
        <?php foreach($this->addresses as $address){ ?>
		<tr data-id="<?php echo $address->id; ?>" data-city="<?php echo $address->city; ?>" data-zip="<?php echo $address->zip; ?>" data-street="<?php echo $address->street; ?>" data-house="<?php echo $address->house; ?>" data-entrance="<?php echo $address->entrance; ?>" data-floor="<?php echo $address->floor; ?>" data-flat="<?php echo $address->flat; ?>">
			<td>
				<input type="radio" value="<?php echo $address->id; ?>" name="address_id" id="address_label_<?php echo $address->id; ?>" <?php echo $address->id==$this->selected_address?'checked="true"':''; ?> />
			</td>
			<td>
                <label for="address_label_<?php echo $address->id; ?>"><?php echo KSSystem::formatAddress($address); ?></label>
            </td>
		</tr>
        <?php } ?>
	</tbody>
</table>