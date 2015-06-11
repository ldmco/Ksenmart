<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

class Roistat {

	public $login 		= null;
	public $password 	= null;
	public $project 	= null;

    protected static $instance = null;

    public static function getInstance($login = null, $password = null) {
        if (self::$instance === null) {
            self::$instance = new static;
        }

        self::$instance->login = $login;
        self::$instance->password = $password;

        if(empty($login) || empty($password)){
    		self::$instance->_getPlgRoistatSettings();
        }

        if(empty(self::$instance->project)){
    		self::$instance->project = self::$instance->getProjectId();
        }
        
        return self::$instance;
    }

    public function checkUser($login = null){
    	if(!empty($login)){
    		$response = file_get_contents('http://cloud.roistat.com/site-api/0.2/integration/check?secret=qwykWQyd23l7a&user=' . $login);
			$response = json_decode($response);
    		if($response->status == 'success'){
    			return true;
    		}
		}
		return false;
    }

    private function _getPlgRoistatSettings(){
		$plugin = JPluginHelper::getPlugin('system', 'roistat');
		$plgRoistat = new JRegistry($plugin->params);

		self::$instance->login 		= $plgRoistat->get('login', null);
		self::$instance->password 	= $plgRoistat->get('password', null);
		self::$instance->project 	= $plgRoistat->get('project', null);
    }

    public function checkLogin(){
        if(!empty(self::$instance->project)){
            return self::$instance->project;
        }

        $response = array();
        if(!empty(self::$instance->login) && !empty(self::$instance->password)){
            $host = JURI::getInstance(JURI::base());
            $host = $host->getHost();
            $response = file_get_contents('http://cloud.roistat.com/site-api/0.2/integration/joomla?secret=qwykWQyd23l7a&user=' . self::$instance->login . '&password=' . self::$instance->password . '&domain=' . $host);
            $response = json_decode($response);
        }
        return $response;
    }

    public function getProjectId(){
    	if(!empty(self::$instance->project)){
    		return self::$instance->project;
    	}

    	if(!empty(self::$instance->login) && !empty(self::$instance->password)){
    		$response = self::$instance->checkLogin();
    		if($response->status == 'success'){
    			return $response->data->project;
    		}
		}
		return null;
    }

    public function set($name, $value){
    	if(property_exists(self::$instance, $name)){
    		self::$instance->{$name} = $value;
    	}
    	return self::$instance;
    }
}