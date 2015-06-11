<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

class KsenMartControllerCatalog extends KsenMartController {
    
    function get_childs() {
        $db = JFactory::getDBO();
        $id = JRequest::getInt('id');
        $layout = JRequest::getVar('layout', 'default');
        $html = '';
        
        $model = $this->getModel('catalog');
        $view = $this->getView('catalog', 'html');
        $view->setModel($model, true);
        $query = $db->getQuery(true);
        $query->select('p.*')->from('#__ksenmart_products as p')->where('p.parent_id=' . $id)->order('p.ordering');
        $query = KSMedia::setItemMainImageToQuery($query);
        $db->setQuery($query);
        $items = $db->loadObjectList();
        foreach ($items as $item) {
            $item->small_img = KSMedia::resizeImage($item->filename, $item->folder, $model->params->get('admin_product_thumb_image_width', 36), $model->params->get('admin_product_thumb_image_heigth', 36), json_decode($item->params, true));
            $item->medium_img = KSMedia::resizeImage($item->filename, $item->folder, $model->params->get('admin_product_medium_image_width', 120), $model->params->get('admin_product_medium_image_heigth', 120), json_decode($item->params, true));
            $view->setLayout($layout . '_item_form');
            $view->item = & $item;
            ob_start();
            $view->display();
            $html.= ob_get_contents();
            ob_end_clean();
        }
        
        $response = array('html' => $html, 'message' => array(), 'errors' => 0);
        $response = json_encode($response);
        JFactory::getDocument()->setMimeEncoding('application/json');
        echo $response;
        JFactory::getApplication()->close();
    }
    
    function get_set_childs() {
        $db = JFactory::getDBO();
        $id = JRequest::getInt('id');
        $html = '';
        
        $model = $this->getModel('catalog');
        $view = $this->getView('catalog', 'html');
        $view->setModel($model, true);
        $query = $db->getQuery(true);
        $query->select('p.*,pp.product_id as set_id')->from('#__ksenmart_products as p')->innerjoin('#__ksenmart_products_relations as pp on pp.relative_id=p.id')->where('pp.product_id=' . $id)->where('pp.relation_type=' . $db->quote('set'))->order('p.ordering');
        $query = KSMedia::setItemMainImageToQuery($query);
        $db->setQuery($query);
        $items = $db->loadObjectList();
        foreach ($items as $item) {
            $item->small_img = KSMedia::resizeImage($item->filename, $item->folder, $model->params->get('admin_product_thumb_image_width'), $model->params->get('admin_product_thumb_image_heigth'), json_decode($item->params, true));
            $item->medium_img = KSMedia::resizeImage($item->filename, $item->folder, $model->params->get('admin_product_medium_image_width'), $model->params->get('admin_product_medium_image_heigth'), json_decode($item->params, true));
            $view->setLayout('default_item_form');
            $view->item = & $item;
            ob_start();
            $view->display();
            $html.= ob_get_contents();
            ob_end_clean();
        }
        
        $response = array('html' => $html, 'message' => array(), 'errors' => 0);
        $response = json_encode($response);
        JFactory::getDocument()->setMimeEncoding('application/json');
        echo $response;
        JFactory::getApplication()->close();
    }
    
    function get_properties() {
        $categories = JRequest::getVar('categories', array());
        JArrayHelper::toInteger($categories);
        $model = $this->getModel('catalog');
        $product = $model->getProduct($categories);
        $model->form = 'product';
        $form = $model->getForm();
        if ($form) $form->bind($product);
        $response = array('html' => $form->getInput('properties'));
        $response = json_encode($response);
        JFactory::getDocument()->setMimeEncoding('application/json');
        echo $response;
        JFactory::getApplication()->close();
    }
    
    function delete_child_group() {
        $group_id = JRequest::getInt('group_id');
        $model = $this->getModel('catalog');
        $model->deleteChildGroup($group_id);
        JFactory::getApplication()->close();
    }
    
    public function get_search_items_html() {
        $ids = JRequest::getVar('ids');
        $items_tpl = JRequest::getVar('items_tpl');
        $html = '';
        
        $model = $this->getModel('catalog');
        $view = $this->getView('catalog', 'html');
        $view->setModel($model, true);
        $items = $model->getProducts($ids);
        $total = count($items);
        if ($total > 0) {
            $view->setLayout($items_tpl);
            foreach ($items as $item) {
                $view->item = & $item;
                ob_start();
                $view->display();
                $html.= ob_get_contents();
                ob_end_clean();
            }
        }
        
        $response = array('html' => $html, 'total' => $total);
        $response = json_encode($response);
        JFactory::getDocument()->setMimeEncoding('application/json');
        echo $response;
        JFactory::getApplication()->close();
    }
    
    public function installSampleData() {
        // Get the application
        /* @var InstallationApplicationWeb $app */
        
        $app = JFactory::getApplication();
        $db = JFactory::getDBO();
        
        $schema = JPATH_ROOT . DIRECTORY_SEPARATOR . 'administrator' . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_ksenmart' . DIRECTORY_SEPARATOR . 'sql' . DIRECTORY_SEPARATOR . 'sample_data_ksenmart_ru.sql';
        $return = true;
        // Get the contents of the schema file.
        if (!($buffer = file_get_contents($schema))) {
            $app->enqueueMessage($db->getErrorMsg(), 'notice');
            return false;
        }
        // Get an array of queries from the schema and process them.
        $queries = $this->_splitQueries($buffer);
        
        foreach ($queries as $query) {
            // Trim any whitespace.
            $query = trim($query);
            // If the query isn't empty and is not a MySQL or PostgreSQL comment, execute it.
            if (!empty($query) && ($query{0} != '#') && ($query{0} != '-')) {
                // Execute the query.
                $db->setQuery($query);
                
                try {
                    $db->execute();
                }
                catch(RuntimeException $e) {
                    $app->enqueueMessage($e->getMessage(), 'notice');
                    $return = false;
                }
            }
        }
        $app->redirect('index.php?option=com_ksenmart');
    }
    
    protected function _splitQueries($query) {
        $buffer = array();
        $queries = array();
        $in_string = false;
        // Trim any whitespace.
        $query = trim($query);
        // Remove comment lines.
        $query = preg_replace("/\n\#[^\n]*/", '', "\n" . $query);
        // Remove PostgreSQL comment lines.
        $query = preg_replace("/\n\--[^\n]*/", '', "\n" . $query);
        // Find function
        $funct = explode('CREATE OR REPLACE FUNCTION', $query);
        // Save sql before function and parse it
        $query = $funct[0];
        // Parse the schema file to break up queries.
        for ($i = 0;$i < strlen($query) - 1;$i++) {
            if ($query[$i] == ";" && !$in_string) {
                $queries[] = substr($query, 0, $i);
                $query = substr($query, $i + 1);
                $i = 0;
            }
            
            if ($in_string && ($query[$i] == $in_string) && $buffer[1] != "\\") {
                $in_string = false;
            } elseif (!$in_string && ($query[$i] == '"' || $query[$i] == "'") && (!isset($buffer[0]) || $buffer[0] != "\\")) {
                $in_string = $query[$i];
            }
            if (isset($buffer[1])) {
                $buffer[0] = $buffer[1];
            }
            $buffer[1] = $query[$i];
        }
        // If the is anything left over, add it to the queries.
        if (!empty($query)) {
            $queries[] = $query;
        }
        // Add function part as is
        for ($f = 1;$f < count($funct);$f++) {
            $queries[] = 'CREATE OR REPLACE FUNCTION ' . $funct[$f];
        }
        
        return $queries;
    }
}
