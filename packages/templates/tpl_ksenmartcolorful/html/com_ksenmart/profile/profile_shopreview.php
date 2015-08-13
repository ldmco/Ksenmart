<article class="item row-fluid reviews wrap_rvw_block" data-id="<?php echo $this->user_review->id; ?>">
	<div class="span2 review_product">
		<div class="img">
             <a href="javascript:void(0);" title="<?php echo $this->user_review->name; ?>">
                <img src="<?=JURI::base()?>templates/ksenmartcolorful/images/logo.png" alt="<?php echo $this->user_review->name; ?>" />
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