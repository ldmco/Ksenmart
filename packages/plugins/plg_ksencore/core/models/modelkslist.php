<?php
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

abstract class JModelKSList extends JModelList
{

	protected $params = null;

	private $ext_name_com = null;
	private $ext_prefix = null;
	private static $models = [];

	public function __construct($config = array())
	{
		parent::__construct($config);

		global $ext_name_com, $ext_prefix;
		$this->ext_name_com = $ext_name_com;
		$this->ext_prefix   = $ext_prefix;
		$jinput             = JFactory::getApplication()->input;

		$this->context .= ($this->getName() && $layout = $jinput->get('layout', 'default')) ? '.' . $layout : '';
		$this->params  = JComponentHelper::getParams($this->ext_name_com);

		self::$models[$this->getName()] = $this;
	}

	public function onExecuteBefore($function = null, $vars = array())
	{
		$model = &$this;
		array_unshift($vars, $model);
		JEventDispatcher::getInstance()->trigger('onBeforeExecute' . strtoupper($this->ext_prefix) . $this->getName() . $function, $vars);

		return $this;
	}

	public function onExecuteAfter($function = null, $vars = array())
	{
		$model = &$this;
		array_unshift($vars, $model);
		JEventDispatcher::getInstance()->trigger('onAfterExecute' . strtoupper($this->ext_prefix) . $this->getName() . $function, $vars);

		return $this;
	}

	public function setModelFields()
	{
		return true;
	}

	public static function getInstance($type, $prefix = '', $config = array())
	{
		if (!empty(self::$models[$type]))
		{
			return self::$models[$type];
		}

		return parent::getInstance($type, $prefix, $config);
	}

	/*protected function _getListCount($query)
	{
		/*if ($query instanceof JDatabaseQuery
			&& $query->type == 'select'
			&& $query->group === null
			&& $query->union === null
			&& $query->unionAll === null
			&& $query->having === null)
		{
			$query = clone $query;
			$query->clear('select')->clear('order')->clear('limit')->clear('offset')->select('COUNT(*)');

			$this->getDbo()->setQuery($query);

			return (int) $this->getDbo()->loadResult();
		}

		// Otherwise fall back to inefficient way of counting all results.

		// Remove the limit and offset part if it's a JDatabaseQuery object
		if ($query instanceof JDatabaseQuery)
		{
			$query = clone $query;
			$query->clear('limit')->clear('offset');
		}

		$this->getDbo()->setQuery($query);
		$store = $this->getStoreId();
		$rows = $this->getDbo()->loadObjectList();
		$this->cache[$store] = array_slice($rows, $this->getStart(), $this->getState('list.limit'));

		return count($rows);

		return parent::_getListCount($query);
	}*/

	/*protected function _getListCount($query)
	{
		// Use fast COUNT(*) on JDatabaseQuery objects if there is no GROUP BY or HAVING clause:
		if ($query instanceof JDatabaseQuery
			&& $query->type == 'select'
			&& $query->union === null
			&& $query->unionAll === null
			&& $query->having === null)
		{
			$query = clone $query;
			$query->clear('select')->clear('order')->clear('limit')->clear('offset')->select('COUNT(*)');

			$this->getDbo()->setQuery($query);

			return (int) $this->getDbo()->loadResult();
		}

		// Otherwise fall back to inefficient way of counting all results.

		// Remove the limit and offset part if it's a JDatabaseQuery object
		if ($query instanceof JDatabaseQuery)
		{
			$query = clone $query;
			$query->clear('limit')->clear('offset');
		}

		$this->getDbo()->setQuery($query);
		$this->getDbo()->execute();

		return (int) $this->getDbo()->getNumRows();
	}*/

	public function getForm($data = array(), $loadData = true, $control = 'jform')
	{

		JKSForm::addFormPath(JPATH_COMPONENT . '/models/forms');
		JKSForm::addFieldPath(JPATH_COMPONENT . '/models/fields');

		if (!$this->form)
		{
			$this->form = $this->getName();
		}

		$form = JKSForm::getInstance($this->ext_name_com . '.' . $this->form, $this->form, array(
			'control'   => $control,
			'load_data' => $loadData
		));

		if (empty($form))
			return false;

		return $form;
	}

}