<?php defined( '_JEXEC' ) or die; ?>
<li>
<div class="km-list-left-module km-user-groups mod_km_usergroups">
	<div class="km-list-left-module-title">
		<label><?php echo JText::_('mod_km_usergroups_title')?></label>
		<a class="sh hides" href="#"></a>
	</div>	
	<div class="km-list-left-module-content">
		<div class="lists">
			<div class="row">	
				<ul>
					<?php foreach($usergroups as $usergroup):?>
					<li class="<?php echo ($usergroup->selected?'active':'');?>">
						<label>
							<?php echo $usergroup->title?>
							<input type="checkbox" value="<?php echo $usergroup->id?>" name="usergroups[]" onclick="UserGroupsModule.setItem(this);" <?php echo ($usergroup->selected?'checked':'')?>>
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