<?php
defined( '_JEXEC' ) or die( '=;)' ); 
?>
<div class="item">
	<div class="img">
		<a href="<?=$manufacturer->img_link?>" onclick="return hs.expand(this)"><div style="<?=$manufacturer->small_img_div_style?>"><img style="<?=$manufacturer->small_img_img_style?>" src="<?=$manufacturer->small_img?>" alt=""><span class="hover"></span></div></a>
	</div>
	<div class="name">
		<a href="<?=$manufacturer->link?>"><?=$manufacturer->title?></a>
	</div>		
</div>