<div class="form" id="renew_domain_form">
    <div id="step_page_1">
    <?php if(isset($this->domain_renew->elid)){ ?>
        <div class="row clearfix">Сумма платежа - <b><?php echo $this->domain_renew->elid; ?></b></div>
        <div class="btn-group pull-right">
            <a href="javascript:void(0);" class="btn btn-other finish_step" id="l-step_page_2" data-callback="setPay2Domain()">Оплатить</a>
        </div>
    <?php }else{ ?>
        <?php echo $this->domain_renew->error->msg; ?>
    <?php } ?>
    </div>
</div>
<?php if(isset($this->domain_renew->cost)){ ?>
    <script>
        var current_popup   = null;
        var step_page_1     = null;
    
        jQuery(document).ready(function(){
            
            current_popup   = jQuery('.popup.renew_domain');
            step_page_1     = current_popup.find('#step_page_1');
            
            stepsWindow(current_popup.find('#renew_domain_form'));
        });
        
        function setPay2Domain(){
            NProgress.start();
    
            var elid = '<?php echo $this->domain_renew->elid; ?>';
            
            var data = {elid: elid};
            var href = 'index.php?option=com_ksenmart&view=account&tmpl=ksenmart&task=account.domainRenewS2';
    
            jQuery.ajax({
                type: "POST",
    			url:href,
                data:data,
                dataType: 'json',
                beforeSend: function(){
                    NProgress.inc();
                    step_page_1.children('div:first-child').html(img_loader);
                },
    			success: function(data){
    			 console.log(data);
    			    if(data.result == 'OK'){
                        closePopup(step_page_1);
    			    }else{
                        createPopupNotice(data.error.msg, '.renew_domain');
                        setTimeout(function(){
                            closePopup(step_page_1);
                        }, 5000);
                        return false;
                        NProgress.done();
    			    }
    			}
    		});
        }
    </script>
<?php } ?>