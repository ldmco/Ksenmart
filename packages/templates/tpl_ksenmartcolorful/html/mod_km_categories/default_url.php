<?php
/**
 * @package		Joomla.Site
 * @subpackage	mod_menu
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

?>
<a level="<?php echo $item->level; ?>" class="ksenmart-categories-item-link<?php echo ($item->children) ? ' accordion-toggle collapsed' : ''; ?>" href="<?php echo $item->link; ?>">
	<?php echo htmlspecialchars($item->title); ?>
</a>
<?php if ($item->children): ?>
	<b class="caret pull-right"></b>
<?php endif ?>