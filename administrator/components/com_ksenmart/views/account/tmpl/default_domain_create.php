<div class="form" id="new_domain_form">
    <div id="step_page_1">
        <div class="row clearfix">
            <label class="inputname">Доменное имя</label>
            <input type="text" name="domain_name" class="inputbox inputbox_235 prepend" required="true" autofocus="true" />
            <span class="prepend"><a href="javascript:void(0);" class="l-promo">Промокод</a></span>
        </div>
        <div class="row clearfix" style="display: none;">
            <label class="inputname">Промо код</label>
            <input type="text" name="promocode" class="inputbox" required="true" />
        </div>
        <div class="btn-group pull-right">
            <a href="javascript:void(0);" class="btn btn-other btn-dsbl" id="l-step_page_2" data-callback="getVariantsDomains()">Далее</a>
        </div>
    </div>
    <div id="step_page_2">
        <div class="row clearfix table_block"></div>
        <div class="btn-group pull-right">
            <a href="javascript:void(0);" class="btn btn-remove prev_step" id="l-step_page_1">Назад</a>
            <a href="javascript:void(0);" class="btn btn-other btn-dsbl" id="l-step_page_3" data-callback="getDomainContact()">Далее</a>
        </div>
    </div>
    <div id="step_page_3">
        <div class="row clearfix">
            <div class="row clearfix">
            <?php //if(!empty($this->domain_contacts)){ ?>
                <label class="inputname">Анкета клиента</label>
                <select name="customer" class="sel domain_contacts">
                    <?php foreach($this->domain_contacts as $domain_contact){ ?>
                    <option value="<?php echo $domain_contact->id; ?>"><?php echo $domain_contact->name; ?></option>
                    <?php } ?>
                </select>
            <?php //} ?>
            </div>
            <div class="row clearfix">
                <a href="javascript:void(0);" class="btn btn-other pull-right js-domaincontact_create">Создать анкету</a>
            </div>
        </div>
        <div class="btn-group pull-right">
            <a href="javascript:void(0);" class="btn btn-remove prev_step" id="l-step_page_2">Назад</a>
            <a href="javascript:void(0);" class="btn btn-other btn-dsbl" id="l-step_page_4" data-callback="sendSelectedDomains()">Далее</a>
        </div>
    </div>
    <div id="step_page_4">
        <div class="row clearfix"></div>
        <div class="btn-group pull-right">
            <a href="javascript:void(0);" class="btn btn-remove prev_step" id="l-step_page_3">Назад</a>
            <a href="javascript:void(0);" class="btn btn-other btn-dsbl finish_step" id="l-step_page_4" data-callback="registrationDomain()">Зарегестрировать</a>
        </div>
    </div>
</div>
<script>

    var current_popup   = null;
    var domains_zone    = null;
    var promocode       = null;
    
    var step_page_1     = null;
    var domain_name     = null;
    var sp1_a_dsbl      = null;
    
    var step_page_2     = null;
    var sp2_a_dsbl      = null;
    
    var step_page_3     = null;
    var sp3_a_dsbl      = null;    
    
    var step_page_4     = null;
    var sp4_a_dsbl      = null;

    jQuery(document).ready(function(){
        var params = {
                changedEl: ".sel", 
            } 
        cuSel(params);
        
        current_popup   = jQuery('.popup.new_domain');
        domains_zone    = ['net.ru', 'com', 'com.ru', 'info', 'me', 'msk.ru', 'net', 'ru', 'su', 'рф', 'xxx'];
        promocode       = null;
        
        step_page_1     = current_popup.find('#step_page_1');
        domain_name     = step_page_1.find('input[name="domain_name"]');
        sp1_a_dsbl      = step_page_1.find('.btn-dsbl');
        
        step_page_2     = current_popup.find('#step_page_2');
        sp2_a_dsbl      = step_page_2.find('.btn-dsbl');
        
        step_page_3     = current_popup.find('#step_page_3');
        sp3_a_dsbl      = step_page_3.find('.btn-dsbl');
        
        step_page_4     = current_popup.find('#step_page_4');
        sp4_a_dsbl      = step_page_4.find('.btn-dsbl');
        
        reg_data = null;
        
        var customer = step_page_3.find('[name="customer"]');
        if(customer.length){
            if(customer.val() != 'null' && customer.val().length > 0){
                sp3_a_dsbl.removeClass('btn-dsbl');
            }else{
                sp3_a_dsbl.addClass('btn-dsbl');
            }
        }
        
        domain_name.on('keyup', function(){
            checkDomain(jQuery(this));
        });
        
        domain_name.on('change', function(){
            checkDomain(jQuery(this));
        });
        
        jQuery('.l-promo').on('click', function(){
            step_page_1.find('input[name="promocode"]').parent().fadeToggle(400);
        });
        
        step_page_2.on('click', 'tr input[type="checkbox"]', function(){
            if(step_page_2.find('tr input[type="checkbox"]:checked').length > 0){
                sp2_a_dsbl.removeClass('btn-dsbl');
            }else{
                sp2_a_dsbl.addClass('btn-dsbl');
            }
        });
        
        stepsWindow(current_popup.find('#new_domain_form'));

        current_popup.parent().on('submit', function(e){
            e.preventDefault();
            
            var href = 'index.php?option=com_ksenmart&view=account&tmpl=ksenmart&task=account.createdomain';
            
            var domain_name = step_page_1.find('input[name="domain_name"]').val();
            
            var data = {domain_name: domain_name};
            
            jQuery.ajax({
                type: "POST",
                url:href,
                data:data,
                dataType: 'json',
                beforeSend: function(){
                    NProgress.inc();
                },
                success: function(data){
                    //console.log(data);                 
                    if(data.result != 'success'){
                        //loading_block.fadeOut(400, function(){jQuery(this).remove()});
                    }else{
                        //textarea.val('');
                        //location.reload();
                    }
                    NProgress.done();
                }
            });
        });
    });
    
    function getDomainContact(){
        NProgress.done();
    }
    
    function checkDomain(e){
        var domain_name_val = e.val();
        var pattern         = /^(([A-Za-z0-9А-Яа-я\-]+\.)+([A-Za-zА-Яа-я]+){2,4})(\:(\d)+)?(\/(.*))?$/i;
        
        domain_name_val.replace('www.', '');

        if(domain_name_val.length > 3){
            if(pattern.test(domain_name_val)){
                sp1_a_dsbl.removeClass('btn-dsbl');
            }else{
                sp1_a_dsbl.addClass('btn-dsbl');
            }
        }else if(domain_name_val.length <= 3){
            sp1_a_dsbl.addClass('btn-dsbl');
        }
    }
    
    function registrationDomain(){
        step_page_4.children('div:first-child').fadeOut(400, function(){
            jQuery(this).clone().prependTo("#step_page_4").html(img_loader).fadeIn(400);
        });
        
        var href = 'index.php?option=com_ksenmart&view=account&tmpl=ksenmart&task=account.registerDomains';
        var data    = {reg_data: reg_data};

        jQuery.ajax({
            type: "POST",
            url:href,
            data:data,
            dataType: 'json',
            beforeSend: function(){
                NProgress.inc();
            },                
            success: function(data){
                if(data.result == 'OK'){
                    closePopup(step_page_4);
                }else{
                    createPopupNotice(data.error.msg, '.new_domain');
                    setTimeout(function(){
                        closePopup(step_page_4);
                    }, 5000);
                    return false;
                }
                NProgress.done();
            }
        });
    }
    
    function sendSelectedDomains(e){
        
        var selected_domains = [];
        var href = 'index.php?option=com_ksenmart&view=account&tmpl=ksenmart&task=account.registerDomainsData';
        
        step_page_2.find('tr input[type="checkbox"]:checked').each(function(i){
            //selected_domains[i] = jQuery(this).val();
            selected_domains.push(jQuery(this).val());
        });
        
        var data    = {selected_domains: selected_domains, promocode: promocode};

        jQuery.ajax({
            type: "POST",
            url:href,
            data:data,
            dataType: 'json',
            beforeSend: function(){
                NProgress.inc();
                step_page_4.children('div:first-child').html(img_loader);
            },
            success: function(data){
                if(!('error' in data)){
                    reg_data = data;
                    
                    var customer = step_page_3.find('[name="customer"]');
                    reg_data.customer = customer;
                    reg_data.subjnic  = customer;
                    
                    step_page_4.children('div:first-child').html(data.totalinfo);
                    sp4_a_dsbl.removeClass('btn-dsbl');
                }else{
                    createPopupNotice(data.error);
                    return false;
                    //location.reload();
                }
                NProgress.done();
            }
        });
    }
    
    function getVariantsDomains(){
        var href = 'index.php?option=com_ksenmart&view=account&tmpl=ksenmart&task=account.checkDomains';
        var domain_name_val = domain_name.val();
        
        promocode           = step_page_1.find('input[name="promocode"]').val();
        
        if(domain_name_val.length > 0){
            step_page_2.children('div:first-child').html(img_loader);
            var data    = {domain_name: domain_name_val, domains_zone: domains_zone};
            
            jQuery.ajax({
                type: "POST",
                url:href,
                data:data,
                dataType: 'json',
                beforeSend: function(){
                    NProgress.inc();
                },
                success: function(data){
                    if(data.result == 'success'){
                        step_page_2.children('div:first-child').html(generateDomainsTable(data));
                    }else{
                        createPopupNotice(data.error_text);
                        return false;
                    }
                    NProgress.done();
                }
            });
        }
    }
    
    function generateDomainsTable(data){
        var html = '<table class="cat"><tbody>';

        for(var i=0; i < data.answer.domains.length; i++){
            var status = data.answer.domains[i].result;
            
            var tr = '<tr class="success">';
            if(status == 'error'){
                tr = '<tr class="error">';
                status = data.answer.domains[i].error_text;
            }
            html += tr;
            html += '<td>';
            if(status == 'Available'){
                html += '<input type="checkbox" name="domain" value="'+data.answer.domains[i].dname+'" /></td>';
            }
            html += '</td>';
            html += '<td>'+data.answer.domains[i].dname+'</td>';
            html += '<td>'+getDomainStatus(status)+'</td>';
            html += '</tr>';
        }
        
        html += '</tbody></table>';
        return html;
    }
    
    function getDomainStatus(status){
        switch(status){
            case 'Available':
                status = 'Свободен'
            break;
        }
        return status;
    }
</script>