<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<li>
<div class="km-list-left-module km-user-fields mod_ks_userfields">
	<div class="km-list-left-module-title">
		<label><?php echo JText::_('mod_ks_userfields_title')?></label>
		<a class="sh hides" href="#"></a>
		<a class="add km-modal" rel='{"x":"500","y":"150"}' href="<?php echo JRoute::_('index.php?option=com_ksen&view=users&layout=userfield&tmpl=component');?>"></a>
	</div>	
	<div class="km-list-left-module-content">
		<div class="lists">
			<div class="row-fluid">	
				<ul>
					<?php if (count($userfields)>0):?>
					<?php foreach($userfields as $userfield):?>
					<li>
						<label>
							<?php echo $userfield->title?>
							<input type="checkbox" value="<?php echo $userfield->id?>" name="userfields[]">
							<p class="actions">
								<a class="edit km-modal" rel='{"x":"500","y":"150"}' href="<?php echo JRoute::_('index.php?option=com_ksen&view=users&layout=userfield&id='.$userfield->id.'&tmpl=component');?>"><?php echo JText::_('ks_edit')?></a>
								<a class="delete" href="<?php echo JRoute::_('index.php?option=com_ksen&task=delete_module_item&model=users&item=userfield&id='.$userfield->id.'&tmpl=ksenmart');?>"><?php echo JText::_('ks_delete')?></a>
							</p>
						</label>
					</li>
					<?php endforeach;?>
					<?php else:?>
					<li>
						<label>
							<?php echo JText::_('mod_ks_userfields_no_items')?>
						</label>
					</li>					
					<?php endif;?>					
				</ul>
			</div>
		</div>	
	</div>	
</div>
</li>				