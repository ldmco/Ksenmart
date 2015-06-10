<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

$query = $this->_db->getQuery(true);
$query->select('COUNT(*)')->from('#__ksenmart_orders')->where('status_id=1');
$this->_db->setQuery($query);
$count_new_orders = $this->_db->loadResult();
$query = $this->_db->getQuery(true);
$query->select('COUNT(*)')->from('#__ksenmart_orders')->where('status_id=2');
$this->_db->setQuery($query);
$count_unconfirmed_orders = $this->_db->loadResult();
?>
<p class="new"><?php echo JText::sprintf('ksm_orders_widgetinfo_new_orders', $count_new_orders); ?></p>
<p><?php echo JText::sprintf('ksm_orders_widgetinfo_unconfirmed_orders', $count_unconfirmed_orders); ?></p>