	<?php
	print_r($this);
?>1
    <div class="row-fluid">
        <ul class="thumbnails items catalog-items">
    	<? if(!empty($this->rows)){
    		$params 	= $this->params;
    		foreach($this->rows as $product){
    			require('item.php');
    		}
    	}else{
    		require_once('no_products.php');
    	} ?>
        </ul>
    </div>