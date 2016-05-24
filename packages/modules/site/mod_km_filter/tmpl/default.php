<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<div class="ksm-module-filter <?php echo $class_sfx?>">
	<?php if ($module->showtitle): ?>
	<h3><?php echo $module->title; ?></h3>
	<?php endif; ?>
	<form action="<?php echo $form_action; ?>" method="get">
		<?php if ($mod_params['price']['view'] != 'none'): ?>
		<div class="ksm-module-filter-block ksm-module-filter-block-prices ksm-module-filter-block-<?php echo $mod_params['price']['view']; ?>">
			<?php if ($mod_params['price']['view'] == 'slider'): ?>
				<span class="ksm-module-filter-block-prices-text"><?php echo JText::_('MOD_KM_FILTER_PRICE')?></span>
				<span class="ksm-module-filter-block-prices-text-less"><?php echo JText::_('MOD_KM_FILTER_PRICE_LESS')?></span>
				<input type="text" class="ksm-module-filter-block-prices-less" name="price_less" value="<?php echo (int)$price_less?>" />
				<span class="ksm-module-filter-block-prices-text-more"><?php echo JText::_('MOD_KM_FILTER_PRICE_MORE')?></span>
				<input type="text" class="ksm-module-filter-block-prices-more" name="price_more" value="<?php echo (int)$price_more?>" />
				<div class="ksm-module-filter-block-prices-tracker"></div>		
			<?php endif; ?>
		</div>	
		<?php endif; ?>
		<?php if (count($manufacturers) > 0 && $mod_params['manufacturer']['view'] != 'none'){ ?>
		<div class="ksm-module-filter-block ksm-module-filter-block-manufacturers ksm-module-filter-block-<?php echo $mod_params['manufacturer']['display']; ?> ksm-module-filter-block-<?php echo $mod_params['manufacturer']['view']; ?>">
			<ul class="ksm-module-filter-block-listing">
				<li class="ksm-module-filter-block-listing-header"><?php echo JText::_('MOD_KM_FILTER_MANUFACTURERS'); ?></li>
				<?php if ($mod_params['manufacturer']['view'] != 'list'): ?>
					<?php foreach($manufacturers as $manufacturer){ ?>
					<li class="ksm-module-filter-block-listing-item <?php echo $manufacturer->selected?' active':''; ?>" data-id="<?php echo $manufacturer->id; ?>">
						<label>
							<?php if ($mod_params['manufacturer']['view'] == 'images' && $manufacturer->image!=''){ ?>					
								<input style="display:none;" onclick="KMChangeFilter(this);" type="checkbox" name="manufacturers[]" value="<?php echo $manufacturer->id; ?>" <?php if ($manufacturer->selected) echo 'checked'; ?> />
								<img src="<?php echo $manufacturer->image; ?>" alt="<?php echo $manufacturer->title; ?>" />
							<?php }elseif ($mod_params['manufacturer']['view'] == 'checkbox'){ ?>
								<input onclick="KMChangeFilter(this);" type="checkbox" name="manufacturers[]" value="<?php echo $manufacturer->id; ?>" <?php if ($manufacturer->selected) echo 'checked'; ?> />
								<span><?php echo $manufacturer->title; ?></span>
							<?php }elseif ($mod_params['manufacturer']['view'] == 'radio'){ ?>
								<input onclick="KMChangeFilter(this);" type="radio" name="manufacturers[]" value="<?php echo $manufacturer->id; ?>" <?php if ($manufacturer->selected) echo 'checked'; ?> />
								<span><?php echo $manufacturer->title; ?></span>
							<?php }else{ ?>
								<input style="display:none;" onclick="KMChangeFilter(this);" type="checkbox" name="manufacturers[]" value="<?php echo $manufacturer->id; ?>" <?php if ($manufacturer->selected) echo 'checked'; ?> />
								<span><?php echo $manufacturer->title; ?></span>
							<?php } ?>
						</label>
					</li>
					<?php } ?>	
				<?php else: ?>
					<li class="ksm-module-filter-block-listing-row">
						<select name="manufacturers[]" onchange="KMChangeFilter(this);">
							<option value=""><?php echo JText::_('MOD_KM_FILTER_CHOOSE')?></option>
							<?php foreach($manufacturers as $manufacturer){ ?>
							<option class="ksm-module-filter-block-listing-item <?php if ($manufacturer->selected) echo 'active'; ?>" value="<?php echo $manufacturer->id; ?>" data-id="<?php echo $manufacturer->id; ?>" <?php if ($manufacturer->selected) echo 'selected'; ?>><?php echo $manufacturer->title; ?></option>
							<?php } ?>	
						</select>
					</li>
				<?php endif; ?>
			</ul>
		</div>
		<?php } ?>		
		<div class="ksm-module-filter-block-properties">
		<?php foreach($properties as $property){ ?>
		<?php if(!empty($property->values) && $property->view != 'none'){ ?>
		<div class="ksm-module-filter-block ksm-module-filter-block-property ksm-module-filter-block-property-<?php echo $property->id?> ksm-module-filter-block-<?php echo $property->display?> ksm-module-filter-block-<?php echo $property->view?>">
			<ul class="ksm-module-filter-block-listing">
				<li class="ksm-module-filter-block-listing-header"><?php echo $property->title; ?></li>
				<?php if ($property->view != 'list'): ?>
					<?php foreach($property->values as $value){ ?>
					<li class="ksm-module-filter-block-listing-item <?php echo $value->selected?' active':''; ?>" data-id="<?php echo $value->id; ?>">
						<label>
							<?php if ($property->view == 'images' && $value->image!=''){ ?>					
								<input style="display:none;" onclick="KMChangeFilter(this);" type="checkbox" name="properties[]" value="<?php echo $value->id; ?>" <?php if ($value->selected) echo 'checked'; ?> />
								<img src="<?php echo JURI::root().$value->image; ?>" alt="<?php echo $value->title; ?>" />
							<?php }elseif ($property->view == 'checkbox'){ ?>
								<input onclick="KMChangeFilter(this);" type="checkbox" name="properties[]" value="<?php echo $value->id; ?>" <?php if ($value->selected) echo 'checked'; ?> />
								<span><?php echo $value->title; ?></span>
							<?php }elseif ($property->view == 'radio'){ ?>
								<input onclick="KMChangeFilter(this);" type="radio" name="properties[<?php echo $property->property_id; ?>]" value="<?php echo $value->id; ?>" <?php if ($value->selected) echo 'checked'; ?> />
								<span><?php echo $value->title; ?></span>
							<?php }else{ ?>
								<input style="display:none;" onclick="KMChangeFilter(this);" type="checkbox" name="properties[]" value="<?php echo $value->id; ?>" <?php if ($value->selected) echo 'checked'; ?> />
								<span><?php echo $value->title; ?></span>
							<?php } ?>
						</label>
					</li>
					<?php } ?>	
				<?php else: ?>
				<li class="ksm-module-filter-block-listing-row">
					<select name="properties[]" onchange="KMChangeFilter(this);">
						<option value=""><?php echo JText::_('MOD_KM_FILTER_CHOOSE')?></option>
						<?php foreach($property->values as $value){ ?>
						<option class="ksm-module-filter-block-listing-item <?php if ($value->selected) echo 'active'; ?>" value="<?php echo $value->id; ?>" data-id="<?php echo $value->id; ?>" <?php if ($value->selected) echo 'selected'; ?>><?php echo $value->title; ?></option>
						<?php } ?>	
					</select>
				</li>
				<?php endif; ?>
			</ul>
		</div>		
		<?php } ?>
		<?php } ?>	
		</div>
		<?php if (count($countries) > 0 && $mod_params['country']['view'] != 'none'){ ?>
		<div class="ksm-module-filter-block ksm-module-filter-block-countries ksm-module-filter-block-<?php echo $mod_params['country']['display']; ?> ksm-module-filter-block-<?php echo $mod_params['country']['view']; ?>">
			<ul class="ksm-module-filter-block-listing">
				<li class="ksm-module-filter-block-listing-header"><?php echo JText::_('MOD_KM_FILTER_COUNTRIES'); ?></li>
				<?php if ($mod_params['country']['view'] != 'list'): ?>
					<?php foreach($countries as $country){ ?>
					<li class="ksm-module-filter-block-listing-item <?php echo $country->selected?' active':''; ?>" data-id="<?php echo $country->id; ?>">
						<label>
							<?php if ($mod_params['country']['view'] == 'images' && $country->image!=''){ ?>					
								<input style="display:none;" onclick="KMChangeFilter(this);" type="checkbox" name="countries[]" value="<?php echo $country->id; ?>" <?php if ($country->selected) echo 'checked'; ?> />
								<img src="<?php echo $country->image; ?>" alt="<?php echo $country->title; ?>" />
							<?php }elseif ($mod_params['country']['view'] == 'checkbox'){ ?>
								<input onclick="KMChangeFilter(this);" type="checkbox" name="countries[]" value="<?php echo $country->id; ?>" <?php if ($country->selected) echo 'checked'; ?> />
								<span><?php echo $country->title; ?></span>
							<?php }elseif ($mod_params['country']['view'] == 'radio'){ ?>
								<input onclick="KMChangeFilter(this);" type="radio" name="countries[]" value="<?php echo $country->id; ?>" <?php if ($country->selected) echo 'checked'; ?> />
								<span><?php echo $country->title; ?></span>
							<?php }else{ ?>
								<input style="display:none;" onclick="KMChangeFilter(this);" type="checkbox" name="countries[]" value="<?php echo $country->id; ?>" <?php if ($country->selected) echo 'checked'; ?> />
								<span><?php echo $country->title; ?></span>
							<?php } ?>
						</label>
					</li>
					<?php } ?>	
				<?php else: ?>
					<li class="ksm-module-filter-block-listing-row">
						<select name="countries[]" onchange="KMChangeFilter(this);">
							<option value=""><?php echo JText::_('MOD_KM_FILTER_CHOOSE')?></option>
							<?php foreach($countries as $country){ ?>
							<option class="ksm-module-filter-block-listing-item <?php if ($country->selected) echo 'active'; ?>" value="<?php echo $country->id; ?>" data-id="<?php echo $country->id; ?>" <?php if ($country->selected) echo 'selected'; ?>><?php echo $country->title; ?></option>
							<?php } ?>	
						</select>
					</li>
				<?php endif; ?>
			</ul>
		</div>
		<?php } ?>		
		<?php if ($params->get('show_filter_button', 0) == 1 || $params->get('show_clear_button', 0) == 1): ?>
		<div class="ksm-module-filter-block ksm-module-filter-block-buttons">
			<?php if ($params->get('show_filter_button', 0) == 1): ?>
			<input type="button" class="ksm-module-filter-button-filter" value="<?php echo JText::_('MOD_KM_FILTER_FIND'); ?>" onclick="KMChangeFilter(this);">
			<?php endif; ?>
			<?php if ($params->get('show_filter_button', 0) == 1): ?>
			<input type="button" class="ksm-module-filter-button-clear" value="<?php echo JText::_('MOD_KM_FILTER_CLEAR'); ?>" onclick="KMClearFilter();">
			<?php endif; ?>
		</div>
		<?php endif; ?>
		<?php foreach($categories as $category){ ?>
		<input type="hidden" name="categories[]" value="<?php echo $category;?>" />
		<?php } ?>	
		<input type="hidden" name="order_type" value="<?php echo $order_type;?>" />
		<input type="hidden" name="order_dir" value="<?php echo $order_dir;?>" />
	</form>
</div>
<script>
	var view='<?php echo JRequest::getVar('view',''); ?>';
	var price_min=<?php echo (int)$price_min; ?>;
	var price_max=<?php echo (int)$price_max; ?>;	
	var price_less=<?php echo (int)$price_less; ?>;
	var price_more=<?php echo (int)$price_more; ?>;	
	var show_filter_button=<?php echo $params->get('show_filter_button', 0); ?>;	
</script>