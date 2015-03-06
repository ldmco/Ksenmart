<?php defined('_JEXEC') or die(); ?>
<?php if(!$this->show_shop_review){ ?>
<div class="row-fluid">
    <p class="lead add_shop_review pull-right clearfix"><span>+</span> <a href="javascript:void(0);" class="link_b_border btn">Добавить отзыв о магазине</a></p>
</div>
<div class="review add noTransition row-fluid" style="display: none;">
	<form method="post" action="#tab4">
		<div class="control-group">
			<div class="controls">
				<img data-rate="1" src="<?php echo JURI::root()?>components/com_ksenmart/images/star2-small.png" alt="" />
				<img data-rate="2" src="<?php echo JURI::root()?>components/com_ksenmart/images/star2-small.png" alt="" />
				<img data-rate="3" src="<?php echo JURI::root()?>components/com_ksenmart/images/star2-small.png" alt="" />
				<img data-rate="4" src="<?php echo JURI::root()?>components/com_ksenmart/images/star2-small.png" alt="" />
				<img data-rate="5" src="<?php echo JURI::root()?>components/com_ksenmart/images/star2-small.png" alt="" />
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
        <input type="hidden" name="rate" id="comment_rate" value="0" />
		<input type="hidden" name="task" value="profile.add_shop_review" />
	</form>
    <hr />
</div>
<?php }else{ ?>
<div class="review edit noTransition row-fluid" style="display: none;">
	<form method="post" action="#tab4">
		<div class="control-group">
			<div class="controls">
				<?php for($k=1;$k<6;$k++) {
					if(floor($this->user_review->rate) >= $k){ ?>
				<img data-rate="<?php echo $k; ?>" src="<?php echo JURI::root()?>components/com_ksenmart/images/star-small.png" alt="" />
				<?php }else{ ?>
				<img data-rate="<?php echo $k; ?>" src="<?php echo JURI::root()?>components/com_ksenmart/images/star2-small.png" alt="" />
				<?php }
				} ?>
			</div>
		</div>
		<div class="control-group">
			<div class="controls">
				<textarea name="review" placeholder="Отзыв о магазине" required="true"><?php echo $this->user_review->comment; ?></textarea>
			</div>
		</div>
		<div class="control-group">
			<div class="controls top">
				<input type="submit" class="btn btn-success" value="Редактировать" />
			</div>
		</div>
        <input type="hidden" name="rate" id="comment_rate" value="0" />
        <input type="hidden" name="id" value="<?php echo $this->user_review->id; ?>" />
		<input type="hidden" name="task" value="profile.edit_shop_review" />
	</form>
    <hr />
</div>
<article class="item row-fluid reviews wrap_rvw_block" data-id="<?php echo $this->user_review->id; ?>">
	<div class="span2 review_product">
		<div class="img">
             <a href="javascript:void(0);" title="<?php echo $this->user_review->name; ?>">
                <img src="<?=JURI::base()?>templates/ksenmartinfant/img/logo-1.jpg" alt="<?php echo $this->user_review->name; ?>" />
            </a>
        </div>
	</div>
	<div class="span10 content_review_wrapp">
		<dl class="dl-horizontal">
            <dt>Оценка</dt>
            <dd class="rating">
				<?php for($k=1; $k<6; $k++){
					if(floor($this->user_review->rate) >= $k){ ?>
				<img src="<?php echo JURI::root()?>components/com_ksenmart/images/star-small.png" alt="" />
				<?php }else{ ?>
				<img src="<?php echo JURI::root()?>components/com_ksenmart/images/star2-small.png" alt="" />
				<?php }
				} ?>
            </dd>
		  <dt>Комментарий</dt>
		  <dd class="quick_edit magical_text noTransition" data-type="comment" data-title="Редактировать" contenteditable="false"><?php echo nl2br($this->user_review->comment); ?></dd>
		</dl>
        <div class="toolbar hide">                
            <ul class="inline">
                <li>
                    <a href="javascript:void(0);" class="link_b_border save_dynamic_link save_shop_review"><i class="icon-ok"></i> Сохранить</a>
                </li>
                <li>
                    <a href="javascript:void(0);" class="link_b_border cancel_edit"><i class="icon-remove"></i> Отменить</a>
                </li>
            </ul>
        </div>
	</div>
</article>
<?php } ?>
<?php
if(!empty($this->reviews)){ ?>
    <h1><?php echo JText::_('KSM_PRODUCTS_REVIEW_TITLE'); ?></h1>
    <?php foreach($this->reviews AS $review){
        $user = KSUsers::getUser($review->user);
    ?>    
    <article class="item row-fluid review_product wrap_rvw_block" data-id="<?php echo $review->id; ?>">
    	<div class="span2 info">
    		<div class="img">
                 <a href="<?php echo $review->link; ?>" title="<?php echo $review->title; ?>">
                    <img src="<?php echo $review->small_img; ?>" alt="<?php echo $review->title; ?>" />
                </a>
            </div>
			<div class="title">
                <a href="<?php echo $review->link; ?>" title="<?php echo $review->title; ?>"><?php echo $review->title; ?></a>
            </div>
    	</div>
    	<div class="span10">
    		<dl class="dl-horizontal">
                <dt>Оценка</dt>
                <dd>
                    <div class="rating">                                    
        				<?php for($k=1; $k<6; $k++){
        					if(floor($review->rate) >= $k){ ?>
        				<img src="<?php echo JURI::root(); ?>components/com_ksenmart/images/star-small.png" alt="" />
        				<?php }else{ ?>
        				<img src="<?php echo JURI::root(); ?>components/com_ksenmart/images/star2-small.png" alt="" />
        				<?php }
        				} ?>
                    </div>                    
                </dd>
                <dt>Комментарий</dt>
                <dd>
                    <div class="quick_edit magical_text noTransition" data-type="comment" data-title="Редактировать" contenteditable="false"><?php echo $review->comment; ?></div>
                </dd>
                <dt class="text-success">Плюсы</dt>
                <dd class="text-success">
                    <div class="quick_edit magical_text noTransition" data-type="good" data-title="Редактировать" contenteditable="false"><?php echo $review->good; ?></div>
                </dd>
                <dt class="text-error">Минусы</dt>
                <dd class="text-error" >
                    <div class="quick_edit magical_text noTransition" data-type="bad" data-title="Редактировать" contenteditable="false"><?php echo $review->bad; ?></div>
                </dd>
    		</dl>
            <div class="toolbar hide">                
                <ul class="inline">
                    <li>
                        <a href="javascript:void(0);" class="link_b_border save_dynamic_link save_product_review"><i class="icon-ok"></i> Сохранить</a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" class="link_b_border cancel_edit"><i class="icon-remove"></i> Отменить</a>
                    </li>
                </ul>
            </div>
    	</div>
    </article>
    <?php } ?>
<?php }else{ ?>
	<h1><?php echo JText::_('KSM_PRODUCTS_REVIEW_TITLE'); ?></h1>
    <h2 class="text-center">Нет отзывов о товарах</h2>
<?php } ?>
<div class="pagination">
    <?php echo $this->pagination->getPagesLinks(); ?>
</div>