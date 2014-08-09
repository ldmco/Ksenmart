<?php defined('_JEXEC') or die;
/**
 *
 * @package     Joomla.Plugin
 * @subpackage  System.Ksencore
 * @since       2.5+
 * @author      TakT
 */
class plgSystemKsenCore extends JPlugin {
    
    protected $autoloadLanguage = true;
    
    public function onLoadKsen($ext_name_local, array $hFolders = array() , array $ignoreHelpers = array() , array $config = array()) {
        global $ext_name, $ext_name_com, $ext_prefix;
        $ext_name = $ext_name_local;
        if (strripos($ext_name_local, '.') !== false) {
            list($ext_name, $ext_prefix) = explode('.', $ext_name_local);
        }

        $ext_name_com = 'com_' . $ext_name;
        $document = JFactory::getDocument();
        $version  = new JVersion();
        $option   = JFactory::getApplication()->input->get('option', null, 'string');
        
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
            
            KSSystem::addJS(array(
                'hammer.min'
            ));
            $document->addScript('//ajax.googleapis.com/ajax/libs/angularjs/1.2.13/angular.js');
            $document->addScript('//code.angularjs.org/1.2.13/i18n/angular-locale_ru.js');
            $document->addStylesheet(JUri::root() . 'administrator/modules/mod_ks_mainmenu/css/default.css');

            KSSystem::addJS(array(
                'angular-ui-router.min',
                'angular-animate',
                'angular-sanitize',
                'angular-file-upload',
                'angular-hammer',
                'ui-bootstrap-custom-0.10.0.min', 
                'ui-bootstrap-custom-tpls-0.10.0.min',
                'app',
                'states'
                
            ));
            $document->addScript('components/' . $ext_name_com . '/assets/js/app.js');
            $document->addScript('components/' . $ext_name_com . '/assets/js/states.js');
            
            KSSystem::addCSS(array('ng-table'));
            KSSystem::addJS(array(
                'directives/ngRepeatReorder',
                'directives/ng-table',
                'directives/loadingContainer',
                'directives/progressRouter',
                'directives/ngThumb',
                'directives/ngBindModel',
                'directives/sortable',
                'directives/ngJlangBind',

                'filtres/filterItems',
                'filtres/categoriesFilter',

                'controllers/ModalCtrl',
                'controllers/ModalInnerCtrl',
                'controllers/CategoryChangeCtrl',
                'controllers/ProductRelationsCtrl',
                'controllers/getFileUploadCtrl',
                'controllers/KSListCtrl',
                'controllers/KSForm',
                'controllers/ProductChildsCtrl',
                'controllers/KSMainMenuCtrl',
                'controllers/KSListInnerCtrl',
                'controllers/KSFieldCtrl',
                'controllers/KSFieldTreeCtrl',
                'factories/ItemsFactory'
            ));
        }
        
        $script = '
            var KS = {
                extension: \'' . $ext_name_com . '\',
                option: \'' . $ext_name_com . '\'
            };
        ';
        $document->addScriptDeclaration($script);
        
        KSSystem::loadPlugins();
        KSSystem::import('libraries.ksdb');
    }
}