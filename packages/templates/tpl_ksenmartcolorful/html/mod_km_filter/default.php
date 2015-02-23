<?php defined('_JEXEC') or die; ?>
<div class="mod_ksm_filter ksenmart-search well noTransition <?php echo $class_sfx; ?>" id="ksenmart-search">
	<?php if($module->showtitle){ ?>
	<h3><?php echo $module->title; ?></h3>
	<?php } ?>
	<form action="<?php echo $form_action; ?>" method="get">
		<?php if ($params->get('price', 'none')->view != 'none'){ ?>
		<div class="prices tracks act">
			<fieldset>
				<?php if ($params->get('price')->view == 'slider'): ?>
				<div class="inputs">
					<span><?php echo JText::_('MOD_KM_FILTER_PRICE')?></span>
					<span><?php echo JText::_('MOD_KM_FILTER_PRICE_LESS')?></span><input type="text" id="search-price-less" class="search-price" name="price_less" value="<?php echo (int)$price_less?>" />
					<span><?php echo JText::_('MOD_KM_FILTER_PRICE_MORE')?></span><input type="text" id="search-price-more" class="search-price" name="price_more" value="<?php echo (int)$price_more?>" />
					<span>руб</span>
				</div>
				<div class="tracker">
				</div>		
				<?php endif; ?>
			</fieldset>
		</div>	
		<?php } ?>
		<?php if (count($manufacturers) > 0 && $params->get('manufacturer', 'none')->view != 'none'){ ?>
		<div class="manufacturers brands filter_box display-<?php echo $params->get('manufacturer', 'row')->display?>">
			<ul class="nav nav-list">
				<li class="nav-header"><?php echo JText::_('MOD_KM_FILTER_MANUFACTURERS'); ?></li>
				<?php if ($params->get('manufacturer')->view != 'list'): ?>
					<?php foreach($manufacturers as $manufacturer){ ?>
					<li class="manufacturer_<?php echo $manufacturer->id; ?> manufacturer <?php echo $manufacturer->selected?' active':''; ?><?php echo !empty($manufacturer->image)?' item_img':''; ?>">
						<a href="javascript:void(0);" title="<?php echo $manufacturer->title; ?>">
						<?php if ($params->get('manufacturer')->view == 'images' && $manufacturer->image!=''){ ?>					
						<label class="item image_item <?php if ($manufacturer->selected) echo 'active';?>">
							<input style="display:none;" onclick="KMChangeFilter(this,'manufacturer');" type="checkbox" name="manufacturers[]" value="<?php echo $manufacturer->id; ?>" <?php if ($manufacturer->selected) echo 'checked'; ?> />
							<div class="color"><img src="<?php echo $manufacturer->image; ?>" alt="<?php echo $manufacturer->title; ?>" /></div>
							<span class="delta">&#x25C6;</span>
						</label>
						<?php }elseif ($params->get('manufacturer')->view == 'checkbox'){ ?>
						<label class="item <?php if ($manufacturer->selected) echo 'active'; ?>">
							<input onclick="KMChangeFilter(this,'manufacturer');" type="checkbox" name="manufacturers[]" value="<?php echo $manufacturer->id; ?>" <?php if ($manufacturer->selected) echo 'checked'; ?> />
							<span><?php echo $manufacturer->title; ?></span>
						</label>	
						<?php }elseif ($params->get('manufacturer')->view == 'radio'){ ?>
						<label class="item <?php if ($manufacturer->selected) echo 'active'; ?>">
							<input onclick="KMChangeFilter(this,'manufacturer');" type="radio" name="manufacturers[]" value="<?php echo $manufacturer->id; ?>" <?php if ($manufacturer->selected) echo 'checked'; ?> />
							<span><?php echo $manufacturer->title; ?></span>
						</label>		
						<?php }else{ ?>
						<label class="item <?php if ($manufacturer->selected) echo 'active'; ?>">
							<input onclick="KMChangeFilter(this,'manufacturer');" type="checkbox" name="manufacturers[]" value="<?php echo $manufacturer->id; ?>" <?php if ($manufacturer->selected) echo 'checked'; ?> />
							<span><?php echo $manufacturer->title; ?></span>
						</label>						
						<?php } ?>
						</a>
					</li>
					<?php } ?>	
				<?php else: ?>
				<li>
					<select name="manufacturers[]" onchange="KMChangeFilter(this,'manufacturer_<?php echo $manufacturer->id; ?>');">
						<option value="">Выбрать</option>
						<?php foreach($manufacturers as $manufacturer){ ?>
						<option class="manufacturer_<?php echo $manufacturer->id; ?> manufacturer item <?php if ($manufacturer->selected) echo 'active'; ?>" value="<?php echo $manufacturer->id; ?>" <?php if ($manufacturer->selected) echo 'selected'; ?>><?php echo $manufacturer->title; ?></option>
						<?php } ?>	
					</select>
				</li>
				<?php endif; ?>
			</ul>
		</div>
		<?php } ?>		
		<div class="properties">
		<?php foreach($properties as $property){ ?>
		<?php if(!empty($property->values) && $property->view != 'none'){ ?>
		<div class="property_<?php echo $property->id?> property filter_box display-<?php echo $property->display?>">
			<ul class="nav nav-list">
				<li class="nav-header clearfix"><?php echo $property->title; ?></li>
				<?php if ($property->view != 'list'): ?>
					<?php foreach($property->values as $value){ ?>
					<li class="property_value_<?php echo $value->id; ?> property_value<?php echo $value->selected?' active':''; ?><?php echo !empty($value->image)?' item_img':''; ?>">
						<a href="javascript:void(0);" title="<?php echo $value->title; ?>">
						<?php if ($property->view == 'images' && $value->image!=''){ ?>					
						<label class="item image_item <?php if ($value->selected) echo 'active';?>">
							<input style="display:none;" onclick="KMChangeFilter(this,'property_<?php echo $property->id; ?>');" type="checkbox" name="properties[]" value="<?php echo $value->id; ?>" <?php if ($value->selected) echo 'checked'; ?> />
							<div class="color"><img src="<?php echo JURI::root().$value->image; ?>" alt="<?php echo $value->title; ?>" /></div>
							<span class="delta">&#x25C6;</span>
						</label>
						<?php }elseif ($property->view == 'checkbox'){ ?>
						<label class="item <?php if ($value->selected) echo 'active'; ?>">
							<input onclick="KMChangeFilter(this,'property_<?php echo $property->id; ?>');" type="checkbox" name="properties[]" value="<?php echo $value->id; ?>" <?php if ($value->selected) echo 'checked'; ?> />
							<span><?php echo $value->title; ?></span>
						</label>	
						<?php }elseif ($property->view == 'radio'){ ?>
						<label class="item <?php if ($value->selected) echo 'active'; ?>">
							<input onclick="KMChangeFilter(this,'property_<?php echo $property->id; ?>');" type="radio" name="properties[<?php echo $property->property_id; ?>]" value="<?php echo $value->id; ?>" <?php if ($value->selected) echo 'checked'; ?> />
							<span><?php echo $value->title; ?></span>
						</label>		
						<?php }else{ ?>
						<label class="item <?php if ($value->selected) echo 'active'; ?>">
							<input onclick="KMChangeFilter(this,'property_<?php echo $property->id; ?>');" type="checkbox" name="properties[]" value="<?php echo $value->id; ?>" <?php if ($value->selected) echo 'checked'; ?> />
							<span><?php echo $value->title; ?></span>
						</label>						
						<?php } ?>
						</a>
					</li>
					<?php } ?>	
				<?php else: ?>
				<li>
					<select name="properties[]" onchange="KMChangeFilter(this,'property_<?php echo $property->id; ?>');">
						<option value="">Выбрать</option>
						<?php foreach($property->values as $value){ ?>
						<option class="property_value_<?php echo $value->id; ?> property_value item <?php if ($value->selected) echo 'active'; ?>" value="<?php echo $value->id; ?>" <?php if ($value->selected) echo 'selected'; ?>><?php echo $value->title; ?></option>
						<?php } ?>	
					</select>
				</li>
				<?php endif; ?>
			</ul>
		</div>		
		<?php } ?>
		<?php } ?>	
		</div>
		<?php if(count($countries) > 0){ ?>
		<div class="countries" style="display: none;">
			<ul class="nav nav-list">
				<li class="nav-header"><?php echo JText::_('MOD_KM_FILTER_COUNTRIES'); ?></li>
			<?php foreach($countries as $country) { ?>
				<li class="country_<?php echo $country->id; ?> country<?php echo $country->selected?' active':''; ?>">
					<a href="javascript:void(0);" title="<?php echo $country->title; ?>">
						<label class="item">
							<input type="checkbox" name="countries[]" value="<?php echo $country->id; ?>" <?php if ($country->selected) echo 'checked'; ?> />
							<?php echo $country->title; ?>
						</label>
					</a>
				</li>
			<?php } ?>
			</ul>
		</div>
		<?php } ?>		
		<?php foreach($categories as $category){ ?>
		<input type="hidden" name="categories[]" value="<?php echo $category;?>" />
		<?php } ?>	
		<input type="hidden" name="order_type" value="<?php echo $order_type;?>" />
		<input type="hidden" name="order_dir" value="<?php echo $order_dir;?>" />
		<input type="checkbox" name="new" value="1" style="visibility:hidden;" />
		<input type="checkbox" name="promotion" value="1" style="visibility:hidden;" />
		<input type="checkbox" name="hot" value="1" style="visibility:hidden;" />
		<input type="checkbox" name="recommendation" value="1" style="visibility:hidden;" />
	</form>
</div>
<script>
	var clicked='<?php echo JRequest::getVar('clicked',''); ?>';
	var view='<?php echo JRequest::getVar('view',''); ?>';
	var price_min=<?php echo (int)$price_min; ?>;
	var price_max=<?php echo (int)$price_max; ?>;	
	var price_less=<?php echo (int)$price_less; ?>;
	var price_more=<?php echo (int)$price_more; ?>;	
</script>