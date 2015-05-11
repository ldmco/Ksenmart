<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<li>
	<div class="km-list-left-module ksenmart-settings-groups active">
		<div class="km-list-left-module-title ksenmart-settings-groups-title">
			<label><?php echo JText::_('MOD_KS_SETTINGS_GROUPS_TITLE')?></label>
			<a class="sh hides" href="#" onclick="shModuleContent(this,'.ksenmart-settings-groups-content');return false;"></a>
		</div>	
		<div class="km-list-left-module-content ksenmart-settings-groups-content">
			<ul>
				<?php foreach($forms as $name => $form):?>	
				<?php $fieldSets = $form->getFieldsets();?>
				<?php $countFS = count($fieldSets);?>
				<li id="settings-<?php echo $name;?>" class="settings-tab">
					<div>
						<label>
							<?php echo JText::_(strtoupper($ext_prefix) . '_' . strtoupper($name) . '_SETTINGS_TITLE');?>
						</label>
						<?php if ($countFS>1):?>
						<a href="#" onclick="shModuleChilds(this,this.parentNode.parentNode.getElementsByTagName('ul')[0]);return false;" class="sh show"></a>
						<?php endif;?>		
					</div>	
					<?php if ($countFS>1):?>
					<ul>
						<?php foreach ($fieldSets as $name => $fieldSet):?>
						<?php $label = empty($fieldSet->label) ? $name : $fieldSet->label;?>
						<li class="settings-sub-tab" id="settings-sub-<?php echo $name?>"><label><?php echo JText::_($label)?></label></li>
						<?php endforeach;?>
					</ul>		
					<?php endif;?>												
				</li>
				<?php endforeach;?>							
			</ul>
		</div>	
	</div>	
</li>
