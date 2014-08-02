<section class="shop_reviews item row-fluid">
    <article class="row-fluid item">
        <div class="span2 avatar">
            <a href="javascript:void(0)" title="<?php echo $this->review->name; ?>">
                <img src="<?php echo JURI::root().$this->review->logo_thumb; ?>" alt="<?php echo $this->review->name; ?>" class="border_ksen" />
            </a>
        </div>
        <div class="span10 comment_wrapp">
            <div class="name"><?php echo $this->review->name; ?></div>
    		<div class="rating">
    			<?php for($k=1;$k<6;$k++) {
    				if(floor($this->review->rate) >= $k){ ?>
    			<img src="<?php echo JURI::root()?>components/com_ksenmart/images/star-small.png" alt="" />
    			<?php }else{ ?>
    			<img src="<?php echo JURI::root()?>components/com_ksenmart/images/star2-small.png" alt="" />
    			<?php }
    			} ?>
    		</div>
            <div class="row-fluid comment">
                <?php echo nl2br($this->review->comment); ?>
            </div>
        </div>
    </article>
</section>