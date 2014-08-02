<?php
defined( '_JEXEC' ) or die;
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
					<?php foreach($types as $type):?>
					<li class="<?php echo ($type->selected?'active':'');?>">
						<label>
							<?php echo JText::_('ksm_exportimport_'.$type->name);?>
							<input type="radio" value="<?php echo $type->name?>" onclick="setExportImportType(this);" name="type" <?php echo ($type->selected?'checked':'')?>>
						</label>
					</li>
					<?php endforeach;?>
				</ul>
			</div>
		</div>	
	</div>	
</div>
</li>				