<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

class JFormFieldUserFields extends JFormField {
	
	protected $type = 'UserFields';
	
	public function getInput() {
		$db = JFactory::getDBO();
		$html = '';
		
		$query = $db->getQuery(true);
		$query->select('*')->from('#__ksen_user_fields')->order('ordering');
		$db->setQuery($query);
		$userfields = $db->loadObjectList();
		
		foreach ($userfields as $userfield) {
			$value = isset($this->value[$userfield->id]) ? $this->value[$userfield->id]->value : '';
			$html.= '<div class="row">';
			$html.= '	<label class="inputname">' . $userfield->title . '</label>';
			$html.= '	<input type="text" class="inputbox width360px" name="' . $this->name . '[' . $userfield->id . ']" value="' . $value . '">';
			$html.= '</div>';
		}
		
		return $html;
	}
}