<?php
$query=$this->db->getQuery(true);
$query->select('COUNT(*)')->from('#__ksenmart_orders')->where('status_id=1');
$this->db->setQuery($query);
$count_new_orders=$this->db->loadResult();
$query=$this->db->getQuery(true);
$query->select('COUNT(*)')->from('#__ksenmart_orders')->where('status_id=2');
$this->db->setQuery($query);
$count_unconfirmed_orders=$this->db->loadResult();
?>
<p class="new"><?php echo JText::sprintf('ksm_orders_widgetinfo_new_orders',$count_new_orders);?></p>
<p><?php echo JText::sprintf('ksm_orders_widgetinfo_unconfirmed_orders',$count_unconfirmed_orders);?></p>