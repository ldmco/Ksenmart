<?php defined( '_JEXEC' ) or die; ?>
<div class="catalog">
	<h3><?php echo $this->country->title;?></h3>
	<div class="catalog-description"><?php echo $this->country->content;?></div>
	<?php if (!empty($this->seo_text)):?>
	<div class="catalog-description"><?php echo $this->seo_text;?></div>
	<?php endif;?>
	<?php if (!empty($this->rows)){ ?>
        <ul class="nav nav-list">
        <?php foreach($this->rows as $manufacturer){ ?>
    		<li><a href="<?php echo JRoute::_('index.php?option=com_ksenmart&view=catalog&manufacturers[]='.$manufacturer->id.'&clicked=manufacturers');; ?>" title="<?php echo $manufacturer->title; ?>"><?php echo $manufacturer->title; ?></a></li>
    	<?php } ?>
        </ul>
	<?php }else{ ?>
		<?php echo $this->loadTemplate('nomanufacturers', 'default'); ?>
    <?php } ?>
</div>