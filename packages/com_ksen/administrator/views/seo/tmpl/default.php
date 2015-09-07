<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<?php JHTML::_('behavior.modal'); ?>
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
			<td width="250" class="left-column">
				<div id="tree">
					<form id="list-filters">
						<ul>
							<?php echo KSSystem::loadModules('km-list-left')?>
						</ul>
					</form>			
				</div>	
			</td>
			<td valign="top">
				<div id="seo_content">
					<?php echo $this->loadTemplate($this->seo_type);?>
				</div>	
			</td>	
		</tr>	
	</table>	
</div>	