<?php
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>
<div class="row <?php echo $element['name']; ?>">
    <div class="ksm-slidemodule-<?php echo $element['type']; ?> slide_module <?php echo $element['class']; ?>">
        <div class="module-head">
            <label><?php echo JText::_($element['label']); ?></label>
			<?php if ($element['add_button']) { ?>
                <a class="add km-modal" rel='{"x":"90%","y":"90%"}'
                   href="<?php echo JRoute::_('index.php?option=com_ksenmart&view=' . $element['view'] . '&layout=' . $element['item_type'] . '&tmpl=component'); ?>"></a>
			<?php } ?>
			<?php if ($element['remove_button']) { ?>
                <a class="remove" href="#"><?php echo JText::_('KS_SLIDEMODULE_REMOVE_ALL'); ?></a>
			<?php } ?>
            <a class="show_module_content" onclick="shSlideModuleContent(this);return false;"></a>
        </div>
        <div class="module-content no-favorites">
            <div class="lists">
                <div class="row">
					<?php echo $html; ?>
                </div>
            </div>
        </div>
    </div>
</div>