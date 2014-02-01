<?php	 		 		 	
defined( '_JEXEC' ) or die;
?>
<form method="post" class="form" enctype="multipart/form-data">
	<?php foreach($this->configs as $config_key=>$config):?>
	<table class="cat" width="100%" cellspacing="0" id="<?php echo $config_key;?>">	
		<thead>
			<tr>
				<th align="left" style="position:relative;">
					<?php echo JText::_('ksm_seo-titles-config-'.$config_key)?>
					<input type="submit" class="saves-green" value="<?php echo JText::_('ksm_save')?>">
				</th>	
			</tr>
		<thead>
		<tbody>
		<tr>
			<td class="rightcol" style="background:#f9f9f9!important;padding-top:15px;">
				<div class="row">
					<div class="seo-blocks">
						<?php foreach($config->config as $key=>$block_config):?>
						<div class="seo-block <?php echo ($block_config->activable?'seo-block-activable':'')?> <?php echo ($block_config->sortable?'seo-block-sortable':'')?> <?php echo (!$block_config->active?'inactive':'')?>" alt="<?php echo ($block_config->user==1?$block_config->title:JText::_('ksm_'.$key.'-titles-example'));?>">
							<label class="seo-block-content">
								<span><?php echo ($block_config->user==1?$block_config->title:JText::_('ksm_'.$key));?></span>
								<input type="hidden" class="block_active" name="config[<?php echo $config_key;?>][<?php echo $key;?>][active]" value="<?php echo $block_config->active;?>">
								<input type="hidden" name="config[<?php echo $config_key;?>][<?php echo $key;?>][activable]" value="<?php echo $block_config->activable;?>">
								<input type="hidden" name="config[<?php echo $config_key;?>][<?php echo $key;?>][sortable]" value="<?php echo $block_config->sortable;?>">
								<input type="hidden" class="user" name="config[<?php echo $config_key;?>][<?php echo $key;?>][user]" value="<?php echo $block_config->user;?>">
								<?php if ($block_config->user==1):?>
								<input type="hidden" class="title" name="config[<?php echo $config_key;?>][<?php echo $key;?>][title]" value="<?php echo $block_config->title;?>">
								<?php endif;?>
								<input type="hidden" class="key" value="<?php echo $key;?>">
							</label>
						</div>	
						<?php endforeach;?>
						<div class="seo-block-bottom">
							<div class="seo-example"><?php echo JText::_('ksm_seo_example')?><span></span></div>
							<a class="add" href="<?php echo JRoute::_('index.php?option=com_ksenmart&view=seo&layout=seotitlevalue&tmpl=component');?>"><?php echo JText::_('ksm_add')?></a>
						</div>	
					</div>
				</div>
			</td>
		</tr>
		</tbody>
	</table>
	<?php endforeach;?>
	<input type="hidden" name="task" value="seo.save_titles_configs" />
</form>	
<div class="seo-block-mask" style="display:none;">
	<div class="seo-block seo-block-activable seo-block-sortable" alt="">
		<label class="seo-block-content">
			<span></span>
			<input type="hidden" class="block_active" value="1">
			<input type="hidden" class="activable" value="1">
			<input type="hidden" class="sortable" value="1">
			<input type="hidden" class="user" value="1">
			<input type="hidden" class="title" value="">
		</label>
	</div>
</div>
<script>
var separator='<?php echo $this->params->get('path_separator',' ');?>';

jQuery('.seo-blocks').sortable({
	items:'.seo-block-sortable',
	cancel:'.inactive',
	stop: function(event, ui){
		updateSeoExample(ui.item.parents('.seo-blocks'));
	}
});

jQuery('.seo-blocks').each(function(){
	updateSeoExample(jQuery(this));
});
</script>