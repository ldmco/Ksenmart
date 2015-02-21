<div class="control-group">
	<label class="control-label">Ваш регион</label>
	<div class="controls">
		<select id="region_id" name="region_id" onchange="KMCartChangeRegion(this);" required="">
			<option value="">Выбрать регион</option>
			<?php foreach($this->regions as $region):?>
			<option value="<?php echo $region->id; ?>" <?php echo ($region->selected?'selected':''); ?>><?php echo $region->title; ?></option>
			<?php endforeach; ?>
		</select>
	</div>
</div>