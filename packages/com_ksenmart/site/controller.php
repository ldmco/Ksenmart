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
		$cachable   = true;
		$document   = JFactory::getDocument();
		$viewType   = $document->getType();
		$jinput     = JFactory::getApplication()->input;
		$viewName   = $jinput->getCmd('view', $this->default_view);
		$viewLayout = $jinput->getCmd('layout', 'default');

		$config = JFactory::getConfig();
		if ($viewName == 'profile' || $viewName == 'cart') $cachable = false;
		if ($cachable && $config->get('caching', 0)) {
			$user   = JFactory::getUser();
			$isroot = $user->authorise('core.admin');
			if ($isroot) $cachable = false;
		}
		if ($cachable && $config->get('caching', 0)) {
			$discounts = KSMPrice::getDiscount();
			foreach ($discounts as $discount) {
				$regions = json_decode($discount->regions, true);
				if (count($regions)) $cachable = false;
				$user_groups = json_decode($discount->user_groups, true);
				if (count($user_groups)) $cachable = false;
				$user_actions = json_decode($discount->user_actions, true);
				if (count($user_actions)) $cachable = false;
			}
		}

		$safeurlparams = array('categories' => 'ARRAY', 'manufacturers' => 'ARRAY', 'properties' => 'ARRAY', 'countries' => 'ARRAY', 'price_less' => 'INT', 'price_more' => 'INT', 'title' => 'STRING', 'new' => 'INT', 'hot' => 'INT', 'promotion' => 'INT', 'recommendation' => 'INT', 'order_type' => 'STRING', 'order_dir' => 'STRING', 'limit' => 'UINT', 'limitstart' => 'UINT', 'id' => 'INT', 'lang' => 'CMD');

		//TODO: Нужны ли эти строчки вообще?
		/*$view = $this->getView($viewName, $viewType, '', array('base_path' => $this->basePath, 'layout' => $viewLayout));

		if ($model = $this->getModel($viewName)) {
			$view->setModel($model, true);
		}

		$view->document = $document;
		$view->setLayout($viewLayout);*/

		parent::display($cachable, $safeurlparams);

		return $this;
	}

	function get_layouts() {
		$jinput   = JFactory::getApplication()->input;
		$view     = $jinput->getCmd('view');
		$layouts  = $jinput->get('layouts', array(), 'ARRAY');
		$format   = $jinput->getCmd('format', 'html');
		$response = array();

		$model = $this->getModel($view);
		$view  = $this->getView($view, $format);
		$model->setModelFields();
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

	function set_session_variable() {
		$session = JFactory::getSession();
		$name    = JRequest::getVar('name', null);
		$value   = JRequest::getVar('value', null);
		if (!empty($name)) {
			$name = 'com_ksenmart.' . $name;
			$session->set($name, $value);
		}
		JFactory::getApplication()->close();
	}

	function get_session_variable() {
		$session = JFactory::getSession();
		$name    = JRequest::getVar('name', null);
		$value   = '';
		if (!empty($name)) {
			$name  = 'com_ksenmart.' . $name;
			$value = $session->get($name, null);
		}
		echo $value;
		JFactory::getApplication()->close();
	}

	function set_session_data() {
		$session_data = JRequest::getVar('session_data', '{}');
		$session_data = json_decode($session_data, true);
		if (!count($session_data)) $_SESSION = $session_data;
		JFactory::getApplication()->close();
	}

	function get_session_data() {
		$session_data = $_SESSION;
		$session_data = json_encode($session_data);
		echo $session_data;
		JFactory::getApplication()->close();
	}

	function set_user_activity() {
		$session = JFactory::getSession();
		$time    = JRequest::getVar('time', time());
		$session->set('com_ksenmart.user_last_activity', $time);
		JFactory::getApplication()->close();
	}

	public function pluginAction() {

		$app     = JFactory::getApplication();
		$format  = strtolower($this->input->getWord('format'));
		$results = null;
		$parts   = null;

		// Check for valid format
		if (!$format) {
			$results = new InvalidArgumentException('Please specify response format other that HTML (json, raw, etc.)', 404);
		} elseif ($this->input->get('plugin')) {
			$plugin     = ucfirst($this->input->get('plugin'));
			$action     = ucfirst($this->input->get('action'));
			$dispatcher = JEventDispatcher::getInstance();

			try {
				$results = $dispatcher->trigger('onAjax' . $plugin . $action);
				$results = $results[0];
			}
			catch (Exception $e) {
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
				} // Output string/ null
				elseif (is_scalar($results)) {
					$out = (string) $results;
				} // Output array/ object
				else {
					$out = implode((array) $results);
				}

				$app->close($out);
				break;
		}
	}
}
