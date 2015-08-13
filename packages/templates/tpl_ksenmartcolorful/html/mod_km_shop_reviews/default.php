<?php defined( '_JEXEC' ) or die; ?>
<?php if(!empty($reviews)){ ?>
<section class="shop_reviews_module<?php echo $moduleclass_sfx; ?>">
    <?php if($module->showtitle){ ?>
    <h3><?php echo $module->title; ?></h3>
    <?php } ?>
    <?php foreach($reviews as $review){ ?>
        <article class="row-fluid item">
			<div class="span12">
				<div class="span2 avatar">
					<a href="<?php echo JRoute::_('index.php?option=com_ksenmart&view=comments&layout=shopreview&id='.$review->id); ?>" title="<?php echo $review->name; ?>">
						<img src="<?php echo JURI::root().$review->logo_thumb; ?>" alt="<?php echo $review->name; ?>" class="border_ksen" />
					</a>
				</div>
				<div class="span9">
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
				</div>
			</div>
            <div class="span12 comment_wrapp">
				<div class="ug"></div>
				<div class="inner">
					 <div class="row-fluid comment">
						<?php echo mb_substr($review->comment, 0, $params->get('count_symbol', 200)); ?>
					</div>
					<div class="read_more">
						<a href="<?php echo JRoute::_('index.php?option=com_ksenmart&view=comments&layout=shopreview&id='.$review->id); ?>" title="Подробнее">Подробнее</a>
					</div>
				</div>
            </div>
        </article>
    <?php } ?>
    <div class="row-fluid more">
        <a href="<?php echo JRoute::_('index.php?option=com_ksenmart&view=comments&layout=shopreviews'); ?>" title="<?php echo JText::_('MODULE_KM_SHOP_REVIEWS_ALL_REVIEWS'); ?>"><?php echo JText::_('MODULE_KM_SHOP_REVIEWS_ALL_REVIEWS'); ?></a>
    </div>
</section>
<?php } ?>