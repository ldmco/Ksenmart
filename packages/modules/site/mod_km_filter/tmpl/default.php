<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<div class="mod_ksm_filter ksenmart-search well noTransition <?php echo $class_sfx; ?>" id="ksenmart-search">
	<?php if($module->showtitle){ ?>
	<h3><?php echo $module->title; ?></h3>
	<?php } ?>
	<form action="<?php echo $form_action; ?>" method="get">
		<?php if ($mod_params['price']['view'] != 'none'){ ?>
		<div class="prices tracks act">
			<fieldset>
				<?php if ($mod_params['price']['view'] == 'slider'): ?>
				<div class="inputs">
					<span><?php echo JText::_('MOD_KM_FILTER_PRICE')?></span>
					<span><?php echo JText::_('MOD_KM_FILTER_PRICE_LESS')?></span><input type="text" id="search-price-less" class="search-price" name="price_less" value="<?php echo (int)$price_less?>" />
					<span><?php echo JText::_('MOD_KM_FILTER_PRICE_MORE')?></span><input type="text" id="search-price-more" class="search-price" name="price_more" value="<?php echo (int)$price_more?>" />
				</div>
				<div class="tracker">
				</div>		
				<?php endif; ?>
			</fieldset>
		</div>	
		<?php } ?>
		<?php if (count($manufacturers) > 0 && $mod_params['manufacturer']['view'] != 'none'){ ?>
		<div class="manufacturers brands filter_box display-<?php echo $mod_params['manufacturer']['display']; ?>">
			<ul class="nav nav-list">
				<li class="nav-header"><?php echo JText::_('MOD_KM_FILTER_MANUFACTURERS'); ?></li>
				<?php if ($mod_params['manufacturer']['view'] != 'list'): ?>
					<?php foreach($manufacturers as $manufacturer){ ?>
					<li class="manufacturer_<?php echo $manufacturer->id; ?> manufacturer <?php echo $manufacturer->selected?' active':''; ?><?php echo !empty($manufacturer->image)?' item_img':''; ?>">
						<a href="javascript:void(0);" title="<?php echo $manufacturer->title; ?>">
						<?php if ($mod_params['manufacturer']['view'] == 'images' && $manufacturer->image!=''){ ?>					
						<label class="item image_item <?php if ($manufacturer->selected) echo 'active';?>">
							<input style="display:none;" onclick="KMChangeFilter(this);" type="checkbox" name="manufacturers[]" value="<?php echo $manufacturer->id; ?>" <?php if ($manufacturer->selected) echo 'checked'; ?> />
							<div class="color"><img src="<?php echo $manufacturer->image; ?>" alt="<?php echo $manufacturer->title; ?>" /></div>
							<span class="delta">&#x25C6;</span>
						</label>
						<?php }elseif ($mod_params['manufacturer']['view'] == 'checkbox'){ ?>
						<label class="item <?php if ($manufacturer->selected) echo 'active'; ?>">
							<input onclick="KMChangeFilter(this);" type="checkbox" name="manufacturers[]" value="<?php echo $manufacturer->id; ?>" <?php if ($manufacturer->selected) echo 'checked'; ?> />
							<span><?php echo $manufacturer->title; ?></span>
						</label>	
						<?php }elseif ($mod_params['manufacturer']['view'] == 'radio'){ ?>
						<label class="item <?php if ($manufacturer->selected) echo 'active'; ?>">
							<input onclick="KMChangeFilter(this);" type="radio" name="manufacturers[]" value="<?php echo $manufacturer->id; ?>" <?php if ($manufacturer->selected) echo 'checked'; ?> />
							<span><?php echo $manufacturer->title; ?></span>
						</label>		
						<?php }else{ ?>
						<label class="item <?php if ($manufacturer->selected) echo 'active'; ?>">
							<input onclick="KMChangeFilter(this);" type="checkbox" name="manufacturers[]" value="<?php echo $manufacturer->id; ?>" <?php if ($manufacturer->selected) echo 'checked'; ?> />
							<span><?php echo $manufacturer->title; ?></span>
						</label>						
						<?php } ?>
						</a>
					</li>
					<?php } ?>	
				<?php else: ?>
				<li>
					<select name="manufacturers[]" onchange="KMChangeFilter(this);">
						<option value=""><?php echo JText::_('MOD_KM_FILTER_CHOOSE')?></option>
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
							<input style="display:none;" onclick="KMChangeFilter(this);" type="checkbox" name="properties[]" value="<?php echo $value->id; ?>" <?php if ($value->selected) echo 'checked'; ?> />
							<div class="color"><img src="<?php echo JURI::root().$value->image; ?>" alt="<?php echo $value->title; ?>" /></div>
							<span class="delta">&#x25C6;</span>
						</label>
						<?php }elseif ($property->view == 'checkbox'){ ?>
						<label class="item <?php if ($value->selected) echo 'active'; ?>">
							<input onclick="KMChangeFilter(this);" type="checkbox" name="properties[]" value="<?php echo $value->id; ?>" <?php if ($value->selected) echo 'checked'; ?> />
							<span><?php echo $value->title; ?></span>
						</label>	
						<?php }elseif ($property->view == 'radio'){ ?>
						<label class="item <?php if ($value->selected) echo 'active'; ?>">
							<input onclick="KMChangeFilter(this);" type="radio" name="properties[<?php echo $property->property_id; ?>]" value="<?php echo $value->id; ?>" <?php if ($value->selected) echo 'checked'; ?> />
							<span><?php echo $value->title; ?></span>
						</label>		
						<?php }else{ ?>
						<label class="item <?php if ($value->selected) echo 'active'; ?>">
							<input onclick="KMChangeFilter(this);" type="checkbox" name="properties[]" value="<?php echo $value->id; ?>" <?php if ($value->selected) echo 'checked'; ?> />
							<span><?php echo $value->title; ?></span>
						</label>						
						<?php } ?>
						</a>
					</li>
					<?php } ?>	
				<?php else: ?>
				<li>
					<select name="properties[]" onchange="KMChangeFilter(this);">
						<option value=""><?php echo JText::_('MOD_KM_FILTER_CHOOSE')?></option>
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
		<?php if (count($countries) > 0 && $mod_params['country']['view'] != 'none'){ ?>
		<div class="countries filter_box display-<?php echo $mod_params['country']['display']; ?>">
			<ul class="nav nav-list">
				<li class="nav-header"><?php echo JText::_('MOD_KM_FILTER_COUNTRIES'); ?></li>
				<?php if ($mod_params['country']['view'] != 'list'): ?>
					<?php foreach($countries as $country){ ?>
					<li class="country_<?php echo $country->id; ?> country <?php echo $country->selected?' active':''; ?><?php echo !empty($country->image)?' item_img':''; ?>">
						<a href="javascript:void(0);" title="<?php echo $country->title; ?>">
						<?php if ($mod_params['country']['view'] == 'images' && $country->image!=''){ ?>					
						<label class="item image_item <?php if ($country->selected) echo 'active';?>">
							<input style="display:none;" onclick="KMChangeFilter(this);" type="checkbox" name="countries[]" value="<?php echo $country->id; ?>" <?php if ($country->selected) echo 'checked'; ?> />
							<div class="color"><img src="<?php echo $country->image; ?>" alt="<?php echo $country->title; ?>" /></div>
							<span class="delta">&#x25C6;</span>
						</label>
						<?php }elseif ($mod_params['country']['view'] == 'checkbox'){ ?>
						<label class="item <?php if ($country->selected) echo 'active'; ?>">
							<input onclick="KMChangeFilter(this);" type="checkbox" name="countries[]" value="<?php echo $country->id; ?>" <?php if ($country->selected) echo 'checked'; ?> />
							<span><?php echo $country->title; ?></span>
						</label>	
						<?php }elseif ($mod_params['country']['view'] == 'radio'){ ?>
						<label class="item <?php if ($country->selected) echo 'active'; ?>">
							<input onclick="KMChangeFilter(this);" type="radio" name="countries[]" value="<?php echo $country->id; ?>" <?php if ($country->selected) echo 'checked'; ?> />
							<span><?php echo $country->title; ?></span>
						</label>		
						<?php }else{ ?>
						<label class="item <?php if ($country->selected) echo 'active'; ?>">
							<input onclick="KMChangeFilter(this);" type="checkbox" name="countries[]" value="<?php echo $country->id; ?>" <?php if ($country->selected) echo 'checked'; ?> />
							<span><?php echo $country->title; ?></span>
						</label>						
						<?php } ?>
						</a>
					</li>
					<?php } ?>	
				<?php else: ?>
				<li>
					<select name="countries[]" onchange="KMChangeFilter(this);">
						<option value=""><?php echo JText::_('MOD_KM_FILTER_CHOOSE')?></option>
						<?php foreach($countries as $country){ ?>
						<option class="country_<?php echo $country->id; ?> country item <?php if ($country->selected) echo 'active'; ?>" value="<?php echo $country->id; ?>" <?php if ($country->selected) echo 'selected'; ?>><?php echo $country->title; ?></option>
						<?php } ?>	
					</select>
				</li>
				<?php endif; ?>
			</ul>
		</div>
		<?php } ?>		
		<?php if ($params->get('show_filter_button', 0) == 1 || $params->get('show_clear_button', 0) == 1): ?>
		<div class="buttons">
			<?php if ($params->get('show_filter_button', 0) == 1): ?>
			<input type="button" class="btn btn-success filter-button" value="<?php echo JText::_('MOD_KM_FILTER_FIND'); ?>" onclick="KMChangeFilter(this);">
			<?php endif; ?>
			<?php if ($params->get('show_filter_button', 0) == 1): ?>
			<input type="button" class="btn clear-button" value="<?php echo JText::_('MOD_KM_FILTER_CLEAR'); ?>" onclick="KMClearFilter();">
			<?php endif; ?>
		</div>
		<?php endif; ?>
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
	<div class="clearfix"></div>
</div>
<script>
	var view='<?php echo JRequest::getVar('view',''); ?>';
	var price_min=<?php echo (int)$price_min; ?>;
	var price_max=<?php echo (int)$price_max; ?>;	
	var price_less=<?php echo (int)$price_less; ?>;
	var price_more=<?php echo (int)$price_more; ?>;	
	var show_filter_button=<?php echo $params->get('show_filter_button', 0); ?>;	
</script>