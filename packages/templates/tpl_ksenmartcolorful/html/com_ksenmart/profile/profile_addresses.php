<?php defined('_JEXEC') or die(); ?>
<div class="row-fluid">
	<p class="lead add_address pull-right"><span>+</span> <a href="javascript:void(0);" class="link_b_border">Добавить новый адрес</a></p>
</div>
<div class="items row-fluid">
	<div class="new_address noTransition" style="display: none;">
		<form method="post" class="form-horizontal" action="#tab3">
			<div class="control-group">
				<label class="control-label require" for="city">Адрес</label>
				<div class="controls">
					<input type="text" class="inputbox" name="city" id="city" placeholder="Город" required="true" />
				</div>
			</div>
			<div class="control-group">
				<label class="control-label require" for="street">Улица</label>
				<div class="controls">
					<input type="text" class="inputbox" name="street" id="street" placeholder="Улица" required="true" />
				</div>
			</div>
			<div class="control-group">
				<label class="control-label require" for="house">Дом</label>
				<div class="controls">
					<input type="text" class="inputbox" name="house" id="house" placeholder="Дом" required="true" />
				</div>
			</div>
			<div class="control-group">
				<label class="control-label require" for="floor">Этаж</label>
				<div class="controls">
					<input type="text" class="inputbox" name="floor" placeholder="Этаж" id="floor" />
				</div>
			</div>
			<div class="control-group">
				<label class="control-label require" for="inputApartment">Квартира</label>
				<div class="controls">
					<input type="text" class="inputbox" name="flat" placeholder="Квартира" id="inputApartment" />
				</div>
			</div>
			<div class="control-group">
				<div class="controls top">
					<label class="checkbox custom">
						<input type="checkbox" name="default" id="new_default" value="1" /> Использовать по умолчанию
					</label>
					<button type="submit" class="button btn btn-success">Добавить</button>
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
				<th>Адрес</th>
				<th>По умолчанию</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($this->addresses as $address){ ?>
			<tr class="expnd" data-tr="<?php echo $address->id; ?>">
				<td>
					<a href="<?php echo JRoute::_('index.php?option=com_ksenmart&task=profile.del_address&id='.$address->id.'#tab3'); ?>" title="Удалить адрес"><i class="icon-remove-circle"></i></a>
				</td>
				<td><?php echo KSSystem::formatAddress($address); ?></td>
				<td>
					<label class="radio address">
						<input type="radio" name="default_address" data-address_id="<?php echo $address->id; ?>" id="default_<?php echo $address->id; ?>" value="1"<?php echo ($address->default==1?' checked':'')?> />
						Использовать по умолчанию
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
                				<label class="control-label require" for="city">Адрес</label>
                				<div class="controls">
                					<input type="text" class="inputbox" name="city" id="city" placeholder="Город" required="true" value="<?php echo $address->city; ?>" />
                				</div>
                			</div>
                			<div class="control-group">
                				<label class="control-label require" for="street">Улица</label>
                				<div class="controls">
                					<input type="text" class="inputbox" name="street" id="street" placeholder="Улица" required="true" value="<?php echo $address->street; ?>" />
                				</div>
                			</div>
                			<div class="control-group">
                				<label class="control-label require" for="house">Дом</label>
                				<div class="controls">
                					<input type="text" class="inputbox" name="house" id="house" placeholder="Дом" required="true" value="<?php echo $address->house; ?>" />
                				</div>
                			</div>
                			<div class="control-group">
                				<label class="control-label require" for="floor">Этаж</label>
                				<div class="controls">
                					<input type="text" class="inputbox" name="floor" placeholder="Этаж" id="floor" value="<?php echo $address->floor; ?>" />
                				</div>
                			</div>
                			<div class="control-group">
                				<label class="control-label require" for="inputApartment">Квартира</label>
                				<div class="controls">
                					<input type="text" class="inputbox" name="flat" placeholder="Квартира" id="inputApartment" value="<?php echo $address->flat; ?>" />
                				</div>
                			</div>
                			<div class="control-group">
                				<div class="controls top">
                					<label class="checkbox custom">
                						<input type="checkbox" name="default" id="new_default" value="1"<?php echo ($address->default==1?' checked':'')?> /> Использовать по умолчанию
                					</label>
                					<button type="submit" class="button btn btn-success">Редактировать</button>
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
	<h2 class="text-center">Нет адресов</h2>
	<?php } ?>
</div>