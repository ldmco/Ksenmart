<?php defined( '_JEXEC' ) or die;

abstract class KsenTable extends JTable {
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