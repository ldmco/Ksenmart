<? defined('_JEXEC') or die; ?>
<div class="catalog row layout_block layout_grid">
    <div class="items catalog-items">
            <ul id="product_list" class="row items catalog-items">
            	<? if (!empty($this->watched)) {
            		$params = $this->params;
            		foreach($this->watched as $product) {		
            			require('templates/'.JFactory::getApplication()->getTemplate().'/html/com_ksenmart/catalog/item.php');
            		}	
            	}else{
            		require('no_products.php');	
            	}
            	?>
            </ul>
    </div>
</div>
<div class="pagination">
    <?php echo $this->pagination->getPagesLinks(); ?>
</div>