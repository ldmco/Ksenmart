<?php defined( '_JEXEC' ) or die; ?>
<div class="search_page clearfix">
    <h2 class="title">Результаты поиска:</h2>
    <?php if($this->products || $this->cat_search || $this->manufacture_search){ ?>
    <div class="search_info lead">
        Найденно <?php echo !empty($this->products)?count($this->products).' товаров':''; ?><?php echo !empty($this->cat_search)?', '.count($this->cat_search).' категорий':''; ?><?php echo !empty($this->manufacture_search)?' и '.count($this->manufacture_search).' производитель':''; ?>
    </div>
    <?php } ?>
    <?php
        //echo $this->loadTemplate('relevant_search');
        echo $this->loadTemplate('cat_search');
        echo $this->loadTemplate('manufacture_search');
        echo $this->loadTemplate('results');
    ?>
</div>