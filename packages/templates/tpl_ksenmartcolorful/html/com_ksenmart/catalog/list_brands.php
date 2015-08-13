<?php defined( '_JEXEC' ) or die;
$letter_brands = JRequest::getVar('letter_brands', null);
?>
<div class="catalog">
	<ul class="nav nav-list">
	<? if (!empty($this->brands)){
		echo '<li class="nav-header">'.$letter_brands.'</li>';
		foreach($this->brands as $brand){
			$link = JRoute::_('index.php?option=com_ksenmart&view=catalog&manufacturers[]='.$brand->id.'&clicked=manufacturers');
			echo '<li><a href="'.$link.'" title="'.$brand->title.'">'.$brand->title.'</a></li>';
		}	
	}else{
		require_once('no_products.php');
	} ?>
	</ul>
</div>