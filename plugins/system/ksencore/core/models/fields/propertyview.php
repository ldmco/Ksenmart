<?php defined('_JEXEC') or die;

class JFormFieldPropertyView extends JFormField {
	protected $type = 'PropertyView';
	private $views = array(
		'select',
		'checkbox',
		'radio'
	);
	
	public function getInput() {
		$html = '<ul>';
		
		foreach ($this->views as $view) {
			$checked = '';
			$active = '';
			if ($this->value == $view) {
				$checked = ' checked="checked" ';
				$active = ' active ';
			}
			$html.= '<li class="' . $active . '">';
			$html.= '<label>' . JText::_('ksm_properties_propertyview_' . $view) . '<input type="radio" ' . $checked . ' value="' . $view . '" name="' . $this->name . '" onclick="setActiveOneRequired(this);" /></label>';
			$html.= '</li>';
		}
		$html.= '</ul>';
		
		return $html;
	}
}