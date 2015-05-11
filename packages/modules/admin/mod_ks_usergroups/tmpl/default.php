<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<li>
<div class="km-list-left-module km-user-groups mod_ks_usergroups">
	<div class="km-list-left-module-title">
		<label><?php echo JText::_('mod_ks_usergroups_title')?></label>
		<a class="sh hides" href="#"></a>
		<a class="add km-modal" rel='{"x":"500","y":"90%"}' href="<?php echo JRoute::_('index.php?option=com_ksen&view=users&layout=usergroup&tmpl=component');?>"></a>
	</div>	
	<div class="km-list-left-module-content">
		<div class="lists">
			<div class="row-fluid">	
				<ul>
					<?php foreach($usergroups as $usergroup):?>
					<li class="<?php echo ($usergroup->selected?'active':'');?>">
						<label>
							<?php echo $usergroup->title?>
							<input type="checkbox" value="<?php echo $usergroup->id?>" name="usergroups[]" onclick="UserGroupsModule.setItem(this);" <?php echo ($usergroup->selected?'checked':'')?>>
							<p class="actions">
								<a class="edit km-modal" rel='{"x":"500","y":"90%"}' href="<?php echo JRoute::_('index.php?option=com_ksen&view=users&layout=usergroup&id='.$usergroup->id.'&tmpl=component');?>"><?php echo JText::_('ks_edit')?></a>
								<a class="delete" href="<?php echo JRoute::_('index.php?option=com_ksen&task=delete_module_item&model=users&item=usergroup&id='.$usergroup->id.'&tmpl=ksenmart');?>"><?php echo JText::_('ks_delete')?></a>
							</p>
						</label>
					</li>
					<?php endforeach;?>
				</ul>
				<input type="hidden" name="usergroups[]" value="">
			</div>
		</div>	
	</div>	
</div>
</li>				