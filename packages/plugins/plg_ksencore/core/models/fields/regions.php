<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

class JFormFieldRegions extends JFormField {
	
	protected $type = 'Regions';
	
	public function getInput() {
		$db = JFactory::getDBO();
		$res_html = '';
		$query = $db->getQuery(true);
		$query->select('*')->from('#__ksenmart_countries')->where('published=1')->order('ordering');
		$db->setQuery($query);
		$countries = $db->loadObjectList('id');
		
		
		foreach ($countries as & $country) {
			$query = $db->getQuery(true);
			$query->select('*')->from('#__ksenmart_regions')->where('published=1')->where('country_id=' . $country->id)->order('ordering');
			$db->setQuery($query);
			$country->regions = $db->loadObjectList('id');
		}
		unset($country);
		
		$html = '';
		$html.= '<div class="row countries">';
		$html.= '	<ul>';
		
		
		foreach ($this->value as $country_id => $regions) {
			if (isset($countries[$country_id])) {
				$html.= '	<li country_id="' . $country_id . '"><span>' . $countries[$country_id]->title . '</span><i></i><input type="hidden" name="' . $this->name . '[' . $country_id . '][]"></li>';
			}
		}
		
		$html.= '		<li class="no-countries" style="' . (count($this->value) > 0 ? 'display:none;' : '') . '"><span>' . JText::_('ksm_shippings_shipping_no_countries') . '</span></li>';
		$html.= '	</ul>';
		$html.= '</div>';
		$html.= '<div class="row">';
		$html.= '	<a href="#" id="add-country" class="add">' . JText::_('ksm_add') . '</a>';
		$html.= '</div>';
		$html.= '<div id="popup-window3" class="popup-window" style="display: none;">';
		$html.= '	<div style="width: 460px;height: 260px;margin-left: -230px;margin-top: -130px;">';
		$html.= '		<div class="popup-window-inner">';
		$html.= '			<div class="heading">';
		$html.= '				<h3>' . JText::_('ksm_shippings_shipping_countries') . '</h3>';
		$html.= '				<div class="save-close">';
		$html.= '					<button class="close" onclick="return false;"></button>';
		$html.= '				</div>';
		$html.= '			</div>';
		$html.= '			<div class="contents">';
		$html.= '				<div class="contents-inner">';
		$html.= '					<div class="slide_module">';
		$html.= '						<div class="row">';
		$html.= '							<ul>';
		$html.= '								<li class="all-countries"><span>' . JText::_('ksm_shippings_shipping_all_countries') . '</span></li>';
		
		
		foreach ($countries as $country) $html.= '							<li country_id="' . $country->id . '" class="' . (array_key_exists($country->id, $this->value) ? 'active' : '') . '"><span>' . $country->title . '</span></li>';
		$html.= '							</ul>';
		$html.= '						</div>';
		$html.= '					</div>';
		$html.= '				</div>';
		$html.= '			</div>';
		$html.= '		</div>';
		$html.= '	</div>';
		$html.= '</div>';
		$this->element['label'] = 'KSM_SHIPPINGS_SHIPPING_COUNTRIES';
		$this->element['type'] = 'countries';
		$res_html.= KSSystem::wrapFormField('slidemodule', $this->element, $html);
		
		$html = '';
		$html.= '<div class="row regions">';
		$html.= '	<ul>';
		$count_regions = 0;
		
		
		foreach ($this->value as $country_id => $regions) {
			$count_regions+= count($regions);
			
			
			foreach ($regions as $region_id) if (isset($countries[$country_id]) && isset($countries[$country_id]->regions[$region_id])) $html.= '	<li region_id="' . $region_id . '"><span>' . $countries[$country_id]->regions[$region_id]->title . '</span><i></i><input type="hidden" name="' . $this->name . '[' . $country_id . '][]" value="' . $region_id . '"></li>';
		}
		$html.= '		<li class="no-regions" style="' . ($count_regions > 0 ? 'display:none;' : '') . '"><span>' . JText::_('ksm_shippings_shipping_no_regions') . '</span></li>';
		$html.= '	</ul>';
		$html.= '</div>';
		$html.= '<div class="row">';
		$html.= '	<a href="#" id="add-city" class="add">' . JText::_('ksm_add') . '</a>';
		$html.= '</div>';
		$html.= '<div id="popup-window4" class="popup-window" style="display: none;">';
		$html.= '	<div style="width: 460px;height: 260px;margin-left: -230px;margin-top: -130px;">';
		$html.= '		<div class="popup-window-inner">';
		$html.= '			<div class="heading">';
		$html.= '				<h3>' . JText::_('ksm_shippings_shipping_regions') . '</h3>';
		$html.= '				<div class="save-close">';
		$html.= '					<button class="close" onclick="return false;"></button>';
		$html.= '				</div>';
		$html.= '			</div>';
		$html.= '			<div class="contents">';
		$html.= '				<div class="contents-inner">';
		$html.= '					<div class="slide_module">';
		$html.= '						<div class="row no-countries" style="' . (count($this->value) > 0 ? 'display:none;' : '') . '">';
		$html.= '							<ul>';
		$html.= '								<li><span>' . JText::_('ksm_shippings_shipping_no_countries') . '</span></li>';
		$html.= '							</ul>';
		$html.= '						</div>';
		
		
		foreach ($countries as $country) {
			$html.= '					<div class="row regions-row regions-row-' . $country->id . '" style="' . (array_key_exists($country->id, $this->value) ? '' : 'display:none;') . '">';
			$html.= '						<h3>' . $country->title . '</h3>';
			$html.= '						<ul>';
			$html.= '							<li class="all-regions"><span>' . JText::_('ksm_shippings_shipping_all_regions') . '</span></li>';
			
			
			foreach ($country->regions as $region) $html.= '						<li region_id="' . $region->id . '" country_id="' . $country->id . '" class="' . (isset($this->value[$country->id]) && in_array($region->id, $this->value[$country->id]) ? 'active' : '') . '"><span>' . $region->title . '</span></li>';
			$html.= '						</ul>';
			$html.= '					</div>';
		}
		$html.= '					</div>';
		$html.= '				</div>';
		$html.= '			</div>';
		$html.= '		</div>';
		$html.= '	</div>';
		$html.= '</div>';
		$this->element['label'] = 'KSM_SHIPPINGS_SHIPPING_REGIONS';
		$this->element['type'] = 'regions';
		
		$script = '
		jQuery(document).ready(function(){
		
			jQuery(".all-countries").click(function(){
				if (jQuery(this).is(".active"))
				{
					jQuery(this).removeClass("active");
					jQuery("#popup-window3 li").each(function(){
						if (!jQuery(this).is(".all-countries") && jQuery(this).is(".active"))
							removeCountry(jQuery(this).attr("country_id"));
					});
				}
				else
				{
					jQuery(this).addClass("active");
					jQuery("#popup-window3 li").each(function(){
						if (!jQuery(this).is(".all-countries") && !jQuery(this).is(".active"))
							addCountry(jQuery(this).attr("country_id"));
					});				
				}
			});
			
			jQuery("#popup-window3 li").click(function(){
				if (!jQuery(this).is(".all-countries") && jQuery(this).is(".active"))
					removeCountry(jQuery(this).attr("country_id"));
				else if (!jQuery(this).is(".all-countries") && !jQuery(this).is(".active"))
					addCountry(jQuery(this).attr("country_id"));
			});
			
			jQuery("body").on("click", ".ksm-slidemodule-countries .countries li i", function(){
				var country_id=jQuery(this).parents("li").attr("country_id");
				removeCountry(country_id);
			});
			
			jQuery(".all-regions").click(function(){
				if (jQuery(this).is(".active"))
				{
					jQuery(this).removeClass("active");
					jQuery(this).parent().find("li").each(function(){
						if (!jQuery(this).is(".all-regions") && jQuery(this).is(".active"))
							removeRegion(jQuery(this).attr("region_id"));
					});
				}
				else
				{
					jQuery(this).addClass("active");
					jQuery(this).parent().find("li").each(function(){
						if (!jQuery(this).is(".all-regions") && !jQuery(this).is(".active"))
							addRegion(jQuery(this).attr("region_id"));
					});				
				}
			});
			
			jQuery("#popup-window4 .regions-row li").click(function(){
				if (!jQuery(this).is(".all-regions") && jQuery(this).is(".active"))
					removeRegion(jQuery(this).attr("region_id"));
				else if (!jQuery(this).is(".all-regions") && !jQuery(this).is(".active"))
					addRegion(jQuery(this).attr("region_id"));
			});
			
			jQuery("body").on("click", ".ksm-slidemodule-regions .regions li i", function(){
				var region_id=jQuery(this).parents("li").attr("region_id");
				removeRegion(region_id);
			});			
		
		});
		
		function addCountry(country_id)
		{
			var html="";
			var title=jQuery("#popup-window3 li[country_id="+country_id+"] span").text();
			html+="<li country_id="+country_id+">";
			html+="		<span>"+title+"</span>";
			html+="		<i></i>";
			html+="		<input type=\'hidden\' name=\'' . $this->name . '["+country_id+"][]\'>";
			html+="</li>";
			jQuery("#popup-window3 li[country_id="+country_id+"]").addClass("active");
			jQuery(".ksm-slidemodule-countries .countries .no-countries").hide();
			jQuery(".ksm-slidemodule-countries .countries ul").append(html);
			jQuery("#popup-window4 .no-countries").hide();
			jQuery("#popup-window4 .regions-row-"+country_id).show();			
		}
		
		function removeCountry(country_id)
		{
			jQuery(".ksm-slidemodule-countries .countries li[country_id="+country_id+"]").remove();
			if (jQuery(".ksm-slidemodule-countries .countries li").length==1)
				jQuery(".ksm-slidemodule-countries .countries .no-countries").show();
			jQuery("#popup-window3 li[country_id="+country_id+"]").removeClass("active");
			jQuery("#popup-window4 .regions-row-"+country_id+" li").each(function(){
				if (!jQuery(this).is(".all-regions") && jQuery(this).is(".active"))
					removeRegion(jQuery(this).attr("region_id"));
			});			
			jQuery("#popup-window4 .regions-row-"+country_id).hide();
			if (jQuery("#popup-window4 .regions-row:visible").length==0)
				jQuery("#popup-window4 .no-countries").show();
		}

		function addRegion(region_id)
		{
			var html="";
			var title=jQuery("#popup-window4 li[region_id="+region_id+"] span").text();
			var country_id=jQuery("#popup-window4 li[region_id="+region_id+"]").attr("country_id");
			html+="<li region_id="+region_id+">";
			html+="		<span>"+title+"</span>";
			html+="		<i></i>";
			html+="		<input type=\'hidden\' name=\'' . $this->name . '["+country_id+"][]\' value=\'"+region_id+"\'>";
			html+="</li>";
			jQuery("#popup-window4 li[region_id="+region_id+"]").addClass("active");
			jQuery(".ksm-slidemodule-regions .regions .no-regions").hide();
			jQuery(".ksm-slidemodule-regions .regions ul").append(html);
		}
		
		function removeRegion(region_id)
		{
			jQuery(".ksm-slidemodule-regions .regions li[region_id="+region_id+"]").remove();
			if (jQuery(".ksm-slidemodule-regions .regions li").length==1)
				jQuery(".ksm-slidemodule-regions .regions .no-regions").show();
			jQuery("#popup-window4 li[region_id="+region_id+"]").removeClass("active");
		}		
		';
		$document = JFactory::getDocument();
		$document->addScriptDeclaration($script);
		
		$res_html.= KSSystem::wrapFormField('slidemodule', $this->element, $html);
		
		
		return $res_html;
	}
}
