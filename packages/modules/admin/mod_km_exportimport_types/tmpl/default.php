<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<li>
<div class="km-list-left-module mod_km_exportimport_types">
	<div class="km-list-left-module-title">
		<label><?php echo JText::_('mod_km_exportimport_types_title')?></label>
		<a class="sh hides" href="#"></a>
	</div>	
	<div class="km-list-left-module-content">
		<div class="lists">
			<div class="row-fluid">	
				<ul>
					<?php if (count($types)>0):?>
					<?php foreach($types as $type):?>
					<li class="<?php echo ($type->selected?'active':'');?>">
						<label>
							<?php echo JText::_($type->name);?>
							<input type="radio" value="<?php echo $type->element?>" onclick="setExportImportType(this);" name="type" <?php echo ($type->selected?'checked':'')?>>
						</label>
					</li>
					<?php endforeach;?>
					<?php else:?>
					<li>
						<label>
							<?php echo JText::_('mod_km_exportimport_types_no_items')?>
						</label>
					</li>					
					<?php endif;?>					
				</ul>
			</div>
		</div>	
	</div>	
</div>
</li>				