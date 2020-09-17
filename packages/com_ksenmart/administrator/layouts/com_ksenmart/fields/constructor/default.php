<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

extract($displayData);
?>
<style>
    .holst {
        width: 800px;
        max-width: 800px;
    }
    #constructor_block {
        position: relative;
        padding: 0;
        margin: 0;
    }
    .constructor-marker {
        position: absolute !important;
        background: red;
        border-radius: 100%;
        color: #fff;
        display: block;
        width: 18px;
        height: 18px;
        line-height: 18px;
        text-align: center;
    }
    #constructor_block canvas {
        //width: 100%;
        position: absolute;
        left: 0;
        top: 0;
    }
    .constructor_units .del {
        position: relative;
    }
</style>

<div class="row-fluid">
    <div id="constructor_block" data-marker_count="<?php echo (count($units) + 1); ?>">
        <img class="holst" src="<?php echo $image->img; ?>" />
        <canvas id="constructor_image"></canvas>
        <?php foreach ($units as $unit): ?>
	        <span class="constructor-marker"
                  data-unit_id="<?php echo $unit->number; ?>"
                  data-left="<?php echo $unit->left; ?>"
                  data-top="<?php echo $unit->top; ?>"
                  data-left_line="<?php echo $unit->left_line; ?>"
                  data-top_line="<?php echo $unit->top_line; ?>"
                  data-text="<?php echo JText::_('KSM_CONSTRUCTOR_UNIT_DELETE'); ?>"
                  style="left: <?php echo $unit->left; ?>px; top: <?php echo $unit->top; ?>px;"><?php echo $unit->number; ?></span>
        <?php endforeach; ?>
    </div>
</div>
