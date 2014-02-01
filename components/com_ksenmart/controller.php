<?php defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

class KsenMartController extends JController {
    protected $default_view = 'shopcatalog';

    public function display($cachable = false, $urlparams = false) {

        $cachable = true;
        $document = JFactory::getDocument();
        $viewType = $document->getType();
        $viewName = JRequest::getCmd('view', $this->default_view);
        $viewLayout = JRequest::getCmd('layout', 'default');

        if($viewName == 'shopprofile' || $viewName == 'shopopencart') $cachable = false;

        $safeurlparams = array(
            'categories' => 'ARRAY',
            'manufacturers' => 'ARRAY',
            'properties' => 'ARRAY',
            'countries' => 'ARRAY',
            'price_less' => 'INT',
            'price_more' => 'INT',
            'title' => 'STRING',
            'new' => 'INT',
            'hot' => 'INT',
            'promotion' => 'INT',
            'recommendation' => 'INT',
            'order_type' => 'STRING',
            'order_dir' => 'STRING',
            'limit' => 'UINT',
            'limitstart' => 'UINT',
            'id' => 'INT',
            'lang' => 'CMD');

        $view = $this->getView($viewName, $viewType, '', array('base_path' => $this->basePath, 'layout' => $viewLayout));

        if($model = $this->getModel($viewName)) {
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
        
        foreach($layouts as $layout) {
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

}
