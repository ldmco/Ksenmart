<?php
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>
<div class="ksm-catalog-categories">
	<h3><?php echo JText::_('KSM_CATALOG_CATEGORIES_TITLE'); ?></h3>
	<div class="ksm-catalog-categories-block">
		<?php foreach ($this->categories as $category): ?>
			<div class="ksm-catalog-category">
				<div class="ksm-catalog-category-img">
					<a href="<?php echo $category->link; ?>" title="<?php echo $category->title; ?>">
						<img src="<?php echo $category->small_img; ?>">
					</a>
					<div class="ksm-catalog-category-name">
						<a href="<?php echo $category->link; ?>"><?php echo $category->title; ?></a>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</div>
