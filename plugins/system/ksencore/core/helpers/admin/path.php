<?php defined('_JEXEC') or die;

class KSPath {
    public $path_items = array();
    protected static $instance = null;

    public function __construct() {
        global $ext_name_com;
		$app = JFactory::getApplication();
		$extension=$app->getUserStateFromRequest('com_ksen.panel.default.extension', 'extension','com_ksen');			
        $this->addItem('<div class="logo"></div>', 'index.php?option=' . $ext_name_com . '&widget_type=all&extension='.$extension);
    }

    public static function getInstance() {
        if(empty(self::$instance)) {
            $instance = new KSPath();
            self::$instance = &$instance;
        }

        return self::$instance;
    }

    public function addItem($text = '', $link = '') {
        $this->path_items[] = array('text' => $text, 'link' => $link);
    }

}