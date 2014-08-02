<?php
/**
 * @package		Joomla.Site
 * @subpackage	mod_menu
 * @copyright	Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;
?>

<ul class="nav <?php echo $class_sfx;?>"<?php
	$tag = '';
	if ($params->get('tag_id')!=NULL) {
		$tag = $params->get('tag_id').'';
		echo ' id="'.$tag.'"';
	}
?>>
<?php
//print_r($list);
foreach ($list as $i => &$item) {
	$flink = $item->flink;
	$item->link = JFilterOutput::ampReplace(htmlspecialchars($flink));	
	
	$class = '';
	
	if ($item->id == $active_id) {
		$class = 'active';
	}
	
	if($item->deeper){
		$class .= ' dropdown'; 
	}
	
	$class = ' class="'.trim($class).'"';
	

	if($item->deeper){
		echo '
			<li'.$class.'>
				<a class="dropdown-toggle" data-toggle="dropdown" href="'.$item->link.'" title="'.$item->title.'">'.$item->title.'<b class="caret"></b></a>
				<ul class="dropdown-menu">
		';
	}
	
	if(!$item->deeper && !$item->parent && $item->parent_id == 1){
		echo '<li'.$class.'><a href="'.$item->link.'" title="'.$item->title.'">'.$item->title.'</a></li>';
	}
	
	if($item->parent_id > 1){
		echo '
			<li'.$class.'><a href="'.$item->link.'" title="'.$item->title.'">'.$item->title.'</a></li>
		';
	}
	if($item->level_diff == 1){
		echo '</ul></li>';
	}
}
?></ul>
