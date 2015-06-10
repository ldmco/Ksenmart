<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<div class="catalog">
    <div class="pagination pagination-centered pagination-small letters js-letters">
        <ul>
            <li class="active"><a href="javascript:void(0);" data-letter="all" title="<?php echo JText::_('KSM_CATALOG_MANUFACTURERS_SHOW_ALL'); ?>"><?php echo JText::_('KSM_CATALOG_MANUFACTURERS_SHOW_ALL'); ?></a></li>
            <?php foreach($this->brands as $key => $letter){ ?>
            <li><a href="javascript:void(0);" data-letter="<?php echo $key; ?>" title="<?php echo $key; ?>"><?php echo $key; ?></a></li>
            <?php } ?>
        </ul>
    </div>
    <div class="row-fluid brands js-brands noTransition">
        <?php foreach($this->brands as $key => $letter){ ?>
        	<ul class="nav nav-list well" data-brands-letter="<?php echo $key; ?>">
                <li class="nav-header"><?php echo $key; ?></li>
        	<?php foreach($letter as $brand){ ?>
        		<li><a href="<?php echo JRoute::_('index.php?option=com_ksenmart&view=catalog&manufacturers[]='.$brand->id); ?>" title="<?php echo $brand->title; ?>">
                    <img src="<?php echo $brand->small_img; ?>" alt="<?php echo $brand->title; ?>" />
                    <?php echo $brand->title; ?>
                    </a>
                </li>
        	<?php } ?>
        	</ul>
        <?php } ?>
    </div>
</div>