<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
jimport('joomla.application.component.controller');
class KsenMartController extends JControllerLegacy {
    
    protected $default_view = 'catalog';
    
    public function display($cachable = false, $urlparams = false) {
        
        $cachable = true;
        $document = JFactory::getDocument();
        $viewType = $document->getType();
        $viewName = JRequest::getCmd('view', $this->default_view);
        $viewLayout = JRequest::getCmd('layout', 'default');
        
        if ($viewName == 'profile' || $viewName == 'cart') $cachable = false;
        
        $safeurlparams = array('categories' => 'ARRAY', 'manufacturers' => 'ARRAY', 'properties' => 'ARRAY', 'countries' => 'ARRAY', 'price_less' => 'INT', 'price_more' => 'INT', 'title' => 'STRING', 'new' => 'INT', 'hot' => 'INT', 'promotion' => 'INT', 'recommendation' => 'INT', 'order_type' => 'STRING', 'order_dir' => 'STRING', 'limit' => 'UINT', 'limitstart' => 'UINT', 'id' => 'INT', 'lang' => 'CMD');
        
        $view = $this->getView($viewName, $viewType, '', array('base_path' => $this->basePath, 'layout' => $viewLayout));
        
        if ($model = $this->getModel($viewName)) {
            $view->setModel($model, true);
        }
        
        $view->document = $document;
        $view->setLayout($viewLayout);
        
        $conf = JFactory::getConfig();
        
        parent::display($cachable, $safeurlparams);
        
        return $this;
    }
    
    function get_layouts() {
        $view = JRequest::getVar('view');
        $layouts = JRequest::getVar('layouts', array());
        $response = array();
        
        $model = $this->getModel($view);
        $view = $this->getView($view, 'html');
        $view->setModel($model, true);
        
        foreach ($layouts as $layout) {
            $view->setLayout($layout);
            
            ob_start();
            $view->display();
            $response[$layout] = ob_get_contents();
            ob_end_clean();
        }
        
        $response = json_encode($response);
        JFactory::getDocument()->setMimeEncoding('application/json');
        JFactory::getApplication()->close($response);
    }
    
    public function pluginAction() {
        
        $app        = JFactory::getApplication();
        $format     = strtolower($this->input->getWord('format'));
        $results    = null;
        $parts      = null;

        // Check for valid format
        if (!$format) {
            $results = new InvalidArgumentException('Please specify response format other that HTML (json, raw, etc.)', 404);
        } elseif ($this->input->get('plugin')) {
            $plugin = ucfirst($this->input->get('plugin'));
            $action = ucfirst($this->input->get('action'));
            $dispatcher = JEventDispatcher::getInstance();
            
            try {
                $results = $dispatcher->trigger('onAjax' . $plugin . $action);
                $results = $results[0];
            }
            catch(Exception $e) {
                $results = $e;
            }
        }
        // Return the results in the desired format
        switch ($format) {
            // JSONinzed
            case 'json':
                $app->close(new JResponseJson($results, null, false, $this->input->get('ignoreMessages', true, 'bool')));
            break;

            // Human-readable format
            case 'debug':
                $app->close('<pre>' . print_r($results, true) . '</pre>');
            break;
            
            // Handle as raw format
            default:
                // Output exception
                if ($results instanceof Exception) {
                    // Log an error
                    JLog::add($results->getMessage(), JLog::ERROR);
                    // Set status header code
                    $app->setHeader('status', $results->getCode(), true);
                    // Echo exception type and message
                    $out = get_class($results) . ': ' . $results->getMessage();
                }
                // Output string/ null
                elseif (is_scalar($results)) {
                    $out = (string)$results;
                }
                // Output array/ object
                else {
                    $out = implode((array)$results);
                }
                
                $app->close($out);
            break;
        }
    }
}
