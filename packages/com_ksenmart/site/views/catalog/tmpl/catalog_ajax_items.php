<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

if(!empty($this->rows)) {
    foreach($this->rows as $product) {
        echo KSSystem::loadTemplate(array('product' => $product, 'params' => $this->params));
    }
}