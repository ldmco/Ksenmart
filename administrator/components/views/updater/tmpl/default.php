<?php defined( '_JEXEC' ) or die; ?>	
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
				<?php echo KsenMartHelper::loadModules('left')?>
			</td>
			<td valign="top">
				<div id="content">
					<?php if (KMUpdaterFunctions::checkUpdates()!==false):?>
					<form method="post" style="float:left;margin-right:10px;">
						<input type="button" class="saves" value="<?php echo JText::_('update')?>">
						<input type="hidden" name="option" value="com_ksenmart">
						<input type="hidden" name="view" value="updater">
						<input type="hidden" name="task" value="updater.update_ksenmart">
					</form>	
					<form method="post" style="float:left">
						<input type="button" class="saves" value="<?php echo JText::_('download_update')?>">
						<input type="hidden" name="option" value="com_ksenmart">
						<input type="hidden" name="view" value="updater">
						<input type="hidden" name="task" value="updater.download_update_ksenmart">
					</form>						
					<?php endif;?>
					<table class="cat" width="100%" cellspacing="0">
						<thead>
							<tr>
								<th width="20%">
									<?php echo JText::_('type')?>
								</th>
								<th width="60%">
									<?php echo JText::_('name')?>
								</th>
								<th width="20%">
									<?php echo JText::_('version')?>
								</th>						
							</tr>
						</thead>
						<tbody>
						<?php if (KMUpdaterFunctions::checkUpdates()!==false):?>
							<?php foreach($this->updates as $update):?>
							<tr>
								<td align="center"><?php echo JText::_($update['type'])?></td>
								<td align="center"><?php echo JText::_($update['name'])?></td>
								<td align="center"><?php echo $update['current_version']?> => <?php echo $update['new_version']?></td>
							</tr>
							<?php endforeach;?>
						<?php else:?>
						<tr>
							<td colspan="3">
								<h1><center><?php echo JText::_('no_updates')?></center></h1>
							</td>
						</tr>
						<?php endif;?>
						</tbody>
					</table>			
				</div>
			</td>	
		</tr>
	</table>		
</div>
