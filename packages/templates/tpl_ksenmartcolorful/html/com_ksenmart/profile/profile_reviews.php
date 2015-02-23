<?php defined('_JEXEC') or die(); ?>
<?php if(!$this->show_shop_review){ ?>
    <?php echo $this->loadTemplate('addshopreview'); ?>
<?php }else{ ?>
    <?php echo $this->loadTemplate('shopreview'); ?>
<?php } ?>
<?php
if(!empty($this->reviews)){ ?>
    <h2><?php echo JText::_('KSM_PRODUCTS_REVIEW_TITLE'); ?></h2>
    <?php foreach($this->reviews AS $review){ ?>    
        <?php echo $this->loadTemplate('review', 'profile', array('review' => $review)); ?>
    <?php } ?>
<?php }else{ ?>
    <h2 class="text-center">Нет отзывов</h2>
<?php } ?>
<div class="pagination">
    <?php echo $this->pagination->getPagesLinks(); ?>
</div>