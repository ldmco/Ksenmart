<?php defined( '_JEXEC' ) or die; ?>
<div class="reviews">
	<div class="head">
		<h4><?php echo JText::_('KSM_PRODUCT_POST_A_REVIEW_TITLE'); ?></h4>
		<?php if ($this->params->get('show_comment_form')==1):?>
		<?php require_once('product_comment_form.php');?>
		<?php endif;?>			
	</div>
	<?php if (count($this->product->comments)>0):?>
	<div class="items">
		<?php $i = 0;?>
		<?php foreach($this->product->comments as $comment):?>
		<?php $i++;?>
		<?php $user = KSUsers::getUser($comment->user);?>
		<?php if ($i == 3):?>
		<div class="all-comments">
		<?php endif;?>
		<div class="item">
			<div class="w100">
				<div class="ava"><a href="javascript:void(0)"><img src="<?php echo $comment->logo_thumb; ?>" alt="" /></a></div>
				<div class="info">
					<div class="name"><?php echo $comment->name?></div>
					<div class="rating">
						<?php for($k=1;$k<6;$k++):?>
						<?php if (floor($comment->rate)>=$k):?>
						<img src="<?php echo JURI::root()?>components/com_ksenmart/images/star-small.png" alt="" />
						<?php else:?>
						<img src="<?php echo JURI::root()?>components/com_ksenmart/images/star2-small.png" alt="" />
						<?php endif;?>
						<?php endfor;?>
					</div>
				</div>
			</div>
			<div class="txt">
				<?php echo $comment->comment?>
			</div>
		</div>
		<?php endforeach;?>		
		<?php if ($i>2):?>
		</div>
		<?php endif;?>
	</div>
	<?php if ($i>2):?>
	<div class="show-all"><a href="#"><?php echo JText::_('KSM_PRODUCT_POST_A_REVIEW_SHOWALL'); ?></a></div>
	<?php endif;?>
	<?php else:?>
	<h4>Нет отзывов</h4>
	<?php endif;?>
</div>