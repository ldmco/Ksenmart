<?php 
defined( '_JEXEC' ) or die;
?>
<div class="ksenmart-panelhelper">
	<div class="question">
		<h4><?php echo JText::_('mod_km_panelhelper_how_to_start')?></h4>
		<ul>
		<?php foreach($panelhelps as $panelhelp):?>
			<a help="<?php echo $panelhelp?>"><?php echo JText::_('mod_km_'.$panelhelp)?></a>
			<div class="answer <?php echo $panelhelp?>">
				<?php echo JText::_('mod_km_'.$panelhelp.'_desc')?>
			</div>			
		<?php endforeach;?>
	</div>
	<div class="clr"></div>
</div>