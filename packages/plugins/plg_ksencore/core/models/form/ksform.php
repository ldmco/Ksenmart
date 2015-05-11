<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

class JKSForm extends JForm {

    private $ext_prefix = null;

    public function __construct($name, array $options = array()){
        parent::__construct($name, $options);

        global $ext_prefix;
        $this->ext_prefix   = $ext_prefix;
    }

    public static function getInstance($name, $data = null, $options = array(), $replace = true, $xpath = false) {

        global $ext_prefix;

        $formname = explode('.', $name);
        $formname = $formname[count($formname) - 1];

        $dispatcher = JDispatcher::getInstance();
        $dispatcher->trigger('onBeforeGet' . strtoupper($ext_prefix) . 'Form' . ucfirst($formname), array(&$this, &$name, &$data, &$options, &$replace, &$xpath));
        // Reference to array with form instances
        $forms = &self::$forms;

        // Only instantiate the form if it does not already exist.
        if(!isset($forms[$name])) {

            $data = trim($data);

            if(empty($data)) {
                throw new Exception(JText::_('JLIB_FORM_ERROR_NO_DATA'));
            }

            // Instantiate the form.
            $forms[$name] = new JKSForm($name, $options);

            // Load the data.
            if(substr(trim($data), 0, 1) == '<') {
                if($forms[$name]->load($data, $replace, $xpath) == false) {
                    throw new Exception(JText::_('JLIB_FORM_ERROR_XML_FILE_DID_NOT_LOAD'));

                    return false;
                }
            } else {
                if($forms[$name]->loadFile($data, $replace, $xpath) == false) {
                    throw new Exception(JText::_('JLIB_FORM_ERROR_XML_FILE_DID_NOT_LOAD'));

                    return false;
                }
            }
        }
        $dispatcher->trigger('onAfterGet' . strtoupper($ext_prefix) . 'Form' . ucfirst($formname), array(&$this, &$forms[$name]));

        return $forms[$name];
    }

    public function getLabel($name, $group = null) {
        
        $dispatcher = JDispatcher::getInstance();
        $formname   = $this->getName();
        $formname   = explode('.', $formname);
        $formname   = $formname[count($formname) - 1];
        $html       = '';

        $dispatcher->trigger('onBeforeGet' . strtoupper($this->ext_prefix) . 'FormLabel' . ucfirst($formname) . ucfirst($name), array(&$this, &$name, &$html));
        if($name != 'empty' && $field = $this->getField($name, $group)) {
            $html .= $field->label;
        }

        $dispatcher->trigger('onAfterGet' . strtoupper($this->ext_prefix) . 'FormLabel' . ucfirst($formname) . ucfirst($name), array(&$this, &$name, &$html));
        return $html;
    }

    public function getInput($name, $group = null, $value = null) {

        $dispatcher = JDispatcher::getInstance();
        $formname   = $this->getName();
        $formname   = explode('.', $formname);
        $formname   = $formname[count($formname) - 1];
        $html       = '';

        $dispatcher->trigger('onBeforeGet' . strtoupper($this->ext_prefix) . 'FormInput' . ucfirst($formname) . ucfirst($name), array(&$this, &$name, &$html));
        if($name != 'empty' && $field = $this->getField($name, $group, $value)) {
            
            $element    = $this->findField($name, $group);
            $field_html = $field->input;

            if(isset($element['wrap']) && !empty($element['wrap'])) {
                $field_html = KSSystem::wrapFormField($element['wrap'], $element, $field_html);
            }
            $html .= $field_html;
        }

        $dispatcher->trigger('onAfterGet' . strtoupper($this->ext_prefix) . 'FormInput' . ucfirst($formname) . ucfirst($name), array(&$this, &$name, &$html));
        return $html;
    }
}