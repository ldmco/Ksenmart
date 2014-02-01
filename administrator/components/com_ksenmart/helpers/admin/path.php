<?php 
defined( '_JEXEC' ) or die;

class KMPath
{
	var $path_items=array();
	protected static $instance = null;
	
	function __construct() {
		$this->addItem('<div class="logo"></div>','index.php?option=com_ksenmart&widget_type=all');
	}
	
	public static function getInstance()
	{
		if (empty(self::$instance))
		{
			$instance = new KMPath();
			self::$instance = & $instance;
		}

		return self::$instance;
	}	
	
	function addItem($text='',$link='')
	{
		$this->path_items[]=array('text'=>$text,'link'=>$link);
	}
	
}
?>