<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

class plgSystemKsenCore extends JPlugin {
    
    protected $autoloadLanguage = true;
    private $input = null;
    
    public function onLoadKsen($ext_name_local, array $hFolders = array() , array $ignoreHelpers = array() , array $config = array()) {
        global $ext_name, $ext_name_com, $ext_prefix;
        $ext_name = $ext_name_local;
        if (strripos($ext_name_local, '.') !== false) {
            list($ext_name, $ext_prefix) = explode('.', $ext_name_local);
        }

        $ext_name_com = 'com_' . $ext_name;
        $document = JFactory::getDocument();
        $version  = new JVersion();
        $this->input = JFactory::getApplication()->input;
        $option   = $this->input->get('option', null, 'string');
        $extension   = $this->input->get('extension', $ext_name_com, 'string');
        
        include_once dirname(__FILE__) . '/core/defines.php';
        include_once KSC_ADMIN_PATH_CORE_HELPERS . 'helper.php';
    
        KSLoader::loadCoreHelpers($hFolders, $ignoreHelpers);
        
        if (!isset($config['admin'])) {
            $config['admin'] = false;
        }

        JHtml::_('behavior.keepalive');
        if($version->RELEASE < 3.0){

        }else{
            JHtml::_('jquery.framework');
            JHtml::_('jquery.ui');
            JHtml::_('bootstrap.framework');
        }
        
        if ($config['admin']) {
            KSSystem::addCSS(array(
                'style',
                'prog-style',
                'nprogress',
                'ui-lightness/jquery-ui-1.8.20.custom'
            ));

            if($version->RELEASE >= 3.0){
                JHtml::_('jquery.ui', array('sortable'));
                JHtml::_('bootstrap.framework');
                JHtml::_('behavior.framework');
            }else{
                $document->addScript('http://ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js');
            }
            KSSystem::addJS(array('common', 'style', 'nprogress',  'list', 'listmodule'));
        }
        
        if ($this->params->get('angularJS', 1) && $config['angularJS']) {
            
        }
        
        $script = '
            var KS = {
                extension: \'' . $extension . '\',
                option: \'' . $ext_name_com . '\'
            };
        ';
        $document->addScriptDeclaration($script);
        
        KSSystem::loadPlugins();
        KSSystem::import('libraries.ksdb');
    }
}