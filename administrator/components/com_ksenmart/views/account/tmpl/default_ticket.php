<?php if($this->actn != 'reload'){ ?>
<dl class="dl-horizontal">
    <dt>Тема</dt>
    <dd><?php echo $this->ticket->subject; ?></dd>
    <?php if(isset($this->ticket->product) && !empty($this->ticket->product)){ ?>
    <dt>Продукт/услуга</dt>
    <dd><?php echo $this->ticket->product; ?></dd>    
    <?php } ?>
</dl>
<div class="messages">
<?php } ?>
<?php echo $this->loadTemplate('messages'); ?>
<?php if($this->actn != 'reload'){ ?>
</div>
<form class="send_form clearfix" id="send_form" method="POST">
    <div class="row">
        <textarea class="text" name="text" required="true" autofocus="true"></textarea>
    </div>
    <a href="javascript:void(0);" class="reload btn btn-reload">Обновить переписку</a>
    <input type="submit" class="btn btn-send pull-right" value="Ответить" />
</form>
<script>
jQuery(document).ready(function(){
        
    var messages = jQuery('.popup.ticket .messages');
        
    jQuery('#send_form').on('submit', function(e){
        var messages = jQuery('.popup.ticket .messages');
        e.preventDefault();
        
        var textarea = jQuery(this).find('.text');
        var text = textarea.val();
        var href = 'index.php?option=com_ksenmart&view=account&tmpl=ksenmart&task=account.setUserAnswer';
        
        /*
        jQuery(this).prepend('<div class="loading_block"><img src="<?php echo JURI::base(); ?>components/com_ksenmart/css/i/ajax-loader_2.gif" alt="Загрузка" /></div>');
        var loading_block = jQuery(this).children('.loading_block');
        loading_block.fadeIn(400)
        */
        
		jQuery.post(
			href,
            {
                elid: <?php echo $this->ticket->elid; ?>,
                text: text
            },
			function(data){
			    if(data != ''){
			        createPopupNoticeTextarea(data, '.ticket');
                    //loading_block.fadeOut(400, function(){jQuery(this).remove()});
			    }else{
			        textarea.val('');
                    reloadMessageList();
			    }
			}
		);
    });
    
    function reloadMessageList(){
        var href = 'index.php?option=com_ksenmart&view=account&tmpl=ksenmart&layout=ticket&actn=reload';
        messages.load(href, {elid:<?php echo $this->ticket->elid; ?>, actn:'reload'});
    }
    
    jQuery('.reload').on('click', function(){
        reloadMessageList();
    });
    
    setInterval(reloadMessageList, 30000);
});
</script>
<?php } ?>