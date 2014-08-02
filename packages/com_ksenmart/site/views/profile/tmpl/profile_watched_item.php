<?php
defined( '_JEXEC' ) or die( '=;)' ); 
?>
<article class="item">
	<form method="post" action="<?=$product->add_link_cart?>" >
		<div class="img">
			<a href="<?=$product->link?>"><img src="<?=$product->small_img?>" alt="" /></a>
		</div>
		<div class="article">
		артикул: <?=$product->product_code?>
		</div>
		<div class="name">
			<a href="<?=$product->link?>"><?=$product->title?></a>
		</div>
		<footer class="bottom">
			<div class="price"><?=$product->val_price?></div>
			<div class="buy"><button class="button">Купить</button></div>
		</footer>
		<input type="hidden" name="count" value="<?=$product->product_packaging?>">	
		<input type="hidden" name="product_packaging" value="<?=$product->product_packaging?>">		
		<input type="hidden" name="id" value="<?=$product->id?>">			
	</form>			
</article>