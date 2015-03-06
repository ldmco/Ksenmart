<?php
defined('_JEXEC') or die;

require_once JPATH_ROOT . '/components/com_banners/helpers/banner.php';
$baseurl = JURI::base();
$i = 0;
?>
<div id="mycarousel" class="carousel slide<?php echo $moduleclass_sfx ?>">
	<ol class="carousel-indicators">
		<?php foreach($list as $item){ ?>
			<li idx="<?php echo $i; ?>" class="<?php echo $i == 0?'active':''; ?>"></li>
			<?php $i++; ?>
		<?php } ?>
	</ol>
	<div class="carousel-inner">
	<?php $i = 0; ?>
    <?php foreach($list as $item){ ?>
		<?php if ($i == 0): ?>
		<div class="ghost">
			<img src="<?php echo $item->params->get('imageurl');?>" alt="<?php echo $item->name; ?>" />
		</div>
		<?php endif; ?>
    	<div class="item<?php echo $i == 0?' active':''; ?>">
            <?php if(!empty($item->clickurl)){ ?>
            <a href="<?php echo $item->clickurl; ?>" title="<?php echo $item->name; ?>">
            <?php } ?>
            <img src="<?php echo $item->params->get('imageurl');?>" alt="<?php echo $item->name; ?>" />
            <?php if(!empty($item->clickurl)){ ?>
            </a>
            <?php } ?>
        </div>
        <?php $i++; ?>
    <?php } ?>
	</div>
</div>
<style>
.carousel .ghost{
	visibility:hidden;
}
.carousel  img{
	width:100%;
}
.carousel .item{
	position:absolute;
	opacity:0;
	z-index:4;
	width:100%;
	top:0px;
	left:0px;
	display:block;
}
.carousel .item.active{
	opacity:1;
}
#mycarousel .carousel-indicators li{
	cursor:pointer;
}
</style>
<script>
var current_slide = 0;
var count_slides = jQuery('#mycarousel .item').length;
var slideTimer = null;
setSlideTimer();

jQuery('#mycarousel .carousel-indicators li').click(function(){
	var idx = parseInt(jQuery(this).attr('idx'));
	setSlide(idx);
	setSlideTimer();
	return false;
});

function setSlideTimer(){
	clearInterval(slideTimer);
	slideTimer = setInterval(setNextSlide, 5000);
}

function setNextSlide(){
	var next_slide = current_slide + 1;
	if (next_slide == count_slides)
		next_slide = 0;
	setSlide(next_slide);
}

function setSlide(idx){
	jQuery('#mycarousel .item.active').animate({'opacity': 0}, 500, function(){
		jQuery(this).removeClass('active');
		jQuery('#mycarousel .carousel-indicators li.active').removeClass('active');
	});
	jQuery('#mycarousel .item:eq('+idx+')').animate({'opacity': 1}, 500, function(){
		jQuery(this).addClass('active');
		jQuery('#mycarousel .carousel-indicators li[idx="'+idx+'"]').addClass('active');
	});	
	current_slide = idx;
}
</script>