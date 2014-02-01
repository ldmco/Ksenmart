<?php
defined( '_JEXEC' ) or die;
?>
<div class="km-path">
	<?php $k=1;?>
	<?php foreach($path->path_items as $path_item):?>
		<?php if ($path_item['link']):?>
		<a href="<?php echo $path_item['link']?>">
		<?php endif;?>
		<?php echo $path_item['text']?>
		<?php if ($path_item['link']):?>
		</a>
		<?php endif;?>		
		<?php if ($k<count($path->path_items)):?>
		<span></span>
		<?php endif;?>	
		<?php $k++;?>
	<?php endforeach;?>
</div>