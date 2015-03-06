<?
defined( '_JEXEC' ) or die( '=;)' );
if (!empty($this->pagination)){
    //print_r($this->pagination);
?>
<div id="pagination" class="pagination">
	<?php echo $this->pagination->getPagesLinks(); ?>
</div>	
<? } ?>

