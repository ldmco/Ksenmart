<?php defined('_JEXEC') or die; ?>
<?php $i = 0; ?>
<?php $listCount = count($list); ?>
<script>
    jQuery(document).ready(function(){
        jQuery('#carousel').carousel();
    });
</script>
<div id="carousel" class="carousel slide<?php echo $moduleclass_sfx ?>">
    <div class="carousel-inner">
    <?php foreach($list as $item): ?>
        <div class="item<?php echo $i == 0?' active':''; ?>">
            <?php if(!empty($item->clickurl)): ?>
            <a href="<?php echo $item->clickurl; ?>" title="<?php echo $item->name; ?>">
            <?php endif; ?>
            <img src="<?php echo $item->params->get('imageurl');?>" alt="<?php echo $item->name; ?>" />
            <?php if(!empty($item->clickurl)): ?>
            </a>
            <?php endif; ?>
        </div>
        <?php $i++; ?>
    <?php endforeach; ?>
    </div>
    <a class="carousel-control left" href="#carousel" data-slide="prev">‹</a>
    <a class="carousel-control right" href="#carousel" data-slide="next">›</a>
    <ol class="carousel-indicators">
        <?php for($i = 0; $i < $listCount; $i++): ?>
            <li data-target="#carousel" data-slide-to="<?php echo $i; ?>"<?php echo $i == 0 ? ' class="active"' : ''; ?>></li>
        <?php endfor; ?>
      </ol>
</div>