<?php
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
$layout = new JLayoutFile('nav_tab_content');
?>
<?php if(!empty($this->product->content)){ ?>
    <div <?php echo JMicrodata::htmlProperty('description'); ?> class="ksm-product-tabs-content ksm-product-tabs-content-description active" id="tab1">
        <?php echo html_entity_decode($this->product->content); ?>
    </div>
<?php } ?>
<div class="ksm-product-tabs-content <?php echo empty($this->product->content)?' active':''; ?>" id="tab3">
    <?php echo $this->loadTemplate('comments','product');?>
</div>
<?php if ($this->params->get('show_comment_form') == 1){ ?>
    <div class="ksm-product-tabs-content" id="tab4">
        <?php echo $this->loadTemplate('comment_form','product');?>
    </div>
<?php } ?>
