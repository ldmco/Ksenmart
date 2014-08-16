<?php defined( '_JEXEC' ) or die; 
    $link = JRoute::_('index.php?option=com_ksenmart&view=cart&layout=congratulation&order_id='.$this->order_id.'&Itemid='.KSSystem::getShopItemid());
?>
<script>
    window.parent.location = '<?php echo $link;?>';
</script>