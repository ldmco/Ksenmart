<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<div class="search_page clearfix">
    <h2 class="title">Результаты поиска:</h2>
    <?php if($this->products || $this->cat_search || $this->manufacture_search){ ?>
    <div class="search_info lead">
        Найденно <?php echo !empty($this->products)?$this->pagination->total.' товаров':''; ?><?php echo !empty($this->cat_search)?', '.count($this->cat_search).' категорий':''; ?><?php echo !empty($this->manufacture_search)?' и '.count($this->manufacture_search).' производитель':''; ?>
    </div>
    <?php } ?>
    <?php echo $this->loadTemplate('cat_search'); ?>
    <?php echo $this->loadTemplate('manufacture_search'); ?>
    <?php echo $this->loadTemplate('results'); ?>
    <?php if(!empty($this->model->_correct_string)): ?>
        <div class="correct">
        Возможно вы ищите "<span><?php echo $this->model->_correct_string; ?></span>";
        </div>
    <?php endif; ?>
</div>