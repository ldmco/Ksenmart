<?php defined( '_JEXEC' ) or die; ?>
<?php if(!$this->show_shop_review && KSUsers::getUser()->id > 0){ ?>
<div class="row-fluid">
    <p class="lead add_shop_review pull-right clearfix"><span>+</span> <a href="javascript:void(0);" class="link_b_border lrg">Добавить отзыв о магазине</a></p>
</div>
<div class="review add noTransition row-fluid" style="display: none;">
	<form method="post">
		<?php if(empty(KSUsers::getUser()->name)){ ?>
		<div class="control-group">
			<div class="controls">
				<input type="text" name="name" placeholder="Имя" required="true" />
			</div>
		</div>
		<?php } ?>
		<div class="control-group">
			<div class="controls">
				<img data-rate="1" src="http://room9.ldmco.ru/components/com_ksenmart/images/star2-small.png" alt="">
				<img data-rate="2" src="http://room9.ldmco.ru/components/com_ksenmart/images/star2-small.png" alt="">
				<img data-rate="3" src="http://room9.ldmco.ru/components/com_ksenmart/images/star2-small.png" alt="">
				<img data-rate="4" src="http://room9.ldmco.ru/components/com_ksenmart/images/star2-small.png" alt="">
				<img data-rate="5" src="http://room9.ldmco.ru/components/com_ksenmart/images/star2-small.png" alt="">
			</div>
		</div>
		<div class="control-group">
			<div class="controls">
				<textarea name="review" placeholder="Отзыв о магазине" required="true"></textarea>
			</div>
		</div>
		<div class="control-group">
			<div class="controls top">
				<input type="submit" class="btn btn-success" value="Добавить" />
			</div>
		</div>
		<input type="hidden" name="task" value="profile.add_shop_review" />
		<input type="hidden" name="rate" id="comment_rate" value="0">
	</form>
    <hr />
</div>
<?php } ?>
<?php if(!empty($this->reviews)){ ?>
<section class="shop_reviews row-fluid">
    <h3><?php echo JText::_('KSM_SHOP_REVIEWS_PATH_TITLE'); ?></h3>
    <?php foreach($this->reviews as $review){ ?>
        <article class="row-fluid item" id="review_<?php echo $review->id; ?>">
            <div class="span2 avatar">
                <a href="javascript:void(0)" title="<?php echo $review->name; ?>">
                    <img src="<?php echo JURI::root().$review->logo_thumb; ?>" alt="<?php echo $review->name; ?>" class="border_ksen" />
                </a>
            </div>
            <div class="span10 comment_wrapp">
                <div class="name"><?php echo $review->name; ?></div>
				<div class="rating">
					<?php for($k=1;$k<6;$k++) {
						if(floor($review->rate) >= $k){ ?>
					<img src="<?php echo JURI::root()?>components/com_ksenmart/images/star-small.png" alt="" />
					<?php }else{ ?>
					<img src="<?php echo JURI::root()?>components/com_ksenmart/images/star2-small.png" alt="" />
					<?php }
					} ?>
				</div>
                <div class="row-fluid comment">
                    <?php echo nl2br(mb_substr($review->comment, 0, $this->params->get('count_symbol', 400))); ?>
                </div>
                <div class="read_more">
                    <a href="<?php echo JRoute::_('index.php?option=com_ksenmart&view=comments&layout=shopreview&id='.$review->id); ?>" title="Подробнее">Подробнее</a>
                </div>
            </div>
        </article>
    <?php } ?>
</section>
<?php } ?>