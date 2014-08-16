<?php
defined( '_JEXEC' ) or die;
?>
<div class="review">
	<div class="top">
		<div class="name">
			<a href="<?php echo JRoute::_('index.php?option=com_ksenmart&view=Comments&id='.$comment->id.'&Itemid='.KsenmartHelper::getShopItemid())?>"><?php echo ($comment->name!=''?$comment->name:'Аноним')?></a>
		</div>
		<div class="date">
			<?php echo KsenmartHelper::formatCommentDate($comment->date_add)?>
		</div>	
	</div>
	<div class="txt">
		<?php if ($comment->product!=0):?>
		<div class="product">
			<a href="<?php echo JRoute::_('index.php?option=com_ksenmart&view=product&id='.$comment->product.'&Itemid='.KsenmartHelper::getShopItemid())?>"><div style="<?php echo $comment->product_img_div_style;?>"><img style="<?php echo $comment->product_img_div_style;?>" src="<?php echo $comment->product_img;?>" alt=""></div></a>
		</div>	
		<?php endif;?>	
		<a href="<?php echo JRoute::_('index.php?option=com_ksenmart&view=Comments&id='.$comment->id.'&Itemid='.KsenmartHelper::getShopItemid())?>"><?php echo $comment->comment?></a>
	</div>
</div>