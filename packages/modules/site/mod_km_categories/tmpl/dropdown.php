<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

// Note. It is important to remove spaces between elements.
$activeParentId = JRequest::getVar('categories', null);
//print_r($list);
$activeParentId = $activeParentId[0];
?>
<div class="accordion <?php echo $class_sfx?>" id="dropdownCat">
	<?php if($module->showtitle){ ?>
	<h3><?php echo $module->title; ?></h3>
	<?php } ?>
	<?php
	foreach ($list as $i => &$item) {
		$class = '';
		$collapsePrent = '';
		if ($item->id == $active_id) {
			$class .= ' active';
		}
		
		if ($activeParentId == $item->id) {
			$collapsePrent = ' in';
		}
		
		if ($item->deeper && isset($active_id)) {
			$collapse = '';
			foreach($item->children as $children) {
				//echo $children->id;
				if ($active_id == $children->id) {
					$collapse = ' in';
				}
			}
		}

		if (!empty($class)) {
			$class = ' class="'.trim($class) .'"';
		}
		
		if ($item->deeper) {
			echo '<div class="accordion-group">';
		}
		
		if ($item->deeper) {
			echo '
				<div class="accordion-heading">';
			echo '<a class="accordion-toggle" data-toggle="collapse" data-parent="#dropdownCat" href="#collapse_'.htmlspecialchars($item->id).'">'.htmlspecialchars($item->title).'<b class="caret pull-right"></b></a>';
			echo '</div>
					<div id="collapse_'.$item->id.'" class="accordion-body collapse'.$collapse.$collapsePrent.'">
					  <div class="accordion-inner">
						<ul class="nav nav-list">
			';
		}
		
		if(!$item->deeper AND $item->parent_id != 0) {
			echo '<li'.$class.'><a level="'.$item->level.'" class="ksenmart-categories-item-link" href="'.$item->link.'">'.htmlspecialchars($item->title).'</a></li>';
		}
		
		if(!$item->parent_id AND !$item->deeper) {
			echo '<ul class="nav nav-list">';
			echo '<li'.$class.'><a level="'.$item->level.'" class="ksenmart-categories-item-link" href="'.$item->link.'">'.htmlspecialchars($item->title).'</a></li>';
			echo '</ul>';
		}
		
		if($item->shallower) {
			echo '</ul>
				</div>
			</div>';
			echo '</div>';
		}
	}
	?>
</div>