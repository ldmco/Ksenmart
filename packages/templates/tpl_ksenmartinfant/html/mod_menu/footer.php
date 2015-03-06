<?php
/**
 * @package		Joomla.Site
 * @subpackage	mod_menu
 * @copyright	Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

// Note. It is important to remove spaces between elements.
?>
<h4><?php echo $module->title?></h4>
<ul class="list-footer toggle_content clearfix <?php echo $class_sfx;?>"<?php
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
	
	$class = $item->anchor_css ? 'class="'.$item->anchor_css.'" ' : '';
	if ($item->id == $active_id) {
		$class = 'active';
	}
	
	if($item->deeper){
		$class .= ' dropdown'; 
	}
	
	

	if($item->deeper){
		echo '
			<li>
				<a class="dropdown-toggle" data-toggle="dropdown" href="'.$item->link.'" title="'.$item->title.'">'.$item->title.'</a>
				<ul class="dropdown-menu">
		';
	}
	
	if(!$item->deeper && !$item->parent && $item->parent_id == 1){
		echo '<li><a '.$class.'href="'.$item->link.'" title="'.$item->title.'"><i></i>'.$item->title.'</a></li>';
	}
	
	if($item->parent_id > 1){
		echo '
			<li><a href="'.$item->link.'" title="'.$item->title.'">'.$item->title.'</a></li>
		';
	}
	if($item->level_diff == 1){
		echo '</ul></li>';
	}
}
?></ul>

