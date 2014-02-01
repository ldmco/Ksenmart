<?php
defined( '_JEXEC' ) or die;
?>
<form method="post" class="form">
	<table class="cat" width="100%" cellspacing="0">	
		<thead>
			<tr>
				<th align="left" style="position:relative;">
					<?php echo JText::_('ksm_exportimport_yandeximport_settings')?>
					<input type="button" id="save_yandexmarket" class="saves-green" value="<?php echo JText::_('ksm_save')?>">
				</th>	
			</tr>
		<thead>
		<tbody>
		<tr>
			<td class="rightcol" style="background:#f9f9f9!important;padding:15px 10px;">
				<div class="row">
					<a href="<?php echo JURI::base().'components/com_ksenmart/views/exportimport/tmpl/ym-xml.php'?>" target="_blank">
						<?php echo JText::_('ksm_exportimport_yandeximport_text')?>
					</a>	
				</div>
				<div class="row">
					<div align="right">
						<a class="check_all_cats"><?php echo JText::_('ksm_exportimport_check_all')?></a> | <a class="discharge_cats"><?php echo JText::_('ksm_exportimport_discharge')?></a>
					</div>		
					<br>
					<?php echo $this->form->getInput('categories');?>
				</div>	
				<div class="row">
					<?php echo $this->form->getLabel('shopname');?>
					<?php echo $this->form->getInput('shopname');?>
				</div>	
				<div class="row">
					<?php echo $this->form->getLabel('company');?>
					<?php echo $this->form->getInput('company');?>
				</div>				
			</td>
		</tr>
		</tbody>
	</table>
	<input type="hidden" name="categories" value="" />
	<input type="hidden" name="task" value="exportimport.save_yandexmarket" />
	<input type="hidden" name="option" value="com_ksenmart" />
	<input type="hidden" name="view" value="exportimport" />	
</form>	
