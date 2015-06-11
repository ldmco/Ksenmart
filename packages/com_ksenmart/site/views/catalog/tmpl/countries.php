<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<div class="catalog">
    <div class="row-fluid brands js-brands noTransition">
        <?php foreach($this->brands as $country => $brands){ ?>
        	<ul class="nav nav-list well" data-brands-letter="<?php echo $key; ?>">
                <li class="nav-header"><?php echo $country; ?></li>
        	<?php foreach($brands as $brand){ ?>
        		<li><a href="<?php echo JRoute::_('index.php?option=com_ksenmart&view=catalog&manufacturers[]='.$brand->id); ?>" title="<?php echo $brand->title; ?>"><?php echo $brand->title; ?></a></li>
        	<?php } ?>
        	</ul>
        <?php } ?>
    </div>
</div>