<?php defined('_JEXEC') or die('=;)');

if(!empty($this->rows)) {
    foreach($this->rows as $product) {
        echo KSSystem::loadTemplate(array('product' => $product, 'params' => $this->params));
    }
}