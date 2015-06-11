<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

jimport('joomla.application.component.viewlegacy');
abstract class JViewKSAdmin extends JViewLegacy {

    private $ext_prefix     = null;

    public function __construct($config = array()) {
        parent::__construct($config = array());

        global $ext_name_com, $ext_prefix;
        $this->ext_name_com = $ext_name_com;
        $this->ext_prefix   = $ext_prefix;

        $dispatcher = JDispatcher::getInstance();
        $dispatcher->trigger('onBeforeViewAdmin' . strtoupper($this->ext_prefix) . $this->getName(), array(&$this));

        $this->path = KSPath::getInstance();
        $this->params = JComponentHelper::getParams($this->ext_name_com);
        $this->document = JFactory::getDocument();
    }

    public function display($tpl = null) {
        $dispatcher = JDispatcher::getInstance();
        $dispatcher->trigger('onAfterViewAdmin' . strtoupper($this->ext_prefix) . $this->getName(), array(&$this));

        parent::display($tpl);
    }

    public function loadTemplate($tpl = null) {
        $name = $this->getName();
        $layout = $this->getLayout();
        $html = '';

        $function = isset($tpl) ? $layout . '_' . $tpl : $layout;
        $dispatcher = JDispatcher::getInstance();
        $dispatcher->trigger('onBeforeDisplayAdmin' . strtoupper($this->ext_prefix) . $name . $function, array(&$this, &$tpl, &$html));
        if($tpl != 'empty') $html .= parent::loadTemplate($tpl);

        $dispatcher->trigger('onAfterDisplayAdmin' . strtoupper($this->ext_prefix) . $name . $function, array(&$this, &$tpl, &$html));

        return $html;
    }
}