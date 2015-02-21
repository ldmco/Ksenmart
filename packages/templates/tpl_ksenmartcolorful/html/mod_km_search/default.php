<?php defined('_JEXEC') or die; ?>
<script>
	var clicked='<?php echo JRequest::getVar('clicked','');?>';
	var view='<?php echo JRequest::getVar('view','');?>';
	var leftLimit=<?php echo (int)$price_min?>;
	var rightLimit=<?php echo (int)$price_max?>;	
	
	//<![CDATA[
	<?php if ((int)$price_min!=(int)$price_max):?>
	trackbar.getObject('one').init(
		{
		dual : true, // two intervals
		width : 210, // px
		leftLimit :  <?php echo (int)$price_min;?>, // unit of value
		leftValue : <?php echo (int)$price_less;?>, // unit of value
		rightLimit :  <?php echo (int)$price_max;?>, // unit of value
		rightValue : <?php echo (int)$price_more;?>, // unit of value
		roundUp : 100,
		hehe : ":-)"
		},
		"track1"
	);
	<?php endif;?>
	// -->	
</script>
<div class="ksenmart-search <?php echo $class_sfx?>" id="ksenmart-search">
	<h3><?php echo $module->title?></h3>
	<form action="<?php echo $form_action?>" method="get">
		<div class="price tracks act">
			<fieldset>
				<legend></legend>
				<div class="inputs">
					<span><?php echo JText::_('MOD_KSENMART_SEARCH_PRICE')?></span>
					<span><?php echo JText::_('MOD_KSENMART_SEARCH_PRICE_LESS')?></span><input type="text" id="search-price-less" class="search-price" name="price_less" value="<?php echo (int)$price_less?>" />
					<span><?php echo JText::_('MOD_KSENMART_SEARCH_PRICE_MORE')?></span><input type="text" id="search-price-more" class="search-price" name="price_more" value="<?php echo (int)$price_more?>" />
					<span>руб</span>
				</div>
				<div class="tracker">
					<div id="track1"></div>
				</div>				
			</fieldset>
		</div>	
		<?php if (count($manufacturers)>0):?>
		<div class="manufacturers brands">
			<fieldset>
				<legend><h4><?php echo JText::_('MOD_KSENMART_SEARCH_MANUFACTURERS')?></h4></legend>
				<div class="items">
					<?php foreach($manufacturers as $manufacturer):?>
					<div class="manufacturer_<?php echo $manufacturer->id?> manufacturer">
						<label class="item <?php if ($manufacturer->selected) echo 'active';?>"><input type="checkbox" onclick="KMChangeFilter(this,'manufacturers');" name="manufacturers[]" value="<?php echo $manufacturer->id?>" <?php if ($manufacturer->selected) echo 'checked';?> ><span><?php echo $manufacturer->title?></span></label>
					</div>
					<?php endforeach;?>	
				</div>
			</fieldset>
		</div>
		<?php endif;?>		
		<div class="properties">
		<?php foreach($properties as $property):?>
		<?php if (count($property->values)>0):?>
		<div class="property_<?php echo $property->id?> property filter_box">
			<fieldset>
				<legend><h4><?php echo $property->title?></h4></legend>
				<div class="items">
					<?php foreach($property->values as $value):?>
					<div class="property_value_<?php echo $value->id?> property_value">
						<?php if ($property->picts==1 && $value->image!=''):?>					
						<label class="item image_item <?php if ($value->selected) echo 'active';?>">
							<input onclick="KMChangeFilter(this,'property_<?php echo $property->id?>');" type="checkbox" name="properties[]" value="<?php echo $value->id?>" <?php if ($value->selected) echo 'checked';?> >
							<div class="color"><img src="<?php echo JURI::root().$value->image;?>"></div>
							<span class="delta">&#x25C6;</span>
						</label>
						<?php else:?>
						<label class="item <?php if ($value->selected) echo 'active';?>">
							<input onclick="KMChangeFilter(this,'property_<?php echo $property->id?>');" type="checkbox" name="properties[]" value="<?php echo $value->id?>" <?php if ($value->selected) echo 'checked';?> >
							<span><?php echo $value->title;?></span>
						</label>						
						<?php endif;?>						
					</div>
					<?php endforeach;?>	
				</div>
			</fieldset>
		</div>		
		<?php endif;?>
		<?php endforeach;?>	
		</div>
		<?php if (count($countries)>0):?>
		<div class="countries">
			<fieldset>
				<legend><h4><?php echo JText::_('MOD_KSENMART_SEARCH_COUNTRIES')?></h4></legend>
				<div class="items">
					<?php foreach($countries as $country):?>
					<div class="country_<?php echo $country->id?> country">
						<label class="item <?php if ($country->selected) echo 'active';?>"><input type="checkbox" onclick="KMChangeFilter(this,'countries');" name="countries[]" value="<?php echo $country->id?>" <?php if ($country->selected) echo 'checked';?> ><span><?php echo $country->title?></span></label>
					</div>
					<?php endforeach;?>	
				</div>
			</fieldset>
		</div>
		<?php endif;?>		
		<?php foreach($categories as $category):?>
		<input type="hidden" name="categories[]" value="<?php echo $category;?>">
		<?php endforeach;?>	
		<input type="hidden" name="order_type" value="<?php echo $order_type;?>">
		<input type="hidden" name="order_dir" value="<?php echo $order_dir;?>">
		<input type="checkbox" name="new" value="1" style="visibility:hidden;">
		<input type="checkbox" name="promotion" value="1" style="visibility:hidden;">
		<input type="checkbox" name="hot" value="1" style="visibility:hidden;">
		<input type="checkbox" name="recommendation" value="1" style="visibility:hidden;">
		<input type="submit" id="search-button" value="<?php echo JText::_('MOD_KSENMART_SEARCH_SEARCH')?>">
	</form>
</div>