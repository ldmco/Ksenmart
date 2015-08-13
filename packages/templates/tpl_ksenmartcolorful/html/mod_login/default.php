<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
$document = JFactory::getDocument();

if (!class_exists('KSSystem')){
	include(JPATH_ROOT.'/administrator/components/com_ksenmart/helpers/common/system.php');	
}

$user = KSUsers::getUser();

$block_auth = '
<span class="close_popover close" style="position: absolute;top: 8px;right: 8px;">&#215;</span>
<form class="auth-tab" method="POST" style="width:200px;">
	<div class="control-group">
		<div class="controls">
			<input type="text" class="inputbox" name="login" value="" placeholder="'.JText::_('TPL_KSENMARTCOLORFUL_AUTH_EMAIL').'" required="true" />
		</div>
	</div>
	<div class="control-group">
		<div class="controls">
			<input type="password" class="inputbox" name="password" value="" placeholder="'.JText::_('TPL_KSENMARTCOLORFUL_AUTH_PASSWORD').'" required="true" />
		</div>
	</div>
	<div class="control-group">
		<div class="controls">
			<button type="submit" class="button btn btn-success" style="width:228px">'.JText::_('TPL_KSENMARTCOLORFUL_AUTH_ENTER').'</button>
		</div>
	</div>
	<div class="control-group text-center">
		<ul class="unstyled">
			<li><a href="'.JRoute::_('index.php?option=com_users&view=reset').'">'.JText::_('TPL_KSENMARTCOLORFUL_AUTH_RESET_PASSWORD').'</a></li>
			<li><a href="'.JRoute::_('index.php?option=com_ksenmart&view=profile&layout=registration').'">'.JText::_('TPL_KSENMARTCOLORFUL_AUTH_REGISTRATION').'</a></li>                        
		</ul>
	</div>
</form>
';

$block_auth = str_replace(array("\r", "\n", "\t"), '', $block_auth);
?>
<script>
var login_form = '<?php echo $block_auth; ?>';

jQuery(document).ready(function(){

	jQuery('#auth, #on_fav, .spy_price').popover({
		html: 	 true,
		content: login_form,
		container: 'body'
	});
	
	function hidePopovers(){
		var popover_block = jQuery('.popover');
		popover_block.removeClass('in');
		popover_block.addClass('out');
		
		setTimeout(function(){
			popover_block.remove();
		}, 500);
	}
	
	jQuery('body').on('click', '.close_popover', function(){
		hidePopovers();
	});
	
	jQuery('body').on('submit', '.auth-tab', function(e){
		e.preventDefault();
		
		var login 	 = jQuery('.auth-tab input[name="login"]').val();
		var password = jQuery('.auth-tab input[name="password"]').val();
		
		if(login == ''){
			KMShowMessage("<?php echo JText::_('TPL_KSENMARTCOLORFUL_AUTH_ERROR_EMAIL'); ?>");
			return false;
		}
		if (password=='')
		{
			KMShowMessage("<?php echo JText::_('TPL_KSENMARTCOLORFUL_AUTH_ERROR_EMAIL'); ?>");
			return false;
		}	
		jQuery.ajax({
			type: 'POST',
			url:URI_ROOT+'index.php?option=com_ksenmart&task=shopajax.site_auth&login='+login+'&password='+password,
			success:function(data){
				if (data == 'login'){
					window.location.reload();
				}else{
					KMShowMessage('<?php echo JText::_('TPL_KSENMARTCOLORFUL_AUTH_ERROR'); ?>');
				}
			}
		});	
	});
	
});
</script>
<div class="login user_panel_top">
    <ul class="inline">
		<li>  
            <a href="javascript:void(0);" class="link_b_border" id="auth" data-toggle="popover" data-placement="bottom" title="" data-original-title="<?php echo JText::_('TPL_KSENMARTCOLORFUL_AUTH'); ?>"><?php echo JText::_('TPL_KSENMARTCOLORFUL_AUTH'); ?></a>
        </li>
	</ul>
</div>
