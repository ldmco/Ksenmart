<?php echo stripslashes($this->ticket->hist); ?>
<script>
    jQuery(document).ready(function(){
        var messages = jQuery('.popup.ticket .messages');
        
        var root_billing_root = 'http://ldmco.ru/';
        
        jQuery('.support-msg, .support-msg-admin').each(function(e, i){
            var img     = jQuery(this).children('div').children('img');
            if(img.length > 0){
                if(jQuery(this).hasClass('support-msg-admin')){
                    var img_src = img.attr('src');
                    img.attr('src', root_billing_root+''+img_src);
                }else{
                    getCurrentAvatar(img);
                }
            }else{
                jQuery(this).prepend(default_img);
            }
            
            jQuery(this).children('.support-from, .support-body').wrapAll('<div class="support-msg-wrapper"></div>');                        
        });
        
        if(messages.length > 0){
            messagesBlockResize(messages);
        }
    });
    
    function getCurrentAvatar(this_img){
        var href = 'index.php?option=com_ksenmart&view=account&tmpl=ksenmart&task=account.getCurrentUserAvatar';
        var data = ''; 
        
        jQuery.post(
			href,
            data,
			function(data){
			    if(data != ''){
                    this_img.parent().append(data);
                    this_img.remove();
                    return true;
			    }else{
			         
			    }
			}
		);
    }
</script>