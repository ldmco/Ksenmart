<article class="item row-fluid review_product wrap_rvw_block" data-id="<?php echo $this->review->id; ?>">
    	<div class="span2 info">
    		<div class="img">
                 <a href="<?php echo $this->review->link; ?>" title="<?php echo $this->review->title; ?>">
                    <img src="<?php echo $this->review->small_img; ?>" alt="<?php echo $this->review->title; ?>" />
                </a>
            </div>
			<div class="title">
                <a href="<?php echo $this->review->link; ?>" title="<?php echo $this->review->title; ?>"><?php echo $this->review->title; ?></a>
            </div>
    	</div>
    	<div class="span10">
    		<dl class="dl-horizontal">
                <dt>Оценка</dt>
                <dd>
                    <div class="rating">                                    
        				<?php for($k=1; $k<6; $k++){
        					if(floor($this->review->rate) >= $k){ ?>
        				<img src="<?php echo JURI::root(); ?>components/com_ksenmart/images/star-small.png" alt="" />
        				<?php }else{ ?>
        				<img src="<?php echo JURI::root(); ?>components/com_ksenmart/images/star2-small.png" alt="" />
        				<?php }
        				} ?>
                    </div>                    
                </dd>
                <dt>Комментарий</dt>
                <dd>
                    <div class="quick_edit magical_text noTransition" data-type="comment" data-title="Редактировать" contenteditable="false"><?php echo $this->review->comment; ?></div>
                </dd>
                <dt class="text-success">Плюсы</dt>
                <dd class="text-success">
                    <div class="quick_edit magical_text noTransition" data-type="good" data-title="Редактировать" contenteditable="false"><?php echo $this->review->good; ?></div>
                </dd>
                <dt class="text-error">Минусы</dt>
                <dd class="text-error" >
                    <div class="quick_edit magical_text noTransition" data-type="bad" data-title="Редактировать" contenteditable="false"><?php echo $this->review->bad; ?></div>
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