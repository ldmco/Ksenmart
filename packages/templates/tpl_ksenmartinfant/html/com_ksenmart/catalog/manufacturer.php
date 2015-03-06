<?php
defined( '_JEXEC' ) or die( '=;)' );
?>
<script>
	var total_items=<?php echo $this->total;?>;
</script>
<div class="catalog">
	<h1><?php echo $this->manufacturer->title;?></h1>
	<div class="catalog-description"><?php echo $this->manufacturer->content;?></div>
	<?php if (!empty($this->seo_text)):?>
	<div class="catalog-description"><?php echo $this->seo_text;?></div>
	<?php endif;?>	
	<?php require_once('sort_links.php');?>
	<div class="items catalog-items">
	<? if (!empty($this->rows)){
		$counter 	= 0;
		$countRows 	= count($this->rows);
		$params		= $this->params;
		foreach($this->rows as $product){
			$counter++;
			if ($counter % 5 == 0 OR $counter == 1) echo '<div class="row-fluid"><ul class="thumbnails items catalog-items">';
            echo $this->loadTemplate('item', 'default', array('product' => $product, 'params' => $this->params)); 
			if ($counter % 4 == 0 OR $countRows == $counter) echo '</ul></div>';
		}	
	}else{
		require_once('no_products.php');
	}
	?>
	</div>
	<?
	if ($this->params->get('site_use_pagination',1)==1)
		require('pagination.php');
	else
	{
	?>
	<div class="more">
		<a href="#"><span>Еще товары</span></a>
	</div>	
	<?
	}
	?>
</div>