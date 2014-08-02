<?php defined( '_JEXEC' ) or die; ?>
<script>
	var JText_confirm_del_template='<?php echo JText::_('confirm_del_template')?>';
</script>
<div class="clearfix panel">
    <div class="pull-left">
        <?php echo KsenMartHelper::loadModules('km-top'); ?>
    </div>
    <div class="pull-right">
        <?php echo KsenMartHelper::loadModules('top_right'); ?>
    </div>
    <div class="row-fluid">
        <?php echo KSSystem::loadModules('ks-top-bottom'); ?>
    </div>
</div>
<?php echo KsenMartHelper::loadModules('top'); ?>
<div id="center">
	<table id="cat" width="100%">
		<tr>
			<td width="250" class="left-column">
				<div id="tree">
					<ul>
						<li>
							<?php echo KsenMartHelper::loadModules('','sendmails_templates',array('selected'=>array($this->template->id)))?>
						</li>	
					</ul>	
				</div>	
			</td>
			<td valign="top">
				<div id="content">
					<?php if ($this->template->id!=0):?>
						<?php echo $this->loadTemplate('mail');?>
					<?php elseif (JRequest::getVar('sended','')!=''):?>	
						<?php echo $this->loadTemplate('sended');?>
					<?php else:?>
						<?php echo $this->loadTemplate('text');?>
					<?php endif;?>
				</div>	
			</td>	
		</tr>	
	</table>	
</div>
	