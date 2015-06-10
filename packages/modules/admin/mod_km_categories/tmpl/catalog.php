<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<li>
<div class="km-list-left-module km-categories mod_km_categories">
	<div class="km-list-left-module-title">
		<label><?php echo JText::_('mod_km_categories_title')?></label>
		<a class="sh hides" href="#"></a>
		<a class="add km-modal" rel='{"x":"90%","y":"90%"}' href="<?php echo JRoute::_('index.php?option=com_ksenmart&view=catalog&layout=category&tmpl=component');?>"></a>
	</div>	
	<div class="km-list-left-module-content">
		<div class="lists">
			<div class="row-fluid">
				<ul>
					<?php if (count($categories)>0):?>
					<?php foreach($categories as $category):?>
					<li class="<?php echo $category->class;?>">
						<?php if ($category->deeper):?>
						<div>
						<?php endif;?>
						<label>
							<?php echo $category->title?>
							<input type="checkbox" value="<?php echo $category->id?>" name="categories[]" onclick="CategoriesModule.setItem(this);" <?php echo ($category->selected?'checked':'')?>>
							<p class="actions">
								<a class="edit km-modal" rel='{"x":"90%","y":"90%"}' href="<?php echo JRoute::_('index.php?option=com_ksenmart&view=catalog&layout=category&id='.$category->id.'&tmpl=component');?>"><?php echo JText::_('ksm_edit')?></a>
								<a class="delete" href="<?php echo JRoute::_('index.php?option=com_ksenmart&task=delete_module_item&model=catalog&item=category&id='.$category->id.'&tmpl=ksenmart');?>"><?php echo JText::_('ksm_delete')?></a>
							</p>
						</label>
						<?php if ( $category->deeper):?>
						<a href="#" class="sh <?php echo in_array($category->id, $path)?'hides':'show';?>"></a>
						<?php endif;?>
						<?php 
							if ($category->deeper) {
							echo '</div><ul class="'.(in_array($category->id, $path)?'opened':'').'">';
						}
						elseif ($category->shallower) {
							echo '</li>';
							echo str_repeat('</ul></li>', $category->level_diff);
						}
						else {
							echo '</li>';
						}
						?>
					<?php endforeach;?>
					<?php else:?>
					<li>
						<label>
							<?php echo JText::_('KS_CATEGORIES_NO_ITEMS')?>
						</label>
					</li>
					<?php endif;?>
				</ul>
				<input type="hidden" name="categories[]" value="">
			</div>
		</div>	
	</div>	
</div>
</li>