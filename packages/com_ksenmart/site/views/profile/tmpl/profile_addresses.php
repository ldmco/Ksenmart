<?php defined('_JEXEC') or die(); ?>
<p class="lead add_address"><span>+</span> <a href="javascript:void(0);" class="link_b_border">Добавить новый адрес</a></p>
<div class="items">
	<div class="new_address noTransition" style="display: none;">
		<form method="post" class="form-horizontal" action="#tab3">
			<div class="control-group">
				<label class="control-label require" for="city">Город</label>
				<div class="controls">
					<input type="text" class="inputbox" name="city" id="city" placeholder="Город" required="true" />
				</div>
			</div>
			<div class="control-group">
				<label class="control-label require" for="zip">Индекс</label>
				<div class="controls">
					<input type="text" class="inputbox" name="zip" id="zip" placeholder="Индекс" />
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
				<label class="control-label require" for="entrance">Подъезд</label>
				<div class="controls">
					<input type="text" class="inputbox" name="entrance" id="entrance" placeholder="Подъезд" />
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
					<input type="submit" class="btn btn-success" value="Добавить" />
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
                				<label class="control-label require" for="city">Город</label>
                				<div class="controls">
                					<input type="text" class="inputbox" name="city" id="city" placeholder="Город" required="true" value="<?php echo $address->city; ?>" />
                				</div>
                			</div>
                			<div class="control-group">
                				<label class="control-label require" for="zip">Индекс</label>
                				<div class="controls">
                					<input type="text" class="inputbox" name="zip" id="zip" placeholder="Индекс" value="<?php echo $address->zip; ?>" />
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
                				<label class="control-label require" for="entrance">Подъезд</label>
                				<div class="controls">
                					<input type="text" class="inputbox" name="entrance" id="entrance" placeholder="Подъезд" value="<?php echo $address->entrance; ?>" />
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
                					<input type="submit" class="btn btn-success" value="Редактировать" />
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