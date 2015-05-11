<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<div class="relevants">
<?php
    echo $this->loadTemplate('cat_search');
    echo $this->loadTemplate('manufacture_search');
?>
</div>
<?php echo $this->loadTemplate('results'); ?>