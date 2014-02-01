<div class="form" id="new_credit_form">
    <div class="row clearfix">
        <label class="inputname">Плательщика</label>
        <select class="sel" name="sender">
            <?php foreach($this->users as $user){ ?>
            <option value="<?php echo $user->id; ?>"><?php echo $user->name; ?></option>
            <?php } ?>
        </select>
    </div>
    <div class="row clearfix">
        <label class="inputname">Способ оплаты</label>
        <select name="type" class="sel">
            <option value="10">Банковской картой (ООО «Л.Д.М. и Ко»)</option>
            <option value="11">WebMoney (ООО «Л.Д.М. и Ко»)</option>
            <option value="12">Яндекс.Деньги (ООО «Л.Д.М. и Ко»)</option>
            <option value="16">QIWI (ООО «Л.Д.М. и Ко»)</option>
            <option value="9">ROBOKASSA (ООО «Л.Д.М. и Ко»)</option>
        </select>
    </div>
    <div class="row clearfix">
        <label class="inputname">Сумма</label>
        <input type="text" name="amount" class="inputbox prepend" required="true" />
        <span class="prepend"> руб.</span>
    </div>
    
    <input type="hidden" name="paycurrency" value="126" />
</div>
<script>
    jQuery(document).ready(function(){
        
    	var params = {
    			changedEl: ".sel", 
    		} 
    	cuSel(params);
        
        var current_popup = jQuery('.popup.new_credit');
        
        current_popup.parent().on('submit', function(e){

            e.preventDefault();
            
            var form = current_popup.find('#new_credit_form');
            
            if(current_popup.find('input[name="phoneid"]').length != 0){
                var href = 'index.php?option=com_ksenmart&view=account&tmpl=ksenmart&task=account.createCreditQiwi';

                var qiwi_form   = current_popup.find('#qiwi_form');
                var elid        = qiwi_form.find('input[name="elid"]').val();
                var phoneid     = qiwi_form.find('input[name="phoneid"]').val();
                var amount      = qiwi_form.find('input[name="amount"]').val();
                var alertsms    = qiwi_form.find('input[name="alertsms"]').val();

                var data = {phoneid: phoneid, alertsms: alertsms, amount: amount, elid: elid};
            }else{
                var href = 'index.php?option=com_ksenmart&view=account&tmpl=ksenmart&task=account.createCredit';
                
                var sender       = form.find('input[name="sender"]').val();
                var type         = form.find('input[name="type"]').val();
                var amount       = form.find('input[name="amount"]').val();
                var paycurrency  = form.find('input[name="paycurrency"]').val();
                
                var data = {sender: sender, type: type, amount: amount, paycurrency: paycurrency};
            }

            jQuery.post(
    			href,
                data,
    			function(data){
    			 console.log(data);
    			    if(data != '' && data > 0){
                        var id      = data;
                        
                        if(type != 16){
                            var link    = 'https://ldmco.ru/mancgi/roboxpayment?elid='+id;
                            var wndws   = window.open(link);
                        }else if(type == 16){
                            form.fadeOut(400, function(){
                                
                                var qiwi_form_block = '<div class="form qiwi" id="qiwi_form">'
                                                        +'<div class="desc">Укажите десятизначный номер вашего мобильного телефона - это последние десять цифр вашего федерального номера (пример - 9991234567). После нажатия на кнопку "Ok" вам необходимо пройти в личный кабинет своего Qiwi кошелька и оплатить выписанный счет.</div>'
                                                        +'<div class="row clearfix">'
                                                            +'<label class="inputname">Сумма</label>'
                                                            +'<input type="text" name="amount" readonly="true" value="" class="inputbox" />'
                                                        +'</div>'
                                                        +'<div class="row clearfix">'
                                                            +'<label class="inputname">Номер мобильного телефона</label>'
                                                            +'<input type="text" name="phoneid" required="true" class="inputbox" />'
                                                        +'</div>'
                                                        +'<div class="row clearfix">'
                                                            +'<label class="inputname" for="alertsms_checkbox">SMS оповещение</label>'
                                                            +'<input class="checkbox" id="alertsms_checkbox" type="checkbox" name="alertsms" />'
                                                        +'</div>'
                                                        +'<input type="hidden" name="elid" value="'+id+'">'
                                                    +'</div>';

                                jQuery(this).after(qiwi_form_block);

                                var qiwi_form   = current_popup.find('#qiwi_form');
                                var amount_qiwi = qiwi_form.find('input[name="amount"]').val();
                                
                                qiwi_form.find('input[name="amount"]').val(amount+' RUB');
                                
                                qiwi_form.fadeIn(400);
                                
                                jQuery(this).remove();
                            });
                        }
                        
                        var popup       = current_popup;
                        var popup_class = popup.attr('class').replace('popup ', '');
            
                        popup.fadeOut(400, function(){
                            jQuery(this).parent().remove();
                            jQuery('.overlay.'+popup_class).fadeOut(400, function(){
                                jQuery(this).remove();
                            });
                        });
                        return true;
                        //loading_block.fadeOut(400, function(){jQuery(this).remove()});
    			    }else{
                        //location.reload();
    			    }
    			}
    		);
        });
    });
</script>