<?php
defined('JPATH_PLATFORM') or die;

abstract class JHtmlKMCategories
{

	function options($parent=0)
	{
		$items=JHtmlKMCategories::getCategories($parent,0,array());
		return $items;
	}
	
	function getCategories($parent,$level,$items)
	{
		$db = JFactory::getDbo();
		$query="select * from #__ksenmart_categories where published='1' and parent='$parent' order by ordering";
		$db->setQuery($query);
		$cats = $db->loadObjectList();
		foreach ($cats as $cat)
		{
			$cat->title = str_repeat('- ', $level) . $cat->title;
			$items[] = JHtml::_('select.option', $cat->id, $cat->title);
			$items=JHtmlKMCategories::getCategories($cat->id,$level+1,$items);
		}	
		return $items;	
	}
	
}
