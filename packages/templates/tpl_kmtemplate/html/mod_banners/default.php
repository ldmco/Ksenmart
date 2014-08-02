<?php defined('_JEXEC') or die;

    require_once JPATH_ROOT . '/components/com_banners/helpers/banner.php';
    $baseurl = JURI::base();
    $i = 0;
?>
<script>
    jQuery(document).ready(function(){
        jQuery('#carousel').carousel();
    });
</script>
<div id="carousel" class="carousel slide<?php echo $moduleclass_sfx ?>">
	<div class="carousel-inner">

    <?php foreach($list as $item){ ?>
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
    <a class="carousel-control left" href="#carousel" data-slide="prev">‹</a>
    <a class="carousel-control right" href="#carousel" data-slide="next">›</a>
</div>