<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

class KsenmartTable extends JTable {
	function bindCheckStore($src, $ignore = null){
		if (!$this->bind($src, $ignore)){
			return false;
		}
		if(!$this->check()) {
			return false;
		}
		if (!$this->store()){
			return false;
		}
		return true;
	}
}