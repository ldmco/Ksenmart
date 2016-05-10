<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<a data-level="<?php echo $item->level; ?>" class="ksm-module-categories-item-link" href="<?php echo $item->link; ?>"><?php echo htmlspecialchars($item->title); ?></a>