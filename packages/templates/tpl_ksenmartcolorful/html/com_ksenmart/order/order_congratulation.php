<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<script>
    window.onload = function(){
    	jQuery.ajax({
    		url: URI_ROOT+'index.php?option=com_ksenmart&view=cart&layout=minicart&tmpl=ksenmart',
    		success: function( data ) {
    			if (window.parent.document.getElementById('minicart'))
    				window.parent.document.getElementById('minicart').innerHTML=data;
    			window.parent.KMShowCartMessage('Товар добавлен в корзину');
    			window.parent.KMClosePopupWindow();		
    		}
    	});
    }
</script>