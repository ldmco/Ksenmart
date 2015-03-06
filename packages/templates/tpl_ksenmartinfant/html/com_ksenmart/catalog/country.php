<?php
defined( '_JEXEC' ) or die( '=;)' );
?>
<script>
	var total_items=0;
</script>
<div class="catalog">
	<h3><?php echo $this->country->title;?></h3>
	<div class="catalog-description"><?php echo $this->country->content;?></div>
	<?php if (!empty($this->seo_text)):?>
	<div class="catalog-description"><?php echo $this->seo_text;?></div>
	<?php endif;?>
	<ul class="nav nav-list">
	<? if (!empty($this->rows)){
		echo '<li class="nav-header">'.$letter_brands.'</li>';
		foreach($this->rows as $manufacturer){
			$link = JRoute::_('index.php?option=com_ksenmart&view=catalog&manufacturers[]='.$manufacturer->id.'&clicked=manufacturers');
			echo '<li><a href="'.$link.'" title="'.$manufacturer->title.'">'.$manufacturer->title.'</a></li>';
		}	
	}else{
		require_once('no_manufacturers.php');
	} ?>
	</ul>
</div>