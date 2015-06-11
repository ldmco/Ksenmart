<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<form method="post" class="form" enctype="multipart/form-data">
	<?php foreach($this->configs as $config_key=>$config):?>
	<table class="cat" width="100%" cellspacing="0" id="<?php echo $config_key;?>">	
		<thead>
			<tr>
				<th align="left" style="position:relative;">
					<?php echo JText::_('ks_seo-meta-config-'.$config_key)?>
					<input type="submit" class="saves-green" value="<?php echo JText::_('KS_SAVE'); ?>">
				</th>	
			</tr>
		<thead>
		<tbody>
		<tr>
			<td class="rightcol" style="background:#f9f9f9!important;padding-top:15px;">
				<div class="row">
					<label class="inputname">Description</label>
					<select name="config[<?php echo $config_key;?>][description][flag]" id="sel_<?php echo $config_key;?>_dflag" class="sel">
						<option value="1" <?php echo ($config->config->description->flag==1?'selected':'');?>><?php echo JText::_('ks_seo-meta-make-from');?></option>
						<option value="0" <?php echo ($config->config->description->flag==0?'selected':'');?>><?php echo JText::_('ks_seo-meta-not-make');?></option>
					</select>	
					<select name="config[<?php echo $config_key;?>][description][type]" id="sel_<?php echo $config_key;?>_dtype" class="sel marginleft20">
						<?php foreach($config->config->description->types as $type):?>
						<option value="<?php echo $type;?>" <?php echo ($config->config->description->type==$type?'selected':'');?>><?php echo JText::_('ks_'.$type);?></option>
						<?php endforeach;?>
					</select>
					<div class="seo-meta-symbols">
						<?php echo JText::_('ks_seo-meta-not-more');?>
						<input type="text" class="inputbox" name="config[<?php echo $config_key;?>][description][symbols]" value="<?php echo $config->config->description->symbols;?>">
						<?php echo JText::_('ks_seo-meta-symbols');?>
					</div>
				</div>
				<div class="row">
					<label class="inputname">Keywords</label>
					<select name="config[<?php echo $config_key;?>][keywords][flag]" id="sel_<?php echo $config_key;?>_kflag" class="sel">
						<option value="1" <?php echo ($config->config->keywords->flag==1?'selected':'');?>><?php echo JText::_('ks_seo-meta-make-from');?></option>
						<option value="0" <?php echo ($config->config->keywords->flag==0?'selected':'');?>><?php echo JText::_('ks_seo-meta-not-make');?></option>
					</select>	
					<select name="config[<?php echo $config_key;?>][keywords][type]" id="sel_<?php echo $config_key;?>_ktype" class="sel marginleft20">
						<?php foreach($config->config->keywords->types as $type):?>
						<option value="<?php echo $type;?>" <?php echo ($config->config->keywords->type==$type?'selected':'');?>><?php echo JText::_('ks_'.$type);?></option>
						<?php endforeach;?>
					</select>
				</div>	
			</td>
		</tr>
		</tbody>
	</table>
	<?php endforeach;?>
	<input type="hidden" name="task" value="seo.save_meta_configs" />
</form>
<script>
clearKMListBinds();
</script>