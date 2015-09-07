<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

// Note. It is important to remove spaces between elements.
?>
<div class="accordion catalog-menu ksm-categories <?php echo $class_sfx?>" id="dropdownCat">
	<h3><?php echo $module->title?></h3>
	<ul class="nav nav-list menu-list-1">
	<?php
	foreach ($list as $i => &$item) :
		$class = 'ksenmart-categories-item';
		if ($item->id == $active_id) {
			$class .= ' current';
		}

		if (in_array($item->id, $path)) {
			$class .= ' active';
		}

		if ($item->deeper) {
			$class .= ' deeper in accordion-heading';
		}

		if (!empty($class)) {
			$class = ' class="'.trim($class) .'"';
		}

		echo '<li'.$class.'>';

		require JModuleHelper::getLayoutPath('mod_km_categories', 'default_url');

		if ($item->deeper) {
			$class = 'nav nav-list accordion-inner menu-list-'.($item->level+1);
			if (!in_array($item->id, $path)) {
				$class .= ' hide';
			}
			echo '<ul class="'.$class.'">';
		}
		elseif ($item->shallower) {
			echo '</li>';
			echo str_repeat('</ul></li>', $item->level_diff);
		}
		else {
			echo '</li>';
		}
	endforeach;
	?>
	</ul>
</div>