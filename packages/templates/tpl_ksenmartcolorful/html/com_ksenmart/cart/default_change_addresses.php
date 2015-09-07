<table class="table table-hover select_address">
	<tbody>
        <?php foreach($this->addresses as $address){ ?>
		<tr data-id="<?php echo $address->id; ?>" data-city="<?php echo $address->city; ?>" data-street="<?php echo $address->street; ?>" data-house="<?php echo $address->house; ?>" data-floor="<?php echo $address->floor; ?>" data-flat="<?php echo $address->flat; ?>">
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