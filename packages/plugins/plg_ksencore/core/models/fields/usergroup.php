<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

class JFormFieldUserGroup extends JFormField {

	protected $type = 'UserGroup';
	
	public function getInput(){
		$db=JFactory::getDBO();
		$query=$db->getQuery(true);
		$query->select('*')->from('#__usergroups');
		$db->setQuery($query);
		$usergroups=$db->loadObjectList();
		
		$html='';
		$html.='<ul>';
		foreach($usergroups as $usergroup)
		{
			$html.='<li class="'.($usergroup->id==$this->value?'active':'').'">';
			$html.='	<label>'.JText::_($usergroup->title).'<input onclick="setActiveOne(this);" type="radio" name="'.$this->name.'" value="'.$usergroup->id.'" '.($usergroup->id==$this->value?'checked':'').' /></label>';
			$html.='</li>';
		}
		$html.='</ul>';
		
		return $html;
	}
}