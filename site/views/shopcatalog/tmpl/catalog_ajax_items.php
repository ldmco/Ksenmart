<?php defined('_JEXEC') or die('=;)');

if(!empty($this->rows)) {
    foreach($this->rows as $product) {
        echo KMSystem::loadTemplate(array('product' => $product, 'params' => $this->params));
    }
}