<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

class KsenControllerAdmin extends JControllerAdmin {
    
    protected $default_view = 'panel';
    
    private $ext_name = null;
    private $ext_name_com = null;
    
    public function __construct($config = array()) {
        parent::__construct($config);
        
        global $ext_name, $ext_name_com;
        $this->ext_name = $ext_name;
        $this->ext_name_com = JFactory::getApplication()->input->get('extension', $ext_name_com, 'string');
    }
    
    public function display($cachable = false, $urlparams = false) {
        $document = JFactory::getDocument();
        $viewType = $document->getType();
        $option = JRequest::getCmd('option', 'com_ksen');
        $viewName = JRequest::getCmd('view', $this->default_view);
        $viewLayout = JRequest::getCmd('layout', 'default');
        if ($option != 'com_ksen' && $viewName == 'panel')
        {
            $this->setRedirect('index.php?option=com_ksen&extension='.$option);
            return true;        
        }
        
        $view = $this->getView($viewName, $viewType, '', array('base_path' => $this->basePath, 'layout' => $viewLayout));
        
        if ($model = $this->getModel($viewName)) {
            $view->setModel($model, true);
            $view->state = $view->get('State');
        }
        
        $view->setLayout($viewLayout);
        $conf = JFactory::getConfig();
        $view->display();
        
        return $this;
    }
    
    public function closePopup($on_close = '') {
        echo '
        <script type="text/javascript">
        ' . $on_close . '
        window.parent.closePopupWindow();
        </script>';
        JFactory::getApplication()->close();
    }
    
    public function get_list_items() {
        $viewname = JRequest::getVar('view');
        $item_tpl = JRequest::getVar('item_tpl');
        $no_items_tpl = JRequest::getVar('no_items_tpl');
        $html = '';
        $total = 0;
        $no_items = false;
        
        $view = $this->getView($viewname, 'html');
        $model = $this->getModel($viewname);
        $view->setModel($model, true);
        $view->state = $model->getState();
        $items = $model->getListItems();
        $total = $model->getTotal();
        if (count($items) > 0) {
            $view->setLayout($item_tpl);
            foreach ($items as $item) {
                $view->item = & $item;
                ob_start();
                $view->display();
                $html.= ob_get_contents();
                ob_end_clean();
            }
        } else {
            $view->setLayout($no_items_tpl);
            ob_start();
            $view->display();
            $html.= ob_get_contents();
            ob_end_clean();
            $no_items = true;
        }
        $response = array('html' => $html, 'total' => $total, 'no_items' => $no_items);
        $response = json_encode($response);
        JFactory::getDocument()->setMimeEncoding('application/json');
        echo $response;
        JFactory::getApplication()->close();
    }
    
    public function sort_list_items() {
        $table = JRequest::getCmd('table');
        $items = JRequest::getVar('items', array());
        $message = array();
        $errors = 0;
        
        JTable::addIncludePath(JPATH_ADMINISTRATOR . DS . 'components' . DS . $this->ext_name_com . DS . 'tables');
        $table = JTable::getInstance($table, ucfirst($this->ext_name) . 'Table', array());
        if (!$table) {
            $message[] = JText::_('KSM_CANNOT_GET_TABLE');
            $errors++;
        }
        if (!$errors) {
            foreach ($items as $item_id => $ordering) {
                if (!$table->load($item_id)) {
                    $message[] = JText::_('KSM_CANNOT_INVALID_PRIMARY_KEY');
                    $errors++;
                }
                $table->ordering = $ordering;
                if (!$table->store()) {
                    $message[] = JText::_('KSM_CANNOT_STORE_DATA');
                    $errors++;
                }
            }
        }
        if (!$errors) $message[] = JText::_('KSM_SORT_OK');
        else $message[] = JText::_('KSM_SORT_FAILURE');
        
        $response = array('message' => $message, 'errors' => $errors);
        $response = json_encode($response);
        JFactory::getDocument()->setMimeEncoding('application/json');
        echo $response;
        JFactory::getApplication()->close();
    }
    
    public function save_list_items() {
        $table = JRequest::getCmd('table');
        $items = JRequest::getVar('items', array());
        $message = array();
        $errors = 0;
        
        JTable::addIncludePath(JPATH_ADMINISTRATOR . DS . 'components' . DS . $this->ext_name_com . DS . 'tables');
        $table = JTable::getInstance($table, ucfirst($this->ext_name) . 'Table', array());
        if (!$table) {
            $message[] = JText::_('KSM_CANNOT_GET_TABLE');
            $errors++;
        }
        if (!$errors) {
            foreach ($items as $item_id => $item) {
                if (!$table->load($item_id)) {
                    $message[] = JText::_('KSM_CANNOT_INVALID_PRIMARY_KEY');
                    $errors++;
                }
                foreach ($item as $field => $value) {
                    if ($field == 'ordering' && $table->ordering != $value) {
                        $db = JFactory::getDBO();
                        $query = $db->getQuery(true);
                        $query->update($table->getTableName());
                        if ($value > $table->ordering) $query->set('ordering=ordering-1')->where('ordering>' . $table->ordering)->where('ordering<=' . $value);
                        else $query->set('ordering=ordering+1')->where('ordering>=' . $value)->where('ordering<' . $table->ordering);
                        $db->setQuery($query);
                        $db->query();
                    }
                    $table->{$field} = $value;
                }
                if (!$table->store()) {
                    $message[] = JText::_('KSM_CANNOT_STORE_DATA');
                    $errors++;
                }
            }
        }
        if (!$errors) $message[] = JText::_('KSM_SAVE_OK');
        else $message[] = JText::_('KSM_SAVE_FAILURE');
        
        $response = array('message' => $message, 'errors' => $errors);
        $response = json_encode($response);
        JFactory::getDocument()->setMimeEncoding('application/json');
        JFactory::getApplication()->close($response);
    }
    
    public function delete_list_items() {
        $view = JRequest::getVar('view');
        $items = JRequest::getVar('items', array());
        $message = array();
        $errors = 0;
        
        $model = $this->getModel($view);
        if (!$model->deleteListItems($items)) {
            $errors++;
            $message[] = $model->getErrors();
        }
        $response = array('message' => $message, 'errors' => $errors);
        $response = json_encode($response);
        JFactory::getDocument()->setMimeEncoding('application/json');
        echo $response;
        JFactory::getApplication()->close();
    }
    
    public function copy_list_items() {
        $view = JRequest::getVar('view');
        $items = JRequest::getVar('items', array());
        $message = array();
        $errors = 0;
        
        $model = $this->getModel($view);
        if (!$model->copyListItems($items)) {
            $errors++;
            $message[] = $model->getErrors();
        }
        $response = array('message' => $message, 'errors' => $errors);
        $response = json_encode($response);
        JFactory::getDocument()->setMimeEncoding('application/json');
        echo $response;
        JFactory::getApplication()->close();
    }
    
    public function save_form_item() {
        $mvc = JRequest::getVar('mvc', JRequest::getVar('view', null));
        $formname = JRequest::getVar('formname', JRequest::getVar('layout', null));
        $close = JRequest::getVar('close', 1);
        
        $model = $this->getModel($mvc);
        $data = JRequest::getVar('jform', array(), 'post', 'array');
        
        $model->form = $formname;
        $form = $model->getForm();
        if (!$form) {
            JError::raiseError(500, $model->getError());
            return false;
        }
        $id = (int)$data['id'];
        $data = $model->validate($form, $data);
        
        if ($data === false) {
            $this->setRedirect('index.php?option=' . $this->ext_name_com . '&view=' . $mvc . '&layout=' . $formname . '&id=' . $id . '&tmpl=component', JText::_('KSM_SERVER_SIDE_VALIDATION_ERROR'));
            return false;
        }
        
        $method = 'save' . $formname;
        if (!$return = $model->{$method}($data)) {
            $this->setRedirect('index.php?option=' . $this->ext_name_com . '&view=' . $mvc . '&layout=' . $formname . '&id=' . $id . '&tmpl=component', JText::_('KSM_SERVER_SIDE_SAVE_ERROR') . implode('<br>', $model->getErrors()));
            return false;
        }
        $id = $return['id'];
        $on_close = $return['on_close'];
        
        if (!$close) {
            $this->setRedirect('index.php?option=' . $this->ext_name_com . '&view=' . $mvc . '&layout=' . $formname . '&id=' . $id . '&tmpl=component');
            return true;
        }
        
        $this->closePopup($on_close);
    }
    
    public function get_form_fields() {
        $model = JRequest::getVar('model');
        $form = JRequest::getVar('form');
        $fields = JRequest::getVar('fields', array());
        $vars = JRequest::getVar('vars', array(), 'get', 'array');
        
        $model = $this->getModel($model);
        $item = $model->{'get' . $form}($vars);
        $model->form = $form;
        $form = $model->getForm();
        if ($form) $form->bind($item);
        
        $response = array();
        foreach ($fields as $field) $response[$field] = $form->getInput($field);
        
        $response = json_encode($response);
        JFactory::getDocument()->setMimeEncoding('application/json');
        echo $response;
        JFactory::getApplication()->close();
    }
    
    public function delete_module_item() {
        $model = JRequest::getVar('model');
        $item = JRequest::getVar('item');
        $id = JRequest::getVar('id');
        $message = array();
        $errors = 0;
        
        $model = $this->getModel($model);
        $method = 'delete' . strtoupper($item);
        if (!$model->{$method}($id)) {
            $errors++;
            $message[] = $model->getErrors();
        }
        
        $response = array('message' => $message, 'errors' => $errors);
        $response = json_encode($response);
        JFactory::getDocument()->setMimeEncoding('application/json');
        echo $response;
        JFactory::getApplication()->close();
    }
    
    public function update_module() {
        $module = JRequest::getVar('module');
        $message = array();
        $errors = 0;
        $html = '';
        
        $html = KSSystem::loadModule($module);
        
        $response = array('html' => $html, 'message' => $message, 'errors' => $errors);
        $response = json_encode($response);
        JFactory::getDocument()->setMimeEncoding('application/json');
        JFactory::getApplication()->close($response);
    }
    
    public function getKSVersion() {
        $kmdestination = JPATH_ROOT . '/administrator/components/' . $this->ext_name_com;
        
        $tmpInstaller = new JInstaller;
        $tmpInstaller->setPath('source', $kmdestination);
        $manifest = $tmpInstaller->getManifest();
        
        $info = new stdClass();
        $info->name = JText::_($manifest->name);
        $info->version = (string)$manifest->version;
        
        JFactory::getApplication()->close(json_encode($info));
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