<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

class JFormFieldUserActions extends JFormField {
	
	protected $type = 'UserActions';
	
	public function getInput() {
		$dispatcher = JDispatcher::getInstance();
		JPluginHelper::importPlugin('kmdiscountactions');
		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('name,element')->from('#__extensions')->where('folder="kmdiscountactions"')->where('enabled=1');
		$db->setQuery($query);
		$plugins = $db->loadObjectList('element');
		
		$html = '';
		if (count($plugins)) {
			$html.= '<div class="lists">';
			$html.= '	<div class="row">';
			$html.= '	 	<ul class="actions-ul">';
			
			foreach ($this->value as $type => $params) {
				$results = $dispatcher->trigger('onDisplayParamsForm', array(
					$type,
					$params
				));
				if (isset($results[0]) && $results[0]) $html.= $results[0];
			}
			$html.= '	 	</ul>';
			$html.= '	</div>';
			$html.= '	<div class="row">';
			$html.= '	 	<a href="#" class="add" id="add-action">' . JText::_('ksm_add') . '</a>';
			$html.= '	</div>';
			$html.= '</div>';
			$html.= '<div id="popup-window5" class="popup-window">';
			$html.= '	<div style="width: 460px;height: 340px;margin-left: -230px;">';
			$html.= '		<div class="popup-window-inner">';
			$html.= '			<div class="heading">';
			$html.= '				<h3>' . JText::_('ksm_discount_actions_lbl') . '</h3>';
			$html.= '				<div class="save-close">';
			$html.= '					<button class="close" onclick="return false;"></button>';
			$html.= '				</div>';
			$html.= '			</div>';
			$html.= '			<div class="contents">';
			$html.= '				<div class="contents-inner">';
			$html.= '					<div class="slide_module">';
			$html.= '						<div class="row">';
			$html.= '							<ul>';
			
			foreach ($plugins as $plugin) {
				$html.= '							<li class="' . ($plugin->element == $this->value ? 'active' : '') . '">';
				$html.= '								<label onclick="addDiscountAction(this);" rel="' . $plugin->element . '">' . JText::_($plugin->name) . '</label>';
				$html.= '							</li>';
			}
			$html.= '							</ul>';
			$html.= '						</div>';
			$html.= '					</div>';
			$html.= '				</div>';
			$html.= '			</div>';
			$html.= '		</div>';
			$html.= '	</div>';
			$html.= '</div>';
		}
		
		return $html;
	}
}
