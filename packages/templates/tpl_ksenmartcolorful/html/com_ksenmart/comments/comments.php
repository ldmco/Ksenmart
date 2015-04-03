<?php defined( '_JEXEC' ) or die; ?>
<div class="">
	<h2>Отзывы</h2>
	<div id="reviews" class="items shop_reviews comment-items">
	<?php if (!empty($this->rows)):?>
	<?php foreach($this->rows as $comment):?>
	<?php echo $this->loadTemplate('item', null, array('review' => $comment)); ?>
	<?php endforeach;?>
	<?php else:?>
	<?php require_once('no_comments.php');?>
	<?php endif;?>
	</div>
	<?php if ($this->params->get('site_use_pagination',1)==1 && !empty($this->pagination)):?>
	<div class="pagi">
	<?php echo $this->pagination->getPagesLinks(); ?>
	</div>	
	<?php endif;?>
</div>