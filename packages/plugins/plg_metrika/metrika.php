<?php defined('_JEXEC') or die('Restricted access');

if (!class_exists('KMPlugin')) {
    require (JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_ksenmart' . DS . 'classes' . DS . 'kmplugin.php');
}

class plgKMPluginsMetrika extends KMPlugin {
    
	function __construct(&$subject, $config) {
		parent::__construct($subject, $config);
	}
	
	function onAfterGetKSMFormProduct($form, $instance) {
		$metrika_xml = '
			<field
				name="metrika_watch"
				type="text"
				default=""
				label="KSM_PLUGIN_METRIKA_WATCH_LBL"
				description ="KSM_PLUGIN_METRIKA_WATCH_DESC"
				class="inputbox"
				labelclass="inputname"
			/>
		';
		$metrika_element = new JXMLElement($metrika_xml);
		$instance->setField($metrika_element);
		$metrika_xml = '
			<field
				name="metrika_cart"
				type="text"
				default=""
				label="KSM_PLUGIN_METRIKA_CART_LBL"
				description ="KSM_PLUGIN_METRIKA_CART_DESC"
				class="inputbox"
				labelclass="inputname"
			/>
		';
		$metrika_element = new JXMLElement($metrika_xml);
		$instance->setField($metrika_element);
		$metrika_xml = '
			<field
				name="metrika_spy_price"
				type="text"
				default=""
				label="KSM_PLUGIN_METRIKA_PRICE_LBL"
				description ="KSM_PLUGIN_METRIKA_PRICE_DESC"
				class="inputbox"
				labelclass="inputname"
			/>
		';
		$metrika_element = new JXMLElement($metrika_xml);
		$instance->setField($metrika_element);
		
		return true;
	}
	
	function onAfterGetKSMFormInputProductProduct_code($form, &$field_name, &$html) {
		$html.= '<span class="linka metrika_link" rel="metrika">';
		$html.= '<a>' . JText::_('KSM_PLUGIN_METRIKA_LINK') . '</a>';
		$html.= '</span>';
		$html.= '</div>';
		$html.= '<div class="row metrika" style="display: none">';
		$html.= 	$form->getLabel('metrika_watch');
		$html.= 	$form->getInput('metrika_watch');
		$html.= '</div>';
		$html.= '<div class="row metrika" style="display: none">';
		$html.= 	$form->getLabel('metrika_cart');
		$html.= 	$form->getInput('metrika_cart');
		$html.= '</div>';
		$html.= '<div class="row metrika" style="display: none">';
		$html.= 	$form->getLabel('metrika_spy_price');
		$html.= 	$form->getInput('metrika_spy_price');
		$data = $form->getData()->jsonSerialize();
		$title = $data->title;
		$alias = $data->alias;
		if(empty($alias)){
			if(!empty($title)){
				$gen = KSFunctions::GenAlias($title);
				$metrika_cart = $gen . '_cart_' . time();		
				$metrika_spy_price = $gen . '_price_' . time();		
				$metrika_watch = $gen . '_watch_' . time();		
			} else {
				$metrika_cart = 'cart_' . time();
				$metrika_spy_price = 'price_' . time();
				$metrika_watch = 'watch_' . time();
			}
		} else {
			$metrika_cart = $alias . '_cart_' . time();
			$metrika_spy_price = $alias . '_price_' . time();
			$metrika_watch = $alias . '_watch_' . time();
		}
		$js = "
		jQuery(document).ready(function(){
			var metrika_cart = '" . $metrika_cart . "'
			var metrika_spy_price = '" . $metrika_spy_price . "'
			var metrika_watch = '" . $metrika_watch . "'
			jQuery('.metrika_link').on('click', function(){
				var cur_metrika_cart = jQuery('#jform_metrika_cart').val();
				if(cur_metrika_cart==''){
					jQuery('#jform_metrika_cart').val(metrika_cart);
				}
				var cur_metrika_spy_price = jQuery('#jform_metrika_spy_price').val();
				if(cur_metrika_spy_price==''){
					jQuery('#jform_metrika_spy_price').val(metrika_spy_price);
				}
				var cur_metrika_watch = jQuery('#jform_metrika_watch').val();
				if(cur_metrika_watch==''){
					jQuery('#jform_metrika_watch').val(metrika_watch);
				}
				
				return true;
			});
		});";
		$document = JFactory::getDocument();
		$document->addScriptDeclaration($js);
		
		return true;
	}
	
	/*function onAfterExecuteKSMCataloggetProduct($model, &$data){
		$data->metrika_cart = !empty($data->metrika_cart) ? $data->metrika_cart : $data->alias . '_cart';
		$data->metrika_spy_price = !empty($data->metrika_spy_price) ? $data->metrika_spy_price : $data->alias . '_price';
		
		return true;
	}*/
	
	function onAfterGetKSMFormChild($form, $instance) {
		$metrika_xml = '
			<field
				name="metrika_watch"
				type="text"
				default=""
				label="KSM_PLUGIN_METRIKA_WATCH_LBL"
				description ="KSM_PLUGIN_METRIKA_WATCH_DESC"
				class="inputbox"
				labelclass="inputname"
			/>
		';
		$metrika_element = new JXMLElement($metrika_xml);
		$instance->setField($metrika_element);
		$metrika_xml = '
			<field
				name="metrika_cart"
				type="text"
				default=""
				label="KSM_PLUGIN_METRIKA_CART_LBL"
				description ="KSM_PLUGIN_METRIKA_CART_DESC"
				class="inputbox"
				labelclass="inputname"
			/>
		';
		$metrika_element = new JXMLElement($metrika_xml);
		$instance->setField($metrika_element);
		$metrika_xml = '
			<field
				name="metrika_spy_price"
				type="text"
				default=""
				label="KSM_PLUGIN_METRIKA_PRICE_LBL"
				description ="KSM_PLUGIN_METRIKA_PRICE_DESC"
				class="inputbox"
				labelclass="inputname"
			/>
		';
		$metrika_element = new JXMLElement($metrika_xml);
		$instance->setField($metrika_element);
		
		return true;
	}
	
	function onBeforeGetKSMFormInputChildTags($form, $field_name, $html) {
		$html.= '<span class="linka metrika_link" rel="metrika">';
		$html.= '<a>' . JText::_('KSM_PLUGIN_METRIKA_LINK') . '</a>';
		$html.= '</span>';
		$html.= '</div>';
		$html.= '<div class="row metrika" style="display: none">';
		$html.= 	$form->getLabel('metrika_watch');
		$html.= 	$form->getInput('metrika_watch');
		$html.= '</div>';
		$html.= '<div class="row metrika" style="display: none">';
		$html.= 	$form->getLabel('metrika_cart');
		$html.= 	$form->getInput('metrika_cart');
		$html.= '</div>';
		$html.= '<div class="row metrika" style="display: none">';
		$html.= 	$form->getLabel('metrika_spy_price');
		$html.= 	$form->getInput('metrika_spy_price');
		$data = $form->getData()->jsonSerialize();
		$title = $data->title;
		$alias = $data->alias;
		if(empty($alias)){
			if(!empty($title)){
				$gen = KSFunctions::GenAlias($title);
				$metrika_cart = $gen . '_cart_' . time();		
				$metrika_spy_price = $gen . '_price_' . time();		
				$metrika_watch = $gen . '_watch_' . time();		
			} else {
				$metrika_cart = 'cart_' . time();
				$metrika_spy_price = 'price_' . time();
				$metrika_watch = 'watch_' . time();
			}
		} else {
			$metrika_cart = $alias . '_cart_' . time();
			$metrika_spy_price = $alias . '_price_' . time();
			$metrika_watch = $alias . '_watch_' . time();
		}
		$js = "
		jQuery(document).ready(function(){
			var metrika_cart = '" . $metrika_cart . "'
			var metrika_spy_price = '" . $metrika_spy_price . "'
			var metrika_watch = '" . $metrika_watch . "'
			jQuery('.metrika_link').on('click', function(){
				var cur_metrika_cart = jQuery('#jform_metrika_cart').val();
				if(cur_metrika_cart==''){
					jQuery('#jform_metrika_cart').val(metrika_cart);
				}
				var cur_metrika_spy_price = jQuery('#jform_metrika_spy_price').val();
				if(cur_metrika_spy_price==''){
					jQuery('#jform_metrika_spy_price').val(metrika_spy_price);
				}
				var cur_metrika_watch = jQuery('#jform_metrika_watch').val();
				if(cur_metrika_watch==''){
					jQuery('#jform_metrika_watch').val(metrika_watch);
				}
				
				return true;
			});
		});";
		$document = JFactory::getDocument();
		$document->addScriptDeclaration($js);
		
		return true;
	}
	
	/*function onAfterExecuteKSMCataloggetChild($model, &$data){
		$data->metrika_cart = !empty($data->metrika_cart) ? $data->metrika_cart : $data->alias . '_cart';
		$data->metrika_spy_price = !empty($data->metrika_spy_price) ? $data->metrika_spy_price : $data->alias . '_price';
		
		return true;
	}*/
	
	function onAfterGetKSMFormCategory($form, $instance) {
		$metrika_xml = '
			<field
				name="metrika_watch"
				type="text"
				default=""
				label="KSM_PLUGIN_METRIKA_WATCH_LBL"
				description ="KSM_PLUGIN_METRIKA_WATCH_DESC"
				class="inputbox"
				labelclass="inputname"
			/>
		';
		$metrika_element = new JXMLElement($metrika_xml);
		$instance->setField($metrika_element);
		
		return true;
	}
	
	function onAfterGetKSMFormInputCategoryChilds_title($form, &$field_name, &$html) {
		$html.= '<span class="linka metrika_link" rel="metrika">';
		$html.= '<a>' . JText::_('KSM_PLUGIN_METRIKA_LINK') . '</a>';
		$html.= '</span>';
		$html.= '</div>';
		$html.= '<div class="row metrika" style="display: none">';
		$html.= 	$form->getLabel('metrika_watch');
		$html.= 	$form->getInput('metrika_watch');
		$data = $form->getData()->jsonSerialize();
		$title = $data->title;
		$alias = $data->alias;
		if(empty($alias)){
			if(!empty($title)){
				$gen = KSFunctions::GenAlias($title);
				$metrika_watch = $gen . '_watch_' . time();		
			} else {
				$metrika_watch = 'watch_' . time();
			}
		} else {
			$metrika_watch = $alias . '_watch_' . time();
		}
		$js = "
		jQuery(document).ready(function(){
			var metrika_watch = '" . $metrika_watch . "'
			jQuery('.metrika_link').on('click', function(){
				var cur_metrika_watch = jQuery('#jform_metrika_watch').val();
				if(cur_metrika_watch==''){
					jQuery('#jform_metrika_watch').val(metrika_watch);
				}
				
				return true;
			});
		});";
		$document = JFactory::getDocument();
		$document->addScriptDeclaration($js);
		
		return true;
	}
	
	function onAfterGetKSMFormManufacturer($form, $instance) {
		$metrika_xml = '
			<field
				name="metrika_watch"
				type="text"
				default=""
				label="KSM_PLUGIN_METRIKA_WATCH_LBL"
				description ="KSM_PLUGIN_METRIKA_WATCH_DESC"
				class="inputbox"
				labelclass="inputname"
			/>
		';
		$metrika_element = new JXMLElement($metrika_xml);
		$instance->setField($metrika_element);
		
		return true;
	}
	
	function onAfterGetKSMFormInputManufacturerAlias($form, &$field_name, &$html) {
		$html.= '</div>';
		$html.= '<div class="row">';
		$html.= '<span class="linka metrika_link" rel="metrika">';
		$html.= '<a>' . JText::_('KSM_PLUGIN_METRIKA_LINK') . '</a>';
		$html.= '</span>';
		$html.= '</div>';
		$html.= '<div class="row metrika" style="display: none">';
		$html.= 	$form->getLabel('metrika_watch');
		$html.= 	$form->getInput('metrika_watch');
		$data = $form->getData()->jsonSerialize();
		$title = $data->title;
		$alias = $data->alias;
		if(empty($alias)){
			if(!empty($title)){
				$gen = KSFunctions::GenAlias($title);
				$metrika_watch = $gen . '_watch_' . time();		
			} else {
				$metrika_watch = 'watch_' . time();
			}
		} else {
			$metrika_watch = $alias . '_watch_' . time();
		}
		$js = "
		jQuery(document).ready(function(){
			var metrika_watch = '" . $metrika_watch . "'
			jQuery('.metrika_link').on('click', function(){
				var cur_metrika_watch = jQuery('#jform_metrika_watch').val();
				if(cur_metrika_watch==''){
					jQuery('#jform_metrika_watch').val(metrika_watch);
				}
				
				return true;
			});
		});";
		$document = JFactory::getDocument();
		$document->addScriptDeclaration($js);
		
		return true;
	}
	
	function onAfterExecuteKSMCataloggetCategory($model, &$category){
		if(isset($category->id) && !empty($category->id) && !isset($category->metrika_watch)){
			$db = JFactory::getDBO();
			$query = $db->getQuery(true);
			$query->select('metrika_watch')->from('#__ksenmart_categories')->where('id=' . $category->id);
			$db->setQuery($query);
			$metrika_watch = $db->loadResult();
			$category->metrika_watch = $metrika_watch;
		}
		
		return true;
	}
	
	function onAfterDisplayKSMCatalogCategory($view, &$tpl, &$html){
		if (empty($view->category->metrika_watch))
			return false;
		$yaCounter = $this->params->get('yaCounter', null);
		if(!empty($view->category->metrika_watch))
			$html .= '<script>yaCounter' . $yaCounter . '.reachGoal(\'' . $view->category->metrika_watch . '\');</script>';
		
		return true;
	}
	
	function onAfterExecuteKSMCataloggetManufacturer($model, &$manufacturer){
		if(isset($manufacturer->id) && !empty($manufacturer->id)){
			$db = JFactory::getDBO();
			$query = $db->getQuery(true);
			$query->select('metrika_watch')->from('#__ksenmart_manufacturers')->where('id=' . $manufacturer->id);
			$db->setQuery($query);
			$metrika_watch = $db->loadResult();
			$manufacturer->metrika_watch = $metrika_watch;
		}
		
		return true;
	}
	
	function onAfterDisplayKSMCatalogManufacturer($view, &$tpl, &$html){
		if (empty($view->manufacturer->metrika_watch))
			return false;
		$yaCounter = $this->params->get('yaCounter', null);
		if(!empty($view->manufacturer->metrika_watch))
			$html .= '<script>yaCounter' . $yaCounter . '.reachGoal(\'' . $view->manufacturer->metrika_watch . '\');</script>';
		
		return true;
	}
	
	function onAfterDisplayKSMProductProduct($view, &$tpl, &$html){
		if (empty($view->product->metrika_watch))
			return false;
		$yaCounter = $this->params->get('yaCounter', null);
		if(!empty($view->product->metrika_watch))
			$html .= '<script>yaCounter' . $yaCounter . '.reachGoal(\'' . $view->product->metrika_watch . '\');</script>';
		
		return true;
	}
	
	function onAfterDisplayKSMProductProduct_prices($view, &$tpl, &$html){
		if (empty($view->product->metrika_cart) && empty($view->product->metrika_spy_price))
			return false;
		$yaCounter = $this->params->get('yaCounter', null);
		if(!empty($view->product->metrika_cart))
			$html = str_replace('type="submit"','type="submit" onclick="yaCounter' . $yaCounter . '.reachGoal(\'' . $view->product->metrika_cart . '\'); return true;"',$html);
		if(!empty($view->product->metrika_spy_price))
			$html = str_replace('class="spy_price','onclick="yaCounter' . $yaCounter . '.reachGoal(\'' . $view->product->metrika_spy_price . '\'); return true;" class="spy_price',$html);
		
		return true;
	}
	
	function onAfterDisplayKSMCatalogDefault_item($view, &$tpl, &$html){
		if (empty($view->product->metrika_cart))
			return false;
		$yaCounter = $this->params->get('yaCounter', null);
		$html = str_replace('type="submit"','type="submit" onclick="yaCounter' . $yaCounter . '.reachGoal(\'' . $view->product->metrika_cart . '\'); return true;"',$html);
		
		return true;
	}
	
	function onAfterDisplayKSMparamscategory_item($view, &$tpl, &$html){
		if (empty($view->product->metrika_cart))
			return false;
		$yaCounter = $this->params->get('yaCounter', null);
		$html = str_replace('type="submit"','type="submit" onclick="yaCounter' . $yaCounter . '.reachGoal(\'' . $view->product->metrika_cart . '\'); return true;"',$html);
		
		return true;
	}
	
}