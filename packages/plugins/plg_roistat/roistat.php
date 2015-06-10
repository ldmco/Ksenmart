<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');
class plgSystemRoistat extends JPlugin {
    
    protected $autoloadLanguage = true;
    private $project = null;

    public function __construct(&$subject, $config = array()){
        parent::__construct($subject, $config);
        $this->loadLanguage('plg_system_roistat.sys');
    }

    public function onAfterGetKSMFormOrder($form, $instance) {
        $likes_xml = '
            <field
                name="roistat"
                type="text"
                label="KSM_ORDERS_ORDER_ROISTAT_LBL"
                description ="KSM_ORDERS_ORDER_ROISTAT_DESC"
                labelclass="inputname"
            />
            ';
        $likes_element = new JXMLElement($likes_xml);
        $instance->setField($likes_element);

        return true;
    }

    public function onAfterDisplayAdminKSMOrdersOrder_Info(&$view, &$tpl, &$html) {

        $html .= '<div calss="row" style="clear: both;">';
        $html .=    $view->form->getLabel('roistat');
        $html .=    $view->form->getInput('roistat');
        $html .= '</div>';

        return true;
    }

    public function onAfterExecuteKSMCartAddToCart($model){
        $app   = JFactory::getApplication();
        $table = JTable::getInstance('Orders', 'KsenmartTable');
        $input = $app->input;
        
        if($table->load(array('id' => $model->order_id))){
            if($table->roistat <= 0){
                $table->roistat = $input->cookie->get('roistat_visit', 0, 'int');
                $table->save(array(
                    'roistat' => $table->roistat
                ));
                return true;
            }
        }
        return false;
    }
    
    public function onBeforeRender() {

        $app = JFactory::getApplication();
        if($app->isAdmin()){
            return;
        }
    	
    	$login 		    = $this->params->get('login', null);
    	$password 	    = $this->params->get('password', null);
    	$this->project	= $this->params->get('project', $this->_getProjectId($login, $password));

    	if(!empty($login) && !empty($password)){
    		$javascript  = "\n";
    		$javascript .= '(function(w, d, s, u, id) { var js = d.createElement(s); js.async = 1; js.src = u; var js2 = d.getElementsByTagName(s)[0]; js2.parentNode.insertBefore(js, js2); window.roistatProjectId = id;})(window, document, \'script\', \'http://cloud.roistat.com/js/roistat-module-visit.js\', \'' . $this->project . '\');';
    		$document = JFactory::getDocument();
    		$document->addScriptDeclaration($javascript);
    	}

    }

    public function onAjaxRoistatCheckUserRoistat(){
        
        JEventDispatcher::getInstance()->trigger('onLoadKsen', array('ksenmart.KSM', array('common'), array('functions', 'media', 'users'), array('angularJS' => 0, 'admin' => true)));
        KSSystem::import('libraries.roistat');

        $roistat = Roistat::getInstance();
        $app     = JFactory::getApplication();
        $input   = $app->input;

        $login      = $input->post->get('login', null, 'username');
        $password   = $input->post->get('password', null, 'password');
        $response   = array(
            'action' => 'auth'
        );

        $roistat->set('login', $login);
        $roistat->set('password', $password);

        if(!$roistat->checkUser($login)){
            $response['action'] = 'new';
        }else{
            $response['roistat'] = $roistat->checkLogin();
            if($response['roistat']->status == 'success'){
                $this->saveSettings($login, $password);
            }
        }

        return $response;
    }

    public function onAjaxRoistatLoginRoistat(){

        JEventDispatcher::getInstance()->trigger('onLoadKsen', array('ksenmart.KSM', array('common'), array('functions', 'media', 'users'), array('angularJS' => 0, 'admin' => true)));
        KSSystem::import('libraries.roistat');

        $roistat = Roistat::getInstance();
        $app     = JFactory::getApplication();
        $input   = $app->input;

        $login      = $input->post->get('login', null, 'username');
        $password   = $input->post->get('password', null, 'password');
        $response   = array(
            'status' => 'error'
        );

        $saveResult = $this->saveSettings($login, $password);

        if($saveResult){
            $response['status'] = 'success';
        }

        return $response;
    }

    public function onAjaxRoistatOrdersInfo(){

        KSSystem::import('libraries.roistat');
        $roistat  = Roistat::getInstance();
        $app      = JFactory::getApplication();
        $input    = $app->input;
        $response = new stdClass;

        $token      = $input->get('token', null, 'string');
        $localToken = md5($roistat->login . md5($roistat->password));

        if($token && $token == $localToken){
            $model    = JModelLegacy::getInstance('Order', 'KsenMartModel');
            $editDate = $input->get('date', time() - 31*24*60*60, 'string');

            $response->statuses = $model->getOrderStatuses();
            $response->orders   = $model->getOrders($editDate);

            foreach ($response->statuses as &$status) {
                $status->name = $status->system ? JText::_('KSM_ORDERS_' . strtoupper($status->name)) : $status->name;
                unset($status->system);
            }

            $app->setHeader('Content-Type', 'application/json; charset=UTF-8');
            $app->sendHeaders();
        }
        $app->close(json_encode($response));
    }

    public function onAfterViewAdminKSMExportImport(&$view){

        KSSystem::import('libraries.roistat');
        JHtml::stylesheet('plg_system_roistat/default.css', false, true, false);

        $roistat = Roistat::getInstance();
        $user_isset = false;

        if(!empty($roistat->login)){
            if($roistat->checkUser($roistat->login) && !empty($roistat->project)){
                $user_isset = true;
            }
        }

        if(!$user_isset){
            JHtml::script('plg_system_roistat/default.js', false, true);
        }else{
            $view->assign('project', $roistat->project);
        }
        
        $view->assign('user_isset', $user_isset);
        $view->addTemplatePath(JPATH_ROOT . '/plugins/system/roistat/tmpl/');

        return true;
    }

    public function onExtensionBeforeSave($name, $table){
        if($this->_name == 'roistat'){

            $table->params = new JRegistry($table->params);
            $table->params->set('project', $this->project);

            $login          = $table->params->get('login', null);
            $password       = $table->params->get('password', null);
            $project        = $table->params->get('project');

            if(empty($project)){
                if(!empty($login) && !empty($password)){
                    $this->project  = $this->_getProjectId($login, $password);
                }else{
                    $this->project = null;
                }
                $table->params->set('project', $this->project);
                $table->params = $table->params->toString();
            }

        }
        return true;
    }

    private function _getProjectId($login, $password){
    	if(!empty($login) && !empty($password)){
            $host = JURI::getInstance(JURI::base());
            $host = $host->getHost();
    		$response = file_get_contents('http://cloud.roistat.com/site-api/0.2/integration/joomla?secret=qwykWQyd23l7a&user=' . $login . '&password=' . $password . '&domain=' . $host);
			$response = json_decode($response);
    		if($response->status == 'success'){
    			return $response->data->project;
    		}
		}
    }

    private static function saveSettings($login, $password){

        if(!empty($login) && !empty($password)){

            $roistat = Roistat::getInstance();
            $roistat->set('login', $login);
            $roistat->set('password', $password);

            $table = JTable::getInstance('extension');
            $table->load(array('name' => 'roistat'));
            $table->params = new JRegistry($table->params);

            $table->params->set('login', $login);
            $table->params->set('password', $password);
            $table->params->set('project', $roistat->getProjectId());

            return $table->save(array(
                'params' => $table->params->toString()
            ));
        }
        return false;
    }
}
