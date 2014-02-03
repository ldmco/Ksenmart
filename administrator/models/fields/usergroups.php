<?php 
defined( '_JEXEC' ) or die;

class JFormFieldUserGroups extends JFormField {

	protected $type = 'UserGroups';
	
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
			$html.='<li class="'.(in_array($usergroup->id,$this->value)?'active':'').'">';
			$html.='	<label>'.JText::_($usergroup->title).'<input onclick="setActive(this);" type="checkbox" name="'.$this->name.'[]" value="'.$usergroup->id.'" '.(in_array($usergroup->id,$this->value)?'checked':'').'></label>';
			$html.='</li>';
		}
		$html.='</ul>';
		
		return $html;
	}
}