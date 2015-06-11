<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<li>
<div class="km-list-left-module km-reports mod_km_reports">
	<div class="km-list-left-module-title">
		<label><?php echo JText::_('mod_km_reports_title')?></label>
		<a class="sh hides" href="#"></a>
	</div>	
	<div class="km-list-left-module-content">
		<div class="lists">
			<div class="row-fluid">	
				<ul>
					<?php foreach($reports as $report):?>
					<li class="<?php echo ($report->selected?'active':'');?>">
						<label>
							<?php echo JText::_('ksm_reports_'.$report->name);?>
							<input type="radio" value="<?php echo $report->name?>" onclick="setReport(this);" name="report" <?php echo ($report->selected?'checked':'')?>>
						</label>
					</li>
					<?php endforeach;?>
				</ul>
			</div>
		</div>	
	</div>	
</div>
</li>				