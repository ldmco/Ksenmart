<?
defined( '_JEXEC' ) or die( '=;)' );
if (!empty($this->pagination)){
    //print_r($this->pagination);
?>
<div class="pagination pagination-centered">
	<?php echo $this->pagination->getPagesLinks(); ?>
</div>	
<? } ?>