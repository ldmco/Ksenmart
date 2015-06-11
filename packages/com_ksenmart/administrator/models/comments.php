<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

KSSystem::import('models.modelksadmin');
class KsenMartModelComments extends JModelKSAdmin {

    function __construct() {
        parent::__construct();
    }

    function populateState() {
        $this->onExecuteBefore('populateState');

        $app = JFactory::getApplication();
        if($layout = JRequest::getVar('layout', 'default')) {
            $this->context .= '.' . $layout;
        }

        $value = $app->getUserStateFromRequest($this->context . 'list.limit', 'limit', $this->params->get('admin_product_limit', 30), 'uint');
        $limit = $value;
        $this->setState('list.limit', $limit);

        $value = $app->getUserStateFromRequest($this->context . '.limitstart', 'limitstart', 0);
        $limitstart = ($limit != 0 ? (floor($value / $limit) * $limit) : 0);
        $this->setState('list.start', $limitstart);

        $order_dir = $app->getUserStateFromRequest($this->context . '.order_dir', 'order_dir', 'desc');
        $this->setState('order_dir', $order_dir);
        $order_type = $app->getUserStateFromRequest($this->context . '.order_type', 'order_type', 'id');
        $this->setState('order_type', $order_type);

        $this->onExecuteAfter('populateState');
    }

    function getListItems() {
        $this->onExecuteBefore('getListItems');

        $order_dir = $this->getState('order_dir');
        $order_type = $this->getState('order_type');
        $query = $this->_db->getQuery(true);
        $query->select('SQL_CALC_FOUND_ROWS *')->from('#__ksenmart_comments')->order($order_type . ' ' . $order_dir);
        $this->_db->setQuery($query, $this->getState('list.start'), $this->getState('list.limit'));
        $items = $this->_db->loadObjectList();
        $query = $this->_db->getQuery(true);
        $query->select('FOUND_ROWS()');
        $this->_db->setQuery($query);
        $this->total = $this->_db->loadResult();

        $this->onExecuteAfter('getListItems', array(&$items));
        return $items;
    }

    function getTotal() {
        $this->onExecuteBefore('getTotal');

        $total = $this->total;

        $this->onExecuteAfter('getTotal', array(&$total));
        return $total;
    }

    function deleteListItems($ids) {
        $this->onExecuteBefore('deleteListItems', array(&$ids));

        foreach($ids as $id) {
            $query = $this->_db->getQuery(true);
            $query->delete('#__ksenmart_comments')->where('id=' . $id);
            $this->_db->setQuery($query);
            $this->_db->query();
            $query = $this->_db->getQuery(true);
            $query->delete('#__ksenmart_comment_rates_values')->where('comment_id=' . $id);
            $this->_db->setQuery($query);
            $this->_db->query();
        }

        $this->onExecuteAfter('deleteListItems', array(&$ids));
        return true;
    }

    function getComment($vars = array()) {
        $this->onExecuteBefore('getComment', array(&$vars));
        $id = JRequest::getInt('id');
        $comment = KSSystem::loadDbItem($id, 'comments');
        if(isset($vars['user_id'])) $comment->user_id = $vars['user_id'];
        if(isset($vars['product_id'])) $comment->product_id = $vars['product_id'];

        $query = $this->_db->getQuery(true);
        $query->select('*')->from('#__ksenmart_comment_rates_values')->where('comment_id=' . $id);
        $this->_db->setQuery($query);
        $comment->rates = $this->_db->loadObjectList('rate_id');

        $this->onExecuteAfter('getComment', array(&$comment));
        return $comment;
    }

    function saveComment($data) {
        $this->onExecuteBefore('saveComment', array(&$data));

        if(empty($data['id'])) {
            $data['date_add'] = JFactory::getDate()->toSql();
        }
        $table = $this->getTable('comments');
        if(!$table->bindCheckStore($data)) {
            $this->setError($table->getError());
            return false;
        }
        $id = $table->id;

        $in = array();
        foreach($data['rates'] as $k => $v) {
            $value = array(
                'comment_id' => $id,
                'rate_id' => $k,
                'value' => $v);
            $table = $this->getTable('commentratesvalues');
            if(!$table->bindCheckStore($value)) {
                $this->setError($table->getError());
                return false;
            }
            $in[] = $table->id;
        }
        $query = $this->_db->getQuery(true);
        $query->delete('#__ksenmart_comment_rates_values')->where('comment_id=' . $id);
        if(count($in)) $query->where('id not in (' . implode(',', $in) . ')');
        $this->_db->setQuery($query);
        $this->_db->query();

        $on_close = 'window.parent.CommentsList.refreshList();';
        $return = array('id' => $id, 'on_close' => $on_close);

        $this->onExecuteAfter('saveComment', array(&$return));
        return $return;
    }

    function getRate() {
        $this->onExecuteBefore('getRate');

        $id = JRequest::getInt('id');
        $rate = KSSystem::loadDbItem($id, 'commentrates');

        $this->onExecuteAfter('getRate', array(&$rate));
        return $rate;
    }

    function saveRate($data) {
        $this->onExecuteBefore('saveRate', array(&$data));

        $table = $this->getTable('commentrates');
        if(!$table->bindCheckStore($data)) {
            $this->setError($table->getError());
            return false;
        }
        $id = $table->id;

        $on_close = 'window.parent.CommentRatesModule.refresh();';
        $return = array('id' => $id, 'on_close' => $on_close);

        $this->onExecuteAfter('saveRate', array(&$return));
        return $return;
    }

    function deleteRate($id) {
        $this->onExecuteBefore('deleteRatee', array(&$id));

        $query = $this->_db->getQuery(true);
        $query->delete('#__ksenmart_comment_rates')->where('id=' . $id);
        $this->_db->setQuery($query);
        $this->_db->query();
        $query = $this->_db->getQuery(true);
        $query->delete('#__ksenmart_comment_rates_values')->where('rate_id=' . $id);
        $this->_db->setQuery($query);
        $this->_db->query();

        $this->onExecuteAfter('deleteRate', array(&$id));
        return true;
    }
}