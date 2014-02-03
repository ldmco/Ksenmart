<?php defined( '_JEXEC' ) or die;
    JHTML::_('behavior.modal');
?>
<div class="clearfix panel">
    <div class="pull-left">
        <?php echo KMSystem::loadModules('km-top-left'); ?>
    </div>
    <div class="pull-right">
        <?php echo KMSystem::loadModules('km-top-right'); ?>
    </div>
    <div class="row-fluid">
        <?php echo KMSystem::loadModules('km-top-bottom'); ?>
    </div>
</div>
<div id="center">
	<table id="cat" width="100%">
		<tr>
			<td width="250" class="left-column">
				<div id="tree">
					<ul>
						<li>			
							<?php echo KMSystem::loadModules('km-list-left')?>
						</li>
					</ul>	
				</div>								
			</td>
			<td valign="top">
				<div id="content">
					<?php echo $this->loadTemplate($this->report);?>
				</div>	
			</td>	
		</tr>	
	</table>	
</div>	