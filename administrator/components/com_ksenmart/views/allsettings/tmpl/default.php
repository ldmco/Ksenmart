<?php defined( '_JEXEC' ) or die;

JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
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
						<?php echo KMSystem::loadModules('km-list-left'); ?>
					</ul>	
				</div>
			</td>
			<td valign="top">
				<form action="<?php echo JRoute::_('index.php?option=com_ksenmart');?>" id="allsettings-form" method="post" class="form" name="adminForm" autocomplete="off" class="form-validate">
					<table class="cat" width="100%" cellspacing="0">	
						<thead>
							<tr>
								<th align="left" style="position:relative;">	
									<?php echo JText::_('ksm_allsettings_settings_title')?>
									<input type="submit" class="saves-green" value="<?php echo JText::_('ksm_save')?>">
								</th>	
							</tr>
						<thead>
						<tbody>
						<tr>
							<td class="rightcol" style="background:#f9f9f9!important;padding-top:15px;">							
								<?php foreach($this->form as $formname=>$form):?>
									<div class="settings-tab-content" id="settings-<?php echo $formname;?>-content">
									<?php $fieldSets = $form->getFieldsets();?>
									<?php foreach ($fieldSets as $name => $fieldSet):?>
										<div class="config-option-list settings-sub-tab-content" id="settings-sub-<?php echo $name?>-content">
										<?php foreach ($form->getFieldset($name) as $field):?>
											<div class="row">
											<?php if (!$field->hidden) : ?>
											<?php echo $field->label; ?>
											<?php endif; ?>
											<?php echo $field->input; ?>
											</div>
										<?php endforeach;?>
										</div>
										<div class="clr"></div>
									<?php endforeach;?>
									</div>
								<?php endforeach;?>
								<input type="hidden" name="id" value="<?php echo $this->component->id;?>" />
								<input type="hidden" name="task" value="allsettings.save" />
								<?php echo JHtml::_('form.token'); ?>					
							</td>
						</tr>
						</tbody>
					</table>							
				</form>							
			</td>	
		</tr>
	</table>		
</div>
