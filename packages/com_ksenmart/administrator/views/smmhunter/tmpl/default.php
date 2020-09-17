<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<div class="clearfix panel">
    <div class="pull-left">
        <?php echo KSSystem::loadModules('ks-top-left'); ?>
    </div>
    <div class="pull-right">
        <?php echo KSSystem::loadModules('ks-top-right'); ?>
    </div>
    <div class="row-fluid">
        <?php echo KSSystem::loadModules('ks-top-bottom'); ?>
    </div>
</div>
<div id="center">
	<table id="cat" width="100%">
		<tr>
			<td valign="top">
				<div class="ks-smmhunter">
					<?php if ($this->form): ?>
						<?php echo $this->loadTemplate('form'); ?>
					<?php else: ?>
						<?php echo $this->loadTemplate('data'); ?>
					<?php endif; ?>
				</div>
			</td>	
		</tr>	
	</table>	
</div>	