<?php
defined('_JEXEC') or die('Restricted access');

if (!class_exists('KMPlugin')) {
	require(JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_ksenmart' . DS . 'classes' . DS . 'kmplugin.php');
}

/**
 * Class plgKMPluginsPropertypositions
 */
class plgKMPluginsPropertypositions extends KMPlugin {

	/**
	 * @var array
	 */
	private $_properties = array();
	/**
	 * @var array
	 */
	private $_top_text_properties = array();
	/**
	 * @var array
	 */
	private $_bottom_text_properties = array();
	/**
	 * @var array
	 */
	private $_price_text_properties = array();
	/**
	 * @var array
	 */
	private $_bottom_image_properties = array();
	/**
	 * @var array
	 */
	private $_features_properties = array();

	/**
	 * plgKMPluginsPropertypositions constructor.
	 *
	 * @param object $subject
	 * @param array  $config
	 */
	function __construct(&$subject, $config) {
		parent::__construct($subject, $config);
	}

	/**
	 * @param $form
	 * @param $instance
	 */
	public function onAfterGetKSMFormProperty(&$form, $instance) {
		$position_xml   = '
			<field
				type="list"
				name="position" 
				class="sel"
				labelclass="inputname"
				label="KSM_PLUGIN_PROPERTYPOSITIONS_POSITION_LBL"
				description="KSM_PLUGIN_PROPERTYPOSITIONS_POSITION_DESC">
				<option value="top_text">KSM_PLUGIN_PROPERTYPOSITIONS_TOP_TEXT</option>
				<option value="bottom_text">KSM_PLUGIN_PROPERTYPOSITIONS_BOTTOM_TEXT</option>
				<option value="price">KSM_PLUGIN_PROPERTYPOSITIONS_PRICE</option>
				<option value="bottom_image">KSM_PLUGIN_PROPERTYPOSITIONS_BOTTOM_IMAGE</option>
				<option value="features">KSM_PLUGIN_PROPERTYPOSITIONS_FEATURES</option>
				<option value="none">KSM_PLUGIN_PROPERTYPOSITIONS_NONE</option>
			</field>
		';
		$action_element = new SimpleXMLElement($position_xml);
		$instance->setField($action_element);
	}

	/**
	 * @param $form
	 * @param $field_name
	 * @param $html
	 */
	public function onAfterGetKSMFormInputPropertyDefault(&$form, &$field_name, &$html = '') {
		$html .= '</div>';
		$html .= '<div class="row">';
		$html .= $form->getLabel('position');
		$html .= $form->getInput('position');
	}

	/**
	 * @param $model
	 * @param $product
	 */
	function onAfterExecuteKSMProductgetProduct($model, $product) {
		$this->_properties = $product->properties;
		foreach ($this->_properties as $property) {
			switch ($property->position) {
				case 'top_text':
					$this->_top_text_properties[] = $property;
					break;
				case 'bottom_text':
					$this->_bottom_text_properties[] = $property;
					break;
				case 'price':
					$this->_price_text_properties[] = $property;
					break;
				case 'bottom_image':
					$this->_bottom_image_properties[] = $property;
					break;
				case 'features':
					$this->_features_properties[] = $property;
					break;
			}
		}
	}

	/**
	 * @param null   $view
	 * @param null   $tmpl
	 * @param string $html
	 */
	function onAfterDisplayKSMproductproduct_info($view = null, $tmpl = null, &$html = '') {
		if ($this->_top_text_properties) {
			$product             = $view->product;
			$product->properties = $this->_top_text_properties;
			$property_html       = KSSystem::loadTemplate(array('product' => $product, 'check_property' => true), 'product', 'product', 'properties');
			$html                = $property_html . $html;
			$product->properties = $this->_properties;
		}
	}

	/**
	 * @param null   $view
	 * @param null   $tmpl
	 * @param string $html
	 */
	function onBeforeDisplayKSMproductproduct_properties($view = null, $tmpl = null, &$html = '') {
		if (isset($view->check_property) && $view->check_property) return;
		$view->product->properties = $this->_bottom_text_properties;
	}

	function onAfterDisplayKSMproductproduct_properties($view) {
		$view->product->properties = $this->_properties;
	}

	/**
	 * @param null   $view
	 * @param null   $tmpl
	 * @param string $html
	 */
	function onAfterDisplayKSMproductproduct_prices($view = null, $tmpl = null, &$html = '') {
		if ($this->_price_text_properties) {
			$product             = $view->product;
			$product->properties = $this->_price_text_properties;
			$property_html       = KSSystem::loadTemplate(array('product' => $product, 'check_property' => true), 'product', 'product', 'properties');
			$html                = $this->str_replace_once('>', '>' . $property_html, $html);
			$document            = JFactory::getDocument();
			$document->addScript('/plugins/' . $this->_type . '/' . $this->_name . '/assets/js/default.js');
			$product->properties = $this->_properties;
		}
	}

	/**
	 * @param null   $view
	 * @param null   $tmpl
	 * @param string $html
	 */
	function onAfterDisplayKSMproductproduct_gallery($view = null, $tmpl = null, &$html = '') {
		if ($this->_bottom_image_properties) {
			$product                   = $view->product;
			$product->properties       = $this->_bottom_image_properties;
			$product->properties_title = true;
			$property_html             = KSSystem::loadTemplate(array('product' => $product, 'check_property' => true), 'product', 'product', 'properties');
			$product->properties_title = false;
			$view->check_property      = false;
			$html .= $property_html;
			$product->properties = $this->_properties;
		}
	}

	function onAfterDisplayKSMproductproduct_tabs_nav($view = null, $tmpl = null, &$html = '') {
		if ($this->_features_properties) {
			$html .= KSSystem::loadPluginTemplate($this->_name, $this->_type, $view, 'nav_tab');
		}
	}

	function onAfterDisplayKSMproductproduct_tabs_contents($view = null, $tmpl = null, &$html = '') {
		if ($this->_features_properties) {
			$product             = $view->product;
			$product->properties = $this->_features_properties;
			$property_html       = KSSystem::loadTemplate(array('product' => $product, 'check_property' => true), 'product', 'product', 'properties');
			$view->property_html = $property_html;
			$html .= KSSystem::loadPluginTemplate($this->_name, $this->_type, $view, 'nav_tab_content');
			$view->check_property = false;
			$product->properties  = $this->_properties;
		}
	}

	function str_replace_once($search, $replace, $text) {
		$pos = strpos($text, $search);

		return $pos !== false ? substr_replace($text, $replace, $pos, strlen($search)) : $text;
	}

}